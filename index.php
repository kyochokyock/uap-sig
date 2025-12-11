<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebGIS Pendidikan Way Kanan</title>
    
    <!-- Tailwind CSS 4 -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.css" />
    
    <style>
        #map { height: calc(100vh - 4rem); }
        .leaflet-popup-content { min-width: 250px; }
        .kecamatan-tooltip {
            background: white;
            border: 2px solid #3b82f6;
            border-radius: 8px;
            padding: 8px 12px;
            font-weight: 600;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-blue-600 text-white shadow-lg">
        <div class="container mx-auto px-4 py-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                    </svg>
                    <div>
                        <h1 class="text-xl font-bold">WebGIS Pendidikan Way Kanan</h1>
                        <p class="text-xs text-blue-100">Sistem Informasi Geografis Pemetaan Pendidikan</p>
                    </div>
              
        </div>
    </header>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-4">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-lg p-4">
                    <h2 class="text-lg font-bold text-gray-800 mb-4">Legenda</h2>
                    
                    <div class="space-y-3">
                        <div class="flex items-center space-x-2">
                            <div class="w-4 h-4 bg-blue-500 rounded-full border-2 border-white shadow"></div>
                            <span class="text-sm text-gray-700">SD (Sekolah Dasar)</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-4 h-4 bg-green-500 rounded-full border-2 border-white shadow"></div>
                            <span class="text-sm text-gray-700">SMP</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-4 h-4 bg-orange-500 rounded-full border-2 border-white shadow"></div>
                            <span class="text-sm text-gray-700">SMA/SMK</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-4 h-4 bg-purple-500 rounded-full border-2 border-white shadow"></div>
                            <span class="text-sm text-gray-700">Pendidikan Keagamaan</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-4 h-4 bg-red-500 rounded-full border-2 border-white shadow"></div>
                            <span class="text-sm text-gray-700">Lainnya</span>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h3 class="text-sm font-semibold text-gray-800 mb-2">Statistik</h3>
                    <div id="stats" class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Lokasi:</span>
                            <span id="totalCount" class="font-semibold">0</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Kecamatan:</span>
                            <span id="kecamatanCount" class="font-semibold">0</span>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h3 class="text-sm font-semibold text-gray-800 mb-2">Info Kecamatan</h3>
                    <div id="kecamatanInfo" class="text-sm text-gray-500 italic">
                        Klik kecamatan untuk melihat detail
                    </div>
                </div>
            </div>

            <!-- Map -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah/Edit -->
    <div id="modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
               
                <form id="locationForm" class="space-y-4">
                    <input type="hidden" id="editId" name="id">
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Sekolah/Institusi</label>
                        <input type="text" id="namobj" name="namobj" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Pendidikan</label>
                        <select id="remark" name="remark" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Pilih Jenis Pendidikan</option>
                            <option value="Sekolah Dasar">Sekolah Dasar (SD)</option>
                            <option value="Pendidikan Menengah Pertama">Pendidikan Menengah Pertama (SMP)</option>
                            <option value="Pendidikan Menengah Atas">Pendidikan Menengah Atas (SMA/SMK)</option>
                            <option value="Pendidikan Keagamaan">Pendidikan Keagamaan</option>
                            <option value="Pendidikan Tinggi">Pendidikan Tinggi</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Latitude</label>
                            <input type="number" id="latitude" name="latitude" step="0.000001" required
                                placeholder="Contoh: -4.5"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Longitude</label>
                            <input type="number" id="longitude" name="longitude" step="0.000001" required
                                placeholder="Contoh: 104.7"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>

                    <div class="text-sm text-gray-600 bg-blue-50 p-3 rounded">
                        <p class="font-semibold mb-1">💡 Tips:</p>
                        <p>Klik pada peta untuk mengisi koordinat otomatis</p>
                    </div>

                    <div class="flex space-x-3 pt-4">
                        <button type="submit" id="submitBtn" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
                            Simpan
                        </button>
                        <button type="button" onclick="closeModal()" class="flex-1 bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-semibold hover:bg-gray-300 transition">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js"></script>
    
    <script>
        let map, kecamatanLayer, pendidikanLayer;
        let markers = [];
        let editMode = false;
        let pendidikanData = null;
        let kecamatanData = null;

        // Initialize Map
        function initMap() {
            map = L.map('map').setView([-4.5, 104.7], 11);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            loadKecamatan();
            loadPendidikan();

            map.on('click', function(e) {
                const isModalOpen = !document.getElementById('modal').classList.contains('hidden');
                
                if (isModalOpen) {
                    console.log('Map clicked, coordinates:', e.latlng); // Debug log
                    document.getElementById('latitude').value = e.latlng.lat.toFixed(6);
                    document.getElementById('longitude').value = e.latlng.lng.toFixed(6);
                    
                    // Visual feedback
                    const latInput = document.getElementById('latitude');
                    const lngInput = document.getElementById('longitude');
                    latInput.style.backgroundColor = '#dcfce7';
                    lngInput.style.backgroundColor = '#dcfce7';
                    
                    setTimeout(() => {
                        latInput.style.backgroundColor = '';
                        lngInput.style.backgroundColor = '';
                    }, 1000);
                }
            });
        }

        // Count facilities in kecamatan
        function countFacilitiesInKecamatan(kecamatanName) {
            if (!pendidikanData) return 0;
            
            let count = 0;
            const kecamatan = kecamatanData.features.find(f => f.properties.NAMOBJ === kecamatanName);
            if (!kecamatan) return 0;

            pendidikanData.features.forEach(feature => {
                const coords = feature.geometry.coordinates;
                const point = L.latLng(coords[1], coords[0]);
                const polygon = L.geoJSON(kecamatan);
                
                if (polygon.getBounds().contains(point)) {
                    count++;
                }
            });

            return count;
        }

        // Get facilities breakdown by type in kecamatan
        function getFacilitiesBreakdown(kecamatanName) {
            if (!pendidikanData) return {};
            
            const breakdown = {};
            const kecamatan = kecamatanData.features.find(f => f.properties.NAMOBJ === kecamatanName);
            if (!kecamatan) return {};

            pendidikanData.features.forEach(feature => {
                const coords = feature.geometry.coordinates;
                const point = L.latLng(coords[1], coords[0]);
                const polygon = L.geoJSON(kecamatan);
                
                if (polygon.getBounds().contains(point)) {
                    const type = feature.properties.REMARK;
                    breakdown[type] = (breakdown[type] || 0) + 1;
                }
            });

            return breakdown;
        }

        // Update kecamatan info sidebar
        function updateKecamatanInfo(kecamatanName, area, facilityCount, breakdown) {
            const infoDiv = document.getElementById('kecamatanInfo');
            
            let breakdownHtml = '';
            for (const [type, count] of Object.entries(breakdown)) {
                breakdownHtml += `
                    <div class="flex justify-between text-xs py-1">
                        <span class="text-gray-600">${type}:</span>
                        <span class="font-semibold">${count}</span>
                    </div>
                `;
            }

            infoDiv.innerHTML = `
                <div class="space-y-2">
                    <h4 class="font-bold text-base text-blue-600">${kecamatanName}</h4>
                    <div class="space-y-1">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Luas:</span>
                            <span class="font-semibold">${area} km²</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Fasilitas:</span>
                            <span class="font-semibold text-blue-600">${facilityCount}</span>
                        </div>
                    </div>
                    ${breakdownHtml ? `
                        <hr class="my-2">
                        <div class="text-xs font-semibold text-gray-700 mb-1">Rincian:</div>
                        ${breakdownHtml}
                    ` : ''}
                </div>
            `;
        }

        // Load Kecamatan GeoJSON
        function loadKecamatan() {
            fetch('api/get_data.php?type=kecamatan')
                .then(res => res.json())
                .then(data => {
                    kecamatanData = data;
                    
                    kecamatanLayer = L.geoJSON(data, {
                        style: function(feature) {
                            return {
                                fillColor: '#3b82f6',
                                weight: 2,
                                opacity: 1,
                                color: '#1e40af',
                                fillOpacity: 0.2
                            };
                        },
                        onEachFeature: function(feature, layer) {
                            const props = feature.properties;
                            const area = (props.Shape_Area * 111000 * 111000).toFixed(2);
                            const facilityCount = countFacilitiesInKecamatan(props.NAMOBJ);
                            const breakdown = getFacilitiesBreakdown(props.NAMOBJ);
                            
                            // Tooltip on hover
                            layer.bindTooltip(`
                                <div class="text-center">
                                    <div class="font-bold">${props.NAMOBJ}</div>
                                    <div class="text-xs">${facilityCount} Fasilitas</div>
                                </div>
                            `, {
                                permanent: false,
                                direction: 'center',
                                className: 'kecamatan-tooltip'
                            });

                            // Popup on click
                            let breakdownHtml = '';
                            for (const [type, count] of Object.entries(breakdown)) {
                                const color = getMarkerColor(type);
                                breakdownHtml += `
                                    <div class="flex items-center justify-between py-1">
                                        <div class="flex items-center space-x-2">
                                            <div class="w-3 h-3 rounded-full" style="background-color: ${color}"></div>
                                            <span class="text-xs">${type}</span>
                                        </div>
                                        <span class="font-semibold text-xs">${count}</span>
                                    </div>
                                `;
                            }
                            
                            layer.bindPopup(`
                                <div class="font-sans">
                                    <h3 class="font-bold text-lg mb-3 text-blue-600">${props.NAMOBJ}</h3>
                                    <div class="space-y-2 mb-3">
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600">Luas Area:</span>
                                            <span class="font-semibold">${area} km²</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600">Total Fasilitas:</span>
                                            <span class="font-semibold text-blue-600">${facilityCount}</span>
                                        </div>
                                    </div>
                                    ${breakdownHtml ? `
                                        <hr class="my-2">
                                        <div class="text-xs font-semibold text-gray-700 mb-2">Rincian Fasilitas:</div>
                                        ${breakdownHtml}
                                    ` : '<p class="text-xs text-gray-500 italic">Belum ada fasilitas pendidikan</p>'}
                                </div>
                            `, {
                                maxWidth: 300
                            });

                            layer.on('mouseover', function(e) {
                                this.setStyle({ 
                                    fillOpacity: 0.5,
                                    weight: 3
                                });
                            });
                            
                            layer.on('mouseout', function(e) {
                                this.setStyle({ 
                                    fillOpacity: 0.2,
                                    weight: 2
                                });
                            });

                            layer.on('click', function(e) {
                                updateKecamatanInfo(props.NAMOBJ, area, facilityCount, breakdown);
                            });
                        }
                    }).addTo(map);

                    document.getElementById('kecamatanCount').textContent = data.features.length;
                })
                .catch(error => {
                    console.error('Error loading kecamatan data:', error);
                });
        }

        // Get marker color based on education type
        function getMarkerColor(remark) {
            const remarkLower = remark.toLowerCase();
            if (remarkLower.includes('dasar') || remarkLower.includes('sd')) return '#3b82f6';
            if (remarkLower.includes('menengah pertama') || remarkLower.includes('smp')) return '#10b981';
            if (remarkLower.includes('menengah atas') || remarkLower.includes('sma') || remarkLower.includes('smk')) return '#f97316';
            if (remarkLower.includes('keagamaan')) return '#a855f7';
            return '#ef4444';
        }

        // Load Pendidikan Data
        function loadPendidikan() {
            console.log('Loading pendidikan data...'); // Debug log
            
            fetch('api/get_data.php')
                .then(res => {
                    console.log('Fetch response status:', res.status); // Debug log
                    
                    if (!res.ok) {
                        throw new Error(`HTTP error! status: ${res.status}`);
                    }
                    return res.json();
                })
                .then(data => {
                    console.log('Pendidikan data loaded:', data); // Debug log
                    
                    pendidikanData = data;
                    markers.forEach(m => map.removeLayer(m));
                    markers = [];

                    if (!data.features || data.features.length === 0) {
                        console.log('No education facilities data found');
                        document.getElementById('totalCount').textContent = '0';
                        return;
                    }

                    console.log('Creating markers for', data.features.length, 'features'); // Debug log

                    data.features.forEach((feature, index) => {
                        const props = feature.properties;
                        const coords = feature.geometry.coordinates;
                        const color = getMarkerColor(props.REMARK);

                        const marker = L.circleMarker([coords[1], coords[0]], {
                            radius: 8,
                            fillColor: color,
                            color: '#fff',
                            weight: 2,
                            opacity: 1,
                            fillOpacity: 0.8
                        }).addTo(map);

                        marker.bindPopup(`
                            <div class="font-sans">
                                <h3 class="font-bold text-base mb-2">${props.NAMOBJ}</h3>
                                <p class="text-sm text-gray-600 mb-3">${props.REMARK}</p>
                                <div class="flex space-x-2">
                                    <button onclick="editLocation(${index})" class="flex-1 bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">
                                        Edit
                                    </button>
                                    <button onclick="deleteLocation(${index})" class="flex-1 bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        `);

                        marker.feature = feature;
                        markers.push(marker);
                    });

                    document.getElementById('totalCount').textContent = data.features.length;
                    console.log('Markers created:', markers.length); // Debug log
                    
                    // Refresh kecamatan layer to update counts
                    if (kecamatanLayer) {
                        map.removeLayer(kecamatanLayer);
                        loadKecamatan();
                    }
                })
                .catch(error => {
                    console.error('Error loading pendidikan data:', error);
                    document.getElementById('totalCount').textContent = '0';
                    alert('⚠️ Gagal memuat data pendidikan!\n\nSilakan refresh halaman atau cek console (F12).');
                });
        }

        // Modal Functions
        function openAddModal() {
            console.log('Opening add modal'); // Debug log
            
            editMode = false;
            document.getElementById('modalTitle').textContent = 'Tambah Lokasi Pendidikan';
            document.getElementById('locationForm').reset();
            document.getElementById('editId').value = '';
            
            // Set default coordinates (center of map)
            const center = map.getCenter();
            document.getElementById('latitude').value = center.lat.toFixed(6);
            document.getElementById('longitude').value = center.lng.toFixed(6);
            
            document.getElementById('modal').classList.remove('hidden');
            
            // Focus on first field
            setTimeout(() => {
                document.getElementById('namobj').focus();
            }, 100);
        }

        function closeModal() {
            console.log('Closing modal'); // Debug log
            document.getElementById('modal').classList.add('hidden');
            document.getElementById('locationForm').reset();
        }

        function editLocation(index) {
            console.log('Editing location index:', index); // Debug log
            
            editMode = true;
            const feature = markers[index].feature;
            const props = feature.properties;
            const coords = feature.geometry.coordinates;

            console.log('Edit data:', { props, coords }); // Debug log

            document.getElementById('modalTitle').textContent = 'Edit Lokasi Pendidikan';
            document.getElementById('editId').value = index;
            document.getElementById('namobj').value = props.NAMOBJ;
            document.getElementById('remark').value = props.REMARK;
            document.getElementById('latitude').value = coords[1];
            document.getElementById('longitude').value = coords[0];
            document.getElementById('modal').classList.remove('hidden');
        }

        function deleteLocation(index) {
            const feature = markers[index].feature;
            const name = feature.properties.NAMOBJ;
            
            if (!confirm(`Yakin ingin menghapus lokasi:\n"${name}"?`)) return;

            console.log('Deleting index:', index); // Debug log

            fetch('api/delete_location.php', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ index: index })
            })
            .then(response => {
                console.log('Delete response status:', response.status); // Debug log
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Delete response data:', data); // Debug log
                
                if (data.success) {
                    alert('✅ ' + (data.message || 'Lokasi berhasil dihapus'));
                    
                    // Reload data
                    setTimeout(() => {
                        loadPendidikan();
                    }, 300);
                } else {
                    alert('❌ Error: ' + (data.message || 'Gagal menghapus lokasi'));
                    console.error('Server error:', data);
                }
            })
            .catch(error => {
                console.error('Delete error:', error); // Debug log
                alert('❌ Terjadi kesalahan saat menghapus!\n\n' + error.message + '\n\nCek console untuk detail.');
            });
        }

        // Form Submit
        document.getElementById('locationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            console.log('Form submitted'); // Debug log

            // Get form values
            const namobj = document.getElementById('namobj').value.trim();
            const remark = document.getElementById('remark').value;
            const latitude = parseFloat(document.getElementById('latitude').value);
            const longitude = parseFloat(document.getElementById('longitude').value);

            console.log('Form data:', { namobj, remark, latitude, longitude }); // Debug log

            // Validate
            if (!namobj) {
                alert('❌ Nama sekolah/institusi harus diisi!');
                document.getElementById('namobj').focus();
                return false;
            }

            if (!remark) {
                alert('❌ Jenis pendidikan harus dipilih!');
                document.getElementById('remark').focus();
                return false;
            }

            if (isNaN(latitude) || isNaN(longitude)) {
                alert('❌ Koordinat harus berupa angka!\n\nKlik pada peta untuk mendapatkan koordinat otomatis.');
                return false;
            }

            if (latitude === 0 && longitude === 0) {
                alert('❌ Koordinat tidak valid!\n\nSilakan klik pada peta untuk mendapatkan koordinat.');
                return false;
            }

            // Prepare data
            const formData = {
                namobj: namobj,
                remark: remark,
                latitude: latitude,
                longitude: longitude
            };

            if (editMode) {
                formData.index = parseInt(document.getElementById('editId').value);
            }

            const url = editMode ? 'api/update_location.php' : 'api/add_location.php';
            
            console.log('Sending to:', url); // Debug log
            console.log('Data:', formData); // Debug log

            // Disable button
            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = editMode ? '🔄 Updating...' : '🔄 Saving...';

            // Send request
            fetch(url, {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            })
            .then(response => {
                console.log('Response status:', response.status); // Debug log
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data); // Debug log
                
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;

                if (data.success) {
                    alert('✅ ' + (data.message || (editMode ? 'Data berhasil diupdate!' : 'Data berhasil ditambahkan!')));
                    closeModal();
                    
                    // Reload data
                    setTimeout(() => {
                        loadPendidikan();
                    }, 300);
                } else {
                    alert('❌ Error: ' + (data.message || 'Gagal menyimpan data'));
                    console.error('Server error:', data);
                }
            })
            .catch(error => {
                console.error('Fetch error:', error); // Debug log
                
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
                
                alert('❌ Terjadi kesalahan!\n\n' + error.message + '\n\nSilakan cek console browser (F12) untuk detail.');
            });

            return false;
        });

        // Initialize on load
        window.addEventListener('load', initMap);
    </script>
</body>
</html>