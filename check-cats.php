<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$cats = App\Models\Category::where('status', 1)->where('parent_id', 0)->with('subcategories')->get();
foreach ($cats as $c) {
    echo $c->name . ' (' . $c->slug . ') => ' . $c->subcategories->count() . " sub\n";
    foreach ($c->subcategories as $s) {
        echo "  - " . $s->subcategoryName . " (status: {$s->status})\n";
    }
}
