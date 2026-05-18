<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/bootstrap/app.php';

$kernel = app()->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $o1 = App\Models\Order::create([
        'product_id' => 1,
        'customer_name' => 'Test 1',
        'phone' => '0912345678',
        'status' => 'new',
        'payment_method' => 'vnpay',
        'payment_status' => 'pending',
        'total_amount' => 10000
    ]);
    
    $o2 = App\Models\Order::create([
        'product_id' => 1,
        'customer_name' => 'Test 2',
        'phone' => '0912345678',
        'status' => 'new',
        'payment_method' => 'vnpay',
        'payment_status' => 'pending',
        'total_amount' => 10000
    ]);

    echo "SUCCESS: Created o1 ID: {$o1->id}, o2 ID: {$o2->id}\n";
    
    // Clean up
    $o1->forceDelete();
    $o2->forceDelete();
    echo "CLEANED UP successfully.\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
