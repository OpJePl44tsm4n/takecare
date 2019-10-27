
<article class="grid-item post col-sm-6 col-lg-4">
    <div class="card">
	    <a href="<?php the_permalink(); ?>">
	        <div class="thumb">  
                <?php echo get_the_post_thumbnail($post->ID, 'medium'); ?>
            </div> 
		</a>

        <div class="content">
            <h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
            <div class="meta">
                <?php 
                    $types = get_the_terms( get_the_id(), 'category' );
                    if($types) {
                        foreach ( $types as $term ) {
                            echo '<a href="'.get_term_link($term->term_id).'">'. $term->name .'</a>';
                            break;
                        }
                    }    
                ?>
            </div>  
        </div>
    </div>
</article>