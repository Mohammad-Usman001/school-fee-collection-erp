<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    private string $backupDir = 'backups';

    /**
     * Show backup list
     */
    public function index()
{
    // ✅ ensure folder exists
    Storage::disk('local')->makeDirectory($this->backupDir);

    // ✅ list backups from local disk
    $files = Storage::disk('local')->files($this->backupDir);

    $backups = collect($files)
        ->filter(function ($f) {
            return strtolower(pathinfo($f, PATHINFO_EXTENSION)) === 'sqlite';
        })
        ->map(function ($file) {
            return [
                'file' => basename($file),
                'path' => $file,
                'size' => Storage::disk('local')->size($file),
                'time' => Storage::disk('local')->lastModified($file),
            ];
        })
        ->sortByDesc('time')
        ->values();

    return view('backup.index', compact('backups'));
}


    /**
     * Create backup
     */
    public function create()
{
    $dbPath = config('database.connections.sqlite.database');

    // ✅ Convert relative path to absolute
    if (!preg_match('/^[A-Z]:/i', $dbPath) && !str_starts_with($dbPath, '/')) {
        $dbPath = base_path($dbPath);
    }

    if (!File::exists($dbPath)) {
        return redirect()->back()->with('error', "Database file not found: $dbPath");
    }

    Storage::makeDirectory($this->backupDir);

    $fileName = 'backup_' . date('Y_m_d_His') . '.sqlite';
    $destPath = storage_path('app/' . $this->backupDir . '/' . $fileName);

    File::copy($dbPath, $destPath);

    return redirect()->route('backup.index')->with('success', 'Backup created successfully!');
}


    /**
     * Download backup file
     */
    public function download($file)
    {
        $filePath = $this->backupDir . '/' . $file;

        if (!Storage::exists($filePath)) {
            abort(404);
        }

        return Storage::download($filePath);
    }

    /**
     * Delete backup file
     */
    public function delete($file)
    {
        $filePath = $this->backupDir . '/' . $file;

        if (!Storage::exists($filePath)) {
            return redirect()->back()->with('error', 'File not found!');
        }

        Storage::delete($filePath);

        return redirect()->route('backup.index')->with('success', 'Backup deleted successfully!');
    }

    /**
     * Restore form
     */
    public function restoreForm()
    {
        return view('backup.restore');
    }

    /**
     * Restore backup file (upload)
     */
    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => ['required', 'file', 'mimes:sqlite,db', 'max:51200'], // 50MB
        ]);

        $uploaded = $request->file('backup_file');

        $dbPath = config('database.connections.sqlite.database');

        if (!File::exists($dbPath)) {
            // if missing, create
            File::put($dbPath, '');
        }

        // ✅ before restore, save current db as safety backup
        Storage::makeDirectory($this->backupDir);
        $safetyName = 'safety_before_restore_' . date('Y_m_d_His') . '.sqlite';
        File::copy($dbPath, storage_path('app/' . $this->backupDir . '/' . $safetyName));

        // ✅ replace db
        File::copy($uploaded->getRealPath(), $dbPath);

        return redirect()->route('backup.index')->with('success', 'Database restored successfully! Please refresh the app.');
    }
}
