<header class="border-bottom bg-white">
    <nav class="navbar navbar-expand-lg d-flex align-items-center justify-content-between bg-grey navbar-default p-0">
        <div class="container-fluid d-flex align-items-stretch align-self-stretch px-3">
            <a class="btn menu d-flex justify-content-center align-items-center p-0" href="#">
                <i class="fa fa-fw fa-bars fs-6"></i>
                <span class="d-none">Menu</span>
            </a>
            <div class="btn-group perfil">
                <button type="button" class="btn bg-light border-0" data-bs-toggle="dropdown" data-bs-display="static" aria-haspopup="true" aria-expanded="false">
                    <div class="d-flex justify-content-center align-items-center flex-row">
                        <img class="rounded-circle perfil-user" src="{{ asset('img/site/svg/user-male-4.svg') }}" alt="Header Avatar">
                        <span class="d-flex flex-column align-items-start ms-2">
                            <span class="username fs-6 text-uppercase">{{ session(\App\Enums\Parametros::CHAVE_USUARIO_LOGADO)->nome }}</span>
                        </span>
                    </div>
                </button>
                <ul class="dropdown-menu dropdown-menu-lg-end animate slideIn border-0">
                    <h6 class="dropdown-header">Bem-vindo {{ session(\App\Enums\Parametros::CHAVE_USUARIO_LOGADO)->apelido }}!</h6>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.login.index') }}">
                            <i class="fa-solid me-2 fa-user"></i>
                            Perfil
                        </a>
                    </li>
                    <div class="dropdown-divider"></div>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.login.index') }}">
                            <i class="fa-solid me-2 fa-right-from-bracket"></i>
                            Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
