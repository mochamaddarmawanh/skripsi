<?php

// Lokasi file gambar yang akan diolah
$gambar = 'download (5).jpeg';

// Memuat gambar menggunakan GD
$image = imagecreatefromjpeg($gambar);

// Mendapatkan lebar dan tinggi gambar
$lebar = imagesx($image);
$tinggi = imagesy($image);

// Inisialisasi matriks array untuk menyimpan citra pixel
$citraPixel = [];

// Loop melalui gambar dan ekstrak nilai pixel
for ($y = 0; $y < $tinggi; $y++) {
    $barisPixel = []; // Inisialisasi matriks untuk setiap baris
    for ($x = 0; $x < $lebar; $x++) {
        $pixel = imagecolorat($image, $x, $y);
        $rgba = imagecolorsforindex($image, $pixel);
        // Menyimpan nilai grayscale (misalnya, jika citra grayscale)
        $barisPixel[] = $rgba['red']; // Menggunakan komponen merah untuk citra grayscale
    }
    $citraPixel[] = $barisPixel; // Menambahkan matriks baris ke matriks citraPixel
}

// Tutup gambar
imagedestroy($image);

$input = $citraPixel;

// Mencetak input dalam format yang diinginkan
echo '$input = [';
foreach ($input as $baris) {
    echo '[' . implode(', ', $baris) . '],<br>';
}
echo '];';
