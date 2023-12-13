<?php

include('database.php');

$face_name = $_POST['face_name'];
$replace_face_name = strtolower(str_replace(' ', '_', $face_name));
$descriptors_decode = json_decode($_POST['descriptors'], true);
$descriptors_encode = json_encode($descriptors_decode);
$sum = count($descriptors_decode);

$stmt = $connection->prepare('INSERT INTO faces (face_name, descriptor, sum) VALUES (?, ?, ?)');
$stmt->bind_param('ssi', $replace_face_name, $descriptors_encode, $sum);
$stmt->execute();

if ($stmt->error) {
    echo $stmt->error;
} else {
    echo 'success';
}

$stmt->close();
$connection->close();