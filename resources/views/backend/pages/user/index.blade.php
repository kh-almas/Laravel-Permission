@extends('Permission::backend.layout')

@section('title', 'Show All Roles | Permission Management')
@section('page-title', 'SHOW USERS')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('permission.dashboard') }}" class="text-decoration-none">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z"/>
            </svg>
            Back
        </a>
    </div>

    @include('Permission::backend.component.notification')

    <div class="table-responsive">
        <table class="table table-sm table-striped table-bordered table-hover align-middle">
            <thead>
            <tr>
                <th scope="col">SL</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Acc Create Time</th>
                @user_permission('can-assign-user-role')
                <th scope="col">Actions</th>
                @end_user_permission
            </tr>
            </thead>
            <tbody>
            @forelse($users as $index => $user)
                <tr>
                    <th scope="row">{{  $index +1 }}</th>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email ?? '-' }}</td>
                    <td>{{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('d M Y') : '-' }}</td>
                    @if(auth()->user()->email !== $user->email && ($user->email !== config('permission.super_admin_email') || auth()->user()->email === config('permission.super_admin_email')) && user_permission('can-assign-user-role'))
                    <td class="d-flex gap-1">
                        <a href="{{ route('permission.users.assign.role', $user->id) }}" class="btn btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Assign Permissions">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-window-plus" viewBox="0 0 16 16">
                                <path d="M2.5 5a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1M4 5a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1m2-.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0"/>
                                <path d="M0 4a2 2 0 0 1 2-2h11a2 2 0 0 1 2 2v4a.5.5 0 0 1-1 0V7H1v5a1 1 0 0 0 1 1h5.5a.5.5 0 0 1 0 1H2a2 2 0 0 1-2-2zm1 2h13V4a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1z"/>
                                <path d="M16 12.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0m-3.5-2a.5.5 0 0 0-.5.5v1h-1a.5.5 0 0 0 0 1h1v1a.5.5 0 0 0 1 0v-1h1a.5.5 0 0 0 0-1h-1v-1a.5.5 0 0 0-.5-.5"/>
                            </svg>
                        </a>
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
        {{ $users->links('Permission::vendor.pagination.custom') }}
    </div>
@endsection
