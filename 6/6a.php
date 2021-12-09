<?php
$input = trim(file_get_contents(__DIR__ . '/input.txt'));
$fish = explode(',', $input);

$days = 80;
$spawnRate = 6;
$rateForNew = 8;

for ($day = 0; $day < $days; $day++) {
    $fish = processFish($fish, $spawnRate, $rateForNew);
}

$count = count($fish);
echo "Number of fish after $days days: $count";

function processFish($fishes, $spawnRate, $rateForNew)
{
    $finalFish = [];
    foreach ($fishes as $fish) {
        $fish--;
        if ($fish < 0) {
            $finalFish[] = $spawnRate;
            $finalFish[] = $rateForNew;
            continue;
        }

        $finalFish[] = $fish;
    }

    return $finalFish;
}
