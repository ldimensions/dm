@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Grocery Details</div>

                <script src="https://maps.googleapis.com/maps/api/js"></script>
<center>
  <input type="button" value="Show my location on Map" onclick="javascript:showlocation()" />
  <br />
</center>

Latitude: <span id="latitude"></span> 
<br />Longitude: <span id="longitude"></span>
<br />
<br />
<div>Map</div>
<div id="mapdiv" />

<script>
var map = null;

function showlocation() {
  // One-shot position request.
  navigator.geolocation.getCurrentPosition(callback, errorHandler);
}

function callback(position) {

  var lat = position.coords.latitude;
  var lon = position.coords.longitude;

  document.getElementById('latitude').innerHTML = lat;
  document.getElementById('longitude').innerHTML = lon;

  var latLong = new google.maps.LatLng(lat, lon);

  var marker = new google.maps.Marker({
    position: latLong
  });

  marker.setMap(map);
  map.setZoom(8);
  map.setCenter(marker.getPosition());
}

google.maps.event.addDomListener(window, 'load', initMap);

function initMap() {
  var mapOptions = {
    center: new google.maps.LatLng(0, 0),
    zoom: 1,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };
  map = new google.maps.Map(document.getElementById("mapdiv"),
    mapOptions);

}

function errorHandler(error) {
  switch (error.code) {
    case error.PERMISSION_DENIED:
      alert("User denied the request for Geolocation.");
      break;
    case error.POSITION_UNAVAILABLE:
      alert("Location information is unavailable.");
      break;
    case error.TIMEOUT:
      alert("The request to get user location timed out.");
      break;
    case error.UNKNOWN_ERROR:
      alert("An unknown error occurred.");
      break;
  }
}

</script>
            </div>
        </div>
    </div>
</div>
@endsection
