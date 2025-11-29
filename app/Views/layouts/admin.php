<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Dashboard' ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        :root {
            --sidebar-width: 280px;
            --primary-color: #6366f1;
            --primary-hover: #4f46e5;
            --sidebar-bg: #ffffff;
            --sidebar-color: #64748b;
            --sidebar-active-bg: #eff6ff;
            --sidebar-active-color: #6366f1;
            --body-bg: #f8fafc;
            --card-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05), 0 2px 4px -2px rgb(0 0 0 / 0.05);
            --modal-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--body-bg);
            min-height: 100vh;
            color: #334155;
        }

        /* Sidebar Styling */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            border-right: 1px solid #e2e8f0;
            z-index: 1000;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .main-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-header {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .sidebar-brand {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-color);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            letter-spacing: -0.025em;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 2rem;
            color: var(--sidebar-color);
            font-weight: 500;
            transition: all 0.2s;
            border-left: 3px solid transparent;
            margin-bottom: 0.25rem;
        }

        .nav-link:hover {
            background-color: #f8fafc;
            color: #1e293b;
        }

        .nav-link.active {
            background-color: var(--sidebar-active-bg);
            color: var(--sidebar-active-color);
            border-left-color: var(--sidebar-active-color);
        }

        .nav-link i {
            font-size: 1.25rem;
            opacity: 0.9;
        }

        /* Topbar Styling */
        .topbar {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            padding: 1rem 2rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            border-radius: 1rem;
            box-shadow: var(--card-shadow);
            position: sticky;
            top: 1rem;
            z-index: 900;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.3);
        }

        /* Card Styling */
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: var(--card-shadow);
            background: white;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            /* transform: translateY(-2px); */
            /* box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.05); */
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid #f1f5f9;
            padding: 1.5rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Modal Styling */
        .modal-content {
            border: none;
            border-radius: 1.5rem;
            box-shadow: var(--modal-shadow);
            overflow: hidden;
        }

        .modal-header {
            border-bottom: 1px solid #f1f5f9;
            padding: 1.5rem 2rem;
            background: #f8fafc;
        }

        .modal-body {
            padding: 2rem;
        }

        .modal-footer {
            border-top: 1px solid #f1f5f9;
            padding: 1.5rem 2rem;
            background: #f8fafc;
        }

        .modal-backdrop.show {
            opacity: 0.7;
            backdrop-filter: blur(4px);
        }

        /* Form Controls */
        .form-control {
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            border-color: #e2e8f0;
            font-size: 0.95rem;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        .form-label {
            font-weight: 500;
            color: #475569;
            margin-bottom: 0.5rem;
        }

        /* Input Group Styling */
        .input-group:focus-within .input-group-text {
            border-color: var(--primary-color);
            background-color: #fff;
        }

        .input-group:focus-within .input-group-text i {
            color: var(--primary-color) !important;
        }

        .input-group:focus-within .form-control {
            box-shadow: none;
            /* Remove default glow to avoid double border effect if needed, or keep it */
        }

        /* Ensure the input group text matches the input height and style */
        .input-group-text {
            border-radius: 0.75rem 0 0 0.75rem;
            border-color: #e2e8f0;
            background-color: #f8fafc;
            transition: all 0.2s;
        }

        .input-group .form-control {
            border-radius: 0 0.75rem 0.75rem 0;
        }

        /* Buttons */
        .btn {
            border-radius: 0.75rem;
            padding: 0.625rem 1.25rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
            box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.3);
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            border-color: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 6px 8px -1px rgba(99, 102, 241, 0.4);
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .main-content {
                margin-left: 0;
                padding: 1rem;
            }

            .sidebar.show {
                transform: translateX(0);
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <a href="/admin/dashboard" class="sidebar-brand">
                <i class="bi bi-grid-1x2-fill"></i>
                <span>Lab Admin</span>
            </a>
        </div>
        <nav class="mt-4">
            <?php $uri = $_SERVER['REQUEST_URI'] ?? '/'; ?>
            <a href="/admin/dashboard" class="nav-link <?= strpos($uri, '/admin/dashboard') === 0 ? 'active' : '' ?>">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
            <a href="/admin/members" class="nav-link <?= strpos($uri, '/admin/members') === 0 ? 'active' : '' ?>">
                <i class="bi bi-person-badge"></i>
                <span>Members</span>
            </a>
            <a href="/admin/publications"
                class="nav-link <?= strpos($uri, '/admin/publications') === 0 ? 'active' : '' ?>">
                <i class="bi bi-journal-text"></i>
                <span>Publications</span>
            </a>
            <a href="/admin/visimisi" class="nav-link <?= strpos($uri, '/admin/visimisi') === 0 ? 'active' : '' ?>">
                <i class="bi bi-building"></i>
                <span>Visi Misi</span>
            </a>
            <a href="/admin/gallery" class="nav-link <?= strpos($uri, '/admin/gallery') === 0 ? 'active' : '' ?>">
                <i class="bi bi-images"></i>
                <span>Gallery</span>
            </a>
            <a href="/admin/contact" class="nav-link <?= strpos($uri, '/admin/contact') === 0 ? 'active' : '' ?>">
                <i class="bi bi-info-circle"></i>
                <span>Info Lab</span>
            </a>
            <a href="/admin/news" class="nav-link <?= strpos($uri, '/admin/news') === 0 ? 'active' : '' ?>">
                <i class="bi bi-newspaper"></i>
                <span>News</span>
            </a>
            <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
                <a href="/admin/approvals" class="nav-link <?= strpos($uri, '/admin/approvals') === 0 ? 'active' : '' ?>">
                    <i class="bi bi-check-circle"></i>
                    <span>Approvals</span>
                </a>
            <?php endif; ?>
            <div class="mt-auto border-top pt-2">
                <a href="/logout" class="nav-link text-danger">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </a>
            </div>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <div class="topbar">
            <button class="btn btn-link d-md-none" id="sidebarToggle">
                <i class="bi bi-list fs-4"></i>
            </button>
            <h4 class="m-0 fw-semibold"><?= $pageTitle ?? 'Dashboard' ?></h4>
            <a href="/admin/my-profile" class="user-menu text-decoration-none text-dark">
                <div class="text-end d-none d-sm-block">
                    <div class="fw-semibold"><?= $user['name'] ?? 'Admin User' ?></div>
                    <small class="text-muted"><?= $user['email'] ?? 'admin@example.com' ?></small>
                </div>
                <div class="avatar"><?= strtoupper(substr($user['name'] ?? 'A', 0, 1)) ?></div>
            </a>
        </div>

        <?= $content ?? '' ?>
    </div>


    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('sidebarToggle')?.addEventListener('click', function () {
            document.querySelector('.sidebar').classList.toggle('show');
        });
    </script>
</body>

</html>