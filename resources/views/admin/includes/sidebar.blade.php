<aside class="sidebar navbar-menu">
    <div class="navbar-brand d-flex justify-content-center align-items-center m-0 p-0">
        <span class="fs-4 fw-bold text-light d-flex align-items-center justify-content-center logotipo p-0">
            <span class="d-flex align-items-center justify-content-center">modulo</span>
            <span class="d-flex align-items-center justify-content-center not-hide">.</span>
            <span class="d-flex align-items-center justify-content-center">intranet</span>
        </span>
    </div>
    <div class="scrollable">
        <ul class="navbar-nav">
            @foreach ($userMenu as $sistema => $menus)
                <h6 class="nav-heading d-flex align-items-center text-uppercase m-0 px-3">
                    {{ $sistema }}
                </h6>
                @foreach ($menus as $rotina => $menu)
                    <li class="ps-1 nav-item">
                        <a class="nav-link d-flex align-items-center px-4 py-3" href="{{ route($menu['rota']) }}">
                            <i class="{{ !empty($menu['icone']) ? $menu['icone'] : 'fa-regular fa-folder' }}"></i>
                            <span class="ms-2">{{ $rotina }}</span>
                        </a>
                    </li>
                @endforeach
            @endforeach
        </ul>
    </div>
</aside>
