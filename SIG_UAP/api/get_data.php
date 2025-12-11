<?php
header('Content-Type: application/json');

// Get query parameter to determine what data to return
$type = isset($_GET['type']) ? $_GET['type'] : 'pendidikan';

if ($type === 'kecamatan') {
    // Return kecamatan data
    $file = '../data/kecamatan_geo.geojson';
    
    if (file_exists($file)) {
        $data = file_get_contents($file);
        echo $data;
    } else {
        echo json_encode([
            "type" => "FeatureCollection",
            "name" => "Kecamatan_geo",
            "crs" => [
                "type" => "name",
                "properties" => ["name" => "urn:ogc:def:crs:OGC:1.3:CRS84"]
            ],
            "features" => []
        ]);
    }
} else {
    // Return pendidikan data (default)
    $file = '../data/pendidikan_geo.geojson';

    if (!file_exists($file)) {
        $defaultData = [
            "type" => "FeatureCollection",
            "name" => "Pendidikan_geo",
            "crs" => [
                "type" => "name",
                "properties" => ["name" => "urn:ogc:def:crs:OGC:1.3:CRS84"]
            ],
            "features" => [
                [
                    "type" => "Feature",
                    "properties" => [
                        "NAMOBJ" => "SMP Negeri 3 Negeri Besar",
                        "LUAS" => 0.0,
                        "REMARK" => "Pendidikan Menengah Pertama"
                    ],
                    "geometry" => [
                        "type" => "Point",
                        "coordinates" => [105.023155892000034, -4.444321376999937, 0.0]
                    ]
                ],
                [
                    "type" => "Feature",
                    "properties" => [
                        "NAMOBJ" => "Pondok Pesantren Salfiyah",
                        "LUAS" => 0.0,
                        "REMARK" => "Pendidikan Keagamaan"
                    ],
                    "geometry" => [
                        "type" => "Point",
                        "coordinates" => [105.023192743000038, -4.448164555999938, 0.0]
                    ]
                ],
                [
                    "type" => "Feature",
                    "properties" => [
                        "NAMOBJ" => "MTS Satu",
                        "LUAS" => 0.0,
                        "REMARK" => "Pendidikan Menengah Pertama"
                    ],
                    "geometry" => [
                        "type" => "Point",
                        "coordinates" => [105.022327434000033, -4.448426938999944, 0.0]
                    ]
                ],
                [
                    "type" => "Feature",
                    "properties" => [
                        "NAMOBJ" => "MTS",
                        "LUAS" => 0.0,
                        "REMARK" => "Pendidikan Menengah Pertama"
                    ],
                    "geometry" => [
                        "type" => "Point",
                        "coordinates" => [105.022119444000055, -4.448464828999931, 0.0]
                    ]
                ]
            ]
        ];
        
        file_put_contents($file, json_encode($defaultData, JSON_PRETTY_PRINT));
    }

    $data = file_get_contents($file);
    echo $data;
}
?>