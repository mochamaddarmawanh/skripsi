<?php

$hasil_konv_relu = [
    [470, 105, 0, 0, 5],
    [160, 0, 0, 0, 0],
    [30, 0, 0, 0, 0],
    [0, 0, 0, 0, 5],
    [105, 0, 0, 0, 160],
];

for ($i = 0; $i < count($hasil_konv_relu); $i++) {
    for ($j = 0; $j < count($hasil_konv_relu[$i]); $j++) {
        // Memastikan nilai tidak melebihi 255
        if ($hasil_konv_relu[$i][$j] > 255) {
            $hasil_konv_relu[$i][$j] = 255;
        }
    }
}

echo "[\n";
foreach ($hasil_konv_relu as $row) {
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
