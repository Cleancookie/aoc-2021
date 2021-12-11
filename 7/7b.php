<?php
$input = trim(file_get_contents(__DIR__ . '/input.txt'));
$crabs = explode(',', $input);

$destination = findMean($crabs);
$fuel = calculateFuel($crabs, $destination);

echo "Dest: $destination, fuel: $fuel" . PHP_EOL;

function calculateFuel($loactions, $destination) {
    $fuel = 0;
    foreach ($loactions as $location) {
        $d = abs($location - $destination);
        for ($i = 1; $i <= $d; $i++) {
            $fuel += $i;
        }
    }

    return $fuel;
}

function findMean($nums) {
    $mean = array_sum($nums) / count ($nums);
    return floor($mean); // Dont know why floor instead of round
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
