<?php

$input = file(__DIR__ . '/input.txt');
$input = array_map(fn ($line) => rtrim($line), $input);

// Get numbers drawn
$numbersDrawn = explode(',', array_shift($input));
$numbersDrawn = array_map(fn ($value) => (int)$value, $numbersDrawn);

// Get boards
$boards = array_map(fn ($board) => array_slice($board, 1), array_chunk($input, 5 + 1));
foreach ($boards as $i => $board) {
    $boards[$i] = array_map(function ($row) {
        // Convert each element in $board to be an array of columns
        $row = str_split($row, 3);

        // convert to numbers
        $row = array_map(fn ($value) => (int)$value, $row);

        return $row;
    }, $board);
}

$winners = [];
$finalNum = null;
foreach ($numbersDrawn as $num) {
    foreach ($boards as $boardId => &$board) {
        if (in_array($boardId, $winners)) {
            continue;
        }

        $board = markNumberOnBoard($board, $num);
        if (isWinning($board)) {
            $winners[] = $boardId;
            $finalNum = $num;
            echo "<h1>BOARD $boardId</h1>";
            echo '<code>'  . viewBoard($board) . '<br><br></code>';
            continue;
        }
    }
}

$winner = end($winners);
$sum = sumBoard($boards[$winner]);
$finalScore = $sum * $finalNum;

echo "Last winning board: $winner, Last number: $finalNum, Sum: $sum, Final Score: $finalScore";

function viewBoard($board) {
    $view = [];
    foreach ($board as $rows) {
        $view[] = implode(' ', array_map(fn ($num) => str_pad((string)$num, 3, ' ', STR_PAD_LEFT), $rows));
    }
    return implode('<br>', $view);
}

function sumBoard($board)
{
    $sum = 0;
    foreach ($board as $rows) {
        $sum += array_reduce($rows, function ($total, $value) {
            return $total + (is_int($value) ? $value : 0);
        }, 0);
    }
    return $sum;
}

function markNumberOnBoard($board, $num)
{
    foreach ($board as $colId => $rows) {
        $board[$colId] = array_map(function($value) use ($num, $board) {
            if ($value === $num) {
//                print_r($num . ' FOUND on board ' . $board[0][0] . PHP_EOL);
            }
            return $value === $num ? 'x' : $value;
        }, $rows);
    }

    return $board;
}

function isWinning($board)
{
    // Check rows
    foreach ($board as $row) {
        // remove all the winning values from this row
        $row = array_filter($row, fn ($value) => $value !== 'x');
        if (empty($row)) {
            // if this row has no values left, then that means theyre all hits and its a winning board
            return true;
        }
    }

    // Check cols
    for ($colId = 0; $colId < count(reset($board)); $colId++) {
        $col = array_filter(array_column($board, $colId), fn ($value) => $value !== 'x');
        if (empty($col)) {
            // if this row has no values left, then that means theyre all hits and its a winning board
            return true;
        }
    }

    return false;
}
