
<script src='https://api.mapbox.com/mapbox.js/v3.1.1/mapbox.js'></script>
<link href='https://api.mapbox.com/mapbox.js/v3.1.1/mapbox.css' rel='stylesheet' />

<section class="map-wrap" style="background:#73b6e6;">
    <div id="map" style="height:450px;"></div>
</section>

  <script>

    L.mapbox.accessToken = 'pk.eyJ1IjoidGh1bmRlcnBsdWdzIiwiYSI6ImNqbjBicTl0azA5Mmsza3Bjdmt3dTFpdnUifQ.Yd8Bg_gH5S1oVss6Rr6vRA';
    var baseLocation = [<?php echo $focus_point['lat']; ?>, <?php echo $focus_point['lng']; ?>];
    var map = L.mapbox.map('map')
        .setView(baseLocation, <?php echo $zoom_level; ?>)
    map.scrollWheelZoom.disable();

    L.mapbox.styleLayer('mapbox://styles/thunderplugs/cjn0criqe4aes2snvkcdtmbf9').addTo(map);
        
    var featureLayer = L.mapbox.featureLayer().addTo(map);

    var geoJson ={
        "type": "FeatureCollection",
        "features": [
            {
                "id": 1,
                "type": "Feature",
                "geometry": {
                    "type": "Point",
                    "coordinates": [
                        "<?php echo $focus_point['lng']; ?>",
                        "<?php echo $focus_point['lat']; ?>"
                    ]
                },
                "properties": {
                    "icon": {
                        "className": "custom-marker-icon",
                        "iconSize": "null"
                    }
                }
            },
            
        ]
    };

    // we are going to load the custom icon defined in the geojson
    featureLayer.on('layeradd', function(e) {
        var marker = e.layer,
        feature = marker.feature;
        marker.setIcon(L.divIcon(feature.properties.icon));
    });

     // Add features to the map.
    featureLayer.setGeoJSON(geoJson);

    featureLayer.eachLayer(function(layer) {
        
        <?php if($popup_content) { // add the content set in the custom fields to the popup ?>
            var content = <?php echo json_encode($popup_content) ?>;
            layer.bindPopup(content);
        <?php } ?>     

        layer.on('click', function(e) {
            map.panTo([layer.feature.geometry.coordinates[1], layer.feature.geometry.coordinates[0]]);
        });
    });

   



  </script>


 