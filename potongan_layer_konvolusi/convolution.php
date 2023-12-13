<?php

function applyConvolution($input, $filter, $padding)
{
    $inputHeight = count($input);
    $inputWidth = count($input[0]);
    $filterHeight = count($filter);
    $filterWidth = count($filter[0]);

    $outputHeight = $inputHeight + 2 * $padding - $filterHeight + 1;
    $outputWidth = $inputWidth + 2 * $padding - $filterWidth + 1;

    $output = [];

    // Tambah zero padding sesuai dengan variabel $padding
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

$input = [
    [50, 150, 255, 255, 255, 255, 250],
    [125, 255, 255, 255, 255, 255, 255],
    [225, 255, 255, 255, 255, 255, 255],
    [255, 255, 255, 255, 255, 255, 255],
    [255, 255, 255, 255, 255, 255, 255],
    [255, 255, 255, 255, 255, 255, 250],
    [150, 255, 255, 255, 255, 255, 100]
];

$filter = [
    [-1, -1, -1],
    [-1, 8, -1],
    [-1, -1, -1]
];

$padding = 0;

$output = applyConvolution($input, $filter, $padding);

echo "[\n";
foreach ($output as $row) {
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
