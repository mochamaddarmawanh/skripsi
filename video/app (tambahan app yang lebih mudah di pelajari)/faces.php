<?php

include('database.php');

$stmt = $connection->prepare("SELECT * FROM faces");

$stmt->execute();

$result = $stmt->get_result();

$faces = [];

while ($row = $result->fetch_assoc()) {
    $temp = [
        'face_name' => $row['face_name'],
        'descriptor' => json_decode($row['descriptor'], true)
    ];

    $faces[] = $temp;
}

$stmt->close();

$connection->close();

echo json_encode(['faces' => $faces]);
