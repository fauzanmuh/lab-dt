<section class="bg-white" style="min-height: 100vh;">
    <div class="container py-5">

        <div class="row g-4 mb-5">
            <div class="col-12 col-lg-7 col-xl-8">
                <div class="position-relative overflow-hidden rounded-4">
                    <img src="/<?= str_replace(' ', '%20', $latest['gambar_utama']) ?>"
                        alt="<?= htmlspecialchars($latest['judul']) ?>" class="w-100 img-fluid rounded-4">
                    <span class="position-absolute top-0 start-0 m-3 p-2 badge bg-white" style="color: #0F9ECC;">
                        Artikel Terbaru
                    </span>

                    <!-- Content -->
                    <div class="position-absolute bottom-0 start-0 end-0 p-3 text-white text-wrap">
                        <h5 class="fw-semibold mb-1">
                            <?= htmlspecialchars($latest['judul']) ?>
                        </h5>
                        <p class="mb-0 small text-truncate">
                            <?= htmlspecialchars($latest['isi_berita']) ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-5 col-xl-4 d-flex flex-column gap-3">
                <?php foreach ($others as $o): ?>
                    <div class="border rounded-3 p-3 d-flex align-items-center gap-2">
                        <img src="/<?= str_replace(' ', '%20', $o['gambar_utama']) ?>" alt="News Image" class="rounded-3"
                            style="width: 148px; height: auto; object-fit: cover;">
                        <div class="d-flex flex-column justify-content-between w-100">
                            <div class="d-flex justify-content-between align-items-center mb-2" style="font-size: 12px;">
                                <p class="m-0"><?= date('d M, Y', strtotime($o['tanggal_posting'])) ?></p>
                                <p class="m-0">5 Min read</p>
                            </div>
                            <p class="m-0 fw-semibold" style="font-size: 14px; color: #0F9ECC;">
                                <?= htmlspecialchars($o['judul']) ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <h2 class="text-vision-gradient" style="font-size: 40px; font-weight: bold;">Berita & Artikel Laboratorium
        </h2>

        <div class="d-flex justify-content-between align-items-center my-4">
            <ul class="nav nav-tabs gallery-tabs justify-content-start">
                <li class="nav-item">
                    <a class="nav-link active" href="#">Semua</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Kegiatan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Ruang Lab</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Acara</a>
                </li>
            </ul>
            <div class="input-group w-25">
                <input type="text" class="form-control" style="background-color: #F0F0F0;" placeholder="Search articles"
                    aria-label="Search" aria-describedby="button-search">
                <button class="btn btn-secondary" type="submit" id="button-search">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <?php if (empty($news)): ?>
                <div class="mt-4 border rounded-4 p-4 text-center">
                    <p class="text-muted mb-0">Belum ada berita.</p>
                </div>
            <?php else: ?>
                <?php foreach ($news as $n): ?>
                    <div class="col-lg-4">
                        <div class="card shadow rounded-3 p-3 card-news">
                            <div class="text-center">
                                <img src="/<?= str_replace(' ', '%20', $n['gambar_utama']) ?>" alt="News Image"
                                    class="w-100 rounded-3" style="height: 189px; object-fit: cover;">
                            </div>
                            <div class="card-body text-start p-0">
                                <h5 class="card-title fw-semibold py-3 fs-6 m-0" style="color: #5F983C;">Penelitian</h5>
                                <p class="card-text all-text-gradient fw-semibold">
                                    <?= htmlspecialchars($n['judul']) ?>
                                </p>
                                <p class="text-muted small mt-2">
                                    <?= date('d F Y', strtotime($n['tanggal_posting'])) ?>
                                </p>
                                <a href="#" class="btn btn-outline-dark mt-2 w-100">Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>