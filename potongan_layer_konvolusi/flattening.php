<?php

function matrixToVector($matrix)
{
    $vector = [];
    foreach ($matrix as $row) {
        foreach ($row as $value) {
            $vector[] = $value;
        }
    }
    return $vector;
}

// Contoh matriks input-filter
$inputFilterMatrix = [
    [[235, 0], [0, 0]],
    [[255, 0], [30, 0]],
    [[255, 0], [30, 0]],
    [[255, 255], [255, 255]],
    [[255, 255], [255, 255]],
    [[150, 255], [100, 255]],
    [[255, 255], [255, 255]],
    [[75, 255], [225, 255]],
    [[255, 255], [255, 255]]
];

// Mengonversi matriks input-filter ke vektor
$outputVector = [];
foreach ($inputFilterMatrix as $matrix) {
    $outputVector = array_merge($outputVector, matrixToVector($matrix));
}

// Cetak vektor 1 dimensi
echo "[" . implode(", ", $outputVector) . "]";
