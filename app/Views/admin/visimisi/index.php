<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-1 fw-bold">Manajemen Profil Lab</h5>
        <p class="text-muted small mb-0">Perbarui Visi dan Misi</p>
    </div>
    <form action="/admin/visimisi" method="POST" id="labProfileForm">
        <div class="card-body">
            <div class="mb-4">
                <h6 class="fw-bold mb-3">Visi</h6>
                <?php
                $visiData = null;
                foreach ($visiMisi as $item) {
                    if ($item['jenis_konten'] === 'visi') {
                        $visiData = $item;
                        break;
                    }
                }
                ?>
                <textarea class="form-control" name="visi" id="visi_editor" rows="5"
                    placeholder="Masukkan pernyataan visi laboratorium"><?= $visiData ? htmlspecialchars($visiData['isi_konten']) : '' ?></textarea>
                <small class="text-muted d-block mt-2">Pernyataan visi utama laboratorium.</small>
            </div>

            <div class="mb-4">
                <h6 class="fw-bold mb-3">Misi</h6>
                <?php
                $misiData = null;
                foreach ($visiMisi as $item) {
                    if ($item['jenis_konten'] === 'misi') {
                        $misiData = $item;
                        break;
                    }
                }
                ?>
                <textarea class="form-control" name="misi" id="misi_editor" rows="5"
                    placeholder="Masukkan pernyataan misi laboratorium"><?= $misiData ? htmlspecialchars($misiData['isi_konten']) : '' ?></textarea>
                <small class="text-muted d-block mt-2">Poin-poin misi terperinci.</small>
            </div>
        </div>
        <div class="card-footer bg-light d-flex justify-content-end">
            <button type="submit" class="btn btn-primary" id="saveBtn">
                <i class="bi bi-check2 me-1"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function () {
        // --- INISIALISASI SUMMERNOTE ---
        $('#visi_editor').summernote({
            placeholder: 'Masukkan Visi...',
            tabsize: 2,
            height: 200
        });
        $('#misi_editor').summernote({
            placeholder: 'Masukkan Misi...',
            tabsize: 2,
            height: 200
        });
    });
</script>