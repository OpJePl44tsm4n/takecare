var map = document.getElementById('map'),
lat = map.dataset.lat,
lng = map.dataset.lng, 
zoom = map.dataset.zoom,
popup = JSON.parse(map.dataset.popup);


L.mapbox.accessToken = 'pk.eyJ1IjoidGh1bmRlcnBsdWdzIiwiYSI6ImNqbjBicTl0azA5Mmsza3Bjdmt3dTFpdnUifQ.Yd8Bg_gH5S1oVss6Rr6vRA';
var baseLocation = [lat, lng];
var map = L.mapbox.map('map')
    .setView(baseLocation, zoom)
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
                    lng,
                    lat
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

featureLayer.eachLayer(function(layer) 
{
    if(popup) { 
        layer.bindPopup(popup);
    }

    layer.on('click', function(e) {
        map.panTo([layer.feature.geometry.coordinates[1], layer.feature.geometry.coordinates[0]]);
    });
});