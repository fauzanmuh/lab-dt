<?php
// Check for session flash messages
$toastMessage = '';
$toastType = '';
$toastTitle = '';

if (isset($_SESSION['flash_success'])) {
    $toastMessage = $_SESSION['flash_success'];
    $toastType = 'success';
    $toastTitle = 'Berhasil!';
    unset($_SESSION['flash_success']);
} elseif (isset($_SESSION['flash_error'])) {
    $toastMessage = $_SESSION['flash_error'];
    $toastType = 'danger';
    $toastTitle = 'Gagal!';
    unset($_SESSION['flash_error']);
} elseif (isset($_GET['success'])) {
    // Legacy support for GET params
    $toastType = 'success';
    $toastTitle = 'Berhasil!';
    if ($_GET['success'] === '1') {
        $toastMessage = 'Operasi berhasil dilakukan.';
    } elseif ($_GET['success'] === 'created') {
        $toastMessage = 'Data berhasil dibuat.';
    } elseif ($_GET['success'] === 'updated') {
        $toastMessage = 'Data berhasil diperbarui.';
    } elseif ($_GET['success'] === 'deleted') {
        $toastMessage = 'Data berhasil dihapus.';
    } else {
        $toastMessage = htmlspecialchars($_GET['success']);
    }
} elseif (isset($_GET['error'])) {
    // Legacy support for GET params
    $toastType = 'danger';
    $toastTitle = 'Gagal!';
    if ($_GET['error'] === '1') {
        $toastMessage = 'Terjadi kesalahan saat memproses permintaan.';
    } else {
        $toastMessage = htmlspecialchars($_GET['error']);
    }
}
?>

<?php if ($toastMessage): ?>
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1050; margin-top: 60px;">
        <div id="globalToast"
            class="toast align-items-center text-white bg-<?= $toastType === 'success' ? 'success' : 'danger' ?> border-0 shadow-lg"
            role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center gap-2">
                    <i
                        class="bi bi-<?= $toastType === 'success' ? 'check-circle-fill' : 'exclamation-circle-fill' ?> fs-5"></i>
                    <div>
                        <h6 class="mb-0 fw-bold"><?= $toastTitle ?></h6>
                        <small><?= $toastMessage ?></small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var toastEl = document.getElementById('globalToast');
            if (toastEl) {
                var toast = new bootstrap.Toast(toastEl);
                toast.show();
            }
        });
    </script>

    <style>
        .bg-success {
            background-color: #7ABC52 !important;
            /* Theme Green */
        }

        .bg-danger {
            background-color: #dc3545 !important;
            /* Standard Red, or could use a theme red if defined */
        }

        .toast {
            border-radius: 12px;
            overflow: hidden;
        }
    </style>
<?php endif; ?>