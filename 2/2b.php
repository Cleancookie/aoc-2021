<?php

$commands = file(__DIR__ . '/input.txt');

$pos = ['horizontal' => 0, 'depth' => 0, 'aim' => 0];
foreach ($commands as $command) {
    $vector = explode(' ', $command);
    $vector[1] = (int)$vector[1];
    if ($vector[0] === 'down') {
        $pos['aim'] += $vector[1];
        continue;
    }

    if ($vector[0] === 'up') {
        $pos['aim'] -= $vector[1];
        continue;
    }

    if ($vector[0] === 'forward') {
        $pos['horizontal'] += $vector[1];
        $pos['depth'] += ($vector[1] * $pos['aim']);
        continue;
    }
}
print_r($pos['horizontal'] * $pos['depth'] . PHP_EOL);
