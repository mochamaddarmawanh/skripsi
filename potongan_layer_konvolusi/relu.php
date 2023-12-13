<?php

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

$hasil_konvolusi = [
    [50, 200, 400, 600, 800, 700, 250],
    [175, 675, 1300, 1900, 2480, 2135, 755],
    [300, 1100, 2000, 2800, 3460, 2820, 960],
    [400, 1400, 2400, 3180, 3565, 2590, 805],
    [500, 1700, 2800, 3460, 3420, 2160, 600],
    [425, 1425, 2300, 2680, 2385, 1355, 350],
    [150, 500, 800, 900, 750, 400, 100]
];

$hasil_relu = relu($hasil_konvolusi);

echo "[\n";
foreach ($hasil_relu as $row) {
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
