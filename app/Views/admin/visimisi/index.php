<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-1 fw-bold">Lab Profile Management</h5>
        <p class="text-muted small mb-0">Update Visi, Misi</p>
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
                <small class="text-muted d-block mt-2">The main vision statement of the laboratory.</small>
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
                <small class="text-muted d-block mt-2">Detailed mission points.</small>
            </div>
        </div>
        <div class="card-footer bg-light d-flex justify-content-end">
            <button type="submit" class="btn btn-primary" id="saveBtn">
                <i class="bi bi-check2 me-1"></i> Save Changes
            </button>
        </div>
    </form>
</div>

<div aria-live="polite" aria-atomic="true" class="position-relative">
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1050; margin-top: 60px;">
        <div id="successToast" class="toast align-items-center text-dark bg-light border-primary" role="alert"
            aria-live="assertive" aria-atomic="true" data-bs-delay="2000">

            <div class="d-flex">
                <div class="toast-body d-flex align-items-center">
                    <i class="bi bi-check-circle-fill me-2 fs-5 text-primary"></i>
                    <span><strong>Berhasil!</strong> Visi dan Misi berhasil disimpan.</span>
                </div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
</div>


<script>
    // Variabel Global
    let originalVisi = '';
    let originalMisi = '';

    // Deklarasikan successToast di scope yang lebih luas
    let successToast;

    $(document).ready(function () {
        // --- 0. INISIALISASI SUMMERNOTE ---
        $('#visi_editor').summernote({
            placeholder: 'Masukkan Visi...',
            tabsize: 2,
            height: 200,
            callbacks: {
                onChange: function (contents, $editable) { updateSubmitButtonState(); }
            }
        });
        $('#misi_editor').summernote({
            placeholder: 'Masukkan Misi...',
            tabsize: 2,
            height: 200,
            callbacks: {
                onChange: function (contents, $editable) { updateSubmitButtonState(); }
            }
        });
        // --- END SUMMERNOTE INIT ---

        // --- TOAST INITIALIZATION ---
        const successToastEl = document.getElementById('successToast');
        if (successToastEl) {
            // Inisialisasi Toast dengan opsi (opsional, bisa juga hanya new bootstrap.Toast(successToastEl))
            successToast = new bootstrap.Toast(successToastEl, { delay: 4000 });

            // Event listener untuk memicu reload setelah Toast tertutup
            // Halaman akan reload setelah durasi data-bs-delay (4 detik) selesai
            $('#successToast').one('hidden.bs.toast', function () {
                window.location.reload();
            });
        }
        // --- END TOAST INITIALIZATION ---

        // --- 1. INISIALISASI DATA AWAL ---
        originalVisi = $('#visi_editor').summernote('code');
        originalMisi = $('#misi_editor').summernote('code');

        updateSubmitButtonState();

        // --- 2. FUNGSI HELPER VISUAL/VALIDASI ---
        function validateForm(form) {
            const visiContent = $('#visi_editor').summernote('isEmpty') ? '' : $('#visi_editor').summernote('code');
            const misiContent = $('#misi_editor').summernote('isEmpty') ? '' : $('#misi_editor').summernote('code');

            if ($.trim(visiContent) === '' || $.trim(misiContent) === '') {
                // (Tambahkan feedback visual di sini jika validasi gagal)
                return false;
            }
            return true;
        }

        function updateSubmitButtonState() {
            const currentVisi = $('#visi_editor').summernote('code');
            const currentMisi = $('#misi_editor').summernote('code');

            const hasChanged = currentVisi !== originalVisi || currentMisi !== originalMisi;
            const $btn = $('#saveBtn');

            if (hasChanged) {
                // Menggunakan btn-primary (hijau) untuk kondisi Ada Perubahan (enabled)
                $btn.removeClass('btn-warning btn-danger').addClass('btn-primary');
                $btn.html('<i class="bi bi-exclamation-triangle-fill"></i> Ada Perubahan - Simpan');
                $btn.prop('disabled', false);
            } else {
                // Tetap menggunakan btn-primary (hijau) untuk kondisi Tidak Ada Perubahan (disabled)
                $btn.removeClass('btn-warning btn-danger').addClass('btn-primary');
                $btn.html('<i class="bi"></i> Belum Ada Perubahan');
                $btn.prop('disabled', true);
            }
        }

        // --- 3. SUBMISSION FORM (LANGSUNG EKSEKUSI) ---

        $('#labProfileForm').on('submit', function (e) {
            e.preventDefault();

            const currentVisi = $('#visi_editor').summernote('code');
            const currentMisi = $('#misi_editor').summernote('code');

            if (!validateForm(this)) {
                return;
            }

            if (currentVisi === originalVisi && currentMisi === originalMisi) {
                return;
            }

            // Langsung Eksekusi Penyimpanan
            executeSaveChanges(currentVisi, currentMisi);
        });


        // --- 4. EKSEKUSI PENYIMPANAN (Fungsi Inti) ---

        function executeSaveChanges(visi, misi) {
            const $btn = $('#saveBtn');
            const originalHtml = $btn.html();

            // Set Loading State
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...');

            // Kirim 2 permintaan AJAX paralel
            const visiRequest = $.post('/admin/visimisi', {
                jenis_konten: 'visi',
                isi_konten: visi
            });
            const misiRequest = $.post('/admin/visimisi', {
                jenis_konten: 'misi',
                isi_konten: misi
            });

            $.when(visiRequest, misiRequest)
                .done(function (visiResponse, misiResponse) {
                    // ✅ SUKSES: Tampilkan Toast Sukses
                    if (typeof successToast !== 'undefined') {
                        successToast.show();
                    } else {
                        // Fallback jika Toast gagal, langsung reload
                        window.location.reload();
                    }
                    // Halaman akan reload setelah Toast tertutup (via event listener)
                })
                .fail(function (xhr, status, error) {
                    // GAGAL: Tampilkan feedback visual di tombol selama 3 detik
                    console.error('AJAX Save Failed:', xhr.status, status, error);

                    $btn.removeClass('btn-primary').addClass('btn-danger').html('<i class="bi bi-x-circle-fill"></i> Gagal! Coba Lagi.');

                    setTimeout(() => {
                        updateSubmitButtonState();
                    }, 3000);
                });
        }
    });
</script>