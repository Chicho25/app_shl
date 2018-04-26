<?php
include("include/config.php");
include("include/defs.php");

     $ruta = GetRecords("SELECT
                            Latitude, Longitude
                            FROM
                            localization
                            WHERE
                            Id_user = 15
                            AND
                            Id_service = 16
                            AND
                            Id_phase = 1
                            And
                            Stat = 2
                            ORDER BY Id ASC");

    $inicioRuta = GetRecords("SELECT
                          Latitude, Longitude
                          FROM
                          localization
                          WHERE
                          Id_user = 15
                          AND
                          Id_service = 16
                          AND
                          Id_phase = 1
                          And
                          Stat = 2
                          ORDER BY Id ASC
                          LIMIT 1");

      $finRuta = GetRecords("SELECT
                            Latitude, Longitude
                            FROM
                            localization
                            WHERE
                            Id_user = 15
                            AND
                            Id_service = 16
                            AND
                            Id_phase = 1
                            And
                            Stat = 2
                            ORDER BY Id DESC
                            LIMIT 1");

      $rutaContar = GetRecords("SELECT
                                 count(*) as contar
                                 FROM
                                 localization
                                 WHERE
                                 Id_user = 15
                                 AND
                                 Id_service = 16
                                 AND
                                 Id_phase = 1
                                 And
                                 Stat = 2");

        $n = $rutaContar[0]['contar']/2;
        $n1 = $n/2;
        $p2 = $n - $n1;
        $p3 = $n + $n1;
        $p2 = floor($p2);
        $p3 = floor($p3);
        $numeracion = 0;
        foreach ($ruta as $key => $value) {
          $numeracion++;
          if($numeracion == $p2){
             $p2Lat = $value['Latitude'];
             $p2Lon = $value['Longitude'];
          }elseif($numeracion == $p3){
              $p3Lat = $value['Latitude'];
              $p3Lon = $value['Longitude'];
          }
          continue;
        }
     ?>
     <!DOCTYPE html>
     <html>
     <head>
     <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
     <meta charset="utf-8">
     <title>Ejemplo de rutas Draggable</title>
     <style>
     html, body, #map-canvas {
       height: 100%;
       margin: 0px;
       padding: 0px
     }
     </style>
     <script src="https://maps.google.com/maps/api/js?key=AIzaSyAb8dDB7xI-1NC_G4190uPvHe-0rzCrEhc"></script>
     <script>

     var rendererOptions = {
     draggable: true
     };
     var directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);;
     var directionsService = new google.maps.DirectionsService();
     var map;

     var panama = new google.maps.LatLng(8.9818829,-79.5258338);
     var panamaOrigen = new google.maps.LatLng(<?php echo $inicioRuta[0]['Latitude'];?>, <?php echo $inicioRuta[0]['Longitude'];?>);
     var panamaDestino = new google.maps.LatLng(<?php echo $finRuta[0]['Latitude'];?>, <?php echo $finRuta[0]['Longitude'];?>);
     var panamawp1 = new google.maps.LatLng(<?php echo $p2Lat; ?>, <?php echo $p2Lon; ?>);
     var panamawp2 = new google.maps.LatLng(<?php echo $p3Lat; ?>, <?php echo $p3Lon; ?>);

     function initialize() {

       var mapOptions = {
     zoom: 7,
           center: panama
       };
       map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
       directionsDisplay.setMap(map);
       directionsDisplay.setPanel(document.getElementById('directionsPanel'));

       google.maps.event.addListener(directionsDisplay, 'directions_changed', function() {
           computeTotalDistance(directionsDisplay.getDirections());
           });

       calcRoute();
     }

     function calcRoute() {

       var request = {
             origin: panamaOrigen,
             destination: panamaDestino,
             waypoints:[{location: panamawp1}, {location: panamawp2}],
             travelMode: google.maps.TravelMode.DRIVING
       };
       directionsService.route(request, function(response, status) {
           if (status == google.maps.DirectionsStatus.OK) {
           directionsDisplay.setDirections(response);
           }
           });
     }

     function computeTotalDistance(result) {
       var total = 0;
       var myroute = result.routes[0];
       for (var i = 0; i < myroute.legs.length; i++) {
         total += myroute.legs[i].distance.value;
       }
       total = total / 1000.0;
       document.getElementById('total').innerHTML = total + ' km';
     }

     google.maps.event.addDomListener(window, 'load', initialize);

     </script>
     </head>
     <body>
     <div id="map-canvas" style="float:left;width:70%; height:100%"></div>
     <div id="directionsPanel" style="float:right;width:30%;height 100%">
       <p>Total Distance: <span id="total"></span></p>
     </div>
     </body>
     </html>
