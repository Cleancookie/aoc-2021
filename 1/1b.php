<?php
$reportRaw = file_get_contents(__DIR__ . '/report.json');
$report = json_decode($reportRaw);
$reportSummed = [];

foreach ($report as $key => $value) {
    if (!isset($report[$key + 1]) && !isset($report[$key + 2])) {
        continue;
    }
    $reportSummed[] = $value + $report[$key + 1] + $report[$key + 2];
}

$increased = 0;
$prev = null;
foreach ($reportSummed as $key => $value) {
    if ($prev === null) {
        $prev = $value;
        continue;
    }

    if ($value > $prev) {
        $increased++;
    }
    $prev = $value;
}
echo ($increased);