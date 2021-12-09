<?php

$input = trim(file_get_contents(__DIR__ . '/input.txt'));
$crabs = explode(',', $input);

$median = findMedian($crabs);
$fuel = calculateFuel($crabs, $median);

print_r('<pre>*** DEBUG ***<br>');
print_r($fuel);
print_r('</pre>');
print_r('<pre>*** DEBUG ***<br>');
print_r($median);
print_r('</pre>');
die;
die;

function calculateFuel($loactions, $destination) {
    $fuel = 0;
    foreach ($loactions as $location) {
        $fuel += abs($location - $destination);
        echo abs($location - $destination) . PHP_EOL;
    }

    return $fuel;
}

function findMedian($nums) {
    $counts = array_count_values($nums);
    $median = array_keys($counts, max($counts));


    return array_sum($median) / count($median);
}
