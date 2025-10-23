@extends('Permission::backend.layout')

@section('title', "Assign Permissions | Permission Management")
@section('page-title', "Assign Permissions to: $role->name")

@section('content')
    <div>
        <a href="{{ route('permission.role_permission') }}" class="btn btn-link mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z"/>
            </svg>
            Back
        </a>

        @include('Permission::backend.component.notification')

        <form action="{{ route('permission.role.save.permission', $role->slug) }}" method="POST">
            @csrf

            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                @foreach($permissions as $permission)
                    <div class="col">
                        <div class="card shadow-sm h-100 p-3 d-flex align-items-center justify-content-center">
                            <input class="form-check-input fs-4 border border-dark mb-2"
                                   type="checkbox"
                                   name="permissions[]"
                                   value="{{ $permission->id }}"
                                   id="perm{{ $permission->id }}"
                                {{ in_array($permission->id, $assignedPermissions) ? 'checked' : '' }}>
                            <label class="text-center fw-semibold" for="perm{{ $permission->id }}">
                                {{ $permission->name }}<br>
                                <small class="text-muted">{{ $permission->slug }}</small>
                            </label>
                        </div>

                    </div>
                @endforeach
            </div>

            @user_permission('can-assign-role-permission')
            <div class="mt-4 d-flex justify-content-center">
                <button type="submit" class="btn btn-primary">
                    Save Permissions
                </button>
            </div>
            @end_user_permission
        </form>
    </div>

    <script>
        setTimeout(() => {
            const alertEl = document.querySelector('.alert');
            if(alertEl) alertEl.remove();
        }, 5000);
    </script>
@endsection
