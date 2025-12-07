<section class="bg-white" style="min-height: 100vh;">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="/about" class="text-decoration-none">Tentang Kami</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($member['nama_lengkap']) ?></li>
                    </ol>
                </nav>

                <div class="card shadow-lg rounded-4 overflow-hidden border-0">
                    <div class="row g-0">
                        <div class="col-md-4 bg-light d-flex align-items-center justify-content-center p-4">
                            <div class="text-center w-100">
                                <div class="avatar-container mb-3 mx-auto" style="width: 200px; height: 200px; overflow: hidden; border-radius: 50%; border: 5px solid #fff; box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);">
                                    <?php if (!empty($member['foto_profil'])): ?>
                                        <img src="/uploads/foto_profil/<?= htmlspecialchars($member['foto_profil']) ?>" 
                                             alt="<?= htmlspecialchars($member['nama_lengkap']) ?>" 
                                             class="w-100 h-100 object-fit-cover">
                                    <?php else: ?>
                                        <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-primary text-white display-1 fw-bold">
                                            <?= strtoupper(substr($member['nama_lengkap'], 0, 1)) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <h5 class="fw-bold mb-1"><?= htmlspecialchars($member['nama_lengkap']) ?></h5>
                                <p class="text-muted mb-3"><?= htmlspecialchars($member['role'] === 'admin' ? 'Administrator' : 'Anggota Lab') ?></p>
                                
                                <div class="d-flex justify-content-center gap-2">
                                    <?php if (!empty($member['email'])): ?>
                                        <a href="mailto:<?= htmlspecialchars($member['email']) ?>" class="btn btn-outline-primary btn-sm rounded-circle" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-envelope-fill"></i>
                                        </a>
                                    <?php endif; ?>
                                    <!-- Add more social links if available in DB -->
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card-body p-4 p-lg-5">
                                <h2 class="fw-bold mb-4 text-dark">Profil Anggota</h2>
                                
                                <div class="mb-4">
                                    <h6 class="text-uppercase text-muted fw-bold small ls-1">Informasi Dasar</h6>
                                    <hr class="mt-1 mb-3">
                                    <div class="row g-3">
                                        <div class="col-sm-4 fw-semibold text-secondary">Nama Lengkap</div>
                                        <div class="col-sm-8 text-dark"><?= htmlspecialchars($member['nama_lengkap']) ?></div>
                                        
                                        <div class="col-sm-4 fw-semibold text-secondary">Username</div>
                                        <div class="col-sm-8 text-dark">@<?= htmlspecialchars($member['username']) ?></div>

                                        <?php if (!empty($member['nip_nim'])): ?>
                                        <div class="col-sm-4 fw-semibold text-secondary">NIP/NIM</div>
                                        <div class="col-sm-8 text-dark"><?= htmlspecialchars($member['nip_nim']) ?></div>
                                        <?php endif; ?>
                                        
                                        <div class="col-sm-4 fw-semibold text-secondary">Status</div>
                                        <div class="col-sm-8">
                                            <?php if ($member['status_aktif']): ?>
                                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">Aktif</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">Tidak Aktif</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <h6 class="text-uppercase text-muted fw-bold small ls-1">Biografi</h6>
                                    <hr class="mt-1 mb-3">
                                    <p class="text-secondary leading-relaxed">
                                        <?= htmlspecialchars($member['nama_lengkap']) ?> adalah bagian dari tim Laboratorium Data Teknologi. 
                                        Beliau berkontribusi dalam berbagai kegiatan penelitian dan pengembangan di laboratorium.
                                    </p>
                                </div>

                                <!-- Publications Section -->
                                <?php if (!empty($publications)): ?>
                                <div class="mb-5">
                                    <h6 class="text-uppercase text-muted fw-bold small ls-1 mb-4">Publikasi</h6>
                                    <div class="row g-4">
                                        <?php foreach ($publications as $pub): ?>
                                            <div class="col-md-6">
                                                <div class="card-modern h-100 d-flex flex-column">
                                                    <div class="card-body d-flex flex-column">
                                                        <h5 class="card-title fw-bold mb-3" style="color: #314755; line-height: 1.4;">
                                                            <?= htmlspecialchars($pub['judul_publikasi']) ?>
                                                        </h5>

                                                        <div class="mt-auto mb-4">
                                                            <p class="card-text text-muted mb-2" style="font-size: 0.9rem;">
                                                                <i class="bi bi-calendar3 me-2"></i><?= htmlspecialchars($pub['tahun_terbit']) ?>
                                                            </p>
                                                            <span class="badge rounded-pill bg-secondary bg-opacity-25 text-secondary">
                                                                <?= $pub['citation_count'] ?? 0 ?> Citations
                                                            </span>
                                                        </div>

                                                        <a href="<?= htmlspecialchars($pub['link_publikasi'] ?? '#') ?>" target="_blank"
                                                            class="btn btn-modern btn-outline-custom w-100 text-center"
                                                            style="border-color: #19586E; color: #19586E;">Baca Publikasi</a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <!-- News Section -->
                                <?php if (!empty($news)): ?>
                                <div class="mb-5">
                                    <h6 class="text-uppercase text-muted fw-bold small ls-1 mb-4">Berita & Artikel</h6>
                                    <div class="row g-4">
                                        <?php foreach ($news as $item): ?>
                                            <div class="col-md-6">
                                                <div class="card h-100 border-0 shadow-sm">
                                                    <?php if (!empty($item['gambar_utama'])): ?>
                                                        <img src="/<?= htmlspecialchars($item['gambar_utama']) ?>" class="card-img-top" alt="<?= htmlspecialchars($item['judul']) ?>" style="height: 140px; object-fit: cover;">
                                                    <?php endif; ?>
                                                    <div class="card-body p-3">
                                                        <h6 class="card-title fw-bold text-dark mb-2 line-clamp-2"><?= htmlspecialchars($item['judul']) ?></h6>
                                                        <p class="card-text text-muted small mb-0"><?= date('d M Y', strtotime($item['tanggal_posting'])) ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <!-- Gallery Section -->
                                <?php if (!empty($gallery)): ?>
                                <div class="mb-4">
                                    <h6 class="text-uppercase text-muted fw-bold small ls-1 mb-4">Galeri</h6>
                                    <div class="row g-4">
                                        <?php foreach ($gallery as $photo): ?>
                                            <div class="col-6 col-md-4">
                                                <div class="card-modern overflow-hidden p-0 gallery-item">
                                                    <img src="/<?= htmlspecialchars($photo['file_path']) ?>" alt="Gallery Image" class="img-fluid w-100"
                                                        style="object-fit: cover; height: 200px; transition: transform 0.5s ease;">
                                                    <div class="gallery-overlay">
                                                        <p class="mb-0 fw-semibold"><?= htmlspecialchars($photo['deskripsi'] ?? '') ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
