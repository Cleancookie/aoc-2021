<?php
echo '<code>';
$input = trim(file_get_contents(__DIR__ . '/input.txt'));
$fishes = explode(',', $input);
$timers = array_count_values($fishes);

$days = 256;
$spawnRate = 6;
$rateForNew = 8;

for ($day = 0; $day < $days; $day++) {
    $newTimers = array_fill(0, $rateForNew + 1, 0);
    foreach ($timers as $daysLeft => $count) {
        $daysLeft = (int)$daysLeft;

        if ($daysLeft-1 < 0) {
            $newTimers[$rateForNew] += $count;
            $newTimers[$spawnRate] += $count;
            continue;
        }

        $newTimers[$daysLeft-1] += $count;
    }
    $timers = $newTimers;
    echo str_pad($day, 3, '0', STR_PAD_LEFT) . ', ' . json_encode($timers) . '<br>';
}

$count = array_sum($timers);
echo "Number of fish after $days days: $count";
echo '</code>';
