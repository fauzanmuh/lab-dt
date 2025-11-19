<nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top">
    <div class="container">
        <!-- Logo -->
        <div class="d-flex align-items-center justify-content-center">
            <a class="navbar-brand fw-semibold" href="/">
                <img src="/assets/images/logo-polinema.png" alt="" class="border-2 border-end pe-2 me-2"
                    style="height: 40px; width: 50px;">
                <img src="/assets/images/jti.png" alt="" style="height: 40px; width: 40px;">
            </a>
        </div>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto d-flex align-items-center" style="color: #575757;">
                <?php
                $current_page = $_SERVER['REQUEST_URI'];
                ?>

                <li class="nav-item">
                    <a class="nav-link <?= $current_page == '/' ? 'active-link' : '' ?>" href="/">Beranda</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= str_contains($current_page, '/about') ? 'active-link' : '' ?>"
                        href="/about">Tentang Kami</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= str_contains($current_page, '/facility') ? 'active-link' : '' ?>"
                        href="/facility">Fasilitas</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= str_contains($current_page, '/gallery') ? 'active-link' : '' ?>"
                        href="/gallery">Galeri</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= str_contains($current_page, '/publications') ? 'active-link' : '' ?>"
                        href="/publications">Publikasi</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= str_contains($current_page, '/news') ? 'active-link' : '' ?>"
                        href="/news">Berita</a>
                </li>
            </ul>
        </div>
    </div>
</nav>