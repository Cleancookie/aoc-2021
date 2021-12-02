<?php

$commands = file(__DIR__ . '/input.txt');

$pos = ['horizontal' => 0, 'depth' => 0];
foreach ($commands as $command) {
    $vector = explode(' ', $command);
    if ($vector[0] === 'forward') {
        $pos['horizontal'] += $vector[1];
        continue;
    }

    $pos['depth'] = $pos['depth'] + ($vector[0] == 'up' ? $vector[1]*-1 : $vector[1]);
}

print_r('<pre>*** DEBUG ***<br>');
print_r($pos['horizontal'] * $pos['depth']);
print_r('</pre>');
die;
