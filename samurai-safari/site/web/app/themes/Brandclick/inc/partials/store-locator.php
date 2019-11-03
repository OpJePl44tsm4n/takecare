
<?php 
    $title = get_sub_field('title');
    $search_label = get_sub_field('search_label');
    $search_placeholder = get_sub_field('search_placeholder');
    $button_title = get_sub_field('button_title'); 
    $focus_point = get_sub_field('focus_point');

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
            <label for="search"><?php echo $search_label; ?></label>
            <input id="search" class="search-ui" placeholder="<?php echo $search_placeholder; ?>" />
            <button id="user-location"><i class="fa fa-compass"></i> Show my location</button>
            <hr>
            <button id="reset-map" class="btn btn-secondary"><?php echo $button_title; ?></button>
        </div>
    </div>
   

    <div id="map" style="height:450px;"></div>
</section>
    
<section class="container list-view">
    
    <h2><?php echo $store_list_title; ?></h2>

    <?php echo $store_list_content; ?>
    
    <?php 
        $term_args = [
            'taxonomy' => 'city', 
            'orderby' => 'name',
        ];
        $terms = get_terms($term_args);
        
        if($terms): ?>
    
        <select id="store-select">
            <option value="" selected><?php _e("All stores", WhiteLabelTheme::THEME_SLUG ) ?></option>
            <?php foreach ( $terms as $term): ?>
                <option id="<?php echo $term->slug; ?>" value="<?php echo $term->slug ?>"><?php echo $term->name ?></option>              
            <?php endforeach; ?>
        </select>
        
    <?php endif; ?>

   
   <?php 
        $args = array(
            'post_type'     => 'store',
            'posts_per_page' => -1
        ); 

        // The Query
        $posts = get_posts( $args ); 
        
        $store_array = [];?>
        
        <?php if ( $posts ) : 
            foreach ( $posts as $post ) : 
                setup_postdata( $post );

                $city = get_field('city');
                $store_title = get_the_title();
                $store_url = get_field('url');
            
                if(!in_array($city->slug, $store_array, true)){
                    $store_array[$city->slug]['name'] = $city->name;
                }

                $store_array[$city->slug]['stores'][$store_title] = $store_url;

            endforeach;     
            wp_reset_postdata();
        endif; ?>

    <ol id="store-list" aria-label="Cities"> 
        <?php if ( $store_array ) : 
            ksort($store_array);
            foreach ( $store_array as $city => $value ) : ?>
                <li id="<?php echo $city; ?>" aria-label="<?php echo $value['name']; ?>">
                    <h3 data-city="<?php echo $city; ?>"><?php echo $value['name']; ?></h3>
                    <div>
                        <?php foreach ( $value['stores'] as $store => $url ) : ?>
                            <a href='<?php echo $url; ?>'><?php echo $store; ?></a>
                        <?php endforeach; ?>    
                    </div>   
                </li>
            <?php endforeach; 
        endif; ?>    
    </ol>
</section>    

  <script>

    L.mapbox.accessToken = 'pk.eyJ1Ijoic2JmZWVzdCIsImEiOiJjajgxeGRzdmc2OWlnMnd0NTU1aW80dGk5In0.U8EM471q3gPht8f_YN0Eog';
    var baseLocation = [<?php echo $focus_point['lat']; ?>, <?php echo $focus_point['lng']; ?>];

    var map = L.mapbox.map('map')
        .setView(baseLocation, 7)
        .addLayer(L.mapbox.tileLayer('mapbox.streets'));
    map.scrollWheelZoom.disable();
        
    var overlays = L.layerGroup().addTo(map);
    var layers;
 

    var itemLayer = L.mapbox.featureLayer()
    .loadURL('<?php echo wp_upload_dir()['baseurl']; ?>/brandclick/stores.geojson')
    .on('ready', function(e) {
        layers = e.target;
        loadMarkers();
    });

    // filters 
    var filterValue = '',
    storeSearch = document.getElementById('search'),
    storeSelect = document.getElementById('store-select'),
    mapReset = document.getElementById('reset-map'),
    cityList = document.querySelectorAll('#store-list li'),
    cityFilter = document.querySelectorAll('#store-list h3'),
    userLocation = document.getElementById('user-location');
    currentCities = [];

    storeSearch.addEventListener('keyup', function(e) {
        filterValue = this.value;
        loadMarkers();
    });

    storeSelect.addEventListener('change', function(e) {
        filterValue = this.value;
        if(filterValue == '') map.setView(baseLocation, 9);
        loadMarkers();
    });

    mapReset.addEventListener('click', function(e) {
        filterValue = '';
        storeSearch.value = '';
        storeSelect.value = '';
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
    
    for (i = 0; i < cityFilter.length; ++i) {
        cityFilter[i].onclick = function(e){
            filterValue = this.dataset.city;
            storeSelect.value = this.dataset.city;
            loadMarkers();
        }
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
                  'marker-color': "#0055B8"
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
            var content = '<h2>' + layer.feature.properties.name + '<\/h2>' +
                '<p>' + layer.feature.properties.address + '<br \/>' + 
                layer.feature.properties.postcode.toUpperCase() + '<br \/>' + 
                layer.feature.properties.city + '<\/p>' +
                '<a target="_blank" href="' +layer.feature.properties.site + '" \/>Site<\/a>';
            layer.bindPopup(content);

            // focus on the clicked marker
            layer.on('click', function(e) {
                map.panTo([layer.feature.geometry.coordinates[1], layer.feature.geometry.coordinates[0]]);
            });

        });

        updateCityList();
    }

    function updateCityList() {

        for (i = 0; i < cityList.length; ++i) {
            if (currentCities.includes(cityList[i].id) ) {
                cityList[i].style.display = "block";
            } else {
                cityList[i].style.display = "none";
            }
        }
    }


  </script>


 