<?php

$reportRaw = file_get_contents('./1/report.json');
$report = json_decode($reportRaw);

$increased = 0;

$iMax = count($report);
$prev = null;
foreach ($report as $key => $value) {
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
