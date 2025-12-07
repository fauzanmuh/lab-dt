<section id="hero">
    <div class="container d-flex flex-column justify-content-center align-items-center text-center">
        <h1 class="fw-bold mb-4 animate-fade text-hero text-white">Laboratorium <span class="gradient-text-hero">Data
                Technology</span></h1>
        <p class="subtext-hero animate-fade mb-5" style="animation-delay: .2s; max-width: 800px;">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
        </p>
        <div class="d-flex justify-content-center align-items-center gap-3" style="font-size: 18px;">
            <a href="/publications" class="btn btn-modern btn-primary-custom animate-fade"
                style="animation-delay: .4s; text-decoration: none;">
                Explore Research
            </a>
            <a href="/gallery" class="btn btn-modern btn-outline-custom animate-fade"
                style="animation-delay: .4s; text-decoration: none;">
                View Galeri
            </a>
        </div>
    </div>
</section>

<section id="about" class="section-padding">
    <div class="container">
        <div class="text-center mb-5">
            <p class="fw-bold text-uppercase" style="color: #7ABC52; letter-spacing: 1px;">Tentang Kami</p>
            <h2 class="fw-bold display-5">Profil Laboratorium</h2>
        </div>

        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="position-relative">
                    <img src="/assets/images/dt-logo.png" alt="Lab Logo" class="img-fluid rounded-4 shadow-lg"
                        style="background: white; padding: 2rem;">
                    <div class="position-absolute top-0 start-0 w-100 h-100 rounded-4"
                        style="background: rgba(122, 188, 82, 0.1); z-index: -1; transform: translate(-20px, -20px);">
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card-modern p-4">
                    <p class="text-muted mb-4" style="text-align: justify; font-size: 1.1rem;">
                        Unit penunjang akademik di Jurusan Teknologi Informasi yang berfokus pada kegiatan pembelajaran,
                        penelitian, serta pengembangan keilmuan di bidang teknologi berbasis data. Laboratorium ini
                        menyediakan fasilitas praktikum dan riset yang mendukung penguasaan pengetahuan serta
                        keterampilan mahasiswa
                        dalam pengolahan data, analisis big data, kecerdasan buatan, dan machine learning.
                    </p>
                    <p class="text-muted mb-4" style="text-align: justify; font-size: 1.1rem;">
                        Selain sebagai sarana praktikum, Laboratorium Teknologi Data juga berperan sebagai pusat
                        penelitian dan pengembangan
                        bagi dosen maupun mahasiswa.
                    </p>
                    <a href="/about" class="btn btn-modern btn-primary-custom" style="text-decoration: none;">
                        Selengkapnya
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="team" style="background: #f8f9fa;" class="section-padding">
    <div class="container text-center">
        <!-- Title -->
        <p class="fw-bold text-uppercase" style="color: #7ABC52; letter-spacing: 1px;">Anggota Tim</p>
        <h2 class="fw-bold display-5 mb-5">Meet Our Team</h2>

        <!-- Team Members -->

        <!-- Head of Lab -->
        <?php if (!empty($headOfLab)): ?>
            <div class="row justify-content-center mb-5">
                <?php foreach ($headOfLab as $member): ?>
                    <div class="col-md-6 col-lg-4">
                        <a href="/member/<?= $member['id_anggota'] ?>" class="text-decoration-none text-dark">
                            <div class="card-modern h-100 text-center p-4 transition-hover border-primary border-2">
                                <?php if (!empty($member['foto_profil'])): ?>
                                    <img src="/uploads/foto_profil/<?= htmlspecialchars($member['foto_profil']) ?>"
                                        class="rounded-circle mb-3 shadow-lg"
                                        style="width: 180px; height: 180px; object-fit: cover; border: 4px solid #7ABC52;">
                                <?php else: ?>
                                    <div class="rounded-circle mb-3 shadow-lg mx-auto d-flex align-items-center justify-content-center bg-primary text-white"
                                        style="width: 180px; height: 180px; border: 4px solid #7ABC52; font-size: 3.5rem;">
                                        <?= strtoupper(substr($member['nama_lengkap'], 0, 1)) ?>
                                    </div>
                                <?php endif; ?>
                                <h3 class="fw-bold mb-1" style="font-size: 1.5rem;">
                                    <?= htmlspecialchars($member['nama_lengkap']) ?>
                                </h3>
                                <p class="text-primary fw-bold mb-0" style="font-size: 1rem;">Kepala Laboratorium</p>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Lab Members -->
        <?php if (!empty($labMembers)): ?>
            <div class="row justify-content-center g-4">
                <?php foreach ($labMembers as $member): ?>
                    <div class="col-md-6 col-lg-3">
                        <a href="/member/<?= $member['id_anggota'] ?>" class="text-decoration-none text-dark">
                            <div class="card-modern h-100 text-center p-4 transition-hover">
                                <?php if (!empty($member['foto_profil'])): ?>
                                    <img src="/uploads/foto_profil/<?= htmlspecialchars($member['foto_profil']) ?>"
                                        class="rounded-circle mb-3 shadow-sm"
                                        style="width: 150px; height: 150px; object-fit: cover; border: 4px solid #f8f9fa;">
                                <?php else: ?>
                                    <div class="rounded-circle mb-3 shadow-sm mx-auto d-flex align-items-center justify-content-center bg-secondary text-white"
                                        style="width: 150px; height: 150px; border: 4px solid #f8f9fa; font-size: 3rem;">
                                        <?= strtoupper(substr($member['nama_lengkap'], 0, 1)) ?>
                                    </div>
                                <?php endif; ?>
                                <h4 class="fw-bold mb-1" style="font-size: 1.25rem;">
                                    <?= htmlspecialchars($member['nama_lengkap']) ?>
                                </h4>
                                <p class="text-muted mb-0" style="font-size: 0.9rem;">Anggota Laboratorium</p>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Button -->
        <a href="/about" class="btn btn-modern btn-outline-custom mt-5"
            style="border-color: #19586E; color: #19586E; text-decoration: none;">
            Lihat Semua Anggota
        </a>

    </div>
