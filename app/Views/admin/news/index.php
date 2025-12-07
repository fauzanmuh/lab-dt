<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="card-title mb-1 fw-bold text-dark">Manajemen Berita</h5>
            <p class="text-muted small mb-0">Kelola berita dan artikel lab</p>
        </div>
        <button type="button" class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal"
            data-bs-target="#addNewsModal">
            <i class="bi bi-plus-lg"></i>
            <span>Tambah Berita</span>
        </button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Berita</th>
                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Penulis</th>
                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Tanggal</th>
                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Status</th>
                        <th class="text-end pe-4 text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($news)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="bi bi-newspaper display-4 mb-3 opacity-50"></i>
                                    <p class="mb-0">Belum ada berita</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($news as $item): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <?php if (!empty($item['gambar_utama'])): ?>
                                            <img src="/<?= htmlspecialchars($item['gambar_utama']) ?>" class="rounded"
                                                style="width: 48px; height: 48px; object-fit: cover;" alt="Thumbnail">
                                        <?php else: ?>
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted"
                                                style="width: 48px; height: 48px;">
                                                <i class="bi bi-image"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold text-dark text-truncate"
                                                style="max-width: 200px;"><?= htmlspecialchars($item['judul']) ?></span>
                                            <span class="text-xs text-muted text-truncate"
                                                style="max-width: 200px;"><?= htmlspecialchars(substr(strip_tags($item['isi_berita']), 0, 50)) ?>...</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-sm bg-light rounded-circle overflow-hidden" style="width: 32px; height: 32px;">
                                            <?php if (!empty($item['foto_profil'])): ?>
                                                <img src="/uploads/foto_profil/<?= htmlspecialchars($item['foto_profil']) ?>" 
                                                     alt="Profile" class="w-100 h-100 object-fit-cover">
                                            <?php else: ?>
                                                <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary fw-bold" style="font-size: 0.8rem;">
                                                    <?= strtoupper(substr($item['penulis'] ?? 'A', 0, 1)) ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="text-sm fw-medium text-dark"><?= htmlspecialchars($item['penulis'] ?? 'Unknown') ?></span>
                                            <span class="text-xs text-muted">@<?= htmlspecialchars($item['username'] ?? '') ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span
                                        class="text-secondary text-sm"><?= date('d M Y', strtotime($item['tanggal_posting'])) ?></span>
                                </td>
                                <td>
                                    <?php
                                    $statusClass = match ($item['status']) {
                                        'approved' => 'bg-success-subtle text-success',
                                        'rejected' => 'bg-danger-subtle text-danger',
                                        default => 'bg-warning-subtle text-warning'
                                    };
                                    $statusLabel = match ($item['status']) {
                                        'approved' => 'Disetujui',
                                        'rejected' => 'Ditolak',
                                        default => 'Tertunda'
                                    };
                                    ?>
                                    <span class="badge <?= $statusClass ?> border"><?= $statusLabel ?></span>
                                    <?php if ($item['status'] === 'rejected' && !empty($item['catatan_admin'])): ?>
                                        <div class="mt-1 text-xs text-danger">
                                            <i class="bi bi-exclamation-circle me-1"></i>
                                            <?= htmlspecialchars($item['catatan_admin']) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex align-items-center gap-1 justify-content-end">
                                        <button class="btn btn-sm btn-light text-primary" data-id="<?= $item['id_berita'] ?>"
                                            data-judul="<?= htmlspecialchars($item['judul']) ?>"
                                            data-isi="<?= htmlspecialchars($item['isi_berita']) ?>"
                                            data-penulis="<?= $item['id_penulis'] ?>" data-status="<?= $item['status'] ?>"
                                            data-gambar="<?= htmlspecialchars($item['gambar_utama'] ?? '') ?>"
                                            data-catatan="<?= htmlspecialchars($item['catatan_admin'] ?? '') ?>"
                                            data-catatan="<?= htmlspecialchars($item['catatan_admin'] ?? '') ?>"
                                            onclick="editNews(this)" data-bs-toggle="modal" data-bs-target="#editNewsModal"
                                            title="Edit Berita">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-light text-danger"
                                            onclick="confirmDelete('<?= $item['id_berita'] ?>')" title="Hapus Berita">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    <form id="deleteForm-<?= $item['id_berita'] ?>"
                                        action="/admin/news/<?= $item['id_berita'] ?>/delete" method="POST" class="d-none">
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <?php include __DIR__ . '/../../partials/pagination.php'; ?>
    </div>
</div>

