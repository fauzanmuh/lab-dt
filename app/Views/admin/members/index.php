<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="card-title mb-1 fw-bold text-dark">Daftar Anggota</h5>
            <p class="text-muted small mb-0">Manajemen anggota lab</p>
        </div>
        <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
            <button type="button" class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal"
                data-bs-target="#addMemberModal">
                <i class="bi bi-plus-lg"></i>
                <span>Tambah Anggota</span>
            </button>
        <?php endif; ?>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Anggota</th>
                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Email</th>
                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">NIP/NIM</th>
                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Role</th>
                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Status</th>
                        <th class="text-end pe-4 text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                            Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($members)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="bi bi-people display-4 mb-3 opacity-50"></i>
                                    <p class="mb-0">Anggota tidak ditemukan</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($members as $member): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar overflow-hidden">
                                            <?php if (!empty($member['foto_profil'])): ?>
                                                <img src="/uploads/foto_profil/<?= htmlspecialchars($member['foto_profil']) ?>"
                                                    alt="Profile" class="w-100 h-100 object-fit-cover">
                                            <?php else: ?>
                                                <?= strtoupper(substr($member['nama_lengkap'], 0, 1)) ?>
                                            <?php endif; ?>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span
                                                class="fw-bold text-dark"><?= htmlspecialchars($member['nama_lengkap']) ?></span>
                                            <span
                                                class="text-xs text-muted">@<?= htmlspecialchars($member['username']) ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-secondary text-sm"><?= htmlspecialchars($member['email']) ?></span>
                                </td>
                                <td>
                                    <span
                                        class="text-secondary text-sm"><?= htmlspecialchars($member['nip_nim'] ?? '-') ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border"><?= ucfirst($member['role']) ?></span>
                                </td>
                                <td>
                                    <?php if ($member['status_aktif']): ?>
                                        <span class="badge bg-success-subtle text-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger-subtle text-danger">Tidak Aktif</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex align-items-center gap-1 justify-content-end">
                                        <button class="btn btn-sm btn-light text-primary"
                                            onclick="editMember('<?= $member['id_anggota'] ?>', '<?= htmlspecialchars($member['nama_lengkap']) ?>', '<?= htmlspecialchars($member['username']) ?>', '<?= htmlspecialchars($member['email']) ?>', '<?= htmlspecialchars($member['nip_nim'] ?? '') ?>', '<?= $member['role'] ?>', <?= $member['status_aktif'] ? 'true' : 'false' ?>)"
                                            data-bs-toggle="modal" data-bs-target="#editMemberModal" title="Edit Member">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
                                            <button type="button" class="btn btn-sm btn-light text-danger"
                                                onclick="confirmDelete('<?= $member['id_anggota'] ?>')" title="Delete Member">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                    <form id="deleteForm-<?= $member['id_anggota'] ?>"
                                        action="/admin/members/<?= $member['id_anggota'] ?>/delete" method="POST"
                                        class="d-none">
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

<!-- Add Member Modal -->
<div class="modal fade" id="addMemberModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Tambah Anggota Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/admin/members" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i
                                    class="bi bi-person text-muted"></i></span>
                            <input type="text" class="form-control border-start-0 ps-0" id="nama_lengkap"
                                name="nama_lengkap" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="foto_profil" class="form-label">Foto Profil</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i
                                    class="bi bi-image text-muted"></i></span>
                            <input type="file" class="form-control border-start-0 ps-0" id="foto_profil"
                                name="foto_profil" accept="image/*">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i
                                    class="bi bi-at text-muted"></i></span>
                            <input type="text" class="form-control border-start-0 ps-0" id="username" name="username"
                                required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i
                                    class="bi bi-envelope text-muted"></i></span>
                            <input type="email" class="form-control border-start-0 ps-0" id="email" name="email"
                                required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="nip_nim" class="form-label">NIP / NIM</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i
                                    class="bi bi-card-heading text-muted"></i></span>
                            <input type="text" class="form-control border-start-0 ps-0" id="nip_nim" name="nip_nim">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" name="role">
                            <option value="operator">Operator</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i
                                    class="bi bi-lock text-muted"></i></span>
                            <input type="password" class="form-control border-start-0 ps-0" id="password"
                                name="password" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light text-muted" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Member Modal -->
