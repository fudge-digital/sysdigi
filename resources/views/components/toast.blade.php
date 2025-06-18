@if(session('success'))
    <div class="toast toast-top toast-end z-50">
        <div class="alert alert-success">
            <span>{{ session('success') }}</span>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="toast toast-top toast-end z-50 toast-error">
        <div class="alert alert-error">
            <span>{{ session('error') }}</span>
        </div>
    </div>
@endif