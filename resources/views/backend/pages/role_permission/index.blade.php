@extends('Permission::backend.layout')

@section('title', 'Show All Roles | Permission Management')
@section('page-title', 'SHOW ROLES')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('permission.dashboard') }}" class="text-decoration-none">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z"/>
            </svg>
            Back
        </a>
        @user_permission('create-role')
        <div class="d-flex gap-2">
            <a href="{{ route('permission.role.create') }}" class="btn btn-primary">
                <i class="bi bi-person-plus me-1"></i> Add Role
            </a>
        </div>
        @end_user_permission
    </div>

    @include('Permission::backend.component.notification')

    <div class="table-responsive">
        <table class="table table-sm table-striped table-bordered table-hover align-middle">
            <thead>
            <tr>
                <th scope="col">SL</th>
                <th scope="col">Name</th>
                <th scope="col">Slug</th>
                <th scope="col">Serial No</th>
                <th scope="col">Created By</th>
                <th scope="col">Created At</th>
                @if(user_permission('can-assign-role-permission') || user_permission('edit-role') || user_permission('delete-role'))
                    <th scope="col">Actions</th>
                @endif

            </tr>
            </thead>
            <tbody>
            @forelse($roles as $index => $role)
                <tr>
                    <th scope="row">{{ $roles->firstItem() + $index }}</th>
                    <td>{{ $role->name }}</td>
                    <td>{{ $role->slug }}</td>
                    <td>{{ $role->serial_no ?? '-' }}</td>
                    <td>{{ $role->user_name ?? '-' }}</td>
                    <td>{{ $role->created_at ? \Carbon\Carbon::parse($role->created_at)->format('d M Y') : '-' }}</td>
                    @if(user_permission('can-assign-role-permission') || user_permission('edit-role') || user_permission('delete-role'))
                    <td class="d-flex gap-1">
                        @if($role->slug !== 'super-admin' || auth()->user()->email === config('permission.super_admin_email'))
                            @if(($role->id !== $userRole->role_id || auth()->user()->email === config('permission.super_admin_email')) && user_permission('can-assign-role-permission'))
                                <a href="{{ route('permission.role.assign.permission', $role->slug) }}" class="btn btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Assign Permissions">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-window-plus" viewBox="0 0 16 16">
                                        <path d="M2.5 5a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1M4 5a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1m2-.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0"/>
                                        <path d="M0 4a2 2 0 0 1 2-2h11a2 2 0 0 1 2 2v4a.5.5 0 0 1-1 0V7H1v5a1 1 0 0 0 1 1h5.5a.5.5 0 0 1 0 1H2a2 2 0 0 1-2-2zm1 2h13V4a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1z"/>
                                        <path d="M16 12.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0m-3.5-2a.5.5 0 0 0-.5.5v1h-1a.5.5 0 0 0 0 1h1v1a.5.5 0 0 0 1 0v-1h1a.5.5 0 0 0 0-1h-1v-1a.5.5 0 0 0-.5-.5"/>
                                    </svg>
                                </a>
                            @endif
                            @user_permission('edit-role')
                            <a href="{{ route('permission.role.edit', $role->slug) }}" class="btn btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Role">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                </svg>
                            </a>
                            @end_user_permission

                            @if($role->id !== $userRole->role_id && user_permission('delete-role'))
                            <form action="{{ route('permission.role.destroy', $role->slug) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Role">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                        <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                                    </svg>
                                </button>
                            </form>
                            @endif
                        @endif
                        <p></p>
                    </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">No roles found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $roles->links('Permission::vendor.pagination.custom') }}
    </div>
@endsection
