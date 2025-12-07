-- ==========================================
-- 1. BERSIHKAN DATABASE (Opsional)
-- ==========================================
DROP TABLE IF EXISTS fasilitas CASCADE;
DROP TABLE IF EXISTS publikasi CASCADE;
DROP TABLE IF EXISTS galeri CASCADE;
DROP TABLE IF EXISTS berita CASCADE;
DROP TABLE IF EXISTS info_lab CASCADE;
DROP TABLE IF EXISTS profil_lab CASCADE;
DROP TABLE IF EXISTS anggota CASCADE;
DROP TYPE IF EXISTS role_enum CASCADE;
DROP TYPE IF EXISTS status_approval_enum CASCADE;
DROP TYPE IF EXISTS jenis_konten_enum CASCADE;
DROP TYPE IF EXISTS kondisi_enum CASCADE;
DROP TYPE IF EXISTS jenis_publikasi_enum CASCADE;
-- ==========================================
-- 2. BUAT TIPE ENUM (Enumeration)
-- ==========================================
-- Enum untuk Role User
CREATE TYPE role_enum AS ENUM ('admin', 'operator');
-- Enum untuk Status Approval (Penting untuk fitur ACC Admin)
CREATE TYPE status_approval_enum AS ENUM ('pending', 'approved', 'rejected');
-- Enum untuk Jenis Konten Profil
CREATE TYPE jenis_konten_enum AS ENUM ('visi', 'misi', 'sejarah', 'struktur_organisasi');
-- Enum untuk Kondisi Fasilitas
CREATE TYPE kondisi_enum AS ENUM ('baik', 'rusak_ringan', 'rusak_berat');
-- Enum untuk Jenis Publikasi

-- ==========================================
-- 3. BUAT TABEL
-- ==========================================
-- A. Tabel Anggota (Menggabungkan User & Data Diri)
-- Sesuai kebutuhan User Req A.1 (Manajemen Anggota) & B.1 (Role Admin/Operator)
CREATE TABLE anggota (
    id_anggota SERIAL PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    nip_nim VARCHAR(20),
    role role_enum NOT NULL DEFAULT 'operator',
    foto_profil VARCHAR(255),
    status_aktif BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- B. Tabel Profil Lab (Visi, Misi, Sejarah)
CREATE TABLE profil_lab (
    id SERIAL PRIMARY KEY,
    jenis_konten jenis_konten_enum NOT NULL,
    isi_konten TEXT NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- C. Tabel Info Lab (Kontak & Footer)
CREATE TABLE info_lab (
    id SERIAL PRIMARY KEY,
    nama_lab VARCHAR(100) DEFAULT 'Lab Data Teknologi',
    alamat TEXT,
    email VARCHAR(100),
    telepon VARCHAR(20),
    link_maps TEXT,
    link_instagram VARCHAR(255),
    link_youtube VARCHAR(255),
    link_linkedin VARCHAR(255),
    link_facebook VARCHAR(255),
    link_twitter VARCHAR(255),
    deskripsi TEXT
);
-- D. Tabel Berita
-- Mencakup kolom approval sesuai User Req B.1 (Alur Persetujuan)
CREATE TABLE berita (
    id_berita SERIAL PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    isi_berita TEXT NOT NULL,
    gambar_utama VARCHAR(255),
    id_penulis INT NOT NULL,
    tanggal_posting TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status status_approval_enum DEFAULT 'pending',
    id_admin_penilai INT NULL,
    catatan_admin TEXT NULL,
    tanggal_validasi TIMESTAMP NULL,
    CONSTRAINT fk_berita_penulis FOREIGN KEY (id_penulis) REFERENCES anggota(id_anggota) ON DELETE CASCADE,
    CONSTRAINT fk_berita_admin FOREIGN KEY (id_admin_penilai) REFERENCES anggota(id_anggota) ON DELETE
    SET NULL
);
-- E. Tabel Galeri
CREATE TABLE galeri (
    id_galeri SERIAL PRIMARY KEY,
    deskripsi TEXT NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    id_uploader INT NOT NULL,
    tanggal_upload TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    -- Kolom Approval
    status status_approval_enum DEFAULT 'pending',
    id_admin_penilai INT NULL,
    catatan_admin TEXT NULL,
    -- Relasi
    CONSTRAINT fk_galeri_uploader FOREIGN KEY (id_uploader) REFERENCES anggota(id_anggota) ON DELETE CASCADE,
    CONSTRAINT fk_galeri_admin FOREIGN KEY (id_admin_penilai) REFERENCES anggota(id_anggota) ON DELETE
    SET NULL
);
-- F. Tabel Publikasi
-- Wajib link ke anggota sesuai User Req A.2
CREATE TABLE publikasi (
    id_publikasi SERIAL PRIMARY KEY,
    judul_publikasi VARCHAR(255) NOT NULL,

    tahun_terbit INT,
    link_publikasi VARCHAR(255),
    deskripsi TEXT,
    id_anggota INT NOT NULL,
    -- Kolom Approval
    status status_approval_enum DEFAULT 'pending',
    id_admin_penilai INT NULL,
    catatan_admin TEXT NULL,
    citation_count INT DEFAULT 0,
    -- Relasi
    CONSTRAINT fk_publikasi_anggota FOREIGN KEY (id_anggota) REFERENCES anggota(id_anggota) ON DELETE CASCADE,
    CONSTRAINT fk_publikasi_admin FOREIGN KEY (id_admin_penilai) REFERENCES anggota(id_anggota) ON DELETE
    SET NULL
);
-- G. Tabel Fasilitas
CREATE TABLE fasilitas (
    id_fasilitas SERIAL PRIMARY KEY,
    nama_fasilitas VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    foto_fasilitas VARCHAR(255),
    jumlah_unit INT DEFAULT 1,
    kondisi kondisi_enum DEFAULT 'baik'
);