</section>

<section id="visimisi" class="section-padding">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-4">
                <div class="pe-lg-4">
                    <p class="fw-bold text-uppercase" style="color: #7ABC52; letter-spacing: 1px;">Visi & Misi</p>
                    <h2 class="fw-bold display-5 mb-4">Our Vision & Mission</h2>
                    <p class="text-muted mb-4">
                        Kami berkomitmen untuk menjadi pusat unggulan dalam riset dan pengembangan teknologi data.
                    </p>
                    <a href="/about" class="btn btn-modern btn-primary-custom" style="text-decoration: none;">
                        Selengkapnya
                    </a>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="d-flex flex-column gap-4">

                    <!-- Vision Card -->
                    <div class="card-modern p-4 mb-4 bg-white shadow-sm">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                                style="width: 50px; height: 50px; background-color: #7ABC52; color: white;">
                                <i class="bi bi-lightning-fill fs-4"></i>
                            </div>
                            <h3 class="fw-bold mb-0">Vision</h3>
                        </div>
                        <div class="p-4 rounded-3" style="background-color: #E8F5E9;">
                            <div class="text-dark mb-0" style="line-height: 1.7;">
                                <?= $visi['isi_konten'] ?>
                            </div>
                        </div>
                    </div>

                    <!-- Mission Card -->
                    <div class="card-modern p-4 bg-white shadow-sm">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                                 style="width: 50px; height: 50px; background-color: #7ABC52; color: white;">
                                <i class="bi bi-lightning-fill fs-4"></i>
                            </div>
                            <h3 class="fw-bold mb-0">Mission</h3>
                        </div>
                        
                        <?php
                        // Parse Mission Content to split by points
                        $missionPoints = [];
                        $content = $misi['isi_konten'];
                        
                        if (strpos($content, '<li') !== false) {
                            preg_match_all('/<li[^>]*>(.*?)<\/li>/is', $content, $matches);
                            $missionPoints = $matches[1];
                        } elseif (strpos($content, '<p') !== false) {
                            preg_match_all('/<p[^>]*>(.*?)<\/p>/is', $content, $matches);
                            $missionPoints = $matches[1];
                        }
                        ?>

                        <?php if (!empty($missionPoints)): ?>
                            <?php $idx = 1; foreach ($missionPoints as $point): ?>
                                <div class="p-3 rounded-3 mb-2" style="background-color: #E8F5E9;">
                                    <div class="text-dark mb-0 d-flex" style="line-height: 1.7;">
                                        <span class="fw-bold me-2"><?= $idx++ ?>.</span>
                                        <div><?= strip_tags($point, '<b><strong><i><em><u><span>') ?></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="p-4 rounded-3" style="background-color: #E8F5E9;">
                                <div class="text-dark mb-0" style="line-height: 1.7;">
                                    <?= $misi['isi_konten'] ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<section id="focusRiset" style="background: #19586E;" class="section-padding">
    <div class="container text-center">
        <div class="mb-5">
            <p class="fw-bold text-uppercase text-white-50" style="letter-spacing: 1px;">Riset Kami</p>
            <h2 class="fw-bold display-5 text-white">Fokus Riset</h2>
        </div>

        <div class="row justify-content-center g-4">
            <!-- Item -->
            <?php for ($i = 0; $i < 5; $i++): ?>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="card-modern h-100 d-flex align-items-center justify-content-center p-3"
                        style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2);">
                        <h3 class="fw-semibold text-white mb-0" style="font-size: 1.1rem;">Analisis Data</h3>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</section>