<div class="modal fade" id="editMemberModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Edit Anggota</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editMemberForm" action="" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_nama_lengkap" class="form-label">Nama Lengkap</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i
                                    class="bi bi-person text-muted"></i></span>
                            <input type="text" class="form-control border-start-0 ps-0" id="edit_nama_lengkap"
                                name="nama_lengkap" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_foto_profil" class="form-label">Foto Profil (Biarkan kosong jika tidak ingin
                            mengubah)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i
                                    class="bi bi-image text-muted"></i></span>
                            <input type="file" class="form-control border-start-0 ps-0" id="edit_foto_profil"
                                name="foto_profil" accept="image/*">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_username" class="form-label">Username</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i
                                    class="bi bi-at text-muted"></i></span>
                            <input type="text" class="form-control border-start-0 ps-0" id="edit_username"
                                name="username" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i
                                    class="bi bi-envelope text-muted"></i></span>
                            <input type="email" class="form-control border-start-0 ps-0" id="edit_email" name="email"
                                required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_nip_nim" class="form-label">NIP / NIM</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i
                                    class="bi bi-card-heading text-muted"></i></span>
                            <input type="text" class="form-control border-start-0 ps-0" id="edit_nip_nim"
                                name="nip_nim">
                        </div>
                    </div>
                    <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
                        <div class="mb-3">
                            <label for="edit_role" class="form-label">Role</label>
                            <select class="form-select" id="edit_role" name="role">
                                <option value="operator">Operator</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_status_aktif" class="form-label">Status</label>
                            <select class="form-select" id="edit_status_aktif" name="status_aktif">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <label for="edit_password" class="form-label">Password (Kosongkan jika tidak ingin
                            mengubah)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i
                                    class="bi bi-lock text-muted"></i></span>
                            <input type="password" class="form-control border-start-0 ps-0" id="edit_password"
                                name="password">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light text-muted" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteMemberModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-danger">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Apakah Anda yakin ingin menghapus anggota ini? Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light text-muted" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger px-4" id="confirmDeleteBtn">Hapus</button>
            </div>
        </div>
    </div>
</div>

<script>
    let deleteMemberId = null;

    $(document).ready(function () {
        // Validation logic
        function validateForm(form) {
            let isValid = true;
            $(form).find('input[required], select[required]').each(function () {
                if ($(this).val() === '') {
                    isValid = false;
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });
            return isValid;
        }

        // Add Member
        $('#addMemberForm').on('submit', function (e) {
            if (!validateForm(this)) {
                e.preventDefault();
                alert('Please fill in all required fields.');
            }
        });

        // Edit Member
        $('#editMemberForm').on('submit', function (e) {
            if (!validateForm(this)) {
                e.preventDefault();
                alert('Please fill in all required fields.');
            }
        });

        // Delete Member
        $('#confirmDeleteBtn').on('click', function () {
            if (deleteMemberId) {
                $('#deleteForm-' + deleteMemberId).submit();
            }
        });

        // Remove invalid class on input
        $('input, select').on('input change', function () {
            $(this).removeClass('is-invalid');
        });
    });

    function editMember(id, nama, username, email, nip, role, status) {
        // Update form action for POST update
        $('#editMemberForm').attr('action', '/admin/members/' + id + '/update');
        $('#edit_email').val(email);
        $('#edit_nama_lengkap').val(nama);
        $('#edit_username').val(username);
        $('#edit_nip_nim').val(nip);
        $('#edit_role').val(role);
        $('#edit_status_aktif').val(status ? '1' : '0');
    }

    function confirmDelete(id) {
        deleteMemberId = id;
        const deleteModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('deleteMemberModal'));
        deleteModal.show();
    }
</script>