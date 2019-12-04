
<article class="grid-item post col-sm-6 col-lg-4">
    <div class="card">
	    <a href="<?php the_permalink(); ?>">
	        <div class="thumb">  
                <?php echo get_the_post_thumbnail($post->ID, 'medium'); ?>
            </div> 
		</a>

        <div class="content">
            <h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
            <p class="intro"><?php echo get_the_excerpt(); ?></p>
            <div class="meta">
                <?php 
                    $founded = get_field('founded');
                    $cities = get_the_terms( get_the_id(), 'city' );
                    $city = isset($cities[0]) ? $cities[0] : false;

                    if( !$city ){
                        $address_obj = get_field('adress');
                        
                        if(isset($address_obj['city'])){
                           $new_term = wp_insert_term($address_obj['city'], 'city');

                           if(is_wp_error($new_term)){
                                wp_set_post_terms($post->ID, $new_term->error_data['term_exists'], 'city'); 
                           } else {
                                wp_set_post_terms($post->ID, $new_term['term_id'], 'city'); 
                           }
                           
                        }
                    }

                    if($city) {
                        echo sprintf('<a class="city" target="_blank" href="%s"><i class="fa fa-map-marker"></i> %s</a>', 
                            get_term_link($city->term_id),
                            $city->name
                        );
                    }   

                    if( $founded ) {
                        
                        echo sprintf('<span class="year-founded"><i class="fa fa-calendar"></i>%s %s</span>', 
                            __('Founded in', TakeCareIo::THEME_SLUG ),
                            $founded
                        );
                    } ?>

                 
                <div class="tags">   
                    <?php 
                    $types = get_the_terms( get_the_id(), 'category' );
                    
                    if($types) {
                        foreach ( $types as $term ) {
                            echo '<a class="main-category" href="'.get_term_link($term->term_id).'">'. $term->name .'</a>';
                            break;
                        }
                    } 

                    $tags = get_the_tags();

                    if($main_tag = get_field('main_tag')){
                        echo '<a class="tag" href="'.get_term_link($main_tag->term_id).'">'. $main_tag->name .'</a>';
                    }

                    if($tags) {
                        $count = count($tags) - 1;
                        $tag_list = ''; 

                        foreach ( $tags as $tag ) {
                            $tag_list .= '<a class="tag" href="'.get_term_link($tag->term_id).'">'. $tag->name .'</a>';
                        }

                        if($tag_list !== ''){ ?>
                            
                            <button class="tag collapsed" 
                                data-toggle="collapse" 
                                data-target="#post-<?php echo $post->ID; ?>-tags" 
                                aria-expanded="false" 
                                aria-controls="post-<?php echo $post->ID; ?>-tags">
                                + <?php echo $count; ?>
                            </button>

                            <div id="post-<?php echo $post->ID; ?>-tags" class="collapse">
                                <button class="close collapsed" 
                                    data-toggle="collapse" 
                                    data-target="#post-<?php echo $post->ID; ?>-tags" 
                                    aria-expanded="false" 
                                    aria-controls="post-<?php echo $post->ID; ?>-tags">
                                    X
                                </button>

                                <?php echo $tag_list; ?>
                            </div>
                            
                        <?php }
                    } ?>
                </div>    

            </div>  
        </div>
    </div>
</article>