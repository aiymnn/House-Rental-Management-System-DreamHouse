@if (@session()->has('status'))

<div class="alert alert-success alert-dismissible fade show" style="width: 100%" role="alert">
    {{ session('status') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

@endif
