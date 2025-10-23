@extends('Permission::backend.layout')

@section('title', 'Create Permission | Permission Management')
@section('page-title', 'CREATE PERMISSION')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('permission.permission') }}" class="text-decoration-none">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z"/>
            </svg>
            Back
        </a>
    </div>

    <div class="card shadow-sm p-4">
        <form id="roleForm" action="{{ route('permission.permission.store') }}" method="POST" novalidate>
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label fw-semibold">Permission Name</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Enter permission name" value="{{ old('name') }}">
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label for="slug" class="form-label fw-semibold">Slug</label>
                <input
                    type="text"
                    name="slug"
                    id="slug"
                    class="form-control"
                    placeholder="Auto generated slug"
                    value="{{ old('slug') }}"
                    pattern="^[a-z0-9]+(?:-[a-z0-9]+)*$"
                    title="Use lowercase letters, numbers, and single hyphens only (e.g. view-notes, edit-notes)."
                    inputmode="latin"
                    autocomplete="off"
                >
                <small id="slugHelp" class="text-muted">Lowercase a–z, 0–9, and hyphens. No spaces (e.g. view-notes, edit-notes).</small><br>
                <small id="slugError" class="text-danger d-none">Invalid slug. Use only lowercase letters/numbers and single hyphens (no spaces).</small>
                @error('slug') <small class="text-danger d-block">{{ $message }}</small> @enderror
            </div>

            @user_permission('add-permission')
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i> Save Permission
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

            nameEl.addEventListener('input', function () {
                const slug = this.value
                    .toLowerCase()
                    .trim()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');
                slugEl.value = slug;
                validateSlug();
            });

            slugEl.addEventListener('input', function () {
                const cleaned = this.value
                    .toLowerCase()
                    .replace(/[^a-z0-9-]/g, '')
                    .replace(/-+/g, '-')
                    .replace(/^-+|-+$/g, '');
                if (cleaned !== this.value) this.value = cleaned;
                validateSlug();
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
