<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cosmetic Store - @yield('title', 'Trang chủ')</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        :root {
            --primary: #BC8F8F;
            --secondary: #E6E6FA;
            --bg: #FFFFFF;
            --text: #333333;
            --card-bg: #F9F9F9;
            --border: #EEEEEE;
            --transition: all 0.3s ease;
        }

        [data-theme="dark"] {
            --bg: #121212;
            --text: #F5F5F5;
            --card-bg: #1E1E1E;
            --border: #333333;
            --secondary: #2C2C2C;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--bg);
            color: var(--text);
            transition: var(--transition);
            line-height: 1.6;
        }

        h1, h2, h3 {
            font-family: 'Playfair Display', serif;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        header {
            padding: 20px 0;
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            background: var(--bg);
            z-index: 1000;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
            text-decoration: none;
            letter-spacing: 2px;
        }

        .nav-links {
            display: flex;
            gap: 30px;
            list-style: none;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--text);
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
        }

        .theme-toggle {
            background: none;
            border: 1px solid var(--primary);
            color: var(--primary);
            padding: 5px 15px;
            cursor: pointer;
            border-radius: 20px;
            font-size: 12px;
            transition: var(--transition);
        }

        .theme-toggle:hover {
            background: var(--primary);
            color: white;
        }

        footer {
            background: var(--secondary);
            padding: 50px 0;
            margin-top: 100px;
            text-align: center;
        }

        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            text-align: center;
        }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-error { background: #f8d7da; color: #721c24; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade {
            animation: fadeIn 0.8s ease forwards;
        }

        .cart-badge {
            background: var(--primary);
            color: white;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 10px;
            position: relative;
            top: -10px;
            margin-left: 5px;
            font-weight: 700;
        }

        .nav-links a:hover {
            color: var(--primary);
        }
    </style>
    @yield('styles')
</head>
<body>
    <header>
        <div class="container">
            <nav>
                <a href="{{ route('home') }}" class="logo">COSMETIC.</a>
                <ul class="nav-links">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('blog.index') }}">Blog</a></li>
                    <li><a href="{{ route('feedback') }}">Liên hệ</a></li>
                    <li>
                        <a href="{{ route('cart.index') }}">
                            Giỏ hàng
                            @if(session('cart') && count(session('cart')) > 0)
                                <span class="cart-badge">{{ count(session('cart')) }}</span>
                            @endif
                        </a>
                    </li>
                </ul>
                <button class="theme-toggle" id="theme-toggle">🌗 Sáng/Tối</button>
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
            <p>&copy; 2024 Cosmetic Store Premium Design. All rights reserved.</p>
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
            title: 'Cosmetic Assistant',
            mainColor: '#BC8F8F',
            bubbleBackground: '#BC8F8F',
            aboutText: 'Powered by Cosmetic Store',
            placeholderText: '{{ $botPlaceholder }}',
            introMessage: '',
            headerTextColor: '#fff',
            bubbleAvatarUrl: 'https://cdn-icons-png.flaticon.com/512/4712/4712027.png',
            displayMessageTime: true,
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
    </script>
    @yield('scripts')
</body>
</html>
