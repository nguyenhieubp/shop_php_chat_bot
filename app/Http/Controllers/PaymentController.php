<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function vnpayReturn(Request $request)
    {
        $vnp_HashSecret = config('vnpay.vnp_HashSecret');
        $vnp_SecureHash = $request->vnp_SecureHash;
        $inputData = array();
        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        
        if ($secureHash == $vnp_SecureHash) {
            if ($request->vnp_ResponseCode == '00') {
                $txnRef = $request->vnp_TxnRef;
                $orderIds = explode('_', $txnRef);
                // The last element is the timestamp
                array_pop($orderIds);

                foreach ($orderIds as $id) {
                    $order = Order::find($id);
                    if ($order) {
                        $order->update([
                            'status' => 'pending',
                            'payment_status' => 'paid',
                            'vnpay_txn_ref' => $request->vnp_TransactionNo
                        ]);
                    }
                }
                
                session()->forget('cart');
                return redirect()->route('home')->with('success', 'Thanh toán thành công! Đơn hàng của bạn đang được xử lý.');
            } else {
                return redirect()->route('home')->with('error', 'Thanh toán không thành công. Vui lòng thử lại.');
            }
        } else {
            return redirect()->route('home')->with('error', 'Chữ ký không hợp lệ!');
        }
    }

    public function vnpayIPN(Request $request)
    {
        $inputData = array();
        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        $vnp_SecureHash = $inputData['vnp_SecureHash'];
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, config('vnpay.vnp_HashSecret'));

        try {
            if ($secureHash == $vnp_SecureHash) {
                $txnRef = $request->vnp_TxnRef;
                $orderIds = explode('_', $txnRef);
                array_pop($orderIds);
                
                $order = Order::find($orderIds[0]);
                if ($order) {
                    if ($order->total_amount * 100 == $request->vnp_Amount) {
                        if ($order->payment_status !== 'paid') {
                            if ($request->vnp_ResponseCode == '00') {
                                foreach ($orderIds as $id) {
                                    $o = Order::find($id);
                                    if ($o) {
                                        $o->update([
                                            'status' => 'pending',
                                            'payment_status' => 'paid',
                                            'vnpay_txn_ref' => $request->vnp_TransactionNo
                                        ]);
                                    }
                                }
                            } else {
                                foreach ($orderIds as $id) {
                                    $o = Order::find($id);
                                    if ($o) {
                                        $o->update(['payment_status' => 'failed']);
                                    }
                                }
                            }
                            $returnData['RspCode'] = '00';
                            $returnData['Message'] = 'Confirm Success';
                        } else {
                            $returnData['RspCode'] = '02';
                            $returnData['Message'] = 'Order already confirmed';
                        }
                    } else {
                        $returnData['RspCode'] = '04';
                        $returnData['Message'] = 'Invalid amount';
                    }
                } else {
                    $returnData['RspCode'] = '01';
                    $returnData['Message'] = 'Order not found';
                }
            } else {
                $returnData['RspCode'] = '97';
                $returnData['Message'] = 'Invalid signature';
            }
        } catch (\Exception $e) {
            $returnData['RspCode'] = '99';
            $returnData['Message'] = 'Unknow error';
        }

        return response()->json($returnData);
    }
}
