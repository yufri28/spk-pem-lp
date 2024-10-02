<!DOCTYPE html>
<html>

<head>
    <title>Contoh Peta OpenStreetMap</title>
    <style>
    #mapid {
        height: 100vh;
    }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
</head>

<body>
    <div id="mapid"></div>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
    var mymap = L.map('mapid').setView([-10.178443, 123.577572], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(mymap);

    <?php
      // Daftar lokasi dengan latitude dan longitude
      $locations = [
        [
          'name' => 'Gereja Yegar Sahaduta Osmo',
          'lat' => -10.17486138789536, 
          'lng' => 123.55491559536014
        ],
        [
          'name' => 'RS AL',
          'lat' => -10.175706195233257, 
          'lng' => 123.55566661388951
        ],
        [
          'name' => 'Batalion Marinir',
          'lat' => -10.1766777209078,
          'lng' => 123.5536603215325
        ]
      ];

      // Loop melalui daftar lokasi dan tambahkan marker pada peta
      foreach ($locations as $location) {
        echo "var marker = L.marker([" . $location['lat'] . ", " . $location['lng'] . "]).addTo(mymap);";
        echo "marker.bindPopup('<b>" . $location['name'] . "</b>').openPopup();";
      }
    ?>
    </script>
</body>

</html>


<?php 

      $data = [1,2];

      $i = 1;
      foreach ($data as $value) {
          echo "C".$i.": $value";
          $i++;
      }

?>