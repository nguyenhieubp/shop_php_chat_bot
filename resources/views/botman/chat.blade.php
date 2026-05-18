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
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #ffffff;
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
            height: calc(100vh - 80px) !important;
            overflow-y: auto !important;
            padding-bottom: 20px !important;
            box-sizing: border-box !important;
        }

        .chat .messages {
            padding: 0 !important;
            margin: 0 !important;
            list-style: none !important;
        }

        .chat .messages li {
            animation: slideInUp 0.4s cubic-bezier(0.19, 1, 0.22, 1) both;
            margin-bottom: 25px !important;
            list-style: none !important;
            position: relative;
            clear: both;
            display: block;
        }

        /* Bot Message Bubbles */
        .chat .messages li.visitor {
            background: #ffffff !important;
            color: #000000 !important;
            border: 1px solid #000000 !important;
            border-radius: 0px !important;
            padding: 15px 18px !important;
            font-size: 14px !important;
            line-height: 1.6 !important;
            max-width: 85% !important;
            float: left !important;
        }

        .chat .messages li.visitor::before {
            content: 'FASHION HUB';
            position: absolute;
            top: -18px;
            left: 0;
            font-size: 9px;
            font-weight: 900;
            color: #ff0000;
            letter-spacing: 0.1em;
        }

        /* User Message Bubbles */
        .chat .messages li.not-visitor {
            background: #000000 !important;
            color: #ffffff !important;
            border-radius: 0px !important;
            padding: 12px 18px !important;
            font-size: 14px !important;
            font-weight: 500 !important;
            max-width: 80% !important;
            float: right !important;
        }

        /* Buttons & Actions */
        .btn {
            background: #000000 !important;
            color: #ffffff !important;
            border: none !important;
            border-radius: 0px !important;
            padding: 14px 20px !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            font-size: 11px !important;
            letter-spacing: 0.15em !important;
            transition: all 0.3s ease !important;
            margin-bottom: 8px !important;
            display: block !important;
            width: 100% !important;
            text-align: center !important;
            cursor: pointer;
            box-sizing: border-box;
        }

        .btn:hover {
            background: #ff0000 !important;
            color: #ffffff !important;
        }

        /* Input Area */
        .chat .input {
            border-top: 1px solid #000000 !important;
            padding: 15px !important;
            background: #ffffff !important;
            position: fixed;
            bottom: 0;
            width: 100%;
            box-sizing: border-box;
            z-index: 9999;
        }

        #userText {
            border-radius: 0px !important;
            border: 1px solid #eeeeee !important;
            padding: 12px 15px !important;
            font-family: inherit !important;
            font-size: 14px !important;
            outline: none !important;
            width: 100%;
            box-sizing: border-box;
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
                        const wrapper = iframe.parentElement;
                        if (wrapper) {
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
                        const wrapper = iframe.parentElement;
                        if (wrapper) {
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

        // Tự động ẩn các tin nhắn trigger/whisper ngầm của người dùng (như 'init', 'hi', 'start', 'menu')
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                mutation.addedNodes.forEach((node) => {
                    if (node.nodeType === 1) {
                        // Tìm tất cả các thẻ li được thêm vào
                        const bubbles = node.matches('li') ? [node] : Array.from(node.querySelectorAll('li'));
                        bubbles.forEach(bubble => {
                            const txt = bubble.textContent.trim().toLowerCase();
                            // Nếu nội dung khớp hoàn toàn với các từ khóa kích hoạt, ẩn đi lập tức
                            if (txt === 'init' || txt === 'hi' || txt === 'hello' || txt === 'start' || txt === 'menu') {
                                bubble.style.display = 'none';
                            }
                        });
                    }
                });
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
