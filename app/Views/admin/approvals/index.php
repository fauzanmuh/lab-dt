<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1">Persetujuan Tertunda</h4>
                <p class="text-muted mb-0">Tinjau dan kelola kiriman yang menunggu persetujuan Anda.</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0">
                <ul class="nav nav-pills nav-fill gap-2 p-1 bg-light rounded-pill" id="approvalTabs" role="tablist"
                    style="max-width: 600px;">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill active py-2 fw-semibold" id="news-tab" data-bs-toggle="tab"
                            data-bs-target="#news" type="button" role="tab" aria-controls="news" aria-selected="true">
                            Berita
                            <?php if (count($pendingNews) > 0): ?>
                                <span class="badge bg-danger rounded-pill ms-2"><?= count($pendingNews) ?></span>
                            <?php endif; ?>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill py-2 fw-semibold" id="gallery-tab" data-bs-toggle="tab"
                            data-bs-target="#gallery" type="button" role="tab" aria-controls="gallery"
                            aria-selected="false">
                            Galeri
                            <?php if (count($pendingGallery) > 0): ?>
                                <span class="badge bg-danger rounded-pill ms-2"><?= count($pendingGallery) ?></span>
                            <?php endif; ?>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill py-2 fw-semibold" id="publications-tab"
                            data-bs-toggle="tab" data-bs-target="#publications" type="button" role="tab"
                            aria-controls="publications" aria-selected="false">
                            Publikasi
                            <?php if (count($pendingPublications) > 0): ?>
                                <span class="badge bg-danger rounded-pill ms-2"><?= count($pendingPublications) ?></span>
                            <?php endif; ?>
                        </button>
                    </li>
                </ul>
            </div>

            <div class="card-body p-4">
                <div class="tab-content" id="approvalTabsContent">
                    <!-- News Tab -->
                    <div class="tab-pane fade show active" id="news" role="tabpanel" aria-labelledby="news-tab">
                        <?php if (empty($pendingNews)): ?>
                            <div class="text-center py-5">
                                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                    style="width: 80px; height: 80px;">
                                    <i class="bi bi-check-lg text-success fs-1"></i>
                                </div>
                                <h5 class="fw-bold text-dark">Semua Beres!</h5>
                                <p class="text-muted">Tidak ada berita yang menunggu persetujuan saat ini.</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 border-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th
                                                class="ps-4 py-3 text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                Artikel</th>
                                            <th
                                                class="py-3 text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                Penulis</th>
                                            <th
                                                class="py-3 text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                Tanggal</th>
                                            <th
                                                class="text-end pe-4 py-3 text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($pendingNews as $item): ?>
                                            <tr>
                                                <td class="ps-4 py-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-3">
                                                            <?php if (!empty($item['gambar_utama'])): ?>
                                                                <img src="/<?= htmlspecialchars($item['gambar_utama']) ?>"
                                                                    class="rounded-3 shadow-sm"
                                                                    style="width: 60px; height: 60px; object-fit: cover;">
                                                            <?php else: ?>
                                                                <div class="bg-light rounded-3 d-flex align-items-center justify-content-center text-secondary"
                                                                    style="width: 60px; height: 60px;">
                                                                    <i class="bi bi-image fs-4"></i>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div>
                                                            <h6 class="fw-bold text-dark mb-1">
                                                                <?= htmlspecialchars($item['judul']) ?>
                                                            </h6>
                                                            <span
                                                                class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 rounded-pill px-2">Pending</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="py-3">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="avatar-sm bg-light rounded-circle overflow-hidden"
                                                            style="width: 32px; height: 32px;">
                                                            <?php if (!empty($item['foto_profil'])): ?>
                                                                <img src="/uploads/foto_profil/<?= htmlspecialchars($item['foto_profil']) ?>"
                                                                    alt="Profile" class="w-100 h-100 object-fit-cover">
                                                            <?php else: ?>
                                                                <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary fw-bold"
                                                                    style="font-size: 0.8rem;">
                                                                    <?= strtoupper(substr($item['penulis'], 0, 1)) ?>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <span
                                                                class="fw-medium text-dark"><?= htmlspecialchars($item['penulis']) ?></span>
                                                            <span
                                                                class="text-xs text-muted">@<?= htmlspecialchars($item['username'] ?? '') ?></span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="py-3 text-secondary">
                                                    <?= date('d M Y', strtotime($item['tanggal_posting'])) ?>
                                                </td>
                                                <td class="text-end pe-4 py-3">
                                                    <div class="d-flex justify-content-end gap-2">
                                                        <form action="/admin/approvals/news/<?= $item['id_berita'] ?>/approve"
                                                            method="POST">
                                                            <button type="submit"
                                                                class="btn btn-success btn-sm px-3 fw-semibold shadow-sm d-flex align-items-center gap-2">
                                                                <i class="bi bi-check-lg"></i> Setujui
                                                            </button>
                                                        </form>
                                                        <button type="button"
                                                            class="btn btn-outline-danger btn-sm px-3 fw-semibold shadow-sm d-flex align-items-center gap-2"
                                                            onclick="openRejectModal('news', <?= $item['id_berita'] ?>)">
                                                            <i class="bi bi-x-lg"></i> Tolak
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Gallery Tab -->
                    <div class="tab-pane fade" id="gallery" role="tabpanel" aria-labelledby="gallery-tab">
                        <?php if (empty($pendingGallery)): ?>
                            <div class="text-center py-5">
                                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                    style="width: 80px; height: 80px;">
                                    <i class="bi bi-check-lg text-success fs-1"></i>
                                </div>
                                <h5 class="fw-bold text-dark">Semua Beres!</h5>
                                <p class="text-muted">Tidak ada foto yang menunggu persetujuan saat ini.</p>
                            </div>
                        <?php else: ?>
                            <div class="row g-4">
                                <?php foreach ($pendingGallery as $item): ?>
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden card-hover-effect">
                                            <div class="position-relative">
                                                <img src="/<?= htmlspecialchars($item['file_path']) ?>" class="card-img-top"
                                                    style="height: 220px; object-fit: cover;" alt="Gallery Image">
                                                <div class="position-absolute top-0 end-0 m-3">
                                                    <span class="badge bg-warning text-dark shadow-sm">Pending</span>
                                                </div>
                                            </div>
                                            <div class="card-body p-3">
                                                <div class="d-flex align-items-center gap-2 mb-3">
                                                    <div class="avatar-sm bg-light rounded-circle overflow-hidden" style="width: 32px; height: 32px;">
                                                        <?php if (!empty($item['foto_profil'])): ?>
                                                            <img src="/uploads/foto_profil/<?= htmlspecialchars($item['foto_profil']) ?>" 
                                                                 alt="Profile" class="w-100 h-100 object-fit-cover">
                                                        <?php else: ?>
                                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary fw-bold" style="font-size: 0.8rem;">
                                                                <?= strtoupper(substr($item['uploader'], 0, 1)) ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div>
                                                        <small class="text-muted d-block" style="font-size: 0.75rem;">Diunggah oleh</small>
                                                        <span class="fw-semibold text-dark small"><?= htmlspecialchars($item['uploader']) ?></span>
                                                    </div>
                                                </div>
                                                <div class="d-grid gap-2">
                                                    <form action="/admin/approvals/gallery/<?= $item['id_galeri'] ?>/approve"
                                                        method="POST" class="d-grid">
                                                        <button type="submit"
                                                            class="btn btn-success btn-sm fw-semibold shadow-sm">
                                                            <i class="bi bi-check-lg me-1"></i> Setujui Foto
                                                        </button>
                                                    </form>
                                                    <button type="button"
                                                        class="btn btn-outline-danger btn-sm fw-semibold shadow-sm"
                                                        onclick="openRejectModal('gallery', <?= $item['id_galeri'] ?>)">
                                                        <i class="bi bi-x-lg me-1"></i> Tolak
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Publications Tab -->
                    <div class="tab-pane fade" id="publications" role="tabpanel" aria-labelledby="publications-tab">
                        <?php if (empty($pendingPublications)): ?>
                            <div class="text-center py-5">
                                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                    style="width: 80px; height: 80px;">
                                    <i class="bi bi-check-lg text-success fs-1"></i>
                                </div>
                                <h5 class="fw-bold text-dark">Semua Beres!</h5>
                                <p class="text-muted">Tidak ada publikasi yang menunggu persetujuan saat ini.</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 border-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="ps-4 py-3 text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Judul Publikasi</th>
                                            <th class="py-3 text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Penulis</th>
                                            <th class="py-3 text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Tahun</th>
                                            <th class="text-end pe-4 py-3 text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($pendingPublications as $item): ?>
                                            <tr>
                                                <td class="ps-4 py-3">
                                                    <div class="d-flex flex-column">
                                                        <span class="fw-bold text-dark mb-1"><?= htmlspecialchars($item['judul_publikasi']) ?></span>
                                                        <div class="d-flex gap-2 align-items-center">
                                                            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 rounded-pill px-2">Pending</span>
                                                            <?php if (!empty($item['link_publikasi'])): ?>
                                                                <a href="<?= htmlspecialchars($item['link_publikasi']) ?>" target="_blank" class="text-xs text-primary text-decoration-none fw-medium">
                                                                    <i class="bi bi-link-45deg"></i> Lihat Tautan
                                                                </a>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="py-3">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="avatar-sm bg-light rounded-circle overflow-hidden" style="width: 32px; height: 32px;">
                                                            <?php if (!empty($item['foto_profil'])): ?>
                                                                <img src="/uploads/foto_profil/<?= htmlspecialchars($item['foto_profil']) ?>" 
                                                                     alt="Profile" class="w-100 h-100 object-fit-cover">
                                                            <?php else: ?>
                                                                <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary fw-bold" style="font-size: 0.8rem;">
                                                                    <?= strtoupper(substr($item['nama_penulis'], 0, 1)) ?>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <span class="fw-medium text-dark"><?= htmlspecialchars($item['nama_penulis']) ?></span>
                                                            <span class="text-xs text-muted">@<?= htmlspecialchars($item['username'] ?? '') ?></span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="py-3">
                                                    <span class="badge bg-light text-dark border fw-normal px-3 py-2 rounded-pill">
                                                        <?= htmlspecialchars($item['tahun_terbit']) ?>
                                                    </span>
                                                </td>
                                                <td class="text-end pe-4 py-3">
                                                    <div class="d-flex justify-content-end gap-2">
                                                        <form action="/admin/approvals/publication/<?= $item['id_publikasi'] ?>/approve" method="POST">
                                                            <button type="submit" class="btn btn-success btn-sm px-3 fw-semibold shadow-sm d-flex align-items-center gap-2">
                                                                <i class="bi bi-check-lg"></i> Setujui
                                                            </button>
                                                        </form>
                                                        <button type="button" class="btn btn-outline-danger btn-sm px-3 fw-semibold shadow-sm d-flex align-items-center gap-2" onclick="openRejectModal('publication', <?= $item['id_publikasi'] ?>)">
                                                            <i class="bi bi-x-lg"></i> Tolak
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-danger">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>Tolak Kiriman
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="rejectForm" action="" method="POST">
                <div class="modal-body pt-3">
                    <p class="text-muted small mb-3">Mohon berikan alasan penolakan agar pengirim dapat memperbaiki
                        kiriman mereka.</p>
                    <div class="mb-3">
                        <label for="catatan_admin" class="form-label fw-semibold">Alasan Penolakan</label>
                        <textarea class="form-control bg-light border-0 p-3" id="catatan_admin" name="catatan_admin"
                            rows="4" required
                            placeholder="Contoh: Gambar kurang jelas, Judul mengandung typo, dll..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0 pb-4 px-4">
                    <button type="button" class="btn btn-light text-muted fw-semibold px-4 rounded-3"
                        data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger fw-semibold px-4 rounded-3 shadow-sm">
                        <i class="bi bi-x-circle me-2"></i>Konfirmasi Penolakan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openRejectModal(type, id) {
        const form = document.getElementById('rejectForm');
        form.action = `/admin/approvals/${type}/${id}/reject`;
        const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
        modal.show();
    }
</script>

<style>
    .nav-pills .nav-link {
        color: #64748b;
        transition: all 0.2s ease;
    }

    .nav-pills .nav-link:hover {
        color: #1e293b;
        background-color: rgba(0, 0, 0, 0.05);
    }

    .nav-pills .nav-link.active {
        background-color: #19586E;
        /* Theme Blue */
        color: white;
        box-shadow: 0 4px 6px -1px rgba(25, 88, 110, 0.3);
    }

    .card-hover-effect {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card-hover-effect:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
    }
</style>