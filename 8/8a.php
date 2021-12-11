<?php
$report = file(__DIR__ . '/example.txt');
$report = file(__DIR__ . '/input.txt');
$report = array_map(fn ($result) => trim($result), $report);
$displays = array_map(
    fn ($numbers) => [
        'input' => explode(' ', $numbers[0]), 'output' => explode(' ', $numbers[1])
    ],
    array_map(fn ($line) => explode(' | ', $line), $report)
);

$counter = 0;

foreach ($displays as $display) {
    foreach ($display['output'] as $segment) {
        $counter += is1478($segment) ? 1 : 0;
    }
}

echo "Coutner: $counter" . PHP_EOL;

function is1478($segment) {
    $segments = str_split($segment);

    return in_array(count($segments), [2, 4, 3, 7]);
}
