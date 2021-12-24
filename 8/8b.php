<?php
echo __DIR__ . '<br>';

$report = file(__DIR__ . '/example.txt');
$report = file(__DIR__ . '/input.txt');
$report = array_map(function ($result) {
  return trim($result);
}, $report);
$displays = array_map(
  function($numbers) {return ['input' => explode(' ', $numbers[0]), 'output' => explode(' ', $numbers[1])]; }, 
  array_map(
    function($line) { return explode(' | ', $line); }, 
    $report
  )
);

$counter = 0;
// Figure them out in this order
// https://i.vgy.me/wGpDEh.png
// look at 3/4 to find top and middle segment
// 1 gets both right segments
// look for 3 to make bottom segment
// 5 will reveal top left and bottom right segments, which also leans top right segment
// last segment is bottom left
// seems like simultaneous equations

foreach ($displays as $display) {
    foreach ($display['output'] as $segment) {
        $counter += is1478($segment) ? 1 : 0;
    }
}

echo "Counter: $counter" . PHP_EOL;

function is1478($segment) {
    $segments = str_split($segment);

    return in_array(count($segments), [2, 4, 3, 7]);
}
