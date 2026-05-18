<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/bootstrap/app.php';

$kernel = app()->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $tables = DB::select('SHOW TABLES');
    $dbName = env('DB_DATABASE');
    $key = "Tables_in_" . $dbName;
    
    foreach ($tables as $t) {
        $tableName = $t->$key;
        echo "Table: $tableName\n";
        $indexes = DB::select("SHOW INDEX FROM $tableName");
        foreach ($indexes as $index) {
            if ($index->Non_unique == 0 && $index->Key_name !== 'PRIMARY') {
                echo " - UNIQUE Index: {$index->Key_name} on column: {$index->Column_name}\n";
            }
        }
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
