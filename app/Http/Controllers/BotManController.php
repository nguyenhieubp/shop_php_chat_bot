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

        $botman->hears('^(init|hi|hello|clear|start|bắt đầu|chào|menu)$', function (BotMan $bot) use ($request) {
            $request->session()->forget(['botman_step', 'botman_state']);
            
            $message = strtolower($bot->getMessage()->getText());
            if ($message === 'menu') {
                $welcomeMsg = BotSetting::get('bot_menu_msg_short', 'Tôi có thể giúp gì tiếp cho bạn?');
            } else {
                $welcomeMsg = BotSetting::get('bot_welcome_msg', 'Chào mừng bạn đến với Fashion Hub! 👕') . "\n\n" . 
                               BotSetting::get('bot_menu_msg', 'Tôi là trợ lý ảo hỗ trợ bạn tìm kiếm và đặt hàng. Bạn muốn làm gì?');
            }
                           
            $question = Question::create($welcomeMsg)
                ->addButtons([
                    Button::create(BotSetting::get('bot_shopping_btn', '🚀 Bắt đầu mua sắm'))->value('start_fast'),
                    Button::create('👔 Tư vấn chọn size')->value('start_consultation'),
                    Button::create(BotSetting::get('bot_search_btn', '🔍 Tìm sản phẩm'))->value('ask_search'),
                    Button::create(BotSetting::get('bot_track_btn', '📦 Tra cứu đơn hàng'))->value('track_order'),
                    Button::create(BotSetting::get('bot_blog_btn', '📝 Đọc Blog'))->value('view_blog'),
                    Button::create(BotSetting::get('bot_feedback_btn', '📧 Gửi góp ý'))->value('send_feedback'),
                    Button::create(BotSetting::get('bot_contact_btn', '📞 Liên hệ'))->value('contact_info'),
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
            $bot->reply('Tuyệt vời! Bạn có thể nhấn vào biểu tượng giỏ hàng trên website hoặc <a href="' . route('cart.index') . '" target="_parent" style="color: #3b82f6; text-decoration: underline; font-weight: bold;">nhấn vào đây</a> để thanh toán.');
        });

        $botman->hears('view_product_{id}', function (BotMan $bot, $id) {
            $product = \App\Models\Product::find($id);
            if ($product) {
                $bot->reply('Bạn đang xem: <b>' . $product->name . '</b><br><a href="' . url('/product/' . $product->slug) . '" target="_parent" style="color: #3b82f6; text-decoration: underline; font-weight: bold;">Xem chi tiết trên website</a>');
            } else {
                $bot->reply('Không tìm thấy thông tin sản phẩm này.');
            }
        });

        // Dedicated Buy Now Handler
        $botman->hears('buy_now_{id}', function (BotMan $bot, $id) use ($request) {
            $product = \App\Models\Product::find($id);
            if (!$product) {
                return $bot->reply('Rất tiếc, sản phẩm này hiện không khả dụng.');
            }

            $request->session()->put('botman_step', 'askName');
            $request->session()->put('botman_state', ['selectedProductId' => $id]);
            
            $bot->reply('Tuyệt vời! Bạn chọn: ' . $product->name);
            $question = Question::create(BotSetting::get('bot_ask_name', 'Vui lòng cho biết tên của bạn:'))
                ->addButtons([Button::create('⬅️ Quay lại')->value('back')]);
            $bot->reply($question);
        });

        $botman->hears('ask_search', function (BotMan $bot) use ($request) {
            $request->session()->put('botman_step', 'askSearch');
            $request->session()->put('botman_state', ['flow' => 'search']);
            $question = Question::create('Bạn đang tìm kiếm sản phẩm gì? (Hãy gõ tên sản phẩm hoặc gõ "tất cả")')
                ->addButtons([Button::create('⬅️ Quay lại')->value('back')]);
            $bot->reply($question);
        });

        $botman->hears('track_order', function (BotMan $bot) use ($request) {
            $request->session()->put('botman_step', 'askTrackPhone');
            $question = Question::create(BotSetting::get('bot_ask_track_phone', 'Vui lòng nhập số điện thoại bạn đã dùng để đặt hàng:'))
                ->addButtons([Button::create('⬅️ Quay lại')->value('back')]);
            $bot->reply($question);
        });

        $botman->hears('view_blog', function (BotMan $bot) use ($request) {
            $posts = Post::latest()->take(3)->get();
            if ($posts->isEmpty()) {
                $question = Question::create('Hiện chưa có bài viết mới nào. Bạn hãy quay lại sau nhé!')
                    ->addButtons([Button::create('⬅️ Quay lại')->value('back')]);
                return $bot->reply($question);
            }
            $bot->reply('Đây là các bài viết mới nhất từ chúng tôi:');
            
            $html = '<div class="product-carousel">';
            foreach ($posts as $post) {
                $html .= '<div class="product-card">';
                
                if ($post->image) {
                    $imgUrl = asset($post->image);
                    $html .= '<img src="'.$imgUrl.'" alt="'.$post->title.'" style="height: 150px;">';
                } else {
                    $html .= '<div style="height: 150px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; font-size: 40px; color: #94a3b8;">📰</div>';
                }
                
                $html .= '<div class="product-card-body">';
                $html .= '<h3 class="product-card-title">'.$post->title.'</h3>';
                
                $excerpt = \Illuminate\Support\Str::limit(strip_tags($post->content), 40);
                $html .= '<p style="font-size: 12px; color: #64748b; margin: 0 0 10px 0;">'.$excerpt.'</p>';
                
                $html .= '<div class="product-card-actions" style="margin-top: auto;">';
                $html .= '<a href="'.route('blog.show', $post->slug).'" target="_parent" class="btn-primary" style="display: block;">Đọc bài viết</a>';
                $html .= '</div></div></div>';
            }
            $html .= '</div>';
            
            $bot->reply($html);
            
            $question = Question::create(BotSetting::get('bot_back_menu_msg', 'Quay lại menu chính?'))
                ->addButtons([Button::create('⬅️ Quay lại')->value('back')]);
            $bot->reply($question);
        });

        $botman->hears('send_feedback', function (BotMan $bot) use ($request) {
            $request->session()->put('botman_state', ['flow' => 'feedback']);
            $request->session()->put('botman_step', 'askFeedbackPhone');
            $question = Question::create('Chào bạn, chúng tôi luôn lắng nghe ý kiến từ khách hàng. Vui lòng cho biết Số Điện Thoại của bạn:')
                ->addButtons([Button::create('⬅️ Quay lại')->value('back')]);
            $bot->reply($question);
        });

        $botman->hears('contact_info', function (BotMan $bot) use ($request) {
            $bot->reply("📍 Địa chỉ: 123 Đường Sắc Đẹp, Quận 1, TP.HCM<br>📞 Hotline: 1900 1234<br>🌐 Website: <a href='" . url('/') . "' target='_parent' style='color: #3b82f6; font-weight: bold;'>Fashion Hub</a><br>📧 Email: support@cosmeticstore.com");
            $question = Question::create(BotSetting::get('bot_back_menu_msg', 'Quay lại menu chính?'))
                ->addButtons([Button::create('⬅️ Quay lại')->value('back')]);
            $bot->reply($question);
        });

        $botman->hears('start_consultation', function (BotMan $bot) use ($request) {
            $request->session()->put('botman_state', ['flow' => 'consultation']);
            $request->session()->put('botman_step', 'askGender');
            
            $question = Question::create(BotSetting::get('bot_consultation_intro', 'Chào bạn! Để tôi tư vấn size chuẩn nhất, bạn vui lòng cho tôi biết giới tính của mình nhé:'))
                ->addButtons([
                    Button::create('Nam')->value('Nam'),
                    Button::create('Nữ')->value('Nữ'),
                    Button::create('Unisex/Khác')->value('Unisex'),
                    Button::create('⬅️ Quay lại')->value('back'),
                ]);
            $bot->reply($question);
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
            $resetCommands = ['hi', 'hello', 'start', 'bắt đầu', 'start_order', 'start_fast', 'chào', 'clear', 'menu'];
            if (in_array(strtolower($message), $resetCommands) || in_array($value, $resetCommands)) {
                $request->session()->forget(['botman_step', 'botman_state']);
                if (strtolower($message) === 'menu' || $value === 'menu') {
                    $welcomeMsg = BotSetting::get('bot_menu_msg_short', 'Tôi có thể giúp gì tiếp cho bạn?');
                } else {
                    $welcomeMsg = BotSetting::get('bot_welcome_msg', 'Chào mừng bạn đến với Fashion Hub! 👕') . "\n\n" . 
                                   BotSetting::get('bot_menu_msg', 'Tôi là trợ lý ảo hỗ trợ bạn tìm kiếm và đặt hàng. Bạn muốn làm gì?');
                }
                               
                $question = Question::create($welcomeMsg)
                    ->addButtons([
                        Button::create(BotSetting::get('bot_shopping_btn', '🚀 Bắt đầu mua sắm'))->value('start_fast'),
                        Button::create('👔 Tư vấn chọn size')->value('start_consultation'),
                        Button::create(BotSetting::get('bot_search_btn', '🔍 Tìm sản phẩm'))->value('ask_search'),
                        Button::create(BotSetting::get('bot_track_btn', '📦 Tra cứu đơn hàng'))->value('track_order'),
                        Button::create(BotSetting::get('bot_blog_btn', '📝 Đọc Blog'))->value('view_blog'),
                        Button::create(BotSetting::get('bot_feedback_btn', '📧 Gửi góp ý'))->value('send_feedback'),
                        Button::create(BotSetting::get('bot_contact_btn', '📞 Liên hệ'))->value('contact_info'),
                    ]);
                return $bot->reply($question);
            }

            if (!$step) {
                $welcomeMsg = BotSetting::get('bot_welcome_msg', 'Chào mừng bạn đến với Fashion Hub! 👕') . "\n\n" . 
                               BotSetting::get('bot_menu_msg', 'Tôi là trợ lý ảo hỗ trợ bạn tìm kiếm và đặt hàng. Bạn muốn làm gì?');
                               
                $question = Question::create($welcomeMsg)
                    ->addButtons([
                        Button::create(BotSetting::get('bot_shopping_btn', '🚀 Bắt đầu mua sắm'))->value('start_fast'),
                        Button::create('👔 Tư vấn chọn size')->value('start_consultation'),
                        Button::create(BotSetting::get('bot_search_btn', '🔍 Tìm sản phẩm'))->value('ask_search'),
                        Button::create(BotSetting::get('bot_track_btn', '📦 Tra cứu đơn hàng'))->value('track_order'),
                        Button::create(BotSetting::get('bot_blog_btn', '📝 Đọc Blog'))->value('view_blog'),
                        Button::create(BotSetting::get('bot_feedback_btn', '📧 Gửi góp ý'))->value('send_feedback'),
                        Button::create(BotSetting::get('bot_contact_btn', '📞 Liên hệ'))->value('contact_info'),
                    ]);
                return $bot->reply($question);
            }

            if ($value === 'back') {
                $prevSteps = [
                    'askCategory' => 'menu',
                    'askSearch' => 'menu',
                    'askGender' => 'menu',
                    'askHeight' => 'askGender',
                    'askWeight' => 'askHeight',
                    'askPrice' => 'askCategory',
                    'askProduct' => 'askPrice',
                    'askName' => 'askProduct',
                    'askPhone' => 'askName',
                    'askAddress' => 'askPhone',
                    'confirmOrder' => 'askAddress',
                    'askTrackPhone' => 'menu',
                    'askFeedbackPhone' => 'menu',
                    'askFeedbackMessage' => 'askFeedbackPhone',
                ];
                $step = $prevSteps[$step] ?? 'menu';
                
                // Quay lại từ askProduct trong flow tìm kiếm thì về lại ô nhập từ khóa
                if ($step === 'askProduct' && ($state['flow'] ?? '') === 'search') {
                    $step = 'askSearch';
                }
                // Quay lại từ askPrice trong flow tìm kiếm (nếu có) thì về lại ô nhập từ khóa
                if ($step === 'askCategory' && ($state['flow'] ?? '') === 'search') {
                    $step = 'askSearch';
                }
                
                $request->session()->put('botman_step', $step);

                if ($step === 'menu') {
                    $request->session()->forget(['botman_step', 'botman_state']);
                    $welcomeMsg = BotSetting::get('bot_menu_msg_short', 'Tôi có thể giúp gì tiếp cho bạn?');
                                   
                    $question = Question::create($welcomeMsg)
                        ->addButtons([
                            Button::create(BotSetting::get('bot_shopping_btn', '🚀 Bắt đầu mua sắm'))->value('start_fast'),
                            Button::create('👔 Tư vấn chọn size')->value('start_consultation'),
                            Button::create(BotSetting::get('bot_search_btn', '🔍 Tìm sản phẩm'))->value('ask_search'),
                            Button::create(BotSetting::get('bot_track_btn', '📦 Tra cứu đơn hàng'))->value('track_order'),
                            Button::create(BotSetting::get('bot_blog_btn', '📝 Đọc Blog'))->value('view_blog'),
                            Button::create(BotSetting::get('bot_feedback_btn', '📧 Gửi góp ý'))->value('send_feedback'),
                            Button::create(BotSetting::get('bot_contact_btn', '📞 Liên hệ'))->value('contact_info'),
                        ]);
                    return $bot->reply($question);
                }
            } else {
                // State Machine Transitions
                if ($step === 'askSearch') {
                    $state['searchQuery'] = strtolower($message);
                    $state['selectedCategory'] = 'all';
                    $state['selectedPrice'] = 'all';
                    $step = 'askProduct';
                } elseif ($step === 'askTrackPhone') {
                    $phone = trim($message);
                    if (!preg_match('/^0[0-9]{9}$/', $phone)) {
                        $bot->reply('⚠️ Số điện thoại không hợp lệ. Số điện thoại phải bắt đầu bằng số 0 và có đúng 10 chữ số (Ví dụ: 0901234567).');
                        $question = Question::create(BotSetting::get('bot_ask_track_phone', 'Vui lòng nhập số điện thoại bạn đã dùng để đặt hàng:'))
                            ->addButtons([Button::create('⬅️ Quay lại')->value('back')]);
                        return $bot->reply($question);
                    }
                    $orders = Order::with('product')->where('phone', $phone)->latest()->take(3)->get();
                    if ($orders->isEmpty()) {
                        $bot->reply(BotSetting::get('bot_order_not_found', 'Rất tiếc, tôi không tìm thấy đơn hàng nào với số điện thoại này. 😢'));
                    } else {
                        $bot->reply(BotSetting::get('bot_order_list_intro', 'Đây là các đơn hàng gần nhất của bạn:'));
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
                    $bot->reply(BotSetting::get('bot_back_menu_msg', 'Gõ "menu" để quay lại.'));
                    $request->session()->forget(['botman_step', 'botman_state']);
                    return;
                } elseif ($step === 'askFeedbackPhone') {
                    $phone = trim($message);
                    if (!preg_match('/^0[0-9]{9}$/', $phone)) {
                        $bot->reply('⚠️ Số điện thoại không hợp lệ. Số điện thoại phải bắt đầu bằng số 0 và có đúng 10 chữ số (Ví dụ: 0901234567).');
                        $question = Question::create('Vui lòng nhập lại số điện thoại của bạn:')
                            ->addButtons([Button::create('⬅️ Quay lại')->value('back')]);
                        return $bot->reply($question);
                    }
                    $state['feedbackPhone'] = $phone;
                    $step = 'askFeedbackMessage';
                } elseif ($step === 'askFeedbackMessage') {
                    Feedback::create([
                        'name' => 'Khách từ Chatbot',
                        'contact' => $state['feedbackPhone'] ?? 'N/A',
                        'subject' => 'Góp ý qua Chatbot',
                        'message' => $message
                    ]);
                    $bot->reply(BotSetting::get('bot_feedback_thanks', 'Cảm ơn bạn đã đóng góp ý kiến! Fashion Hub sẽ ghi nhận và cải thiện dịch vụ tốt hơn. ❤️'));
                    $bot->reply(BotSetting::get('bot_back_menu_msg', 'Gõ "menu" để quay lại.'));
                    $request->session()->forget(['botman_step', 'botman_state']);
                    return;
                } elseif ($step === 'askGender') {
                    $state['gender'] = $value;
                    $step = 'askHeight';
                } elseif ($step === 'askHeight') {
                    $height = (int) $message;
                    if ($height < 100 || $height > 250) return $bot->reply('Vui lòng nhập chiều cao thật của bạn (VD: 170):');
                    $state['height'] = $height;
                    $step = 'askWeight';
                } elseif ($step === 'askWeight') {
                    $weight = (int) $message;
                    if ($weight < 30 || $weight > 200) return $bot->reply('Vui lòng nhập cân nặng thật của bạn (VD: 65):');
                    $state['weight'] = $weight;
                    $step = 'showConsultation';
                } elseif ($step === 'askCategory') {
                    $state['selectedCategory'] = $value;
                    $step = 'askPrice';
                } elseif ($step === 'askPrice') {
                    $state['selectedPrice'] = $value;
                    $step = 'askProduct';
                } elseif ($step === 'askProduct') {
                    // This is now handled by hears('buy_now_{id}')
                } elseif ($step === 'askName') {
                    $state['customerName'] = $message;
                    $step = 'askPhone';
                } elseif ($step === 'askPhone') {
                    $phone = trim($message);
                    if (!preg_match('/^0[0-9]{9}$/', $phone)) {
                        $bot->reply('⚠️ Số điện thoại không hợp lệ. Số điện thoại phải bắt đầu bằng số 0 và có đúng 10 chữ số (Ví dụ: 0901234567).');
                        $question = Question::create('Vui lòng nhập lại số điện thoại nhận hàng:')
                            ->addButtons([Button::create('⬅️ Quay lại')->value('back')]);
                        return $bot->reply($question);
                    }
                    $state['customerPhone'] = $phone;
                    $step = 'askAddress';
                } elseif ($step === 'askAddress') {
                    $state['customerAddress'] = $message;
                    $step = 'confirmOrder';
                } elseif ($step === 'confirmOrder') {
                    if ($value === 'yes' || strtolower($message) === 'xác nhận') {
                        $product = Product::find($state['selectedProductId']);
                        $order = Order::create([
                            'product_id' => $state['selectedProductId'],
                            'customer_name' => $state['customerName'],
                            'phone' => $state['customerPhone'],
                            'address' => $state['customerAddress'] ?? '',
                            'status' => 'new',
                            'payment_method' => 'cod',
                            'payment_status' => 'pending',
                            'total_amount' => $product ? $product->price : 0
                        ]);
                        $bot->reply(BotSetting::get('bot_order_success', '🎉 Cảm ơn bạn! Đơn hàng đã được ghi nhận mang mã số: #') . $order->id);
                        $request->session()->forget(['botman_step', 'botman_state']);
                        return;
                    } else {
                        $bot->reply(BotSetting::get('bot_order_cancel', 'Đã hủy. Hãy nhấn nút để bắt đầu lại nhé!'));
                        $request->session()->forget(['botman_step', 'botman_state']);
                        return;
                    }
                }
            }

            // Save new state
            $request->session()->put('botman_step', $step);
            $request->session()->put('botman_state', $state);

            // Render Next Question
            if ($step === 'askFeedbackMessage') {
                $question = Question::create(BotSetting::get('bot_feedback_intro', 'Vui lòng nhập nội dung góp ý của bạn:'))
                    ->addButtons([Button::create('⬅️ Quay lại')->value('back')]);
                $bot->reply($question);
            } elseif ($step === 'askHeight') {
                $question = Question::create(BotSetting::get('bot_ask_height', 'Chiều cao của bạn là bao nhiêu cm? (VD: 170)'))
                    ->addButtons([Button::create('⬅️ Quay lại')->value('back')]);
                $bot->reply($question);
            } elseif ($step === 'askWeight') {
                $question = Question::create(BotSetting::get('bot_ask_weight', 'Cân nặng của bạn là bao nhiêu kg? (VD: 65)'))
                    ->addButtons([Button::create('⬅️ Quay lại')->value('back')]);
                $bot->reply($question);
            } elseif ($step === 'showConsultation') {
                $bot->typesAndWaits(2);
                
                $query = Product::where('is_active', true);
                if ($state['gender'] !== 'Unisex') {
                    $query->where(function($q) use ($state) {
                        $q->where('gender', $state['gender'])
                          ->orWhere('gender', 'Unisex');
                    });
                }
                
                $h = $state['height'];
                $w = $state['weight'];
                
                $products = $query->where('min_height', '<=', $h)
                                 ->where('max_height', '>=', $h)
                                 ->where('min_weight', '<=', $w)
                                 ->where('max_weight', '>=', $w)
                                 ->take(5)->get();

                if ($products->isEmpty()) {
                    $question = Question::create(BotSetting::get('bot_no_fit_found', 'Rất tiếc, tôi chưa tìm thấy sản phẩm nào có size chuẩn xác tuyệt đối cho bạn. Tuy nhiên, shop còn nhiều mẫu Oversize, bạn có thể tham khảo nhé!'))
                        ->addButtons([Button::create('⬅️ Quay lại')->value('back')]);
                    $bot->reply($question);
                    $request->session()->put('botman_step', 'menu');
                    return;
                }

                $bot->reply(BotSetting::get('bot_fit_results_intro', 'Dựa trên các chỉ số của bạn, đây là những mẫu áo cực kỳ vừa vặn dành cho bạn:'));
                
                $html = '<div class="product-carousel">';
                foreach ($products as $product) {
                    $priceFormat = number_format($product->price) . 'đ';
                    $html .= '<div class="product-card">';
                    
                    if ($product->image) {
                        $imgUrl = asset($product->image);
                        $html .= '<img src="'.$imgUrl.'" alt="'.$product->name.'">';
                    } else {
                        $html .= '<div style="height: 200px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; font-size: 40px; color: #94a3b8;">📦</div>';
                    }
                    
                    $html .= '<div class="product-card-body">';
                    $html .= '<h3 class="product-card-title">'.$product->name.'</h3>';
                    $html .= '<p class="product-card-price">'.$priceFormat.'</p>';
                    $html .= '<div class="product-card-actions">';
                    $html .= '<button class="btn-primary" onclick="window.parent.botmanChatWidget.whisper(\'buy_now_'.$product->id.'\'); return false;">Mua ngay</button>';
                    $html .= '<button onclick="window.parent.botmanChatWidget.whisper(\'add_to_cart_'.$product->id.'\'); return false;">Thêm vào giỏ</button>';
                    $html .= '<button onclick="window.parent.botmanChatWidget.whisper(\'view_product_'.$product->id.'\'); return false;">Chi tiết</button>';
                    $html .= '</div></div></div>';
                }
                $html .= '</div>';
                $bot->reply($html);
                
                $request->session()->forget(['botman_step', 'botman_state']);
                return;

            } elseif ($step === 'askCategory') {
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
                    $question = Question::create('Bạn đang tìm kiếm sản phẩm gì? (Hãy gõ tên sản phẩm hoặc gõ "tất cả")')
                        ->addButtons([Button::create('⬅️ Quay lại')->value('back')]);
                    $bot->reply($question);
                    $request->session()->put('botman_step', 'askSearch');
                    return;
                }
                
                $bot->reply('Của bạn đây! Hãy chọn sản phẩm ưng ý nhất nhé:');
                
                $html = '<div class="product-carousel">';
                foreach ($products as $product) {
                    $priceFormat = number_format($product->price) . 'đ';
                    $html .= '<div class="product-card">';
                    
                    if ($product->image) {
                        $imgUrl = asset($product->image);
                        $html .= '<img src="'.$imgUrl.'" alt="'.$product->name.'">';
                    } else {
                        $html .= '<div style="height: 200px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; font-size: 40px; color: #94a3b8;">📦</div>';
                    }
                    
                    $html .= '<div class="product-card-body">';
                    $html .= '<h3 class="product-card-title">'.$product->name.'</h3>';
                    $html .= '<p class="product-card-price">'.$priceFormat.'</p>';
                    $html .= '<div class="product-card-actions">';
                    $html .= '<button class="btn-primary" onclick="window.parent.botmanChatWidget.whisper(\'buy_now_'.$product->id.'\'); return false;">Mua ngay</button>';
                    $html .= '<button onclick="window.parent.botmanChatWidget.whisper(\'add_to_cart_'.$product->id.'\'); return false;">Thêm vào giỏ</button>';
                    $html .= '<button onclick="window.parent.botmanChatWidget.whisper(\'view_product_'.$product->id.'\'); return false;">Chi tiết</button>';
                    $html .= '</div></div></div>';
                }
                $html .= '</div>';
                $bot->reply($html);

                // 3. Simple Footer with only ONE back button
                $question = Question::create('Bạn chưa ưng ý?')->addButtons([
                    Button::create('⬅️ Quay lại')->value('back')
                ]);
                $bot->reply($question);

            } elseif ($step === 'askPhone') {
                $question = Question::create('Cảm ơn ' . $state['customerName'] . '. ' . BotSetting::get('bot_ask_phone', 'Vui lòng cho biết SĐT của bạn:'))
                    ->addButtons([Button::create('⬅️ Quay lại')->value('back')]);
                $bot->reply($question);

            } elseif ($step === 'askAddress') {
                $question = Question::create(BotSetting::get('bot_ask_address', 'Địa chỉ nhận hàng của bạn ở đâu?'))
                    ->addButtons([Button::create('⬅️ Quay lại')->value('back')]);
                $bot->reply($question);

            } elseif ($step === 'confirmOrder') {
                $product = Product::find($state['selectedProductId']);
                $bot->reply(BotSetting::get('bot_confirm_order_intro', 'Xác nhận đặt hàng:'));
                $bot->reply('📦 Sản phẩm: ' . $product->name);
                $bot->reply('👤 Người nhận: ' . $state['customerName']);
                $bot->reply('📞 SĐT: ' . $state['customerPhone']);
                $bot->reply('🏠 Địa chỉ: ' . $state['customerAddress']);

                $question = Question::create(BotSetting::get('bot_confirm_order_question', 'Bạn xác nhận đặt hàng chứ?'))
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
