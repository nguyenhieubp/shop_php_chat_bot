<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\Post;
use App\Models\Feedback;
use App\Models\BotSetting;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use BotMan\BotMan\Messages\Attachments\Image;

class BotManController extends Controller
{
    public function handle(Request $request)
    {
        $botman = app('botman');

        $botman->hears('^(init|hi|hello|clear)$', function (BotMan $bot) use ($request) {
            $question = Question::create(BotSetting::get('bot_welcome_msg', 'Chào mừng bạn đến với Cosmetic Store! 🌸'))
                ->addButtons([
                    Button::create(BotSetting::get('bot_start_btn', 'Bắt đầu'))->value('start'),
                ]);
            $bot->reply($question);
        });

        // Add to Cart Handler
        $botman->hears('add_to_cart_{id}', function (BotMan $bot, $id) use ($request) {
            $product = \App\Models\Product::find($id);
            if (!$product) {
                return $bot->reply('Rất tiếc, sản phẩm không còn tồn tại.');
            }

            $cart = $request->session()->get('cart', []);
            if (isset($cart[$id])) {
                $cart[$id]['quantity']++;
            } else {
                $cart[$id] = [
                    "name" => $product->name,
                    "quantity" => 1,
                    "price" => $product->price,
                    "image" => $product->image
                ];
            }
            $request->session()->put('cart', $cart);

            $question = Question::create("✅ Đã thêm *{$product->name}* vào giỏ hàng!")
                ->addButtons([
                    Button::create('💳 Thanh toán ngay')->value('checkout_now'),
                    Button::create('🛍️ Tiếp tục xem hàng')->value('menu'),
                ]);
            $bot->reply($question);
        });

        $botman->hears('checkout_now', function (BotMan $bot) {
            $bot->reply('Tuyệt vời! Bạn có thể nhấn vào biểu tượng giỏ hàng trên website hoặc [nhấn vào đây](' . route('cart.index') . ') để thanh toán.');
        });

        $botman->hears('view_product_{id}', function (BotMan $bot, $id) {
            $product = \App\Models\Product::find($id);
            if ($product) {
                $bot->reply('Bạn đang xem: ' . $product->name . '. Nhấn vào đây để xem chi tiết trên website: ' . url('/product/' . $product->slug));
            } else {
                $bot->reply('Không tìm thấy thông tin sản phẩm này.');
            }
        });

        $botman->hears('^(start|bắt đầu|chào|menu)$', function (BotMan $bot) use ($request) {
            $request->session()->forget(['botman_step', 'botman_state']);
            $question = Question::create(BotSetting::get('bot_menu_msg', 'Tôi là trợ lý ảo hỗ trợ bạn tìm kiếm và đặt hàng. Bạn muốn làm gì?'))
                ->addButtons([
                    Button::create(BotSetting::get('bot_shopping_btn', '🚀 Bắt đầu mua sắm'))->value('start_fast'),
                    Button::create(BotSetting::get('bot_search_btn', '🔍 Tìm sản phẩm'))->value('ask_search'),
                    Button::create(BotSetting::get('bot_track_btn', '📦 Tra cứu đơn hàng'))->value('track_order'),
                    Button::create(BotSetting::get('bot_blog_btn', '📝 Đọc Blog'))->value('view_blog'),
                    Button::create(BotSetting::get('bot_feedback_btn', '📧 Gửi góp ý'))->value('send_feedback'),
                    Button::create(BotSetting::get('bot_contact_btn', '📞 Liên hệ'))->value('contact_info'),
                ]);
            $bot->reply($question);
        });

        $botman->hears('ask_search', function (BotMan $bot) use ($request) {
            $request->session()->put('botman_step', 'askSearch');
            $request->session()->put('botman_state', ['flow' => 'search']);
            $bot->reply('Bạn đang tìm kiếm sản phẩm gì? (Hãy gõ tên sản phẩm hoặc gõ "tất cả")');
        });

        $botman->hears('track_order', function (BotMan $bot) use ($request) {
            $request->session()->put('botman_step', 'askTrackPhone');
            $bot->reply('Vui lòng nhập số điện thoại bạn đã dùng để đặt hàng:');
        });

        $botman->hears('view_blog', function (BotMan $bot) use ($request) {
            $posts = Post::latest()->take(3)->get();
            if ($posts->isEmpty()) {
                return $bot->reply('Hiện chưa có bài viết mới nào. Bạn hãy quay lại sau nhé!');
            }
            $bot->reply('Đây là các bài viết mới nhất từ chúng tôi:');
            foreach ($posts as $post) {
                $bot->reply("📖 " . $post->title . "\nLink: " . route('blog.show', $post->slug));
            }
            $bot->reply('Gõ "menu" để quay lại.');
        });

        $botman->hears('send_feedback', function (BotMan $bot) use ($request) {
            $request->session()->put('botman_step', 'askFeedback');
            $bot->reply('Chào bạn, chúng tôi luôn lắng nghe ý kiến từ khách hàng. Vui lòng nhập nội dung góp ý của bạn:');
        });

        $botman->hears('contact_info', function (BotMan $bot) use ($request) {
            $bot->reply("📍 Địa chỉ: 123 Đường Sắc Đẹp, Quận 1, TP.HCM\n📞 Hotline: 1900 1234\n🌐 Website: " . url('/') . "\n📧 Email: support@cosmeticstore.com");
            $bot->reply('Gõ "menu" để quay lại.');
        });

        $botman->hears('start_fast', function (BotMan $bot) use ($request) {
            $request->session()->put('botman_state', ['flow' => 'shopping', 'searchQuery' => 'tất cả']);
            $request->session()->put('botman_step', 'askCategory');
            
            $categories = Category::all();
            $buttons = [Button::create('Tất cả sản phẩm')->value('all')];
            foreach ($categories as $cat) {
                $buttons[] = Button::create($cat->name)->value($cat->id);
            }
            $question = Question::create('Bạn quan tâm đến loại sản phẩm nào?')->addButtons($buttons);
            $question->addButton(Button::create('⬅️ Quay lại')->value('back'));
            $bot->reply($question);
        });

        $botman->fallback(function (BotMan $bot) use ($request) {
            $step = $request->session()->get('botman_step');
            $state = $request->session()->get('botman_state', []);
            
            $payload = $bot->getMessage()->getPayload();
            $message = $bot->getMessage()->getText();
            $value = $payload['value'] ?? $message;

            // Global / Reset Commands (Always trigger start/reset even if in a step)
            $resetCommands = ['hi', 'hello', 'start', 'bắt đầu', 'start_order', 'start_fast', 'chào', 'clear'];
            if (in_array(strtolower($message), $resetCommands) || in_array($value, $resetCommands)) {
                $request->session()->forget(['botman_step', 'botman_state']);
                $question = Question::create('Cosmetic Store xin chào! 🌸')
                    ->addButtons([
                        Button::create('🚀 Bắt đầu mua sắm ngay')->value('start_fast'),
                    ]);
                return $bot->reply($question);
            }

            if (!$step) {
                $question = Question::create('Xin chào! Tôi có thể giúp gì cho bạn?')
                    ->addButtons([
                        Button::create('🚀 Bắt đầu mua sắm')->value('start_fast'),
                        Button::create('🔍 Tìm sản phẩm')->value('ask_search'),
                    ]);
                return $bot->reply($question);
            }

            // Start Fast flow (handled by hears('start_fast') now, but keep here just in case of race conditions)
            if ($value === 'start_fast') {
                return; // Let the hears handler take it
            }

            if ($value === 'ask_search') {
                return; // Let the hears handler take it
            }

            if ($value === 'back') {
                $prevSteps = [
                    'askCategory' => 'askSearch',
                    'askPrice' => 'askCategory',
                    'askProduct' => 'askPrice',
                    'askName' => 'askProduct',
                    'askPhone' => 'askName',
                    'askAddress' => 'askPhone',
                    'confirmOrder' => 'askAddress',
                ];
                $step = $prevSteps[$step] ?? 'askSearch';

                // Override if in search flow
                if ($step === 'askPrice' && ($state['flow'] ?? '') === 'search') {
                    $step = 'askSearch';
                }

                $request->session()->put('botman_step', $step);
            } else {
                // State Machine Transitions
                if ($step === 'askSearch') {
                    $state['searchQuery'] = strtolower($message);
                    $state['selectedCategory'] = 'all';
                    $state['selectedPrice'] = 'all';
                    $step = 'askProduct';
                } elseif ($step === 'askTrackPhone') {
                    $phone = trim($message);
                    $orders = Order::with('product')->where('phone', $phone)->latest()->take(3)->get();
                    if ($orders->isEmpty()) {
                        $bot->reply('Rất tiếc, tôi không tìm thấy đơn hàng nào với số điện thoại này. 😢');
                    } else {
                        $bot->reply('Đây là các đơn hàng gần nhất của bạn:');
                        foreach ($orders as $order) {
                            $statusLabel = [
                                'new' => 'Mới',
                                'processing' => 'Đang xử lý',
                                'shipped' => 'Đang giao',
                                'completed' => 'Hoàn thành',
                                'cancelled' => 'Đã hủy'
                            ][$order->status] ?? $order->status;
                            $productName = $order->product ? $order->product->name : 'Sản phẩm không rõ';
                            $bot->reply("📦 Đơn hàng #{$order->id} ({$order->created_at->format('d/m/Y')})\nSản phẩm: {$productName}\nTrạng thái: {$statusLabel}");
                        }
                    }
                    $bot->reply('Gõ "menu" để quay lại.');
                    $request->session()->forget(['botman_step', 'botman_state']);
                    return;
                } elseif ($step === 'askFeedback') {
                    Feedback::create([
                        'name' => 'Khách từ Chatbot',
                        'contact' => 'N/A',
                        'subject' => 'Góp ý qua Chatbot',
                        'message' => $message
                    ]);
                    $bot->reply('Cảm ơn bạn đã đóng góp ý kiến! Cosmetic Store sẽ ghi nhận và cải thiện dịch vụ tốt hơn. ❤️');
                    $bot->reply('Gõ "menu" để quay lại.');
                    $request->session()->forget(['botman_step', 'botman_state']);
                    return;
                } elseif ($step === 'askCategory') {
                    $state['selectedCategory'] = $value;
                    $step = 'askPrice';
                } elseif ($step === 'askPrice') {
                    $state['selectedPrice'] = $value;
                    $step = 'askProduct';
                } elseif ($step === 'askProduct') {
                    $state['selectedProductId'] = $value;
                    $step = 'askName';
                } elseif ($step === 'askName') {
                    $state['customerName'] = $message;
                    $step = 'askPhone';
                } elseif ($step === 'askPhone') {
                    if (trim($message) === '') return $bot->reply('Vui lòng nhập số điện thoại:');
                    $state['customerPhone'] = $message;
                    $step = 'askAddress';
                } elseif ($step === 'askAddress') {
                    $state['customerAddress'] = $message;
                    $step = 'confirmOrder';
                } elseif ($step === 'confirmOrder') {
                    if ($value === 'yes' || strtolower($message) === 'xác nhận') {
                        $order = Order::create([
                            'product_id' => $state['selectedProductId'],
                            'customer_name' => $state['customerName'],
                            'phone' => $state['customerPhone'],
                            'address' => $state['customerAddress'] ?? '',
                            'status' => 'new'
                        ]);
                        $bot->reply('🎉 Cảm ơn bạn! Đơn hàng mã #' . $order->id . ' đã được ghi nhận.');
                        $request->session()->forget(['botman_step', 'botman_state']);
                        return;
                    } else {
                        $bot->reply('Đã hủy. Hãy nhấn nút để bắt đầu lại nhé!');
                        $request->session()->forget(['botman_step', 'botman_state']);
                        return;
                    }
                }
            }

            // Save new state
            $request->session()->put('botman_step', $step);
            $request->session()->put('botman_state', $state);

            // Render Next Question
            if ($step === 'askCategory') {
                $categories = Category::all();
                $buttons = [Button::create('Tất cả')->value('all')];
                foreach ($categories as $cat) {
                    $buttons[] = Button::create($cat->name)->value($cat->id);
                }
                $question = Question::create('Bạn quan tâm đến loại sản phẩm nào?')->addButtons($buttons);
                $question->addButton(Button::create('⬅️ Quay lại')->value('back'));
                $bot->reply($question);
            } elseif ($step === 'askPrice') {
                $question = Question::create('Bạn muốn tìm trong khoảng giá nào?')
                    ->addButtons([
                        Button::create('Dưới 200k')->value('0-200000'),
                        Button::create('200k - 500k')->value('200000-500000'),
                        Button::create('Trên 500k')->value('500000-99999999'),
                        Button::create('Mọi giá')->value('all'),
                        Button::create('⬅️ Quay lại')->value('back'),
                    ]);
                $bot->reply($question);

            } elseif ($step === 'askProduct') {
                $query = Product::where('is_active', true);
                if ($state['searchQuery'] !== 'tất cả') {
                    $query->where('name', 'LIKE', '%' . $state['searchQuery'] . '%');
                }
                if ($state['selectedCategory'] !== 'all') {
                    $query->where('category_id', $state['selectedCategory']);
                }
                if ($state['selectedPrice'] !== 'all') {
                    [$min, $max] = explode('-', $state['selectedPrice']);
                    $query->where('price', '>=', $min)->where('price', '<=', $max);
                }
                
                $products = $query->take(5)->get();
                if ($products->isEmpty()) {
                    $bot->reply('Rất tiếc, tôi không tìm thấy sản phẩm phù hợp. 😢');
                    $bot->reply('Bạn đang tìm kiếm sản phẩm gì? (Hãy gõ tên sản phẩm hoặc gõ "tất cả")');
                    $request->session()->put('botman_step', 'askSearch');
                    return;
                }
                
                $bot->reply('Của bạn đây! Hãy chọn sản phẩm ưng ý nhất nhé:');
                
                foreach ($products as $product) {
                    // 1. Send Image separately (widget constraint)
                    if ($product->image) {
                        $attachment = new Image(asset($product->image));
                        $bot->reply(OutgoingMessage::create('')->withAttachment($attachment));
                    }

                    // 2. Combine Name, Price, and Actions in ONE bubble
                    $priceFormat = number_format($product->price) . 'đ';
                    $question = Question::create("💎 *{$product->name}*\n💰 Giá: *{$priceFormat}*")
                        ->addButtons([
                            Button::create('🛒 Thêm vào giỏ')->value('add_to_cart_' . $product->id),
                            Button::create('📦 Đặt ngay')->value($product->id),
                            Button::create('👁️ Chi tiết')->value('view_product_' . $product->id),
                        ]);
                    $bot->reply($question);
                }

                // 3. Simple Footer with only ONE back button
                $question = Question::create('Bạn chưa ưng ý?')->addButtons([
                    Button::create('⬅️ Tìm kiếm khác')->value('back')
                ]);
                $bot->reply($question);

            } elseif ($step === 'askName') {
                $product = Product::find($state['selectedProductId']);
                if (!$product) {
                    $bot->reply('Lỗi: Không tìm thấy sản phẩm. Vui lòng thử lại.');
                    $request->session()->forget(['botman_step', 'botman_state']);
                    return;
                }
                $bot->reply('Tuyệt vời! Bạn chọn: ' . $product->name);
                $bot->reply('Vui lòng cho biết tên của bạn:');

            } elseif ($step === 'askPhone') {
                $bot->reply('Cảm ơn ' . $state['customerName'] . '. Vui lòng cho biết SĐT của bạn:');

            } elseif ($step === 'askAddress') {
                $bot->reply('Địa chỉ nhận hàng của bạn ở đâu?');

            } elseif ($step === 'confirmOrder') {
                $product = Product::find($state['selectedProductId']);
                $bot->reply('Xác nhận đặt hàng:');
                $bot->reply('📦 Sản phẩm: ' . $product->name);
                $bot->reply('👤 Người nhận: ' . $state['customerName']);
                $bot->reply('📞 SĐT: ' . $state['customerPhone']);
                $bot->reply('🏠 Địa chỉ: ' . $state['customerAddress']);

                $question = Question::create('Bạn xác nhận đặt hàng chứ?')
                    ->addButtons([
                        Button::create('Xác nhận')->value('yes'),
                        Button::create('Hủy')->value('no'),
                        Button::create('⬅️ Quay lại')->value('back'),
                    ]);
                $bot->reply($question);
            }
        });

        $botman->listen();
    }
}
