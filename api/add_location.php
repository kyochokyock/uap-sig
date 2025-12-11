<?php
header('Content-Type: application/json');

$file = '../data/Pendidikan_geo.geojson';
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['namobj']) || !isset($input['latitude']) || !isset($input['longitude'])) {
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
    exit;
}

$data = json_decode(file_get_contents($file), true);

$newFeature = [
    "type" => "Feature",
    "properties" => [
        "NAMOBJ" => $input['namobj'],
        "LUAS" => 0.0,
        "REMARK" => $input['remark']
    ],
    "geometry" => [
        "type" => "Point",
        "coordinates" => [
            floatval($input['longitude']),
            floatval($input['latitude']),
            0.0
        ]
    ]
];

$data['features'][] = $newFeature;

if (file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT))) {
    echo json_encode(['success' => true, 'message' => 'Lokasi berhasil ditambahkan']);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal menyimpan data']);
}

?>
