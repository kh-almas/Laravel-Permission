<!doctype html>
<html lang="en">
<head>
    @include('Permission::backend.partials.header-link')
    <style>
        .img-preview {
            display: none;
            width: 100%;
            max-height: 200px;
            object-fit: contain;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 5px;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
@include('Permission::backend.partials.header')

<main class="container my-4">
    @yield('content')
</main>

{{--@include('Permission::backend.partials.footer')--}}

@include('Permission::backend.partials.footer-link')
</body>
</html>
