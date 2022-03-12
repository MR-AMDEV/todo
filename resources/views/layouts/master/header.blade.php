<nav class="navbar navbar-light bg-light">
    <div class="container-fluid align-items-center justify-content-between">
        <a class="navbar-brand d-flex align-items-center gap-2" href="/">
            <img src="https://getbootstrap.com/docs/5.1/assets/brand/bootstrap-logo.svg" alt="" width="30" height="24" class="d-inline-block align-text-top">
            <span>Todo App</span>
        </a>
        <div>
            <div class="dropdown me-1">
                <button class="btn btn-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown" data-bs-offset="10,20">
                    <i class="fas fa-user-circle fa-fw"></i>
                    Account
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <form action="{{ route('logout') }}" method="POST" class="d-flex align-items-center">
                            @csrf
                            <button type="submit" class="dropdown-item"><i class="far fa-sm fa-circle fa-fw me-2"></i> Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
