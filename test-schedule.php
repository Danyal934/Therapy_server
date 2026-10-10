<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing schedule...\n";

$kernel = $app->make(App\Console\Kernel::class);

// Use reflection to call the protected schedule method
$reflection = new ReflectionClass($kernel);
$method = $reflection->getMethod('schedule');
$method->setAccessible(true);

$schedule = new Illuminate\Console\Scheduling\Schedule();
$method->invoke($kernel, $schedule);

echo "Found " . count($schedule->events()) . " scheduled events\n";

foreach ($schedule->events() as $event) {
    echo "- " . $event->description . "\n";
}
