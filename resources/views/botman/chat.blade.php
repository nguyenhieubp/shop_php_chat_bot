<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BotMan Chat</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        html, body {
            height: 100% !important;
            max-height: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            overflow: hidden !important;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #ffffff;
            position: relative !important;
            box-sizing: border-box !important;
        }

        #botmanChatRoot > div {
            height: 100% !important;
            max-height: 100% !important;
            width: 100% !important;
            display: flex !important;
            flex-direction: column !important;
            overflow: hidden !important;
            box-sizing: border-box !important;
            margin: 0 !important;
            padding: 0 !important;
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            bottom: 0 !important;
            right: 0 !important;
        }

        /* Message Entry Animation */
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        #messageArea {
            background-color: #ffffff !important;
            padding: 20px 15px !important;
            flex: 1 1 auto !important;
            height: auto !important;
            overflow-y: auto !important;
            padding-bottom: 30px !important;
            box-sizing: border-box !important;
        }

        .chat .messages {
            padding: 0 !important;
            margin: 0 !important;
            list-style: none !important;
        }

        .chat .messages li {
            animation: slideInUp 0.4s cubic-bezier(0.19, 1, 0.22, 1) both;
            margin-bottom: 12px !important;
            list-style: none !important;
            position: relative;
            clear: both;
            display: block;
        }

        /* Bot Message Bubbles (Left Side) */
        .chat .messages li:not(.visitor) {
            background: #f1f5f9 !important;
            color: #0f172a !important;
            border: none !important;
            border-radius: 18px !important;
            border-top-left-radius: 4px !important;
            padding: 12px 16px !important;
            font-size: 14px !important;
            line-height: 1.5 !important;
            max-width: 85% !important;
            float: left !important;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05) !important;
        }

        .chat .messages li:not(.visitor)::before {
            display: none !important;
        }

        /* User Message Bubbles (Right Side) */
        .chat .messages li.visitor {
            background: #000000 !important;
            color: #ffffff !important;
            border: none !important;
            border-radius: 18px !important;
            border-top-right-radius: 4px !important;
            padding: 12px 16px !important;
            font-size: 14px !important;
            font-weight: 500 !important;
            max-width: 80% !important;
            float: right !important;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05) !important;
        }

        .chat .messages li.visitor::before {
            display: none !important;
        }

        /* Buttons & Actions (Options formatted as modern chat pills) */
        .btn {
            background: #ffffff !important;
            color: #0f172a !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 20px !important;
            padding: 9px 16px !important;
            font-weight: 600 !important;
            text-transform: none !important;
            font-size: 13px !important;
            letter-spacing: normal !important;
            transition: all 0.2s cubic-bezier(0.19, 1, 0.22, 1) !important;
            margin-top: 8px !important;
            margin-bottom: 2px !important;
            display: inline-block !important;
            width: auto !important;
            min-width: 110px !important;
            max-width: 100% !important;
            text-align: center !important;
            cursor: pointer;
            box-sizing: border-box;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05) !important;
            margin-right: 6px !important;
        }

        .btn:hover {
            background: #ff0000 !important;
            color: #ffffff !important;
            border-color: #ff0000 !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 8px rgba(255, 0, 0, 0.2) !important;
        }

        /* Input Area (Locked to footer) */
        #userText {
            border-radius: 30px !important;
            border: 1px solid #e2e8f0 !important;
            padding: 12px 18px !important;
            margin: 0 15px 15px 15px !important;
            font-family: inherit !important;
            font-size: 14px !important;
            outline: none !important;
            width: calc(100% - 30px) !important;
            box-sizing: border-box !important;
            background: #f8fafc !important;
            transition: all 0.2s ease !important;
            position: relative !important;
            flex: 0 0 auto !important;
            z-index: 9999 !important;
        }

        #userText:focus {
            border-color: #ff0000 !important;
            background: #ffffff !important;
            box-shadow: 0 0 0 3px rgba(255, 0, 0, 0.1) !important;
        }

        a.banner {
            display: none !important;
        }

        .chat .messages li i, 
        .chat .messages li span.time,
        .chat .about, 
        .chat .header {
            display: none !important;
        }

        /* Floating Fullscreen Button */
        #fullscreenToggleBtn {
            position: fixed;
            top: 15px;
            right: 15px;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #000000;
            color: #ffffff;
            border: 1px solid #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 100000;
            transition: all 0.2s cubic-bezier(0.19, 1, 0.22, 1);
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }

        #fullscreenToggleBtn:hover {
            background: #ff0000;
            transform: scale(1.1);
        }
    </style>
