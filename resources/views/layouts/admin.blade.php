<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - @yield('title', 'Dashboard')</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        :root {
            --primary: #3b82f6; 
            --primary-dark: #2563eb;
            --bg: #f8fafc;
            --sidebar-bg: #020617; 
            --sidebar-text: #94a3b8;
            --sidebar-active: #f8fafc;
            --sidebar-hover: #1e293b;
            --text-main: #1e293b;
            --text-secondary: #64748b;
            --white: #ffffff;
            --border: #e2e8f0;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-md: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text-main); display: flex; min-height: 100vh; overflow-x: hidden; -webkit-font-smoothing: antialiased; }

        .sidebar {
            width: 260px;
            background: var(--sidebar-bg);
            color: var(--sidebar-text);
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            padding: 0;
            overflow-y: auto;
            z-index: 100;
            box-shadow: 4px 0 10px rgba(0,0,0,0.05);
        }

        .sidebar-header {
            padding: 30px 24px;
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--primary);
            border-bottom: 1px solid rgba(255,255,255,0.05);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
        }

        .nav-menu { list-style: none; }
        .nav-item { margin: 4px 12px; }
        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            color: var(--sidebar-text);
            text-decoration: none;
            transition: var(--transition);
            font-size: 13px;
            font-weight: 600;
            border-radius: 8px;
            letter-spacing: 0.01em;
        }
        .nav-link i {
            margin-right: 14px;
            width: 20px;
            text-align: center;
            font-size: 18px;
            opacity: 0.7;
        }
        .nav-link:hover {
            background: var(--sidebar-hover);
            color: var(--sidebar-active);
        }
        .nav-link.active {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35);
        }
        .nav-link.active i { opacity: 1; }

        .main-content {
            margin-left: 260px;
            flex: 1;
            padding: 40px;
            max-width: 1600px;
        }

        .card {
            background: var(--white);
            border-radius: 16px;
            box-shadow: var(--shadow-sm);
            padding: 30px;
            margin-bottom: 30px;
            border: 1px solid var(--border);
            transition: var(--transition);
        }
        .card:hover { box-shadow: var(--shadow); }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 20px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 700;
            font-size: 14px;
            transition: var(--transition);
            border: 1px solid transparent;
        }
        .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(188, 143, 143, 0.4); opacity: 0.95; }
        .btn-danger { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .btn-danger:hover { background: #ef4444; color: white; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3); }
        .btn-sm { padding: 6px 14px; font-size: 12px; border-radius: 8px; }

        table { width: 100%; border-collapse: separate; border-spacing: 0; margin-top: 20px; }
        th, td { text-align: left; padding: 18px 24px; vertical-align: middle; border-bottom: 1px solid var(--border); }
        th { 
            background: #F8FAFC; 
            text-transform: uppercase; 
            font-size: 11px; 
            font-weight: 800; 
            color: var(--text-secondary); 
            letter-spacing: 0.05em;
        }
        tr:last-child td { border-bottom: none; }
        tbody tr:hover { background: #FBFBFC; }

        .column-search {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 12px;
            font-family: inherit;
            outline: none;
            transition: var(--transition);
            background: #FFFFFF;
        }
        .column-search:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(188, 143, 143, 0.15); }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }
        .status-active { background: #DCFCE7; color: #166534; }
        .status-inactive { background: #FEE2E2; color: #991B1B; }

        /* SaaS Style Dashboard Widgets */
        .stat-card {
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            background: rgba(188, 143, 143, 0.1);
            color: var(--primary);
        }

        /* Modal Overhaul */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(12px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 0;
            transition: var(--transition);
        }
        .modal-overlay.active { display: flex; opacity: 1; }
        .modal-card {
            background: white;
            padding: 40px;
            border-radius: 24px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            transform: scale(0.95) translateY(10px);
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            text-align: center;
        }
        .modal-overlay.active .modal-card { transform: scale(1) translateY(0); }
        .modal-icon { font-size: 56px; color: #EF4444; margin-bottom: 24px; }
        .modal-title { font-size: 20px; font-weight: 800; margin-bottom: 12px; color: var(--text-main); }
        .modal-body { color: var(--text-secondary); font-size: 15px; margin-bottom: 32px; line-height: 1.6; }
        .modal-footer { display: flex; gap: 14px; }
        .modal-btn { flex: 1; padding: 14px; border: none; border-radius: 12px; font-weight: 700; cursor: pointer; transition: var(--transition); font-size: 14px; }
        .modal-btn-cancel { background: #F1F5F9; color: #475569; }
        .modal-btn-cancel:hover { background: #E2E8F0; }
        .modal-btn-confirm { background: #EF4444; color: white; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3); }
    </style>
    @yield('styles')
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-header">Admin System</div>
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-line"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.categories') }}" class="nav-link {{ request()->routeIs('admin.categories') ? 'active' : '' }}">
                    <i class="fa-solid fa-folder-tree"></i> Danh mục
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.category.new') }}" class="nav-link {{ request()->routeIs('admin.category.new') ? 'active' : '' }}" style="padding-left: 45px; opacity: 0.8;">
                    <i class="fa-solid fa-plus-circle"></i> Thêm mới
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.products') }}" class="nav-link {{ request()->routeIs('admin.products') ? 'active' : '' }}">
                    <i class="fa-solid fa-box-open"></i> Sản phẩm
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.product.new') }}" class="nav-link {{ request()->routeIs('admin.product.new') ? 'active' : '' }}" style="padding-left: 45px; opacity: 0.8;">
                    <i class="fa-solid fa-plus-circle"></i> Thêm mới
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.orders') }}" class="nav-link {{ request()->routeIs('admin.orders') ? 'active' : '' }}">
                    <i class="fa-solid fa-shopping-cart"></i> Đơn hàng
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.posts') }}" class="nav-link {{ request()->routeIs('admin.posts') ? 'active' : '' }}">
                    <i class="fa-solid fa-file-pen"></i> Bài viết
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.post.new') }}" class="nav-link {{ request()->routeIs('admin.post.new') ? 'active' : '' }}" style="padding-left: 45px; opacity: 0.8;">
                    <i class="fa-solid fa-plus-circle"></i> Soạn thảo
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.reports') }}" class="nav-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                    <i class="fa-solid fa-comment-dots"></i> Phản hồi
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.sliders') }}" class="nav-link {{ request()->routeIs('admin.sliders') ? 'active' : '' }}">
                    <i class="fa-solid fa-images"></i> Quản lý Sliders
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.bot.settings') }}" class="nav-link {{ request()->routeIs('admin.bot.settings') ? 'active' : '' }}">
                    <i class="fa-solid fa-robot"></i> Cài đặt Chatbot
                </a>
            </li>
            <li class="nav-item" style="margin-top: 50px;">
                <a href="{{ route('admin.logout') }}" class="nav-link" style="color: #FF4842;">
                    <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('home') }}" class="nav-link" target="_blank">
                    <i class="fa-solid fa-globe"></i> Xem Website
                </a>
            </li>
        </ul>
    </aside>

    <main class="main-content">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @yield('content')
    </main>

    <!-- Modal HTML... (keeping previous) -->
    <div id="deleteModal" class="modal-overlay">
        <div class="modal-card">
            <div class="modal-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
            <div class="modal-title">Xác nhận xóa</div>
            <div class="modal-body">
                Bạn có chắc chắn muốn xóa mục này? <br>
                Hành động này <strong>không thể hoàn tác</strong>.
            </div>
            <div class="modal-footer">
                <button class="modal-btn modal-btn-cancel" onclick="closeModal()">Hủy bỏ</button>
                <button class="modal-btn modal-btn-confirm" onclick="submitDelete()">Xóa vĩnh viễn</button>
            </div>
        </div>
    </div>

    <script>
        let formToSubmit = null;
        const modal = document.getElementById('deleteModal');
        window.confirmDelete = function(form) { formToSubmit = form; modal.classList.add('active'); }
        function closeModal() { modal.classList.remove('active'); formToSubmit = null; }
        function submitDelete() { if (formToSubmit) formToSubmit.submit(); }
        modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });

        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('column-search')) {
                const table = e.target.closest('table');
                const rows = table.querySelectorAll('tbody tr');
                const inputs = table.querySelectorAll('.column-search');
                
                rows.forEach(row => {
                    let isVisible = true;
                    inputs.forEach(input => {
                        const colIndex = input.closest('th').cellIndex;
                        const filterValue = input.value.toLowerCase();
                        let cellValue = row.cells[colIndex].textContent.trim().toLowerCase();
                        
                        // Specialized Date Picker matching
                        if (input.type === 'date' && filterValue) {
                            // Current cell format is DD/MM/YYYY. Need YYYY-MM-DD for comparison.
                            const parts = cellValue.split('/');
                            if (parts.length === 3) {
                                cellValue = `${parts[2]}-${parts[1].padStart(2, '0')}-${parts[0].padStart(2, '0')}`;
                            }
                            if (cellValue !== filterValue) isVisible = false;
                        } 
                        else if (filterValue && !cellValue.includes(filterValue)) {
                            isVisible = false;
                        }
                    });
                    row.style.display = isVisible ? '' : 'none';
                });
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
