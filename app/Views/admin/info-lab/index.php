<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0 fw-bold text-dark">Informasi Lab</h5>
                <p class="text-muted small mb-0">Perbarui detail kontak dan tautan media sosial</p>
            </div>
            <div class="card-body">

                <form action="/admin/contact/update" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nama_lab" class="form-label fw-bold">Nama Lab</label>
                            <input type="text" class="form-control" id="nama_lab" name="nama_lab"
                                value="<?= htmlspecialchars($contact['nama_lab'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label fw-bold">Alamat Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="<?= htmlspecialchars($contact['email'] ?? '') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="telepon" class="form-label fw-bold">Nomor Telepon</label>
                            <input type="text" class="form-control" id="telepon" name="telepon"
                                value="<?= htmlspecialchars($contact['telepon'] ?? '') ?>">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="alamat" class="form-label fw-bold">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat"
                                rows="3"><?= htmlspecialchars($contact['alamat'] ?? '') ?></textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="deskripsi" class="form-label fw-bold">Deskripsi (Footer)</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi"
                                rows="3"><?= htmlspecialchars($contact['deskripsi'] ?? '') ?></textarea>
                        </div>

                        <div class="col-12 mt-3 mb-3">
                            <h6 class="fw-bold text-secondary text-uppercase small">Media Sosial & Tautan</h6>
                            <hr class="mt-1">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="link_maps" class="form-label fw-bold">Tautan Google Maps</label>
                            <input type="url" class="form-control" id="link_maps" name="link_maps"
                                value="<?= htmlspecialchars($contact['link_maps'] ?? '') ?>"
                                placeholder="https://maps.google.com/...">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="link_instagram" class="form-label fw-bold">URL Instagram</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-instagram"></i></span>
                                <input type="url" class="form-control" id="link_instagram" name="link_instagram"
                                    value="<?= htmlspecialchars($contact['link_instagram'] ?? '') ?>"
                                    placeholder="https://instagram.com/...">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="link_linkedin" class="form-label fw-bold">URL LinkedIn</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-linkedin"></i></span>
                                <input type="url" class="form-control" id="link_linkedin" name="link_linkedin"
                                    value="<?= htmlspecialchars($contact['link_linkedin'] ?? '') ?>"
                                    placeholder="https://linkedin.com/...">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="link_facebook" class="form-label fw-bold">URL Facebook</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-facebook"></i></span>
                                <input type="url" class="form-control" id="link_facebook" name="link_facebook"
                                    value="<?= htmlspecialchars($contact['link_facebook'] ?? '') ?>"
                                    placeholder="https://facebook.com/...">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="link_twitter" class="form-label fw-bold">URL Twitter/X</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-twitter-x"></i></span>
                                <input type="url" class="form-control" id="link_twitter" name="link_twitter"
                                    value="<?= htmlspecialchars($contact['link_twitter'] ?? '') ?>"
                                    placeholder="https://twitter.com/...">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="link_youtube" class="form-label fw-bold">URL YouTube</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-youtube"></i></span>
                                <input type="url" class="form-control" id="link_youtube" name="link_youtube"
                                    value="<?= htmlspecialchars($contact['link_youtube'] ?? '') ?>"
                                    placeholder="https://youtube.com/...">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>