<!DOCTYPE html>
<html>

<head>
  <title>Map Test</title>
  <style>
    #map {
      height: 500px;
      width: 100%;
    }
  </style>
</head>

<body>

  <h2>Google Map Test</h2>
  <div id="map"></div>

  <script>
    function initMap() {
      new google.maps.Map(document.getElementById("map"), {
        center: {
          lat: 14.5995,
          lng: 120.9842
        },
        zoom: 14
      });
    }
  </script>

  <script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDX6Zw_FMcQ02Msub4xP5omFdRf3o7336E&callback=initMap"
    async defer>
  </script>

</body>

</html>