<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/bootstrap/app.php';

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

$kernel = app()->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $tableName = 'orders';
    
    // Get column types and details
    $columns = Schema::getColumnListing($tableName);
    echo "Columns of table '$tableName':\n";
    foreach ($columns as $column) {
        $type = Schema::getColumnType($tableName, $column);
        echo " - $column ($type)\n";
    }
    
    // Get indexes
    echo "\nIndexes of table '$tableName':\n";
    $indexes = DB::select("SHOW INDEX FROM $tableName");
    foreach ($indexes as $index) {
        echo " - Name: {$index->Key_name}, Unique: " . ($index->Non_unique == 0 ? "YES" : "NO") . ", Column: {$index->Column_name}\n";
    }
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
