<?php

$input = trim(file_get_contents(__DIR__ . '/input.txt'));
$crabs = explode(',', $input);

$median = findMedian($crabs);
$fuel = calculateFuel($crabs, $median);

echo $fuel . PHP_EOL;

function calculateFuel($loactions, $destination) {
    $fuel = 0;
    foreach ($loactions as $location) {
        $fuel += abs($location - $destination);
    }

    return $fuel;
}

function findMedian($nums) {
    sort($nums);
    $medianKey = (count($nums)-1) / 2;

    if (is_int($medianKey)) {
        // Theres an even amount of $nums
        $a = $nums[$medianKey];
        $b = $nums[$medianKey + 1];

        return ($a + $b) / 2;
    }

    return $nums[$medianKey];
}
