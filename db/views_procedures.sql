-- Drop existing objects if they exist
DROP MATERIALIZED VIEW IF EXISTS mv_dashboard_stats;
DROP VIEW IF EXISTS view_recent_activity;
DROP PROCEDURE IF EXISTS refresh_dashboard_stats;

-- 1. Materialized View for Dashboard Stats
-- Aggregates counts for users, content, and pending approvals
CREATE MATERIALIZED VIEW mv_dashboard_stats AS
SELECT
    (SELECT COUNT(*) FROM anggota) AS total_users,
    (SELECT COUNT(*) FROM berita) AS total_news,
    (SELECT COUNT(*) FROM galeri) AS total_gallery,
    (SELECT COUNT(*) FROM publikasi) AS total_publications,
    (
        (SELECT COUNT(*) FROM berita WHERE status = 'pending') +
        (SELECT COUNT(*) FROM galeri WHERE status = 'pending') +
        (SELECT COUNT(*) FROM publikasi WHERE status = 'pending')
    ) AS pending_approvals;

-- 2. Standard View for Recent Activity
-- Unions activity from News, Gallery, and Publications
CREATE OR REPLACE VIEW view_recent_activity AS
SELECT
    a.nama_lengkap AS user_name,
    'Posted News' AS action,
    'News' AS module,
    b.judul AS title,
    b.tanggal_posting AS activity_time,
    b.status
FROM berita b
JOIN anggota a ON b.id_penulis = a.id_anggota

UNION ALL

SELECT
    a.nama_lengkap AS user_name,
    'Uploaded Photo' AS action,
    'Gallery' AS module,
    SUBSTRING(g.deskripsi, 1, 50) AS title,
    g.tanggal_upload AS activity_time,
    g.status
FROM galeri g
JOIN anggota a ON g.id_uploader = a.id_anggota

UNION ALL

SELECT
    a.nama_lengkap AS user_name,
    'Added Publication' AS action,
    'Publications' AS module,
    p.judul_publikasi AS title,
    -- Convert year to timestamp for sorting compatibility
    TO_TIMESTAMP(p.tahun_terbit || '-01-01 00:00:00', 'YYYY-MM-DD HH24:MI:SS') AS activity_time,
    p.status
FROM publikasi p
JOIN anggota a ON p.id_anggota = a.id_anggota;

-- 3. Stored Procedure to Refresh Stats
-- Can be called to update the materialized view
CREATE OR REPLACE PROCEDURE refresh_dashboard_stats()
LANGUAGE plpgsql
AS $$
BEGIN
    REFRESH MATERIALIZED VIEW mv_dashboard_stats;
END;
$$;

-- 4. Function to Get Sorted Publications
-- Returns approved publications sorted by citation count and year
CREATE OR REPLACE FUNCTION get_sorted_publications(limit_val INT)
RETURNS TABLE (
    id_publikasi INT,
    judul_publikasi VARCHAR,
    tahun_terbit INT,
    link_publikasi VARCHAR,
    deskripsi TEXT,
    id_anggota INT,
    status status_approval_enum,
    citation_count INT,
    nama_penulis VARCHAR
)
LANGUAGE plpgsql
AS $$
BEGIN
    RETURN QUERY
    SELECT 
        p.id_publikasi,
        p.judul_publikasi,
        p.tahun_terbit,
        p.link_publikasi,
        p.deskripsi,
        p.id_anggota,
        p.status,
        p.citation_count,
        a.nama_lengkap AS nama_penulis
    FROM publikasi p
    JOIN anggota a ON p.id_anggota = a.id_anggota
    WHERE p.status = 'approved'
    ORDER BY 
        COALESCE(p.citation_count, 0) DESC, 
        p.tahun_terbit DESC
    LIMIT limit_val;
END;
$$;
