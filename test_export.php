<?php
require_once 'vendor/autoload.php';
require_once 'bootstrap/app.php';

use App\Exports\BookingsExport;
use Maatwebsite\Excel\Facades\Excel;

try {
    $filters = [
        'status' => '',
        'region' => '',
        'date_from' => '',
        'date_to' => '',
    ];
    
    $export = new BookingsExport($filters);
    echo "Export class instantiated successfully\n";
    echo "Query count: " . $export->query()->count() . " bookings\n";
    echo "Export seems valid!\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
