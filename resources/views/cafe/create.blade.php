@extends('admin.dashboard')

@section('judulAdmin')
    Tambah Cafe
@endsection

@section('contentAdmin')
    <form action="/cafe" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control">
        </div>
        @error('nama')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        <div class="form-group">
            <label>Alamat</label>
            <input type="text" name="alamat" class="form-control">
        </div>
        @error('alamat')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        <div class="form-group">
            <label>Gambar</label>
            <input type="file" name="gambar" class="form-control">
        </div>
        @error('gambar')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        <div class="form-group">
            <label>Deskripsi</label>
            <input type="content" name="content" class="form-control">
        </div>
        @error('content')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <div class="form-group">
            <label>Lokasi</label>
            <div id="map"></div>
            <label>Latitude</label>
            <input type="latitude" name="latitude" id="latitude" class="form-control">
            <label>longtitude</label>
            <input type="longitude" name="longitude" id="longitude" class="form-control">
        </div>
        @error('latitude')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        @error('longtitude')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror


        <button type="submit" class="btn btn-primary">Submit</button>
    </form>



    <script>
        const providerOSM = new GeoSearch.OpenStreetMapProvider();

        var leafletMap = L.map('map', {
            fullscreenControl: true,

            fullscreenControl: {
                pseudoFullscreen: false
            },
            minZoom: 2
        }).setView([0, 0], 2);

        L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(leafletMap);

        let theMarker = {};

        leafletMap.on('click', function(e) {
            let latitude = e.latlng.lat.toString().substring(0, 15);
            let longitude = e.latlng.lng.toString().substring(0, 15);
            let popup = L.popup()
                .setLatLng([latitude, longitude])
                .setContent("Kordinat : " + latitude + " - " + longitude)
                .openOn(leafletMap);

            if (theMarker != undefined) {
                leafletMap.removeLayer(theMarker);
            };

            document.querySelector('#longitude').value =longitude;
            document.querySelector('#latitude').value =latitude;
            
            theMarker = L.marker([latitude, longitude]).addTo(leafletMap);
        });

        const search = new GeoSearch.GeoSearchControl({
            provider: providerOSM,
            style: 'bar',
            searchLabel: 'Sinjai',
        });

        leafletMap.addControl(search);
    </script>
@endsection
