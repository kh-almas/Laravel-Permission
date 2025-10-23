<header class="bg-white py-3 mb-4 border-bottom">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('permission.dashboard') }}" class="text-decoration-none text-dark">
                <h1 class="h3 mb-0">
                    @yield('page-title', env('APP_NAME', 'Permission Management'))
                </h1>
            </a>
            <div>
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
                        </svg>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <li>
                            <div class="d-flex flex-column py-2 mx-3">
                                <span class="fw-semibold text-white">{{ auth()->user()->name }}</span>
                                <small class="text-light opacity-75">{{ auth()->user()->email }}</small>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item d-flex align-items-center text-white">
                                    <i class="bi bi-box-arrow-right me-2"></i>
                                    {{ __('Log Out') }}
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</header>
