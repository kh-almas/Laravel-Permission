@extends('Permission::backend.layout')

@section('title', 'Update Role | Permission Management')
@section('page-title', 'UPDATE ROLE')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('permission.role_permission') }}" class="text-decoration-none">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z"/>
            </svg>
            Back
        </a>
        <div class="d-flex gap-2">
            <a href="{{ route('permission.role.create') }}" class="btn btn-primary">
                <i class="bi bi-person-plus me-1"></i> Add Role
            </a>
        </div>
    </div>

    <div class="card shadow-sm p-4">
        <form id="roleForm" action="{{ route('permission.role.update', $role->slug) }}" method="POST" novalidate>
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label fw-semibold">Role Name</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Enter role name" value="{{ old('name', $role->name) }}">
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label for="slug" class="form-label fw-semibold">Slug</label>
                <input
                    type="text"
                    name="slug"
                    id="slug"
                    class="form-control"
                    placeholder="Auto-generated slug"
                    value="{{ old('slug', $role->slug) }}"
                    pattern="^[a-z0-9]+(?:-[a-z0-9]+)*$"
                    title="Use lowercase letters, numbers, and single hyphens only (e.g., admin, super-admin)."
                    inputmode="latin"
                    autocomplete="off"
                >
                <small id="slugHelp" class="text-muted">Lowercase a–z, 0–9, and hyphens. No spaces.</small><br>
                <small id="slugError" class="text-danger d-none">Invalid slug. Use only lowercase letters/numbers and single hyphens (no spaces).</small>
                @error('slug') <small class="text-danger d-block">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label for="serial_no" class="form-label fw-semibold">Serial No (optional)</label>
                <input type="number" name="serial_no" id="serial_no" class="form-control" placeholder="Enter serial number" value="{{ old('serial_no', $role->serial_no) }}">
                @error('serial_no') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            @user_permission('edit-role')
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i> Update Role
            </button>
            @end_user_permission
        </form>
    </div>

    <script>
        (function () {
            const nameEl = document.getElementById('name');
            const slugEl = document.getElementById('slug');
            const formEl = document.getElementById('roleForm');
            const slugErrorEl = document.getElementById('slugError');

            const slugRegex = /^[a-z0-9]+(?:-[a-z0-9]+)*$/;
            let userEditedSlug = false;

            slugEl.addEventListener('input', () => {
                userEditedSlug = true;
                validateSlug();
            });

            nameEl.addEventListener('input', function () {
                if (!userEditedSlug || slugEl.value.trim() === '') {
                    const slug = this.value
                        .toLowerCase()
                        .trim()
                        .replace(/[^a-z0-9\s-]/g, '')
                        .replace(/\s+/g, '-')
                        .replace(/-+/g, '-')
                        .replace(/^-+|-+$/g, '');
                    slugEl.value = slug;
                    validateSlug();
                }
            });

            function validateSlug() {
                const valid = slugRegex.test(slugEl.value);
                slugEl.setCustomValidity(valid ? '' : 'Invalid slug');
                slugErrorEl.classList.toggle('d-none', valid);
                return valid;
            }

            formEl.addEventListener('submit', function (e) {
                if (!validateSlug()) {
                    e.preventDefault();
                    slugEl.focus();
                }
            });
        })();
    </script>
@endsection
