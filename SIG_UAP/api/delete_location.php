<?php
header('Content-Type: application/json');

$file = '../data/pendidikan.geojson';
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['index'])) {
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
    exit;
}

$data = json_decode(file_get_contents($file), true);
$index = intval($input['index']);

if (!isset($data['features'][$index])) {
    echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan']);
    exit;
}

array_splice($data['features'], $index, 1);

if (file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT))) {
    echo json_encode(['success' => true, 'message' => 'Lokasi berhasil dihapus']);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal menyimpan data']);
}
?>