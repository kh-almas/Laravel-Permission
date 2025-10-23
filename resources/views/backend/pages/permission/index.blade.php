@extends('Permission::backend.layout')

@section('title', 'Show All Permission | Permission Management')
@section('page-title', 'SHOW PERMISSIONS')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('permission.dashboard') }}" class="text-decoration-none">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z"/>
            </svg>
            Back
        </a>
        @if(auth()->user()->email === config('permission.super_admin_email') && user_permission('add-permission'))
        <div class="d-flex gap-2">
            <a href="{{ route('permission.permission.create') }}" class="btn btn-primary">
                <i class="bi bi-person-plus me-1"></i> Add Permission
            </a>
        </div>
        @endif
    </div>

    @include('Permission::backend.component.notification')

    <div class="table-responsive">
        <table class="table table-sm table-striped table-bordered table-hover align-middle">
            <thead>
            <tr>
                <th scope="col">SL</th>
                <th scope="col">Name</th>
                <th scope="col">Slug</th>
                <th scope="col">Created By</th>
                <th scope="col">Created At</th>
                @if(auth()->user()->email === config('permission.super_admin_email') && user_permission('delete-permission'))
                <th scope="col">Actions</th>
                @endif
            </tr>
            </thead>
            <tbody>
            @forelse($permissions as $index => $permission)
                <tr>
                    <th scope="row">{{ 1 + $index }}</th>
                    <td>{{ $permission->name }}</td>
                    <td>{{ $permission->slug }}</td>
                    <td>{{ $permission->user_name ?? '-' }}</td>
                    <td>{{ $permission->created_at ? \Carbon\Carbon::parse($permission->created_at)->format('d M Y') : '-' }}</td>
                    @if(auth()->user()->email === config('permission.super_admin_email') && user_permission('delete-permission'))
                        <td class="d-flex gap-1">
                            <form action="{{ route('permission.permission.destroy', $permission->slug) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Role">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                        <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                                    </svg>
                                </button>
                            </form>
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
        {{ $permissions->links('Permission::vendor.pagination.custom') }}
    </div>
@endsection
