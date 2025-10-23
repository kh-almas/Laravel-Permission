@extends('Permission::backend.layout')

@section('title', 'dashboard | Permission Management')

@section('content')
    <div class="row row-cols-3 g-3 mb-3 justify-content-center">
        @if(user_permission('show-user-list'))
            <div class="mb-3 mb-sm-0">
                <a href="{{ route('permission.users.index') }}" class="text-decoration-none">
                    <div class="card">
                        <div class="card-body p-5 d-flex justify-content-center align-content-center">
                            <h5 class="card-title">Users</h5>
                        </div>
                    </div>
                </a>
            </div>
        @endif
        @if(user_permission('show-permission-list'))
            <div class="mb-3 mb-sm-0">
                <a href="{{ route('permission.permission') }}" class="text-decoration-none">
                    <div class="card">
                        <div class="card-body p-5 d-flex justify-content-center align-content-center">
                            <h5 class="card-title">Permission</h5>
                        </div>
                    </div>
                </a>
            </div>
        @endif
        @if(user_permission('show-role-list'))
        <div class="mb-3 mb-sm-0">
            <a href="{{ route('permission.role_permission') }}" class="text-decoration-none">
                <div class="card">
                    <div class="card-body p-5 d-flex justify-content-center align-content-center">
                        <h5 class="card-title">Role & permission</h5>
                    </div>
                </div>
            </a>
        </div>
        @endif
    </div>
@endsection
