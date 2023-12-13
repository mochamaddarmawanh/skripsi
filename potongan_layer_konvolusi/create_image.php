<?php
// Matriks citra
$citra = [[75, 255],
[225, 255]];

// Lebar dan tinggi gambar
$lebar = count($citra[0]);
$tinggi = count($citra);

// Membuat gambar baru
$gambar = imagecreatetruecolor($lebar, $tinggi);

// Fungsi untuk mengubah warna dari nilai matriks menjadi warna RGB
function nilaiKeWarna($nilai)
{
    return imagecolorallocate($GLOBALS['gambar'], $nilai, $nilai, $nilai);
}

// Mengisi gambar dengan warna sesuai matriks
for ($i = 0; $i < $tinggi; $i++) {
    for ($j = 0; $j < $lebar; $j++) {
        $warna = nilaiKeWarna($citra[$i][$j]);
        imagesetpixel($gambar, $j, $i, $warna);
    }
}

// Menyimpan gambar sebagai file PNG 
imagepng($gambar, 'langkah_13.png');

// Menghapus gambar dari memori
imagedestroy($gambar);