<!-- Add News Modal -->
<div class="modal fade" id="addNewsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Tambah Berita Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/admin/news" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="judul" class="form-label">Judul Berita</label>
                                <input type="text" class="form-control" id="judul" name="judul" required>
                            </div>
                            <div class="mb-3">
                                <label for="isi_berita" class="form-label">Isi Berita</label>
                                <textarea class="form-control" id="isi_berita" name="isi_berita" rows="10"
                                    required></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="id_penulis" class="form-label">Penulis</label>
                                <select class="form-select" id="id_penulis" name="id_penulis" required>
                                    <option value="">Pilih Penulis</option>
                                    <?php foreach ($members as $member): ?>
                                        <option value="<?= $member['id_anggota'] ?>" <?= (isset($_SESSION['user']['id']) && $_SESSION['user']['id'] == $member['id_anggota']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($member['nama_lengkap']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="pending">Tertunda</option>
                                        <option value="approved">Diterbitkan</option>
                                        <option value="rejected">Ditolak</option>
                                    </select>
                                </div>
                            <?php endif; ?>
                            <div class="mb-3">
                                <label for="gambar_utama" class="form-label">Gambar Utama</label>
                                <input type="file" class="form-control" id="gambar_utama" name="gambar_utama"
                                    accept="image/*">
                                <div class="form-text text-muted">Format: JPG, PNG. Max: 2MB</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light text-muted" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Simpan Berita</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit News Modal -->
<div class="modal fade" id="editNewsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Edit Berita</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editNewsForm" action="" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="edit_judul" class="form-label">Judul Berita</label>
                                <input type="text" class="form-control" id="edit_judul" name="judul" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_isi_berita" class="form-label">Isi Berita</label>
                                <textarea class="form-control" id="edit_isi_berita" name="isi_berita" rows="10"
                                    required></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="edit_id_penulis" class="form-label">Penulis</label>
                                <select class="form-select" id="edit_id_penulis" name="id_penulis" required>
                                    <option value="">Pilih Penulis</option>
                                    <?php foreach ($members as $member): ?>
                                        <option value="<?= $member['id_anggota'] ?>">
                                            <?= htmlspecialchars($member['nama_lengkap']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
                                <div class="mb-3">
                                    <label for="edit_status" class="form-label">Status</label>
                                    <select class="form-select" id="edit_status" name="status">
                                        <option value="pending">Tertunda</option>
                                        <option value="approved">Diterbitkan</option>
                                        <option value="rejected">Ditolak</option>
                                    </select>
                                </div>
                            <?php endif; ?>
                            <div class="mb-3">
                                <label for="edit_gambar_utama" class="form-label">Ganti Gambar</label>
                                <input type="file" class="form-control" id="edit_gambar_utama" name="gambar_utama"
                                    accept="image/*">
                                <div class="mt-2" id="current_image_container">
                                    <small class="text-muted d-block mb-1">Gambar saat ini:</small>
                                    <img src="" id="current_image" class="img-fluid rounded border"
                                        style="max-height: 100px;">
                                </div>
                            </div>
                            <div class="mb-3" id="rejection_note_container" style="display: none;">
                                <label class="form-label text-danger">Catatan Penolakan</label>
                                <div class="alert alert-danger bg-danger-subtle border-danger text-danger p-2 mb-0 text-sm"
                                    id="rejection_note"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light text-muted" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteNewsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-danger">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Apakah Anda yakin ingin menghapus berita ini? Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light text-muted" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger px-4" id="confirmDeleteBtn">Hapus</button>
            </div>
        </div>
    </div>
</div>

<script>
    let deleteNewsId = null;

    function editNews(btn) {
        const id = btn.dataset.id;
        const judul = btn.dataset.judul;
        const isi = btn.dataset.isi;
        const penulis = btn.dataset.penulis;
        const status = btn.dataset.status;
        const gambar = btn.dataset.gambar;
        const catatan = btn.dataset.catatan;

        // Update form action
        $('#editNewsForm').attr('action', '/admin/news/' + id + '/update');

        // Populate fields
        $('#edit_judul').val(judul);
        $('#edit_isi_berita').val(isi);
        $('#edit_id_penulis').val(penulis);
        $('#edit_status').val(status);

        // Handle image preview
        if (gambar) {
            $('#current_image').attr('src', '/' + gambar);
            $('#current_image_container').show();
        } else {
            $('#current_image_container').hide();
        }

        // Handle rejection note
        if (status === 'rejected' && catatan) {
            $('#rejection_note').text(catatan);
            $('#rejection_note_container').show();
        } else {
            $('#rejection_note_container').hide();
        }
    }

    function confirmDelete(id) {
        deleteNewsId = id;
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteNewsModal'));
        deleteModal.show();
    }

    $(document).ready(function () {
        // Delete confirmation handler
        $('#confirmDeleteBtn').on('click', function () {
            if (deleteNewsId) {
                $('#deleteForm-' + deleteNewsId).submit();
            }
        });
    });
</script>