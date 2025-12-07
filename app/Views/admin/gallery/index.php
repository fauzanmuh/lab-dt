<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="card-title mb-1 fw-bold text-dark">Manajemen Galeri</h5>
            <p class="text-muted small mb-0">Kelola galeri foto dan dokumentasi</p>
        </div>
        <button type="button" class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal"
            data-bs-target="#addPhotoModal">
            <i class="bi bi-cloud-upload"></i>
            <span>Unggah Foto</span>
        </button>
    </div>
    <div class="card-body">
        <?php if (empty($photos)): ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-images display-4 mb-3 opacity-50"></i>
                <p class="mb-0">Tidak ada foto ditemukan</p>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($photos as $photo): ?>
                    <div class="col-md-4 col-lg-3">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="position-relative">
                                <img src="/<?= htmlspecialchars($photo['file_path']) ?>" class="card-img-top"
                                    alt="<?= htmlspecialchars($photo['deskripsi']) ?>"
                                    style="height: 200px; object-fit: cover;">
                                <div class="position-absolute top-0 end-0 p-2">
                                    <?php if ($photo['status'] === 'approved'): ?>
                                        <span class="badge bg-success-subtle text-success">Disetujui</span>
                                    <?php elseif ($photo['status'] === 'rejected'): ?>
                                        <span class="badge bg-danger-subtle text-danger">Ditolak</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning-subtle text-warning">Tertunda</span>
                                    <?php endif; ?>
                                </div>
                                <?php if ($photo['status'] === 'rejected' && !empty($photo['catatan_admin'])): ?>
                                    <div
                                        class="position-absolute bottom-0 start-0 w-100 p-2 bg-danger bg-opacity-75 text-white text-xs">
                                        <i class="bi bi-exclamation-circle me-1"></i>
                                        <?= htmlspecialchars($photo['catatan_admin']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="card-body">
                                <h6 class="card-title fw-bold text-truncate"><?= htmlspecialchars($photo['deskripsi']) ?></h6>
                                <div class="d-flex align-items-center gap-2 mt-3">
                                    <div class="avatar-sm bg-light rounded-circle overflow-hidden"
                                        style="width: 32px; height: 32px;">
                                        <?php if (!empty($photo['foto_profil'])): ?>
                                            <img src="/uploads/foto_profil/<?= htmlspecialchars($photo['foto_profil']) ?>"
                                                alt="Profile" class="w-100 h-100 object-fit-cover">
                                        <?php else: ?>
                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary fw-bold"
                                                style="font-size: 0.8rem;">
                                                <?= strtoupper(substr($photo['uploader'], 0, 1)) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span
                                            class="text-sm fw-medium text-dark"><?= htmlspecialchars($photo['uploader']) ?></span>
                                        <span
                                            class="text-xs text-muted"><?= date('d M Y', strtotime($photo['tanggal_upload'])) ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-top-0 d-flex justify-content-between align-items-center">
                                <button class="btn btn-sm btn-light text-primary"
                                    onclick="editPhoto(<?= $photo['id_galeri'] ?>, '<?= htmlspecialchars($photo['deskripsi']) ?>', '<?= $photo['status'] ?>', '<?= htmlspecialchars($photo['catatan_admin'] ?? '') ?>', '<?= htmlspecialchars($photo['file_path']) ?>')"
                                    data-bs-toggle="modal" data-bs-target="#editPhotoModal" title="Edit Detail">
                                    <i class="bi bi-pencil"></i> Edit
                                </button>
                                <button type="button" class="btn btn-sm btn-light text-danger"
                                    onclick="confirmDelete(<?= $photo['id_galeri'] ?>)" title="Hapus Foto">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                                <form id="deleteForm-<?= $photo['id_galeri'] ?>"
                                    action="/admin/gallery/<?= $photo['id_galeri'] ?>/delete" method="POST" class="d-none">
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <!-- Pagination -->
    <?php include __DIR__ . '/../../partials/pagination.php'; ?>
</div>

<!-- Add Photo Modal -->
<div class="modal fade" id="addPhotoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Unggah Foto Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/admin/gallery" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="file_path" class="form-label">Berkas Foto</label>
                        <input type="file" class="form-control" id="file_path" name="file_path" accept="image/*"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="id_uploader" class="form-label">Pengunggah</label>
                        <select class="form-select" id="id_uploader" name="id_uploader" required>
                            <?php foreach ($members as $member): ?>
                                <option value="<?= $member['id_anggota'] ?>">
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
                                <option value="approved">Disetujui</option>
                                <option value="rejected">Ditolak</option>
                            </select>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light text-muted" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Unggah Foto</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Photo Modal -->
<div class="modal fade" id="editPhotoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Edit Detail Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editPhotoForm" action="" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_file_path" class="form-label">Ganti Foto (Opsional)</label>
                        <input type="file" class="form-control" id="edit_file_path" name="file_path" accept="image/*">
                        <div class="mt-2" id="current_photo_container">
                            <small class="text-muted d-block mb-1">Foto Saat Ini:</small>
                            <img src="" id="current_photo" class="img-fluid rounded border" style="max-height: 100px;">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="3"
                            required></textarea>
                    </div>
                    <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
                        <div class="mb-3">
                            <label for="edit_status" class="form-label">Status</label>
                            <select class="form-select" id="edit_status" name="status">
                                <option value="pending">Tertunda</option>
                                <option value="approved">Disetujui</option>
                                <option value="rejected">Ditolak</option>
                            </select>
                        </div>
                    <?php endif; ?>
                    <div class="mb-3" id="gallery_rejection_note_container" style="display: none;">
                        <label class="form-label text-danger">Catatan Penolakan</label>
                        <div class="alert alert-danger bg-danger-subtle border-danger text-danger p-2 mb-0 text-sm"
                            id="gallery_rejection_note"></div>
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
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-danger">Konfirmasi Penghapusan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus foto ini? Tindakan ini tidak dapat dibatalkan.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Hapus</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        let deleteId = null;
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

        // Edit Photo
        window.editPhoto = function (id, deskripsi, status, catatan, filePath) {
            $('#editPhotoForm').attr('action', '/admin/gallery/' + id + '/update');
            $('#edit_deskripsi').val(deskripsi);
            $('#edit_status').val(status);

            // Handle photo preview
            if (filePath) {
                $('#current_photo').attr('src', '/' + filePath);
                $('#current_photo_container').show();
            } else {
                $('#current_photo_container').hide();
            }

            // Handle rejection note
            if (status === 'rejected' && catatan) {
                $('#gallery_rejection_note').text(catatan);
                $('#gallery_rejection_note_container').show();
            } else {
                $('#gallery_rejection_note_container').hide();
            }
        };

        // Delete Confirmation
        window.confirmDelete = function (id) {
            deleteId = id;
            deleteModal.show();
        };

        $('#confirmDeleteBtn').click(function () {
            if (deleteId) {
                $('#deleteForm-' + deleteId).submit();
            }
        });

        // Form Validation
        $('form').on('submit', function (e) {
            const requiredInputs = $(this).find('input[required], select[required], textarea[required]');
            let isValid = true;

            requiredInputs.each(function () {
                if (!$(this).val()) {
                    isValid = false;
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Harap isi semua kolom yang wajib diisi.');
            }
        });

        // Remove invalid class on input
        $('input, select, textarea').on('input change', function () {
            $(this).removeClass('is-invalid');
        });
    });
</script>