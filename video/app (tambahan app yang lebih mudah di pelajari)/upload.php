<?php

$success = true;

$face_name = $_POST['file_name'];
$folder_name = strtolower(str_replace(' ', '_', $face_name));
$upload_dir = 'assets/images/faces/' . $folder_name . '/';
$allowed_extensions = array('png', 'jpg', 'jpeg');

if (file_exists($upload_dir)) {
    echo 'Nama wajah sudah dipakai.';
    return;
}

mkdir($upload_dir, 0777, true);

foreach ($_FILES as $file) {
    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);

    if (!in_array(strtolower($file_extension), $allowed_extensions)) {
        echo 'File yang diunggah harus berupa file gambar PNG, JPG, atau JPEG.';
        $success = false;
        break;
    }

    if (!move_uploaded_file($file_tmp, $upload_dir . $file_name)) {
        echo 'Gagal mengunggah mohon ulangi kembali.';
        $success = false;
    }
}

if ($success) {
    echo 'success';
} else {
    if (file_exists($upload_dir)) {
        $files = glob($upload_dir . '*');
        foreach ($files as $file) {
            unlink($file);
        }
        rmdir($upload_dir);
    }
}
