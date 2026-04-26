<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cosmetic Store - @yield('title', 'Trang chủ')</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        :root {
            --primary: #000000;
            --primary-dark: #333333;
            --secondary: #f8fafc;
            --accent: #ff0000;
            --bg: #ffffff;
            --surface: #ffffff;
            --text: #000000;
            --text-muted: #666666;
            --border: #e8e8e8;
            --radius-sm: 0px;
            --radius-md: 0px;
            --radius-lg: 0px;
            --radius-xl: 0px;
            --transition: all 0.3s cubic-bezier(0.19, 1, 0.22, 1);
            --shadow-sm: 0 1px 2px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 10px rgba(0,0,0,0.05);
            --shadow-lg: 0 15px 30px rgba(0,0,0,0.1);
        }

        [data-theme="dark"] {
            --bg: #020617;
            --surface: #0f172a;
            --text: #f8fafc;
            --text-muted: #94a3b8;
            --border: #1e293b;
            --secondary: #1e293b;
            --primary: #60a5fa;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg);
            color: var(--text);
            transition: var(--transition);
            line-height: 1.7;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4 {
            font-weight: 800;
            letter-spacing: -0.02em;
            line-height: 1.2;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 40px;
        }

        header {
            height: 90px;
            display: flex;
            align-items: center;
            border-bottom: 2px solid #000;
            position: sticky;
            top: 0;
            background: #000000;
            color: #ffffff;
            z-index: 1000;
            transition: var(--transition);
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .logo {
            font-size: 28px;
            font-weight: 900;
            color: #ffffff;
            text-decoration: none;
            letter-spacing: -0.05em;
            display: flex;
            align-items: center;
            gap: 12px;
            text-transform: uppercase;
        }

        .logo-circle {
            width: 8px;
            height: 40px;
            background: #ff0000;
            display: block;
        }

        .nav-links {
            display: flex;
            gap: 40px;
            list-style: none;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }

        .nav-links a {
            text-decoration: none;
            color: #ffffff;
            font-weight: 800;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            transition: var(--transition);
            position: relative;
            padding: 10px 0;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 3px;
            background: #ff0000;
            transition: var(--transition);
        }

        .nav-links a:hover::after, .nav-links a.active::after {
            width: 100%;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .theme-toggle {
            width: 44px;
            height: 44px;
            background: var(--surface);
            border: 1px solid var(--border);
            color: var(--text);
            cursor: pointer;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
            font-size: 16px;
        }

        .theme-toggle:hover {
            transform: rotate(20deg);
            border-color: var(--primary);
        }

        footer {
            background: var(--secondary);
            padding: 80px 0 40px;
            margin-top: 100px;
            border-top: 1px solid var(--border);
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            text-align: left;
            margin-bottom: 50px;
        }

        .footer-section h4 {
            color: var(--primary);
            margin-bottom: 20px;
            font-size: 18px;
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section ul li {
            margin-bottom: 10px;
        }

        .footer-section ul li a {
            text-decoration: none;
            color: var(--text);
            opacity: 0.8;
            transition: var(--transition);
        }

        .footer-section ul li a:hover {
            opacity: 1;
            color: var(--primary);
            padding-left: 5px;
        }

        .footer-bottom {
            padding-top: 30px;
            border-top: 1px solid var(--border);
            text-align: center;
            opacity: 0.6;
            font-size: 14px;
        }

        .cart-badge {
            background: var(--primary);
            color: white;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 50%;
            position: absolute;
            top: -10px;
            right: -10px;
            font-weight: 800;
            border: 2px solid var(--surface);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 14px 28px;
            background: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: var(--radius-md);
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition: var(--transition);
            font-size: 15px;
            gap: 10px;
        }

        .btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        /* Chatbot Bubble Pulse Animation */
        @keyframes chatPulse {
            0% { box-shadow: 0 0 0 0 rgba(0, 0, 0, 0.4); }
            70% { box-shadow: 0 0 0 15px rgba(0, 0, 0, 0); }
            100% { box-shadow: 0 0 0 0 rgba(0, 0, 0, 0); }
        }

        #botmanWidget-chat-bubble {
            animation: chatPulse 2s infinite !important;
            border: 2px solid #fff !important;
        }

        .chat-helper-label {
            position: fixed;
            bottom: 40px;
            right: 110px;
            background: #000;
            color: #fff;
            padding: 8px 16px;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            z-index: 10000;
            display: none;
            box-shadow: var(--shadow-md);
        }

        .chat-helper-label::after {
            content: '';
            position: absolute;
            right: -8px;
            top: 50%;
            transform: translateY(-50%);
            border-left: 8px solid #000;
            border-top: 6px solid transparent;
            border-bottom: 6px solid transparent;
        }
    </style>
    @yield('styles')
</head>
<body>
    <header>
        <div class="container" style="width: 100%;">
            <nav>
                <a href="{{ route('home') }}" class="logo">
                    <div class="logo-circle"></div>
                    FASHION HUB.
                </a>
                <ul class="nav-links">
                    <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                    <li><a href="{{ route('blog.index') }}" class="{{ request()->routeIs('blog.*') ? 'active' : '' }}">Blog</a></li>
                    <li><a href="{{ route('feedback') }}" class="{{ request()->routeIs('feedback') ? 'active' : '' }}">Contact</a></li>
                </ul>
                <div class="nav-actions">
                    <a href="{{ route('cart.index') }}" style="color: #ffffff; font-size: 22px; position: relative;">
                        <i class="fa-solid fa-shopping-cart"></i>
                        @if(session('cart') && count(session('cart')) > 0)
                            <span class="cart-badge" style="background: #ff0000; border: none;">{{ count(session('cart')) }}</span>
                        @endif
                    </a>
                    <button class="theme-toggle" id="theme-toggle" title="Switch Theme" style="background: transparent; border: 1px solid #444; color: #fff;">
                        <i class="fa-solid fa-moon"></i>
                    </button>
                </div>
            </nav>
        </div>
    </header>

    <main>
        @if(session('success'))
            <div class="container" style="margin-top: 20px;">
                <div class="alert alert-success">{{ session('success') }}</div>
            </div>
        @endif
        @if(session('error'))
            <div class="container" style="margin-top: 20px;">
                <div class="alert alert-error">{{ session('error') }}</div>
            </div>
        @endif

        @yield('content')
    </main>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <a href="{{ route('home') }}" class="logo" style="margin-bottom: 20px;">COSMETIC.</a>
                    <p>Nâng tầm vẻ đẹp Việt với những sản phẩm cao cấp và dịch vụ tận tâm.</p>
                </div>
                <div class="footer-section">
                    <h4>Khám phá</h4>
                    <ul>
                        <li><a href="{{ route('home') }}">Trang chủ</a></li>
                        <li><a href="{{ route('blog.index') }}">Blog làm đẹp</a></li>
                        <li><a href="#">Sản phẩm mới</a></li>
                        <li><a href="#">Khuyến mãi</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Hỗ trợ</h4>
                    <ul>
                        <li><a href="{{ route('feedback') }}">Liên hệ</a></li>
                        <li><a href="#">Chính sách bảo mật</a></li>
                        <li><a href="#">Điều khoản sử dụng</a></li>
                        <li><a href="#">Câu hỏi thường gặp</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Theo dõi chúng tôi</h4>
                    <div style="display: flex; gap: 15px; font-size: 20px;">
                        <a href="#" style="color: var(--primary);"><i class="fa-brands fa-facebook"></i></a>
                        <a href="#" style="color: var(--primary);"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" style="color: var(--primary);"><i class="fa-brands fa-tiktok"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 Cosmetic Store Premium - Trình diễn công nghệ và cái đẹp. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        const themeToggle = document.getElementById('theme-toggle');
        const body = document.body;

        // Check for saved theme
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme) {
            body.setAttribute('data-theme', savedTheme);
        }

        themeToggle.addEventListener('click', () => {
            const currentTheme = body.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            body.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
        });
    </script>
    <script>
        @php
            $botPlaceholder = \App\Models\BotSetting::get('bot_placeholder', 'Nhấn chọn hoặc nhập tin nhắn...');
        @endphp
        var botmanWidget = {
            frameEndpoint: '/botman/chat',
            title: 'Fashion Assistant',
            mainColor: '#ffffff',
            bubbleBackground: '#ffffff',
            aboutText: '',
            placeholderText: '{{ $botPlaceholder }}',
            introMessage: '',
            headerTextColor: '#000000',
            bubbleAvatarUrl: 'https://cdn-icons-png.flaticon.com/512/10046/10046426.png',
            desktopWidth: 450,
            desktopHeight: 550,
            displayMessageTime: false,
        };
    </script>
    <script src='https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js'></script>
    <script>
        // Lắng nghe sự kiện mở khung chat để tự động chào khách
        var wasChatOpened = false;
        window.addEventListener('chatOpenStateChange', function(event) {
            if (event.detail.isOpen && !wasChatOpened) {
                // Gửi lệnh 'init' ngầm ngay khi mở chat để hiện nút Bắt đầu
                window.botmanChatWidget.whisper('init');
                wasChatOpened = true;
            }
        });

        // Đảm bảo nút Bắt đầu cũng hiện ra nếu khách load lại trang khi đang mở chat
        window.addEventListener('botmanWidgetReady', function() {
            if (window.botmanChatWidget.opened()) {
                window.botmanChatWidget.whisper('init');
            }
        });

        // AJAX Sync: Cập nhật Badge giỏ hàng khi Bot thêm hàng
        setInterval(function() {
            // Chỉ kiểm tra khi khung chat đang mở để tiết kiệm tài nguyên
            if (window.botmanChatWidget && window.botmanChatWidget.opened()) {
                fetch('/api/cart-count')
                    .then(response => response.json())
                    .then(data => {
                        const badge = document.querySelector('.cart-badge');
                        const cartLink = document.querySelector('a[href*="cart"]');
                        
                        if (data.count > 0) {
                            if (badge) {
                                badge.textContent = data.count;
                            } else if (cartLink) {
                                // Nếu chưa có badge thì tạo mới
                                const newBadge = document.createElement('span');
                                newBadge.className = 'cart-badge';
                                newBadge.textContent = data.count;
                                cartLink.appendChild(newBadge);
                            }
                        }
                    });
            }
        }, 2000);

        // Show helper label after 5s
        setTimeout(() => {
            const helper = document.createElement('div');
            helper.className = 'chat-helper-label';
            helper.textContent = 'Cần hỗ trợ?';
            document.body.appendChild(helper);
            
            // Only show if chat is closed
            if (window.botmanChatWidget && !window.botmanChatWidget.opened()) {
                helper.style.display = 'block';
                helper.style.animation = 'slideInUp 0.5s ease both';
            }

            // Hide when chat opens
            window.addEventListener('chatOpenStateChange', function(event) {
                if (event.detail.isOpen) {
                    helper.style.display = 'none';
                }
            });
        }, 5000);
    </script>
    @yield('scripts')
</body>
</html>
