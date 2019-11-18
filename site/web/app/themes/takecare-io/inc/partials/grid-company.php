
<article class="grid-item post col-sm-6 col-lg-4">
    <div class="card">
	    <a href="<?php the_permalink(); ?>">
	        <div class="thumb">  
                <?php echo get_the_post_thumbnail($post->ID, 'medium'); ?>
                <?php 
                    $types = get_the_terms( get_the_id(), 'category' );
                    if($types) {
                        foreach ( $types as $term ) {
                            echo '<a class="main-category" href="'.get_term_link($term->term_id).'">'. $term->name .'</a>';
                            break;
                        }
                    }    
                ?>
                
            </div> 
		</a>

        <div class="content">
            <h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
            <p class="intro"><?php echo get_the_excerpt(); ?></p>
            <div class="meta">
                <?php 
                    $website = get_field('website');
                    $cities = get_the_terms( get_the_id(), 'city' );

                    if($cities) {
                        foreach ( $cities as $city ) {
                            echo sprintf('<a class="city" target="_blank" href="%s"><i class="fa fa-map-marker"></i> %s</a>', 
                                get_term_link($city->term_id),
                                $city->name
                            );
                            break;
                        }
                    }   

                    if( $website ) {
                        $site_title = str_ireplace('www.', '', parse_url($website)['host']);
                        echo sprintf('<a class="site-link" target="_blank" href="%s"><i class="fa fa-link"></i> %s</a>', 
                            $website,
                            $site_title
                        );
                    } ?>

                        
                <hr>
                 <?php 
                    $tags = get_the_tags();
                    if($tags) {
                        $i = 0;
                        foreach ( $tags as $tag ) {
                            if($i == 3) {
                                break;
                            }

                            echo '<a class="tag" href="'.get_term_link($tag->term_id).'">#'. $tag->name .'</a>';
                            $i++;
                        }
                    }    
                ?>
            </div>  
        </div>
    </div>
</article>