</head>
<body>
    <button id="fullscreenToggleBtn" onclick="toggleFullScreen()" title="Phóng to">
        <i class="fa-solid fa-expand"></i>
    </button>
    <script id="botmanWidget" src='https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/chat.js'></script>
    <script>
        function toggleFullScreen() {
            const parentRoot = window.parent.document.getElementById('botmanWidgetRoot');
            if (parentRoot) {
                parentRoot.classList.toggle('botman-fullscreen');
                
                const iframe = window.parent.document.getElementById('chatBotManFrame');
                const btn = document.getElementById('fullscreenToggleBtn');
                
                if (parentRoot.classList.contains('botman-fullscreen')) {
                    btn.innerHTML = '<i class="fa-solid fa-compress"></i>';
                    btn.setAttribute('title', 'Thu nhỏ');
                    
                    if (iframe) {
                        // Traverse up to find outermost container under #botmanWidgetRoot
                        let wrapper = iframe;
                        while (wrapper.parentElement && wrapper.parentElement.id !== 'botmanWidgetRoot') {
                            wrapper = wrapper.parentElement;
                        }
                        
                        if (wrapper && wrapper !== iframe) {
                            // Save original styles
                            wrapper.setAttribute('data-orig-style', wrapper.getAttribute('style') || '');
                            iframe.setAttribute('data-orig-style', iframe.getAttribute('style') || '');
                            
                            // Apply fullscreen styles
                            wrapper.style.setProperty('position', 'fixed', 'important');
                            wrapper.style.setProperty('top', '0', 'important');
                            wrapper.style.setProperty('left', '0', 'important');
                            wrapper.style.setProperty('width', '100vw', 'important');
                            wrapper.style.setProperty('height', '100vh', 'important');
                            wrapper.style.setProperty('max-width', '100%', 'important');
                            wrapper.style.setProperty('max-height', '100%', 'important');
                            wrapper.style.setProperty('bottom', '0', 'important');
                            wrapper.style.setProperty('right', '0', 'important');
                            wrapper.style.setProperty('border-radius', '0px', 'important');
                            wrapper.style.setProperty('margin', '0', 'important');
                            wrapper.style.setProperty('box-shadow', 'none', 'important');
                            
                            iframe.style.setProperty('width', '100%', 'important');
                            iframe.style.setProperty('height', '100%', 'important');
                            iframe.style.setProperty('max-height', '100%', 'important');
                            iframe.style.setProperty('border-radius', '0px', 'important');
                        }
                    }
                } else {
                    btn.innerHTML = '<i class="fa-solid fa-expand"></i>';
                    btn.setAttribute('title', 'Phóng to');
                    
                    if (iframe) {
                        // Traverse up to find outermost container under #botmanWidgetRoot
                        let wrapper = iframe;
                        while (wrapper.parentElement && wrapper.parentElement.id !== 'botmanWidgetRoot') {
                            wrapper = wrapper.parentElement;
                        }
                        
                        if (wrapper && wrapper !== iframe) {
                            // Restore original styles
                            const origStyle = wrapper.getAttribute('data-orig-style');
                            if (origStyle) {
                                wrapper.setAttribute('style', origStyle);
                            } else {
                                wrapper.removeAttribute('style');
                            }
                            
                            const iframeOrigStyle = iframe.getAttribute('data-orig-style');
                            if (iframeOrigStyle) {
                                iframe.setAttribute('style', iframeOrigStyle);
                            } else {
                                iframe.removeAttribute('style');
                            }
                        }
                    }
                }
            }
        }

        // Hàm xóa toàn bộ tin nhắn trước bong bóng Welcome mới để dọn dẹp màn hình chat
        function clearHistoryBeforeWelcome(welcomeBubble) {
            const bubbles = Array.from(document.querySelectorAll('.chat .messages li'));
            const welcomeIndex = bubbles.indexOf(welcomeBubble);
            if (welcomeIndex > 0) {
                for (let i = 0; i < welcomeIndex; i++) {
                    bubbles[i].remove();
                }
            }
        }

        // Tự động ẩn các tin nhắn trigger/whisper ngầm của người dùng và các nút option cũ
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                let hasNewMessage = false;
                mutation.addedNodes.forEach((node) => {
                    if (node.nodeType === 1) {
                        hasNewMessage = true;
                        // Tìm tất cả các thẻ li được thêm vào
                        const bubbles = node.matches('li') ? [node] : Array.from(node.querySelectorAll('li'));
                        bubbles.forEach(bubble => {
                            const txt = bubble.textContent.trim().toLowerCase();
                            // Nếu nội dung khớp hoàn toàn với các từ khóa kích hoạt, ẩn đi lập tức
                            if (txt === 'init' || txt === 'hi' || txt === 'hello' || txt === 'start' || txt === 'menu') {
                                bubble.style.display = 'none';
                            }

                            // Tự động dọn dẹp lịch sử khi quay lại Menu chính / Chào mừng mới
                            if (txt.includes('chào mừng bạn đến với fashion hub') || txt.includes('bạn muốn làm gì?')) {
                                clearHistoryBeforeWelcome(bubble);
                            }
                        });
                    }
                });

                // Nếu có tin nhắn mới xuất hiện, ẩn toàn bộ các nút lựa chọn cũ đi lập tức
                if (hasNewMessage) {
                    const allBubbles = Array.from(document.querySelectorAll('.chat .messages li'));
                    if (allBubbles.length > 1) {
                        for (let i = 0; i < allBubbles.length - 1; i++) {
                            const oldBtns = allBubbles[i].querySelectorAll('.btn');
                            oldBtns.forEach(btn => {
                                btn.style.display = 'none';
                            });
                        }
                    }
                }
            });
        });

        // Bắt đầu quan sát document.body ngay lập tức vì body luôn có sẵn
        observer.observe(document.body, { childList: true, subtree: true });

        // Tự động kích hoạt luồng chào mừng 'init' sau khi iframe load xong và kết nối ổn định
        window.addEventListener('load', () => {
            setTimeout(() => {
                if (window.parent && window.parent.botmanChatWidget) {
                    window.parent.botmanChatWidget.whisper('init');
                }
            }, 250); // Đợi 250ms để websocket/http connection của widget bên trong iframe ổn định
        });
    </script>
</body>
</html>
