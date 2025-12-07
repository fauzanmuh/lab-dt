<footer style="background: #19586E; min-height: 416px; position: relative; overflow: hidden;" class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="d-flex flex-column justify-content-start">
                    <img src="/assets/images/dt-logo.png" alt="dt-logo" style="width: 105px; height: 56px;"
                        class="mb-3">
                    <p class="text-white" style="font-size: 14px;">
                        <?= htmlspecialchars($infoLab['deskripsi'] ?? 'Deskripsi Lab belum diatur.') ?>
                    </p>
                    <div class="d-flex align-items-center gap-4">
                        <?php if (!empty($infoLab['link_linkedin'])): ?>
                            <a href="<?= htmlspecialchars($infoLab['link_linkedin']) ?>" target="_blank"><i
                                    class="bi bi-linkedin text-white fs-4"></i></a>
                        <?php endif; ?>
                        <?php if (!empty($infoLab['link_instagram'])): ?>
                            <a href="<?= htmlspecialchars($infoLab['link_instagram']) ?>" target="_blank"><i
                                    class="bi bi-instagram text-white fs-4"></i></a>
                        <?php endif; ?>
                        <?php if (!empty($infoLab['link_facebook'])): ?>
                            <a href="<?= htmlspecialchars($infoLab['link_facebook']) ?>" target="_blank"><i
                                    class="bi bi-facebook text-white fs-4"></i></a>
                        <?php endif; ?>
                        <?php if (!empty($infoLab['link_twitter'])): ?>
                            <a href="<?= htmlspecialchars($infoLab['link_twitter']) ?>" target="_blank"><i
                                    class="bi bi-twitter-x text-white fs-4"></i></a>
                        <?php endif; ?>
                        <?php if (!empty($infoLab['link_youtube'])): ?>
                            <a href="<?= htmlspecialchars($infoLab['link_youtube']) ?>" target="_blank"><i
                                    class="bi bi-youtube text-white fs-4"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="d-flex flex-column justify-content-start">
                    <h2 class="text-white fw-semibold mb-4" style="font-size: 24px;">
                        <?= htmlspecialchars($infoLab['nama_lab'] ?? 'DATA TECHNOLOGY LAB') ?>
                    </h2>
                    <div class="d-flex flex-column gap-3" style="z-index: 50;">
                        <a href="/" class="text-white text-uppercase text-decoration-none">Beranda</a>
                        <a href="/about" class="text-white text-uppercase text-decoration-none">Tentang Kami</a>
                        <a href="/facility" class="text-white text-uppercase text-decoration-none">Fasilitas</a>
                        <a href="/gallery" class="text-white text-uppercase text-decoration-none">Galeri</a>
                        <a href="/publications" class="text-white text-uppercase text-decoration-none">Publikasi</a>
                        <a href="/news" class="text-white text-uppercase text-decoration-none">Berita</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="d-flex flex-column justify-content-start">
                    <h2 class="text-white fw-semibold mb-4" style="font-size: 24px;">HUBUNGI KAMI</h2>
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex align-content-center gap-2">
                            <i class="bi bi-geo-alt-fill text-white fs-5"></i>
                            <p class="text-white mb-0" style="font-size: 14px;">
                                <?= nl2br(htmlspecialchars($infoLab['alamat'] ?? 'Alamat belum diatur.')) ?>
                            </p>
                        </div>
                        <?php if (!empty($infoLab['telepon'])): ?>
                            <div class="d-flex align-content-center gap-2">
                                <i class="bi bi-telephone-fill text-white fs-5"></i>
                                <p class="text-white mb-0" style="font-size: 14px;">
                                    <?= htmlspecialchars($infoLab['telepon']) ?>
                                </p>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($infoLab['email'])): ?>
                            <div class="d-flex align-content-center gap-2">
                                <i class="bi bi-envelope-fill text-white fs-5"></i>
                                <p class="text-white mb-0" style="font-size: 14px;">
                                    <?= htmlspecialchars($infoLab['email']) ?>
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <img src="/assets/images/mask-footer.png" style="
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            z-index: 1;
        ">
    <div class="text-center text-white" style="
            position: relative;
            z-index: 2;
            padding-top: 20px;
        ">
        Copyright © <?php echo date('Y'); ?> <span
            class="footer-text-gradient"><?= htmlspecialchars($infoLab['nama_lab'] ?? 'Lab Data Teknologi') ?></span>.
        All rights reserved.
    </div>
</footer>