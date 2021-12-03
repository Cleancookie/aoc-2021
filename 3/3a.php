<?php

$results = file(__DIR__ . '/diagnostic-report.txt');
$results = array_map(fn ($result) => trim($result), $results);

$counts = [];

// Count 0s and 1s
foreach ($results as $result) {
    // turn $result into an array where each element is a column
    $bits = str_split($result);
    
    // for each column, increment the corresponding counter for 1 or 0
    foreach ($bits as $col => $bit) {
        $counts[$col][$bit] = isset($counts[$col][$bit]) ? $counts[$col][$bit] + 1 : 1;
    }
}

// Convert the counts into a 1 or 0, then convert that counts array into a string
$gammaRateBinary = implode(array_map(function ($bit) {
    return ($bit[1] > $bit[0]) ? '1' : '0';
}, $counts));
// Epsilon is just the gamma binary but inverted
$epsilonRateBinary = strtr($gammaRateBinary, [1, 0]);

$gammaRate = bindec($gammaRateBinary);
$epsilonRate = bindec($epsilonRateBinary);

$powerConsumption = $gammaRate * $epsilonRate;

echo "Gamma rate: $gammaRate, Epsilon rate: $epsilonRate, Power consumption: $powerConsumption" . PHP_EOL;
