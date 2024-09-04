@extends('auth.layouts.main')

@section('content')
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-sidebartype="full" data-sidebar-position="fixed"
        data-header-position="fixed">
        <div class="position-relative overflow-hidden radial-gradient min-vh-100">
            <div class="position-relative z-index-5">
                <div class="row">
                    <div class="col-xl-6 col-xxl-6">
                        <a href="index-2.html" class="text-nowrap logo-img d-block px-4 py-9 w-100">
                            <img src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/logos/dark-logo.svg"
                                width="180" alt="">
                        </a>
                        <div class="d-none d-xl-flex align-items-center justify-content-center"
                            style="height: calc(100vh - 80px);">
                            <img src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/backgrounds/login-security.svg"
                                alt="" class="img-fluid" width="500">
                        </div>
                    </div>
                    <div class="col-xl-6 col-xxl-6">

                        <div
                            class="authentication-login min-vh-100 bg-body row justify-content-center align-items-center p-4">
                            <div class="col-sm-8 col-md-8 col-xl-12">

                                <div class="card-body  wizard-content">
                                    <h4 class="card-title">Atur Lokasi Perusahaan Anda!</h4>
                                    <form action="{{ route('company.location.store') }}" method="post" class="mt-4">
                                        @csrf
                                        @method('patch')
                                        <section>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        {{-- Input untuk lokasi dan tombol pencarian --}}
                                                        <div class="input-group mb-3">
                                                            <input type="text" id="location-search" class="form-control"
                                                                placeholder="Search for a location">
                                                            <button id="search-button" class="btn btn-primary"
                                                                type="button">Search</button>
                                                        </div>

                                                        {{-- Peta --}}
                                                        <div id="map" class="border rounded" style="height: 300px;">
                                                        </div>

                                                        {{-- Input untuk latitude dan longitude --}}
                                                        <div class="mt-3">
                                                            <input type="text" id="latitude" class="form-control mb-2"
                                                                placeholder="Latitude" name="latitude">
                                                            <input type="text" id="longitude" class="form-control"
                                                                placeholder="Longitude" name="longitude">
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-end">
                                                        <button class="btn btn-primary" type="submit">Lanjutkan</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <script>
        var map = L.map('map').setView([-7.8965894, 112.6090665], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: ''
        }).addTo(map);

        var marker = null;

        function updateMarker(lat, lng, address = '') {
            if (!marker) {
                marker = L.marker([lat, lng]).addTo(map);
            } else {
                marker.setLatLng([lat, lng]);
            }

            map.setView([lat, lng], 15);

            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;

            marker.bindPopup(address).openPopup();
        }

        document.getElementById('search-button').addEventListener('click', function() {
            var location = document.getElementById('location-search').value;

            if (location) {
                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${location}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            var lat = data[0].lat;
                            var lng = data[0].lon;
                            var displayName = data[0].display_name;
                            updateMarker(lat, lng, displayName);
                        } else {
                            alert('Location not found.');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        });

        map.on('click', function(e) {
            var lat = e.latlng.lat;
            var lng = e.latlng.lng;

            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                .then(response => response.json())
                .then(data => {
                    var displayName = data.display_name;
                    updateMarker(lat, lng, displayName);
                })
                .catch(error => console.error('Error:', error));
        });
    </script>
@endsection
