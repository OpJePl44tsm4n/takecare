
<?php 
    $title = get_sub_field('title');
    $search_label = get_sub_field('search_label');
    $search_placeholder = get_sub_field('search_placeholder');
    $button_title = get_sub_field('button_title'); 
    $focus_point = get_sub_field('focus_point');
    $zoom_level = get_sub_field('zoom_level') ?: 13;

    $store_list_title = get_sub_field('store_list_title');
    $store_list_content = get_sub_field('store_list_content');
?>

<script src='https://api.mapbox.com/mapbox.js/v3.1.1/mapbox.js'></script>
<link href='https://api.mapbox.com/mapbox.js/v3.1.1/mapbox.css' rel='stylesheet' />
<script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v1.0.0/leaflet.markercluster.js'></script>

<section class="map-wrap" style="background:#73b6e6;">

    <div class="map-filter card">
        
        <button class="ml-auto filter-toggle" type="button" data-toggle="collapse" data-target="#filterCollapse" aria-controls="filterCollapse" aria-expanded="false" aria-label="Close filter"><span aria-hidden="false"><i class="fa fa-map-marker"></i></span></button>
       

        <div class="collapse show" id="filterCollapse">
            <button class="ml-auto close" type="button" data-toggle="collapse" data-target="#filterCollapse" aria-controls="filterCollapse" aria-expanded="false" aria-label="Close filter"><span aria-hidden="false">&times;</span></button>
            <h2><i class="fa fa-map-marker"></i> <?php echo $title; ?></h2>
            <input id="search" class="search-ui" placeholder="<?php echo $search_placeholder; ?>" />
            <button id="user-location"><i class="fa fa-compass"></i> Show my location</button>
            <hr>
            <button id="reset-map" class="btn btn-primary"><?php echo $button_title; ?></button>
        </div>
    </div>
   

    <div id="map" style="height:450px;"></div>
</section>

  <script>

    L.mapbox.accessToken = 'pk.eyJ1IjoidGh1bmRlcnBsdWdzIiwiYSI6ImNqbjBicTl0azA5Mmsza3Bjdmt3dTFpdnUifQ.Yd8Bg_gH5S1oVss6Rr6vRA';
    var baseLocation = [<?php echo $focus_point['lat']; ?>, <?php echo $focus_point['lng']; ?>];

    var map = L.mapbox.map('map')
        .setView(baseLocation, <?php echo $zoom_level; ?>)
        // .addLayer(L.mapbox.tileLayer('mapbox.streets'))
    map.scrollWheelZoom.disable();
       
    L.mapbox.styleLayer('mapbox://styles/thunderplugs/cjn0criqe4aes2snvkcdtmbf9').addTo(map);
        
    var overlays = L.layerGroup().addTo(map);
    var layers;
 

    var itemLayer = L.mapbox.featureLayer()
    .loadURL('<?php echo wp_upload_dir()['baseurl']; ?>/brandclick/stores.geojson')
    .on('ready', function(e) {
        layers = e.target;
        loadMarkers();
    });

    // we are going to load the custom icon defined in the geojson
    itemLayer.on('layeradd', function(e) {
      var marker = e.layer,
        feature = marker.feature;
      marker.setIcon(L.divIcon(feature.properties.icon));
    });

    // filters 
    var filterValue = '',
    storeSearch = document.getElementById('search'),
    mapReset = document.getElementById('reset-map'),
    userLocation = document.getElementById('user-location');
    currentCities = [];

    storeSearch.addEventListener('keyup', function(e) {
        filterValue = this.value;
        loadMarkers();
    });

    mapReset.addEventListener('click', function(e) {
        filterValue = '';
        storeSearch.value = '';
        map.setView(baseLocation, 9);
        loadMarkers();   
    });

    if (navigator.geolocation) {
        userLocation.style.display = "block";

        userLocation.addEventListener('click', function(e) {
            this.className = 'loading-location';
            navigator.geolocation.getCurrentPosition(setUserLocation);
        });    
    } 

    function setUserLocation(position) {
        baseLocation = [position.coords.latitude, position.coords.longitude];
        userLocation.className = '';
        filterValue = '';
        loadMarkers();   
        map.setView(baseLocation, 12);
    }

    function loadMarkers() {
        currentCities = []; 
        overlays.clearLayers();
        // create a new marker group
        var clusterGroup = new L.MarkerClusterGroup( {
            iconCreateFunction: function(cluster) {
                return L.mapbox.marker.icon({
                  // show the number of markers in the cluster on the icon.
                  'marker-symbol': cluster.getChildCount(),
                  'marker-color': "#f9ed1a",
                  "marker-size": "medium",
                });
            }
        }).addTo(overlays);
        // and add any markers that fit the filtered criteria to that group.
        layers.eachLayer(function(layer) {

            if ( layer.feature.properties.type == 'city' ) {
                if ( filterValue.toLowerCase() === layer.feature.properties.slug) {
                    map.setView([layer.feature.geometry.coordinates[1], layer.feature.geometry.coordinates[0]], 9);
                }    
                return;
            }

            // if filterValue contains numbers, search for postcode
            if(/\d/.test(filterValue)){ 
                filterValue = filterValue.toLowerCase().split(' ').join('');
                searchField = layer.feature.properties.postcode.toLowerCase().split(' ').join('');
            } else {
                filterValue = filterValue.toLowerCase().split(' ').join('-');
                searchField = layer.feature.properties.city.toLowerCase().split(' ').join('-');
            }

            if (searchField.indexOf(filterValue) !== -1) {
                clusterGroup.addLayer(layer);
                currentCities.push(layer.feature.properties.city.toLowerCase());
            }


            // bind the popup info to the points: 
            var content = '<h2><i class="fa fa-bolt"></i> ' + layer.feature.properties.name + '<\/h2>' +
                '<p>' + layer.feature.properties.address + ', ' + 
                layer.feature.properties.city + '</p>' +
                '<a target="_blank" href="' +layer.feature.properties.site + '" /><span><?php _e('more info', TakeCareIo::THEME_SLUG ); ?></span> <i class="fa fa-angle-right"></i></a>';
            layer.bindPopup(content);

            // focus on the clicked marker
            layer.on('click', function(e) {
                map.panTo([layer.feature.geometry.coordinates[1], layer.feature.geometry.coordinates[0]]);
            });

        });
    }



  </script>


 