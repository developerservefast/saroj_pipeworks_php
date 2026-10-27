<?php
$items = [
 ['Bathroom Fitting Installation', 'Neat installation of taps, showers, basins and toilets for homes, shops and offices.'],
 ['Water Leakage Repair', 'We find and fix leaks in pipes, joints and walls quickly, with minimal damage.'],
 ['Drainage & Blockage Cleaning', 'Clearing of blocked drains and slow drainage, with a lasting fix.'],
 ['CPVC Pipeline Work', 'New CPVC pipeline fitting for houses, shops, offices and hotels.'],
 ['Water Tank & Motor Connection', 'Connection and repair work for overhead tanks, pumps and motors.'],
];
foreach ($items as $k => [$n, $d]) {
    App\Models\Service::firstOrCreate(['name' => $n], ['description' => '<p>' . htmlspecialchars($d) . '</p>', 'is_active' => true, 'sort_order' => $k + 2]);
}
echo App\Models\Service::count() . " services total\n";
