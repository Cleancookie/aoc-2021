<?php
$report = file(__DIR__ . '/input.txt');
$report = array_map(fn ($result) => trim($result), $report);

// Convert each row into an array like [ 'start' => ['x' => $x, 'y' => $y], 'end' => [ ... ] ]
$vents = [];
foreach ($report as $row) {
    [$start, $end] = explode(' -> ', $row);
    [$startX, $startY] = explode(',', $start);
    [$endX, $endY] = explode(',', $end);
    $vents[] = [
        'start' => [
            'x' => $startX,
            'y' => $startY,
        ],
        'end' => [
            'x' => $endX,
            'y' => $endY,
        ]
    ];
}

$vents = findStraightVents($vents);
[$width, $height] = findDimensions($vents);
$map = plotVentsOnMap($vents, $width, $height);

$counter = 0;
foreach ($map as $row) {
    foreach ($row as $value) {
        $counter += $value > 1 ? 1 : 0;
    }
}

echo $counter;


function printMap($map)
{
    $map = array_map(function ($row) { return implode($row); }, $map);
    $map = implode('<br>', $map);
    return '<code>' . $map . '</code>';
}

function plotVentsOnMap($vents, $width, $height)
{
    $map = array_fill(0, $width, 0); // Make a row of 0s
    $map = array_fill(0, $height, $map); // Copy and paste this row of 0s so we get a grid of 0s

    foreach ($vents as $key => $vent) {
        $map = plotVent($map, $vent);
    }

    return $map;
}

function plotVent($map, $vent)
{
    $points = drawLine($vent);
    foreach ($points as $point) {
        $map[$point['y']][$point['x']]++;
    }
    return $map;
}

function drawLine($coords)
{
    $points = [];

    // get all x value then all y values
    $xPoints = range($coords['start']['x'], $coords['end']['x']);
    $yPoints = range($coords['start']['y'], $coords['end']['y']);
    if (count($xPoints) === 1) {
        $xPoints = array_fill(0, count($yPoints), $xPoints[0]);
    }
    if (count($yPoints) === 1) {
        $yPoints = array_fill(0, count($xPoints), $yPoints[0]);
    }

    foreach ($xPoints as $key => $x) {
        $points[] = ['x' => $x, 'y' => $yPoints[$key]];
    }

    return $points;
}

function findDimensions(array $vents)
{
    $height = 0;
    $width = 0;

    foreach ($vents as $vent) {
        $biggerX = max(array_column($vent, 'x'));
        if ($width < $biggerX) {
            $width = $biggerX;
        }

        $biggerY = max(array_column($vent, 'y'));
        if ($height < $biggerY) {
            $height = $biggerY;
        }
    }

    return [$width + 1, $height + 1];
}

function findStraightVents($vents)
{
    return $vents;
    return array_filter($vents, function ($vent) {

        return (
            $vent['start']['x'] === $vent['end']['x'] ||
            $vent['start']['y'] === $vent['end']['y']
        );
    });
}
