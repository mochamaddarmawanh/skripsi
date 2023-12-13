<?php

$hasil_konvolusi = [
    [255, 105, 0, 0, 5],
    [160, 0, 0, 0, 0],
    [30, 0, 0, 0, 0],
    [0, 0, 0, 0, 5],
    [105, 0, 0, 0, 160]
];

$pooling = "max_pooling";
$pooling_size_height = 2;
$pooling_size_width = 2;
$stride = 2;

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

if ($pooling === "max_pooling") {
    $output = applyMaxPooling($hasil_konvolusi, $pooling_size_height, $pooling_size_width, $stride);
}

// Output hasil Max Pooling
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
