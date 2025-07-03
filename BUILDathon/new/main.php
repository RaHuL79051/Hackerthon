<!DOCTYPE html>
<html>
<head>
  <title>Travel Assistant</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
  <style>
    body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f4f4f4; }
    #map { height: 50vh; }
    .container { padding: 20px; max-width: 800px; margin: auto; }
    .place { background: #fff; margin-bottom: 10px; padding: 15px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    .place h4 { margin: 0 0 5px; }
    .btn { padding: 6px 12px; background-color: #007bff; color: #fff; border: none; border-radius: 4px; cursor: pointer; }
    .btn:hover { background-color: #0056b3; }
  </style>
</head>
<body>
  <div id="map"></div>
  <div class="container">
    <h2>Nearby Tourist Places</h2>
    <div id="places"></div>
  </div>

  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
  <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.min.js"></script>
  <script>
    let map = L.map('map').setView([20.5937, 78.9629], 5);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19
    }).addTo(map);

    navigator.geolocation.getCurrentPosition(pos => {
      const userLat = pos.coords.latitude;
      const userLng = pos.coords.longitude;

      const userMarker = L.marker([userLat, userLng]).addTo(map)
        .bindPopup("You are here!").openPopup();
      map.setView([userLat, userLng], 13);

      fetch(`get_places.php?lat=${userLat}&lng=${userLng}`)
        .then(res => res.json())
        .then(data => {
          const list = document.getElementById("places");
          data.forEach(place => {
            const div = document.createElement("div");
            div.className = "place";
            div.innerHTML = `<h4>${place.name}</h4><p><strong>City:</strong> ${place.city}</p><p><strong>Distance:</strong> ${place.distance.toFixed(2)} km</p><button class='btn' onclick='showRoute(${userLat}, ${userLng}, ${place.lat}, ${place.lng})'>Show on Map</button>`;
            list.appendChild(div);
          });
        });
    });

    function showRoute(lat1, lng1, lat2, lng2) {
      L.Routing.control({
        waypoints: [L.latLng(lat1, lng1), L.latLng(lat2, lng2)],
        routeWhileDragging: false,
        show: false
      }).addTo(map);
    }
  </script>
</body>
</html>