<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/bootstrap/app.php';

$kernel = app()->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $orders = App\Models\Order::all();
    echo "Total orders: " . $orders->count() . "\n";
    foreach ($orders as $o) {
        echo "ID: {$o->id}, Phone: {$o->phone}, Name: {$o->customer_name}, Status: {$o->status}, Payment Method: {$o->payment_method}, Payment Status: {$o->payment_status}\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
