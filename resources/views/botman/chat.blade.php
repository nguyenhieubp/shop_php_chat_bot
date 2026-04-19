<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BotMan Chat</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700;800&display=swap" rel="stylesheet">
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

        /* Hide Timestamps & About */
        .chat .messages li i, 
        .chat .messages li span.time,
        .chat .about, 
        .chat .header {
            display: none !important;
        }
    </style>
</head>
<body>
    <script id="botmanWidget" src='https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/chat.js'></script>
</body>
</html>