<section id="publication" class="section-padding">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <p class="fw-bold text-uppercase" style="color: #7ABC52; letter-spacing: 1px;">Publikasi</p>
                <h2 class="fw-bold display-5">Publikasi Terbaru</h2>
            </div>
            <a href="/publications" class="btn btn-modern btn-primary-custom" style="text-decoration: none;">
                Selengkapnya
            </a>
        </div>

        <div class="row g-4">
            <?php if (empty($recentPublications)): ?>
                <div class="col-12 text-center">
                    <p class="text-muted">Belum ada publikasi.</p>
                </div>
            <?php else: ?>
                <?php foreach ($recentPublications as $pub): ?>
                    <div class="col-md-6 col-lg-3">
                        <div class="card-modern h-100 d-flex flex-column">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <?php
                                    // Calculate max citations from the most cited list (since it's sorted DESC, first one is max)
                                    $maxCitations = 0;
                                    if (!empty($mostCitedPublications)) {
                                        $maxCitations = $mostCitedPublications[0]['citation_count'];
                                    }

                                    // Check if this publication has the max citations
                                    $isMostCited = ($pub['citation_count'] > 0 && $pub['citation_count'] == $maxCitations);

                                    if ($isMostCited):
                                        ?>
                                        <span class="badge rounded-pill"
                                            style="background: #d1e7dd; color: #0f5132; font-size: 0.75rem;">
                                            Most Cited
                                        </span>
                                    <?php else: ?>
                                        <!-- Spacer to keep layout consistent if needed, or just empty -->
                                        <div></div>
                                    <?php endif; ?>
                                </div>

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
            <?php endif; ?>
        </div>
    </div>
</section>

<section id="gallery" style="background: #f8f9fa;" class="section-padding">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <p class="fw-bold text-uppercase" style="color: #7ABC52; letter-spacing: 1px;">Galeri</p>
                <h2 class="fw-bold display-5">Dokumentasi Kegiatan</h2>
            </div>
            <a href="/gallery" class="btn btn-modern btn-primary-custom" style="text-decoration: none;">
                Lihat Semua
            </a>
        </div>

        <div class="row g-4">
            <?php foreach ($gallery as $photo): ?>
                <div class="col-6 col-lg-4">
                    <div class="card-modern overflow-hidden p-0 gallery-item">
                        <img src="<?= htmlspecialchars($photo['file_path']) ?>" alt="Gallery Image" class="img-fluid w-100"
                            style="object-fit: cover; height: 250px; transition: transform 0.5s ease;">
                        <div class="gallery-overlay">
                            <p class="mb-0 fw-semibold"><?= htmlspecialchars($photo['deskripsi'] ?? '') ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section id="contactUs" class="section-padding">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-5">
                <p class="fw-bold text-uppercase" style="color: #7ABC52; letter-spacing: 1px;">Contact</p>
                <h2 class="fw-bold display-5 mb-4">Let's build something meaningful together</h2>
                <p class="text-muted mb-5" style="font-size: 1.1rem;">
                    We welcome collaborations with fellow researchers, students, and industry partners. Reach out to
                    learn how we can co-create impactful data solutions.
                </p>

                <div class="d-flex align-items-center gap-4 p-4 rounded-4"
                    style="background: rgba(122, 188, 82, 0.05);">
                    <div class="feature-icon-box mb-0 bg-white shadow-sm">
                        <i class="bi bi-envelope"></i>
                    </div>
                    <div>
                        <span class="d-block fw-bold text-dark">Email Us</span>
                        <span class="text-muted">datatechlab@college.edu</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="card-modern p-5">
                    <?php if (!empty($_SESSION['flash_success'])): ?>
                        <div class="alert alert-success mt-3">
                            <?= $_SESSION['flash_success'] ?>
                        </div>
                        <?php unset($_SESSION['flash_success']); ?>
                    <?php endif; ?>
                    <form action="/contact/send" method="POST">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-bold">Full Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control p-3 bg-light border-0" name="name" id="name"
                                    placeholder="John Doe">
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-bold">Email Address <span
                                        class="text-danger">*</span></label>
                                <input type="email" class="form-control p-3 bg-light border-0" name="email" id="email"
                                    placeholder="john@example.com">
                            </div>
                            <div class="col-12">
                                <label for="organization" class="form-label fw-bold">Organization <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control p-3 bg-light border-0" name="organization"
                                    id="organization" placeholder="University / Company">
                            </div>
                            <div class="col-12">
                                <label for="message" class="form-label fw-bold">How Can we Help?</label>
                                <textarea class="form-control p-3 bg-light border-0" name="message" id="message"
                                    rows="5" placeholder="Tell us about your project..."></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-modern btn-primary-custom w-100 py-3">
                                    Send Message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>