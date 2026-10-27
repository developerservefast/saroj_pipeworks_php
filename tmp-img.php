<?php
foreach (App\Models\Service::whereNotNull('image')->get(['name', 'image']) as $s) {
    echo $s->name . ' => ' . $s->image . PHP_EOL;
}
