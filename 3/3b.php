<?php

$report = file(__DIR__ . '/diagnostic-report.txt');
$report = array_map(fn ($result) => trim($result), $report);

$oxygenBinary = filterReport($report, 1);
$co2Binary = filterReport($report, 0);
$life = bindec($oxygenBinary) * bindec($co2Binary);
echo "oxygen: $oxygenBinary, co2: $co2Binary, life: $life";

// Recursive function
function filterReport($report, $mostImportantBit, $col = 0) {
    // Exit condition, if there is only 1 row of binary left, return it
    if (count($report) <= 1) {
        // return first thing in $report
        return reset($report);
    }

    [$ones, $zeros] = tally($report, $col);
    $bitToKeep = bitToKeep($ones, $zeros, $mostImportantBit);

    // Collect all the rows in the report that we care about
    $filteredReport = [];
    foreach ($report as $result) {
        $bits = str_split($result);
        $bit = $bits[$col];
        if ((int)$bit === $bitToKeep) {
            $filteredReport[] = $result;
        }
    }

    return filterReport($filteredReport, $mostImportantBit, $col + 1);
}

function tally($report, $col) {
    $ones = 0;
    $zeros = 0;
    foreach ($report as $result) {
        $bits = str_split($result);
        $bit = (int)$bits[$col];
        if ($bit === 0) {
            $zeros++;
        } elseif ($bit === 1) {
            $ones++;
        }
    }
    return [$ones, $zeros];
}

function bitToKeep($ones, $zeros, $mostImportantBit) {
    if ($ones === $zeros) {
        return $mostImportantBit;
    }

    if ($mostImportantBit === 1) {
        return $ones > $zeros ? 1 : 0;
    }

    return $ones < $zeros ? 1 : 0;
}
