<?php
header('Content-Type: application/json');

$file = '../data/pendidikan.geojson';
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['index']) || !isset($input['namobj'])) {
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
    exit;
}

$data = json_decode(file_get_contents($file), true);
$index = intval($input['index']);

if (!isset($data['features'][$index])) {
    echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan']);
    exit;
}

$data['features'][$index]['properties']['NAMOBJ'] = $input['namobj'];
$data['features'][$index]['properties']['REMARK'] = $input['remark'];
$data['features'][$index]['geometry']['coordinates'] = [
    floatval($input['longitude']),
    floatval($input['latitude']),
    0.0
];

if (file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT))) {
    echo json_encode(['success' => true, 'message' => 'Lokasi berhasil diupdate']);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal menyimpan data']);
}
?>