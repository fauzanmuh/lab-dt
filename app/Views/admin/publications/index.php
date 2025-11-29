<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="card-title mb-1 fw-bold text-dark">Publications List</h5>
            <p class="text-muted small mb-0">Manage research publications and journals</p>
        </div>
        <button type="button" class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal"
            data-bs-target="#addPublicationModal">
            <i class="bi bi-plus-lg"></i>
            <span>Add Publication</span>
        </button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Title</th>
                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Citations</th>
                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Year</th>
                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Author</th>
                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Status</th>
                        <th class="text-end pe-4 text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                            Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($publications)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="bi bi-journal-text display-4 mb-3 opacity-50"></i>
                                    <p class="mb-0">No publications found</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($publications as $pub): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold text-dark text-truncate"
                                            style="max-width: 200px;"><?= htmlspecialchars($pub['judul_publikasi']) ?></span>
                                    </div>
                                <td>
                                    <span class="text-secondary text-sm"><?= $pub['citation_count'] ?? 0 ?></span>
                                </td>

                                <td>
                                    <span class="text-secondary text-sm"><?= $pub['tahun_terbit'] ?></span>
                                </td>
                                <td>
                                    <span class="text-secondary text-sm"><?= htmlspecialchars($pub['nama_penulis']) ?></span>
                                </td>
                                <td>
                                    <?php if ($pub['status'] === 'approved'): ?>
                                        <span class="badge bg-success-subtle text-success">Approved</span>
                                    <?php elseif ($pub['status'] === 'rejected'): ?>
                                        <span class="badge bg-danger-subtle text-danger">Rejected</span>
                                        <?php if (!empty($pub['catatan_admin'])): ?>
                                            <div class="mt-1 text-xs text-danger">
                                                <i class="bi bi-exclamation-circle me-1"></i>
                                                <?= htmlspecialchars($pub['catatan_admin']) ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="badge bg-warning-subtle text-warning">Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex align-items-center gap-1 justify-content-end">
                                        <?php if (!empty($pub['link_publikasi'])): ?>
                                            <a href="<?= htmlspecialchars($pub['link_publikasi']) ?>" target="_blank"
                                                class="btn btn-sm btn-light text-info" title="View Link">
                                                <i class="bi bi-link-45deg"></i>
                                            </a>
                                        <?php endif; ?>
                                        <button class="btn btn-sm btn-light text-primary"
                                            onclick="editPublication(<?= $pub['id_publikasi'] ?>, '<?= htmlspecialchars($pub['judul_publikasi']) ?>', <?= $pub['tahun_terbit'] ?>, '<?= htmlspecialchars($pub['link_publikasi'] ?? '') ?>', '<?= htmlspecialchars($pub['deskripsi'] ?? '') ?>', <?= $pub['id_anggota'] ?>, '<?= $pub['status'] ?>', '<?= htmlspecialchars($pub['catatan_admin'] ?? '') ?>', <?= $pub['citation_count'] ?? 0 ?>)"
                                            data-bs-toggle="modal" data-bs-target="#editPublicationModal"
                                            title="Edit Publication">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-light text-danger"
                                            onclick="confirmDelete(<?= $pub['id_publikasi'] ?>)" title="Delete Publication">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    <form id="deleteForm-<?= $pub['id_publikasi'] ?>"
                                        action="/admin/publications/<?= $pub['id_publikasi'] ?>/delete" method="POST"
                                        class="d-none"></form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Publication Modal -->
<div class="modal fade" id="addPublicationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Add New Publication</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/admin/publications" method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="judul_publikasi" class="form-label">Title</label>
                            <input type="text" class="form-control" id="judul_publikasi" name="judul_publikasi"
                                required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="citation_count" class="form-label">Citations</label>
                            <input type="number" class="form-control" id="citation_count" name="citation_count"
                                value="0" min="0">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="tahun_terbit" class="form-label">Year</label>
                            <input type="number" class="form-control" id="tahun_terbit" name="tahun_terbit"
                                value="<?= date('Y') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="id_anggota" class="form-label">Author</label>
                            <select class="form-select" id="id_anggota" name="id_anggota" required>
                                <?php foreach ($members as $member): ?>
                                    <option value="<?= $member['id_anggota'] ?>">
                                        <?= htmlspecialchars($member['nama_lengkap']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>
                        <?php endif; ?>
                        <div class="col-md-12 mb-3">
                            <label for="link_publikasi" class="form-label">Link (Optional)</label>
                            <input type="url" class="form-control" id="link_publikasi" name="link_publikasi"
                                placeholder="https://...">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="deskripsi" class="form-label">Description (Optional)</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light text-muted" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Create Publication</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Publication Modal -->
<div class="modal fade" id="editPublicationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Edit Publication</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editPublicationForm" action="" method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="edit_judul_publikasi" class="form-label">Title</label>
                            <input type="text" class="form-control" id="edit_judul_publikasi" name="judul_publikasi"
                                required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_citation_count" class="form-label">Citations</label>
                            <input type="number" class="form-control" id="edit_citation_count" name="citation_count"
                                min="0">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_tahun_terbit" class="form-label">Year</label>
                            <input type="number" class="form-control" id="edit_tahun_terbit" name="tahun_terbit"
                                required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_id_anggota" class="form-label">Author</label>
                            <select class="form-select" id="edit_id_anggota" name="id_anggota" disabled>
                                <?php foreach ($members as $member): ?>
                                    <option value="<?= $member['id_anggota'] ?>">
                                        <?= htmlspecialchars($member['nama_lengkap']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text">Author cannot be changed.</div>
                        </div>
                        <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
                            <div class="col-md-6 mb-3">
                                <label for="edit_status" class="form-label">Status</label>
                                <select class="form-select" id="edit_status" name="status">
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>
                        <?php endif; ?>
                        <div class="col-md-12 mb-3">
                            <label for="edit_link_publikasi" class="form-label">Link (Optional)</label>
                            <input type="url" class="form-control" id="edit_link_publikasi" name="link_publikasi">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="edit_deskripsi" class="form-label">Description (Optional)</label>
                            <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="3"></textarea>
                        </div>
                        <div class="col-md-12 mb-3" id="pub_rejection_note_container" style="display: none;">
                            <label class="form-label text-danger">Rejection Note</label>
                            <div class="alert alert-danger bg-danger-subtle border-danger text-danger p-2 mb-0 text-sm"
                                id="pub_rejection_note"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light text-muted" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Update Changes</button>
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
                <h5 class="modal-title fw-bold text-danger">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this publication? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        let deleteId = null;
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

        // Edit Publication
        window.editPublication = function (id, judul, tahun, link, deskripsi, id_anggota, status, catatan, citations) {
            $('#editPublicationForm').attr('action', '/admin/publications/' + id + '/update');
            $('#edit_judul_publikasi').val(judul);
            $('#edit_tahun_terbit').val(tahun);
            $('#edit_link_publikasi').val(link);
            $('#edit_deskripsi').val(deskripsi);
            $('#edit_id_anggota').val(id_anggota);
            $('#edit_status').val(status);
            $('#edit_citation_count').val(citations);

            // Handle rejection note
            if (status === 'rejected' && catatan) {
                $('#pub_rejection_note').text(catatan);
                $('#pub_rejection_note_container').show();
            } else {
                $('#pub_rejection_note_container').hide();
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
                alert('Please fill in all required fields.');
            }
        });

        // Remove invalid class on input
        $('input, select, textarea').on('input change', function () {
            $(this).removeClass('is-invalid');
        });
    });
</script>