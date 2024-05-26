@extends(ucfirst(activeGuard()).'.Layouts.layout')
@section('pagetitle', $Country->title() . ' >> ' . $Region->title()  . ' >> ' . $City->title())
@section('content')
<form method="POST" action="{{ route(activeGuard().'.country.region.cities.update', ['country'=>$Country,'region'=>$Region,'city'=>$City]) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-6">
            <label for="title_ar">@lang('trans.title_ar')</label>
            <input type="text" name="title_ar" id="title_ar" class="form-control" required value="{{ $City['title_ar'] }}">
        </div>
        <div class="col-md-6">
            <label for="title_en">@lang('trans.title_en')</label>
            <input type="text" name="title_en" id="title_en" class="form-control" required value="{{ $City['title_en'] }}">
        </div>

        <input  type="hidden"  value="{{ $Country['id'] }}" name="country_id" id="country_id" class="form-control" required>

        <input  type="hidden"  value="{{ $City['lat'] ?? 26.227934462972144 }}" name="lat" id="lat" class="form-control" required>
        <input  type="hidden"  value="{{ $City['long'] ?? 50.58910840410498 }}" name="long" id="long" class="form-control" required>

        <div class="col-md-6">
            <label for="visibility">@lang('trans.visibility')</label>
            <select class="form-control" required id="visibility" name="status">
                <option {{ $City['status'] == 1 ? 'selected' : '' }} value="1">@lang('trans.visible')</option>
                <option {{ $City['status'] == 0 ? 'selected' : '' }} value="0">@lang('trans.hidden')</option>
            </select>
        </div>
        <div class="col-12 my-3">
            <div class="col-md-12" id="map" style="height: 500px;width: 100%"></div>
        </div>
        <div class="clearfix"></div>
        <div class="col-12 my-4">
            <div class="button-group">
                <button type="submit" class="main-btn btn-hover w-100 text-center">
                    {{ __('trans.Submit') }}
                </button>
            </div>
        </div>
    </div>
</form>
@endsection

@section('js')
    <script>
        var map;
        var markers = [];

        function initMap() {
            var haightAshbury = {lat: parseFloat('{{ $Region['lat'] ?? 26.227934462972144 }}'), lng: parseFloat('{{ $Region['long'] ?? 50.58910840410498 }}')};

            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 15,
                center: haightAshbury,
                mapTypeId: 'terrain'
            });

            $('#lat').val('{{ $Region['lat'] ?? 26.227934462972144 }}');
            $('#long').val('{{ $Region['long'] ?? 50.58910840410498 }}');
            // This event listener will call addMarker() when the map is clicked.
            map.addListener('click', function(event) {
                addMarker(event.latLng);
                var latitude = event.latLng.lat();
                var longitude = event.latLng.lng();
                $('#lat').val(latitude);
                $('#long').val(longitude);
            });

            // Adds a marker at the center of the map.
            addMarker(haightAshbury);
        }

        // Adds a marker to the map and push to the array.
        function addMarker(location) {
            clearMarkers();
            var marker = new google.maps.Marker({
                position: location,
                map: map
            });
            markers.push(marker);
        }

        // Sets the map on all markers in the array.
        function setMapOnAll(map) {
            for (var i = 0; i < markers.length; i++) {
                markers[i].setMap(map);
            }
        }

        // Removes the markers from the map, but keeps them in the array.
        function clearMarkers() {
            setMapOnAll(null);
        }

        // Shows any markers currently in the array.
        function showMarkers() {
            setMapOnAll(map);
        }

        // Deletes all markers in the array by removing references to them.
        function deleteMarkers() {
            clearMarkers();
            markers = [];
        }

        $(document).ready(function () {


            $("#lat").on("input", function(){
                // // Print entered value in a div box
                var lat=$("#lat").val();
                var lang=$("#long").val();

                var haightAshbury =  {lat: parseFloat('{{ $Region['lat'] ?? 26.227934462972144 }}'), lng: parseFloat('{{ $Region['long'] ?? 50.58910840410498 }}')};
                haightAshbury["lat"]=Number(lat);
                haightAshbury["lng"]=Number(lang);

                // Adds a marker at the center of the map.
                addMarker(haightAshbury);


                console.log(haightAshbury);
            });


            $("#long").on("input", function(){
                // // Print entered value in a div box
                var lat=$("#lat").val();
                var lang=$("#long").val();

                var haightAshbury =  {lat: parseFloat('{{ $Region['lat'] ?? 26.227934462972144 }}'), lng: parseFloat('{{ $Region['long'] ?? 50.58910840410498 }}')};
                haightAshbury["lat"]=Number(lat);
                haightAshbury["lng"]=Number(lang);

                // Adds a marker at the center of the map.
                addMarker(haightAshbury);


                console.log(haightAshbury);
            });
        });
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ env('MAP_KEY') }}&callback=initMap"></script>
@endsection