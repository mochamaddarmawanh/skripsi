<?php

$red = [
    [255,  0,  0, 255],
    [  0,  0,  0,  0],
    [255,  0,  0, 255],
    [  0,  0,  0,  0]
];

$green = [
    [  0, 255,  0,   0],
    [255,   0, 255, 0],
    [  0, 255,   0,   0],
    [255,   0, 255, 0]
];

$blue = [
    [  0,  0, 255,   0],
    [  0,  0,  0,  0],
    [  0,  0, 255,   0],
    [  0,  0,  0,  0]
];

// Lebar dan tinggi gambar (berdasarkan salah satu saluran, karena harus sama)
$lebar = count($red[0]);
$tinggi = count($red);

// Membuat gambar baru dengan mode RGB
$gambar = imagecreatetruecolor($lebar, $tinggi);

// Fungsi untuk mengubah saluran menjadi warna RGB
function saluranKeWarna($r, $g, $b) {
    return imagecolorallocate($GLOBALS['gambar'], $r, $g, $b);
}

// Mengisi gambar dengan warna sesuai saluran RGB
for ($i = 0; $i < $tinggi; $i++) {
    for ($j = 0; $j < $lebar; $j++) {
        $warna = saluranKeWarna($red[$i][$j], $green[$i][$j], $blue[$i][$j]);
        imagesetpixel($gambar, $j, $i, $warna);
    }
}

// Menyimpan gambar sebagai file PNG
imagepng($gambar, 'new_rgb.png');

// Menghapus gambar dari memori
imagedestroy($gambar);