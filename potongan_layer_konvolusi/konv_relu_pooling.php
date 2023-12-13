<?php

function applyConvolution($input, $filter, $padding)
{
    $inputHeight = count($input);
    $inputWidth = count($input[0]);
    $filterHeight = count($filter);
    $filterWidth = count($filter[0]);

    // Hitung ukuran output
    $outputHeight = $inputHeight + 2 * $padding - $filterHeight + 1;
    $outputWidth = $inputWidth + 2 * $padding - $filterWidth + 1;

    $output = [];

    // Tambahkan zero padding sesuai dengan variabel $padding
    $paddedInput = [];
    for ($i = 0; $i < $inputHeight + 2 * $padding; $i++) {
        $paddedRow = [];
        for ($j = 0; $j < $inputWidth + 2 * $padding; $j++) {
            if ($i < $padding || $i >= $inputHeight + $padding || $j < $padding || $j >= $inputWidth + $padding) {
                $paddedRow[] = 0;
            } else {
                $paddedRow[] = $input[$i - $padding][$j - $padding];
            }
        }
        $paddedInput[] = $paddedRow;
    }

    for ($i = 0; $i < $outputHeight; $i++) {
        $outputRow = [];
        for ($j = 0; $j < $outputWidth; $j++) {
            $sum = 0;
            for ($k = 0; $k < $filterHeight; $k++) {
                for ($l = 0; $l < $filterWidth; $l++) {
                    $sum += $paddedInput[$i + $k][$j + $l] * $filter[$k][$l];
                }
            }
            $outputRow[] = $sum;
        }
        $output[] = $outputRow;
    }

    return $output;
}

function relu($input)
{
    $output = [];

    foreach ($input as $row) {
        $outputRow = [];
        foreach ($row as $value) {
            // Jika nilai negatif, ubah menjadi nol
            // Jika nilai lebih dari 255, ubah menjadi 255
            $outputRow[] = max(0, min(255, $value));
        }
        $output[] = $outputRow;
    }

    return $output;
}

function applyMaxPooling($input, $poolingHeight, $poolingWidth, $stride)
{
    $inputHeight = count($input);
    $inputWidth = count($input[0]);

    $output = [];

    for ($i = 0; $i < $inputHeight - $poolingHeight + 1; $i += $stride) {
        $outputRow = [];
        for ($j = 0; $j < $inputWidth - $poolingWidth + 1; $j += $stride) {
            $maxValue = PHP_INT_MIN;
            for ($k = 0; $k < $poolingHeight; $k++) {
                for ($l = 0; $l < $poolingWidth; $l++) {
                    $maxValue = max($maxValue, $input[$i + $k][$j + $l]);
                }
            }
            $outputRow[] = $maxValue;
        }
        $output[] = $outputRow;
    }

    return $output;
}

// Input
$input = [
    [0, 0, 0, 0, 0, 0, 0],
    [0, 25, 75, 125, 175, 255, 0],
    [0, 25, 0, 0, 20, 255, 0],
    [0, 50, 0, 0, 120, 145, 0],
    [0, 75, 0, 20, 195, 45, 0],
    [0, 255, 225, 255, 195, 50, 0],
    [0, 0, 0, 0, 0, 0, 0]
];

$filter = [[1, 2, 1],
[2, 4, 2],
[1, 2, 1]];

$padding = 0;

$output = applyConvolution($input, $filter, $padding);

$output_relu = relu($output);

$pooling = "max_pooling";
$pooling_size_height = 2;
$pooling_size_width = 2;
$stride = 2;

if ($pooling === "max_pooling") {
    $output_pooling = applyMaxPooling($output_relu, $pooling_size_height, $pooling_size_width, $stride);

    // Output hasil Max Pooling
    echo "[\n";
    foreach ($output_pooling as $row) {
        echo "[";
        $lastIndex = count($row) - 1;
        foreach ($row as $index => $value) {
            echo $value;
            if ($index !== $lastIndex) {
                echo ", ";
            }
        }
        echo "]\n";
    }
    echo "]";
}
