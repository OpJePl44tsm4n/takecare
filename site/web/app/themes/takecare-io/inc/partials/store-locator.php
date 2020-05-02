
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

<section class="map-wrap" style="background:#73b6e6;">

    <div class="map-filter card">
        
        <button class="ml-auto filter-toggle" type="button" data-toggle="collapse" data-target="#filterCollapse" aria-controls="filterCollapse" aria-expanded="false" aria-label="Close filter"><span aria-hidden="false"><i class="fa fa-map-marker"></i></span></button>
       

        <div class="collapse show" id="filterCollapse">
            <button class="ml-auto close" type="button" data-toggle="collapse" data-target="#filterCollapse" aria-controls="filterCollapse" aria-expanded="false" aria-label="Close filter"><span aria-hidden="false">&times;</span></button>
            <h2><i class="fa fa-map-marker"></i> <?php echo $title; ?></h2>
            <input id="feature-filter" class="search-ui" placeholder="<?php echo $search_placeholder; ?>" />
            <button id="user-location"><i class="fa fa-compass"></i> Show my location</button>
            <hr>
            <button id="reset-map" class="btn btn-primary"><?php echo $button_title; ?></button>
        </div>
    </div>
   

    <div id="map" data-tap-disabled="true" style="height:450px;"></div>
</section>
<script src='https://api.mapbox.com/mapbox-gl-js/v1.7.0/mapbox-gl.js'></script>
<style type="text/css" src="https://api.mapbox.com/mapbox-gl-js/v1.7.0/mapbox-gl.css"></style>
<?php 
    // wp_enqueue_script( 'mapbox-gl-js', 'https://api.mapbox.com/mapbox-gl-js/v1.7.0/mapbox-gl.js', array(), '', false ); 
    // wp_enqueue_script( 'mapbox-gl-css', 'https://api.mapbox.com/mapbox-gl-js/v1.7.0/mapbox-gl.css', array(), '', true ); 
    // wp_enqueue_script( 'map-js', trailingslashit(get_stylesheet_directory_uri()) . 'assets/dist/js/map.js', array('mapbox-gl-js'), '', true ); ?>

    
    <script>
        mapboxgl.accessToken = 'pk.eyJ1IjoidGh1bmRlcnBsdWdzIiwiYSI6ImNqbjBicTl0azA5Mmsza3Bjdmt3dTFpdnUifQ.Yd8Bg_gH5S1oVss6Rr6vRA';
        var map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/thunderplugs/cjn0criqe4aes2snvkcdtmbf9',
            center: [<?php echo $focus_point['lng']; ?>, <?php echo $focus_point['lat']; ?>],
            zoom: <?php echo $zoom_level; ?>
        });


        var filterEl = document.getElementById('feature-filter');
        // holds al geojson elements 
        var companies = []; 


        function normalize(string) {
            return string.trim().toLowerCase();
        }


        filterEl.addEventListener('keyup', function(e) {
            var value = normalize(e.target.value);
             
            // Filter visible features that don't match the input value.
            var filtered = companies.filter(function(feature) {
                var name = normalize(feature.properties.name);
                var city = normalize(feature.properties.city);
                var city_slug = normalize(feature.properties.city_slug);
                return name.indexOf(value) > -1 || city_slug.indexOf(value) > -1 || city.indexOf(value) > -1;
            });

            console.log(filtered);
             
            // Populate the sidebar with filtered results
            
             
            // Set the filter to populate features into the layer.
            map.setFilter('companies', [
                'match',
                ['get', 'name'],
                filtered.map(function(feature) {
                    return feature.properties.name;
                }),
                true,
                false
            ]);
        });


        map.on('load', function() {

            map.addSource('companies', {
                type: 'geojson',
                data: '<?php echo wp_upload_dir()['baseurl']; ?>/greylabel/companies.geojson',
                cluster: true,
                clusterMaxZoom: 14, // Max zoom to cluster points on
                clusterRadius: 50 // Radius of each cluster when clustering points (defaults to 50)
            });

            map.addLayer({
                id: 'clusters',
                type: 'circle',
                source: 'companies',
                filter: ['has', 'point_count'],
                paint: {
                    // Use step expressions (https://docs.mapbox.com/mapbox-gl-js/style-spec/#expressions-step)
                    // with three steps to implement three types of circles:
                    //   * Blue, 20px circles when point count is less than 100
                    //   * Yellow, 30px circles when point count is between 100 and 750
                    //   * Pink, 40px circles when point count is greater than or equal to 750
                    'circle-color': [
                        'step',
                        ['get', 'point_count'],
                        '#51bbd6',
                        50,
                        '#f1f075',
                        100,
                        '#f28cb1'
                    ],
                    'circle-radius': [
                        'step',
                        ['get', 'point_count'],
                        20,
                        50,
                        30,
                        100,
                        40
                    ]
                }
            });


            map.addLayer({
                id: 'cluster-count',
                type: 'symbol',
                source: 'companies',
                filter: ['has', 'point_count'],
                layout: {
                    'text-field': '{point_count_abbreviated}',
                    'text-font': ['DIN Offc Pro Medium', 'Arial Unicode MS Bold'],
                    'text-size': 12
                }
            });
 
            map.addLayer({
                id: 'companies',
                type: 'circle',
                source: 'companies',
                filter: ['!', ['has', 'point_count']],
                paint: {
                    'circle-color': '#11b4da',
                    'circle-radius': 8,
                    'circle-stroke-width': 2,
                    'circle-stroke-color': '#fff'
                }
            });

            var companies = map.querySourceFeatures({ layers: ['companies'] });

            // inspect a cluster on click
            map.on('click', 'clusters', function(e) {
                var features = map.queryRenderedFeatures(e.point, {
                    layers: ['clusters']
                });

                var clusterId = features[0].properties.cluster_id;
                map.getSource('companies').getClusterExpansionZoom(
                    clusterId,
                    function(err, zoom) {
                        if (err) return;
                         
                        map.easeTo({
                            center: features[0].geometry.coordinates,
                            zoom: zoom
                        });
                    }
                );
            });
             
            map.on('mouseenter', 'clusters', function() {
                map.getCanvas().style.cursor = 'pointer';
            });

            map.on('mouseleave', 'clusters', function() {
                map.getCanvas().style.cursor = '';
            });


            map.on('click', 'companies', function(e) {
                var coordinates = e.features[0].geometry.coordinates.slice();

                var content = '<div class="img-container">%s</div><div class="content"><h3>' +e.features[0].properties.name + '</h3>' +e.features[0].properties.city +
                '<a target="_blank" href="' +e.features[0].properties.site + '" /><span><?php _e('more info', TakeCareIo::THEME_SLUG ); ?></span> <i class="fa fa-angle-right"></i></a> </div>' ;
            
                 
                // Ensure that if the map is zoomed out such that multiple
                // copies of the feature are visible, the popup appears
                // over the copy being pointed to.
                while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
                coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
                }
                 
                new mapboxgl.Popup()
                .setLngLat(coordinates)
                .setHTML(content)
                .addTo(map);
            });

        }); 

    </script> 