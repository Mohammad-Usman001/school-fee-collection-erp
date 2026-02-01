@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mx-3 mt-2">
        <i class="fas fa-check-circle mr-1"></i>
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mx-3 mt-2">
        <i class="fas fa-times-circle mr-1"></i>
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-warning alert-dismissible fade show mx-3 mt-2">
        <i class="fas fa-exclamation-triangle mr-1"></i>
        Please fix the errors below.
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
