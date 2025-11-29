<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-1 fw-bold">Lab Profile Management</h5>
        <p class="text-muted small mb-0">Update Visi, Misi</p>
    </div>
    <form action="/admin/visimisi" method="POST" id="labProfileForm">
        <div class="card-body">
            <!-- Visi Section -->
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
                <textarea class="form-control" name="visi" id="visi_textarea" rows="5" placeholder="Masukkan pernyataan visi laboratorium"><?= $visiData ? htmlspecialchars($visiData['isi_konten']) : '' ?></textarea>
                <small class="text-muted d-block mt-2">The main vision statement of the laboratory.</small>
            </div>

            <!-- Misi Section -->
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
                <textarea class="form-control" name="misi" id="misi_textarea" rows="5" placeholder="Masukkan pernyataan misi laboratorium"><?= $misiData ? htmlspecialchars($misiData['isi_konten']) : '' ?></textarea>
                <small class="text-muted d-block mt-2">Detailed mission points.</small>
            </div>
        </div>
        <div class="card-footer bg-light d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check2 me-1"></i> Save Changes
            </button>
        </div>
    </form>
</div>


<script>
    // Store original values untuk tracking perubahan
    let originalVisiContent = document.getElementById('visi_textarea').value;
    let originalMisiContent = document.getElementById('misi_textarea').value;

    // Track perubahan pada textarea
    document.getElementById('visi_textarea').addEventListener('change', function() {
        if (this.value !== originalVisiContent) {
            showChangeIndicator();
        }
    });

    document.getElementById('misi_textarea').addEventListener('change', function() {
        if (this.value !== originalMisiContent) {
            showChangeIndicator();
        }
    });

    function showChangeIndicator() {
        const submitBtn = document.querySelector('#labProfileForm button[type="submit"]');
        if (!submitBtn.classList.contains('btn-warning')) {
            submitBtn.classList.remove('btn-primary');
            submitBtn.classList.add('btn-warning');
            submitBtn.innerHTML = '<i class="bi bi-exclamation-triangle me-1"></i> Ada Perubahan - Simpan Sekarang';
        }
    }

    document.getElementById('labProfileForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const visiContent = document.getElementById('visi_textarea').value.trim();
        const misiContent = document.getElementById('misi_textarea').value.trim();
        
        if (!visiContent || !misiContent) {
            showAlert('warning', 'Validasi Gagal', 'Harap isi kedua field Visi dan Misi');
            return;
        }

        // Check if there are actual changes
        if (visiContent === originalVisiContent && misiContent === originalMisiContent) {
            showAlert('info', 'Tidak Ada Perubahan', 'Tidak ada perubahan data untuk disimpan');
            return;
        }

        // Show confirmation dialog
        if (!confirm('Apakah Anda yakin ingin menyimpan perubahan ini?')) {
            return;
        }

        // Show loading state
        const submitBtn = document.querySelector('#labProfileForm button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Menyimpan...';
        
        // Submit form untuk Visi
        const formDataVisi = new FormData();
        formDataVisi.append('jenis_konten', 'visi');
        formDataVisi.append('isi_konten', visiContent);
        
        // Submit form untuk Misi
        const formDataMisi = new FormData();
        formDataMisi.append('jenis_konten', 'misi');
        formDataMisi.append('isi_konten', misiContent);
        
        try {
            const responseVisi = await fetch('/admin/visimisi', {
                method: 'POST',
                body: formDataVisi
            });
            
            const responseMisi = await fetch('/admin/visimisi', {
                method: 'POST',
                body: formDataMisi
            });

            if (responseVisi.ok && responseMisi.ok) {
                showAlert('success', 'Berhasil', 'Data Visi dan Misi berhasil disimpan!', function() {
                    window.location.reload();
                });
            } else {
                showAlert('error', 'Gagal', 'Terjadi kesalahan saat menyimpan data');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        } catch (error) {
            console.error('Error:', error);
            showAlert('error', 'Error', 'Terjadi kesalahan: ' + error.message);
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    });

    // Function untuk menampilkan alert yang lebih baik
    function showAlert(type, title, message, callback) {
        // Cek apakah SweetAlert2 tersedia
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: type,
                title: title,
                text: message,
                confirmButtonColor: '#6366f1',
                allowOutsideClick: false
            }).then((result) => {
                if (callback) callback();
            });
        } else {
            // Fallback ke alert bawaan
            let alertMessage = `${title}\n\n${message}`;
            alert(alertMessage);
            if (callback) callback();
        }
    }
</script>
