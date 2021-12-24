<?php
echo __DIR__ . '<br>';

$report = file(__DIR__ . '/example.txt');
$report = file(__DIR__ . '/input.txt');
$report = array_map(function ($result) {
    return trim($result);
}, $report);
$displays = array_map(
    function ($numbers) {
        return ['input' => explode(' ', $numbers[0]), 'output' => explode(' ', $numbers[1])];
    },
    array_map(
        function ($line) {
            return explode(' | ', $line);
        },
        $report
    )
);

$segmentDescriptions = [
    '0' => [0, 1, 2, 4, 5, 6], // which segment positions are turned on?
    '1' => [2, 5],
    '2' => [0, 2, 3, 4, 6],
    '3' => [0, 2, 3, 5, 6],
    '4' => [1, 2, 3, 5],
    '5' => [0, 1, 3, 5, 6],
    '6' => [0, 1, 3, 4, 5, 6],
    '7' => [0, 2, 5],
    '8' => [0, 1, 2, 3, 4, 5, 6],
    '9' => [0, 1, 2, 3, 5, 6],
];

$sum = 0;
// Figure them out in this order
// https://i.vgy.me/wGpDEh.png
// look at 3/4 to find top and middle segment
// 1 gets both right segments
// look for 3 to make bottom segment
// 5 will reveal top left and bottom right segments, which also leans top right segment
// last segment is bottom left
// seems like simultaneous equations

foreach ($displays as $display) {
    $mapping = array_fill(0, 10, '');
    $segmentPossibleLetters = array_fill(0, 7, ['a', 'b', 'c', 'd', 'e', 'f', 'g']);

    // We can find 1s 4s and 7s if we just count number of letters as only a 1 has 2 letters etc
    // Do 1 first so we can subtract those segments out of the equation when we mark possible letters for 4 and 7
    foreach ($display['input'] as $segments) {
        $segments = str_split($segments);
        if (count($segments) !== 2) {
            continue;
        }

        $mapping[1] = $segments;
        $segmentPossibleLetters = filterPossibleLettersForSegment($segmentPossibleLetters, $segments, 2);
        $segmentPossibleLetters = filterPossibleLettersForSegment($segmentPossibleLetters, $segments, 5);
        break;
    }
    // I'm hoping that we will always find a 1 lol

    foreach ($display['input'] as $segments) {
        $segments = str_split($segments);
        switch (count($segments)) {
            case 4: // 4
                $mapping[4] = $segments;
                $uniqueSegments = array_diff($segments, $mapping[1]);
                $segmentPossibleLetters = filterPossibleLettersForSegment($segmentPossibleLetters, $uniqueSegments, 1);
                $segmentPossibleLetters = filterPossibleLettersForSegment($segmentPossibleLetters, $uniqueSegments, 3);
                break;
            case 3: // 7
                $mapping[7] = $segments;
                $uniqueSegments = array_diff($segments, $mapping[1]);
                $segmentPossibleLetters = filterPossibleLettersForSegment($segmentPossibleLetters, $uniqueSegments, 0);
                break;
            default:
                break;
        }
    }

    // Now we can work out the bottom segment (6) and middle (3) by looking for 3s
    foreach ($display['input'] as $segments) {
        $segments = str_split($segments);
        if (count($segments) !== 5) {
            continue;
        }

        // it could be a 2 3 or 5
        // if it's got the letters for 7 in it then this is the one we want
        if (count(array_intersect($segments, $mapping[7])) !== count($mapping[7])) {
            continue;
        }

        // remove all the segments that exist in 7 and 4.  We're left with only segment 6
        $mapping[3] = $segments;
        $segments = array_diff($segments, $mapping[7]);
        $segments = array_diff($segments, $mapping[4]);
        if (count($segments) > 1) {
            throw new Exception('Could not determine segment 6');
        }
        $segmentPossibleLetters[6] = [reset($segments)];

        // We can find middle segment as can subtract (0), (6), (2), (5)
        $segments = array_diff($mapping[3], $segmentPossibleLetters[0]);
        $segments = array_diff($segments, $segmentPossibleLetters[6]);
        $segments = array_diff($segments, $mapping[1]);
        $segmentPossibleLetters[3] = $segments;

        // We can find (1) now since it's not whatever (3) is
        $segmentPossibleLetters[1] = array_diff($segmentPossibleLetters[1], $segmentPossibleLetters[3]);
    }

    // Now find 5 to work out (5), which means we can then figure out (2)
    foreach ($display['input'] as $segments) {
        $segments = str_split($segments);
        if (count($segments) !== 5) {
            continue;
        }

        // it could be a 2 3 or 5
        if (count(array_intersect($segments, [
                ...$segmentPossibleLetters[0],
                ...$segmentPossibleLetters[1],
                ...$segmentPossibleLetters[3],
                ...$segmentPossibleLetters[6],
            ])) !== 4
        ) {
            continue;
        }

        $mapping[5] = $segments;
        $segmentPossibleLetters[2] = array_diff($mapping[3], $mapping[5]);
        $segmentPossibleLetters[5] = array_diff($segmentPossibleLetters[5], $segmentPossibleLetters[2]);
    }

    // Whatever letter is, it's (4)
    $segmentPossibleLetters[4] = array_diff($segmentPossibleLetters[4], [
        ...$segmentPossibleLetters[0],
        ...$segmentPossibleLetters[1],
        ...$segmentPossibleLetters[2],
        ...$segmentPossibleLetters[3],
        ...$segmentPossibleLetters[5],
        ...$segmentPossibleLetters[6],
    ]);

    $elements = array_reduce($segmentPossibleLetters, function ($carry, $letters) { return $carry + count($letters);}, 0);
    if ($elements !== 7) {
        throw new Exception('One of the segments was not figured out');
    }

    $output = array_map(function ($segments) {
        // return $segments as int using the mapping created for this display
    }, $display['output']);
}

function filterPossibleLettersForSegment(array $segmentPossibleLetters, array $segments, int $segment)
{
    $segmentPossibleLetters[$segment] = array_intersect($segmentPossibleLetters[$segment], $segments);
    return $segmentPossibleLetters;
}

echo "Outputs sum: $sum" . PHP_EOL;
