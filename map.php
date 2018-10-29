<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Map - San Juan City</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport"> -->
        <link rel="shortcut icon" href="assets/images/favicon.ico" />
        
        <!-- Leaflet CSS -->
        <link rel="stylesheet" href="assets/leaflet/leaflet.css">
        <link rel="stylesheet" href="assets/leaflet/leaflet.label.css">
        <link rel="stylesheet" href="assets/leaflet/leaflet.draw.css">
        <link rel="stylesheet" href="assets/leaflet/MarkerCluster.Default.css">

        <!-- Leaflet JS -->
        <script src="assets/leaflet/leaflet.js"></script>
        <script src="assets/leaflet/leaflet.label.js"></script>
        <script src="assets/leaflet/leaflet.draw.js"></script>
        <script src="assets/leaflet/leaflet.markercluster.js"></script>

        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    </head>

    <body>
        <style>
            /* Set the size of the div element that contains the map */
            #map_id {
                height: 500px; 
                width: 100%;  /* The width is the width of the web page */
            }
            .legend {
                background: white;
                line-height: 1.5em;
            }
        </style>

        <?php echo 'Hellow' ?>

        <div id="map_id"></div>

        <script type="text/javascript" src="assets/geojson/paranaque.json"></script>
        <!-- <script type="text/javascript" src="assets/geojson/san_juan.json"></script> -->

        <script type="text/javascript">    
            var barangaysLayer;

            function highlightFeature(e){
                var layer = e.target;
                layer.setStyle(
                    {
                        weight: 5,
                        color: 'black',
                        fillColor: 'white',
                        fillOpacity: 0.2
                    }
                );
                if(!L.Browser.ie && L.Browser.opera){
                    layer.bringToFront();
                }
            }

            // Reset Highlight
            function resetHighlight(e){
                barangaysLayer.resetStyle(e.target);
            }

            // Zoom in
            function zoomToFeature(e){
                map.fitBounds(e.target.getBounds());
            }

            // Function to attach events on each feature
            var markers = new Array();
            function barangaysOnEachFeature(feature, layer){
                layer.on(
                    {
                        mouseover: highlightFeature,
                        mouseout: resetHighlight,
                        click: zoomToFeature
                    }
                );
            }

            // Conditional Function for Style - based on tax collected per barangay
            function getBarangayColor(taxEst){
                if (taxEst > 1000000) {
                    return 'red';
                } else if (taxEst > 500000) {
                    return 'blue';
                } else {
                    return 'green';
                }
            }

            // Barangay Style
            function barangaysStyle(feature){
                return{
                    // fillColor: getBarangayColor(feature.properties.TAX),
                    weight: 2,
                    opacity: 1,
                    // color: 'white',
                    dashArray: 3,
                    fillOpacity: 0.7,
                }
            }

            var mapboxAccessToken = 'pk.eyJ1Ijoiam1jcyIsImEiOiJjaml5OTk5ejcwODR0M3NucGJzMzlrbmp3In0.NcgqHpxKvG7Dh6elKTujKA';

            var map = L.map('map_id').setView([14.500679, 120.99163], 12);
            
            // Satellite Layer
            var satelliteLayer = L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=' + mapboxAccessToken, {
                id: 'mapbox.satellite',
                maxZoom: 25,
                accessToken: 'your.mapbox.access.token'
            })
            map.addLayer(satelliteLayer);

            // Street Layer
            var streetLayer = L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=' + mapboxAccessToken, {
                id: 'mapbox.streets',
                maxZoom: 25,
                accessToken: 'your.mapbox.access.token'
            })

            // Add Barangay Geojson Layer
            var barangaysLayer = L.geoJson(
                barangay_data,
                {
                    style: barangaysStyle,
                    onEachFeature: barangaysOnEachFeature
                }
            ).bindPopup(function (layer) {
                return layer.feature.properties.NAME_3;
            }).addTo(map);

            // // Fit Bounds
            // map.fitBounds(barangaysLayer.getBounds());

            // Base Maps
            var baseMaps = {
                'Satellite' : satelliteLayer,
                'Roadmap' : streetLayer,
            };

            var overlayMaps = {
                'Barangays' : barangaysLayer
            };

            // Layers Control
            L.control.layers(baseMaps, overlayMaps).addTo(map);

        </script>


    </body>
</html>