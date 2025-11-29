<section class="bg-white" style="min-height: 100vh;">
    <div class="container py-5">
        <h2 class="text-vision-gradient mb-5" style="font-size: 40px; font-weight: bold;">Daftar Publikasi
        </h2>

        <!-- Filters -->
        <div class="p-2 border rounded-3">
            <form action="">
                <div class="row g-2">
                    <!-- Search input -->
                    <div class="col-lg-6">
                        <div class="input-group">
                            <input type="text" class="form-control" style="background-color: #F0F0F0;"
                                placeholder="Search by keywoard, title, etc." aria-label="Search"
                                aria-describedby="button-search">
                            <button class="btn btn-secondary" type="submit" id="button-search">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                    <!-- Year Select Filter -->
                    <div class="col-lg-3">
                        <select class="form-select" aria-label="Year select" style="background-color: #F0F0F0;">
                            <option value="" disabled="" selected>Year</option>
                            <option value="2025">2025</option>
                            <option value="2024">2024</option>
                            <option value="2023">2023</option>
                            <option value="2022">2022</option>
                            <option value="2021">2021</option>
                        </select>
                    </div>
                    <!-- Author Filter(nanti pake select2) -->
                    <div class="col-lg-3">
                        <select class="form-select" aria-label="Author select" style="background-color: #F0F0F0;">
                            <option value="" disabled="" selected>Author</option>
                            <option value="Fabrizio">Fabrizio</option>
                            <option value="Romano">Romano</option>
                        </select>
                    </div>
                    <!-- Research Area -->
                    <div class="col-lg-3">
                        <select class="form-select" aria-label="area select"
                            style="background-color: #F0F0F0; color: #314755;">
                            <option value="" disabled="" selected>Research Area</option>
                            <option value="Malang">Malang</option>
                            <option value="Jakarta">Jakarta</option>
                        </select>
                    </div>
                    <!-- Created date -->
                    <div class="col-lg-3">
                        <select class="form-select" aria-label="created select"
                            style="background-color: #F0F0F0; color: #314755;">
                            <option value="" disabled="" selected>Sort by: Newest</option>
                            <option value="Newest">Newest</option>
                            <option value="Oldest">Oldest</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>

        <div class="d-flex flex-column">
            <div class="d-flex flex-column">
                <?php if (empty($publications)): ?>
                    <div class="mt-4 border rounded-3 p-3 text-center">
                        <p class="text-muted mb-0">Belum ada publikasi.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($publications as $pub): ?>
                        <div class="mt-4 border rounded-3 p-3">
                            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-center gap-3">
                                <div>
                                    <div class="d-flex align-items-center gap-2 fw-bold">
                                        <p class="m-0" style="font-size: 12px; color: #575757;">
                                            <?= htmlspecialchars($pub['tahun_terbit']) ?>
                                        </p>
                                    </div>
                                    <h2 class="all-text-gradient" style="font-size: 16px;">
                                        <?= htmlspecialchars($pub['judul_publikasi']) ?>
                                    </h2>
                                    <h2 class="fw-semibold" style="font-size: 12px; color: #575757;">
                                        Author: <?= htmlspecialchars($pub['nama_penulis']) ?>
                                    </h2>
                                    <p class="fw-semibold" style="font-size: 12px; color: #575757;">
                                        <?= htmlspecialchars($pub['deskripsi'] ?? 'Tidak ada deskripsi.') ?>
                                    </p>
                                    <div class="d-flex align-items-center gap-2">
                                        <?php if (!empty($pub['link_publikasi'])): ?>
                                            <a href="<?= htmlspecialchars($pub['link_publikasi']) ?>" target="_blank"
                                                class="btn mt-2 px-4 py-2 fw-semibold text-white animate-fade rounded-3"
                                                style="animation-delay: .4s; background: #7ABC52; width: fit-content;">
                                                <i class="bi bi-link fw-semibold me-1"></i>
                                                View Link
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <img src="/assets/images/frame.png" alt="" style="width: 252px; height: 160px;"
                                    class="img-fluid">
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>


        <!-- Pagination -->
        <div class="mt-4">
            <?= $pagination->renderPublic($baseUrl) ?>
        </div>
</section>