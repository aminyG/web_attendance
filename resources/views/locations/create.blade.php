@extends('layouts.master')

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <h2 class="text-2xl font-semibold mb-4">Pilih Titik Lokasi</h2>

            @if(session('success'))
                <div class="bg-green-200 text-green-700 p-3 mb-4 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('locations.store') }}">
                @csrf

                <div class="mb-3">
                    <label>Nama Lokasi</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div id="map-container" style="border: 2px solid #2196f3; padding: 4px;">
                    <div id="map" style="height: 300px; width: 100%;"></div>
                </div>

                <input type="hidden" name="latitude" id="latitude" required>
                <input type="hidden" name="longitude" id="longitude" required>

                <div class="mt-3">
                    <label>Jarak radius :</label>
                    <select name="radius" id="radius" class="form-control" onchange="updateRadius()" required>
                        @foreach([10, 20, 30, 50, 100] as $r)
                            <option value="{{ $r }}">{{ $r }} Meter</option>
                        @endforeach
                    </select>
                </div>

                <button class="btn btn-primary mb-3">Simpan Lokasi</button>
            </form>
        </div>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        let map, marker, radiusCircle;

        document.addEventListener("DOMContentLoaded", function () {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    map = L.map('map').setView([lat, lng], 18);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap contributors'
                    }).addTo(map);

                    marker = L.marker([lat, lng], { draggable: true }).addTo(map);

                    document.getElementById('latitude').value = lat;
                    document.getElementById('longitude').value = lng;

                    updateRadius();

                    marker.on('dragend', function () {
                        const latLng = marker.getLatLng();
                        document.getElementById('latitude').value = latLng.lat;
                        document.getElementById('longitude').value = latLng.lng;
                        updateRadius();
                    });
                }, function (error) {
                    alert("Gagal mendapatkan lokasi: " + error.message);
                });
            } else {
                alert("Geolocation tidak didukung browser.");
            }
        });

        function updateRadius() {
            if (!marker) return;

            if (radiusCircle) {
                radiusCircle.remove();
            }

            const radiusValue = document.getElementById('radius').value;
            radiusCircle = L.circle(marker.getLatLng(), {
                color: '#2196f3',
                fillColor: '#2196f3',
                fillOpacity: 0.2,
                radius: radiusValue
            }).addTo(map);
        }
    </script>
@endsection