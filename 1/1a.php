<?php
$report = file(__DIR__ . '/report.txt');

$increased = 0;
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
