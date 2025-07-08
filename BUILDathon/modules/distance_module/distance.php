<!DOCTYPE html>
<html>
<head>
  <title>Travel Assistant</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
  <style>
    * { box-sizing: border-box; }
    body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background: #f0f2f5; color: #333; }
    .layout { display: flex; height: 100vh; overflow: hidden; }
    #map { flex: 1.2; min-width: 60%; height: 100%; border-right: 2px solid #e0e0e0; }
    .sidebar { flex: 0.8; background: #ffffff; padding: 20px; overflow-y: auto; }
    .sidebar h2 { text-align: center; margin-bottom: 20px; font-size: 24px; }
    .place { background: #f9f9f9; margin-bottom: 15px; padding: 15px 20px; border-left: 4px solid #007bff; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.05); transition: 0.3s; }
    .place:hover { transform: translateX(5px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    .place h4 { margin: 0 0 5px; font-size: 18px; }
    .place p { margin: 4px 0; font-size: 14px; }
    .btn { display: inline-block; margin-top: 8px; padding: 8px 14px; background-color: #007bff; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; text-decoration: none; }
    .btn:hover { background-color: #0056b3; }
  </style>
</head>
<body>
  <div class="layout">
    <div id="map"></div>
    <div class="sidebar">
      <h2 id="cityHeading">Nearby Tourist Places</h2>
      <div id="places"></div>
    </div>
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
          document.getElementById("cityHeading").innerText = `Tourist Places in ${data.city}`;
          const list = document.getElementById("places");
          data.places.forEach(place => {
            const div = document.createElement("div");
            div.className = "place";
            div.innerHTML = `
              <h4>${place.name}</h4>
              <p><strong>Distance:</strong> ${place.distance.toFixed(2)} km</p>
              <button class='btn' onclick='showRoute(${userLat}, ${userLng}, ${place.lat}, ${place.lng})'>Show on Map</button>
            `;
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