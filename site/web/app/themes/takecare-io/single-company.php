<?php get_header(); 

    $logo_id = get_field('company_logo');
    $logo = wp_get_attachment_image( $logo_id, 'thumb' );
    $featured_id = get_field('featured_background_image');
    $featured = wp_get_attachment_image( $featured_id, 'full' );
    $website = get_field('website');
    $address = get_field('adress');
?>

    <main id="content" role="main">
            
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); 

            // Get all content 
            $post_id = get_the_ID(); 
            $post_meta = get_post_meta($post_id); 
            
            echo '<pre>';
            echo 'business_type';   var_dump(get_post_meta($post_id,'business_type', true));
            echo 'company_type';   var_dump(get_post_meta($post_id,'company_type', true));
            echo 'team_size';   var_dump(get_post_meta($post_id,'team_size', true));
            echo 'main_category';   var_dump(get_post_meta($post_id,'main_category', true));
            echo 'tags';   var_dump(get_post_meta($post_id,'tags', true));
            echo 'founder';   var_dump(get_post_meta($post_id,'founder', true));
            echo 'founder_linkedin';   var_dump(get_post_meta($post_id,'founder_linkedin', true));
            echo 'team_members';   var_dump(get_post_meta($post_id,'team_members', true));
            echo 'founded';   var_dump(get_post_meta($post_id,'founded', true));

            echo 'contact_mail';   var_dump(get_post_meta($post_id,'contact_mail', true));
            echo 'contact_phone';   var_dump(get_post_meta($post_id,'contact_phone', true));
            echo 'adress';   var_dump(get_post_meta($post_id,'adress', true));
            echo 'funding_recipient';   var_dump(get_post_meta($post_id,'funding_recipient', true));
            echo 'funding_investors';   var_dump(get_post_meta($post_id,'funding_investors', true));
            echo 'funding_currency';   var_dump(get_post_meta($post_id,'funding_currency', true));
            echo 'funding_raised';   var_dump(get_post_meta($post_id,'funding_raised', true));
            echo 'funding_type';   var_dump(get_post_meta($post_id,'funding_type', true));
            echo 'funding_date';   var_dump(get_post_meta($post_id,'funding_date', true));
            echo 'funding_source';   var_dump(get_post_meta($post_id,'funding_source', true));
            echo 'call_to_action';   var_dump(get_post_meta($post_id,'call_to_action', true));
            echo 'location_type';   var_dump(get_post_meta($post_id,'location_type', true));
            echo 'location';   var_dump(get_post_meta($post_id,'location', true));
            echo 'youtube_videos';   var_dump(get_post_meta($post_id,'youtube_videos', true));
            echo 'company_images';   var_dump(get_post_meta($post_id,'company_images', true));
            echo 'articles';   var_dump(get_post_meta($post_id,'articles', true));
            echo 'Instagram';   var_dump(get_post_meta($post_id,'instagram', true));
            echo 'Linkedin';   var_dump(get_post_meta($post_id,'linkedin', true));
            echo 'Twitter';   var_dump(get_post_meta($post_id,'twitter', true));
            echo 'Facebook';   var_dump(get_post_meta($post_id,'facebook', true));
            echo '</pre>';
            ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class('page-content'); ?> >
                <section class="header container">
                    <div class="row">

                        <div class="col-sm-4">
                            <?php 
                                echo $logo; 
                                the_title( '<h1>', '</h1>' );

                                $categories = get_the_category();
                                $separator = ' ';
                                $output = '';
                                if ( ! empty( $categories ) ) {
                                    foreach( $categories as $category ) {
                                        $output .= '<a class="btn btn-pill" href="' . esc_url( get_category_link( $category->term_id ) ) . '" alt="' . esc_attr( sprintf( __( 'View all compannies in %s', TakeCareIo::THEME_SLUG ), $category->name ) ) . '">' . esc_html( $category->name ) . '</a>' . $separator;
                                    }
                                    echo trim( $output, $separator );
                                } 

                                the_excerpt();

                                if( isset($address['address']) ){
                                    echo $address['address'];
                                }

                                if( $website ) {
                                    echo sprintf('<a class="btn btn-primary" href="%s">%s %s %s</a>', 
                                        $website,
                                        __( 'Go to the', TakeCareIo::THEME_SLUG ),
                                        get_the_title(),
                                        __( 'website', TakeCareIo::THEME_SLUG )
                                    );
                                }
                            ?>
                        </div>
                    
                        <div class="col-sm-8">
                            <?php echo $featured; ?>
                        </div>
                    </div>  
                </section>  

                <section class="row">
                    <div class="container content-sm">
                        <?php the_content(); ?>
                    </div>

                    <div id="term-list" class="container">
                        <?php $terms = get_the_terms( $post->ID, 'certificate' );

                        if($terms) {   
                            $terms_list = [];                   
                            foreach ( $terms as $term ) {
                                $parent = $term->parent;

                                if ($parent === 0) {
                                    $parent_link_text = get_field('link_text', $term->taxonomy . '_' . $term->term_id);
                                    $terms_list[$term->term_id]['link_text'] = $parent_link_text; 
                                } else {
                                    $linkUrl = get_field('link', $term->taxonomy . '_' . $term->term_id);
                                    $icon_id = get_field('icon', $term->taxonomy . '_' . $term->term_id);
                                    $icon  = wp_get_attachment_image( $icon_id, 'thumb' );

                                    if($linkUrl) {
                                        $terms_list[$parent]['certs'][$term->term_id]['cert_html'] = '<span class="certificate"><a target="_blank" href="'.$linkUrl.'">'. $icon .'</a></span>';
                                    } else {
                                        $terms_list[$parent]['certs'][$term->term_id]['cert_html'] = '<span class="certificate">'. $icon .'</span>';
                                    }
                                    
                                }
                            } 
                        }


                        foreach ($terms_list as $term => $value) {
                            if(isset($value['certs'])) {
                                $cert_count = count($value['certs']); ?>
                                
                                <div class="term-collapse">
                                    <button class="btn collapsed" 
                                        data-toggle="collapse" 
                                        data-target="#term-<?php echo $term; ?>" 
                                        aria-expanded="false" 
                                        aria-controls="term-<?php echo $term; ?>"> <?php echo str_replace( '{count}', $cert_count, $value['link_text'] ); ?>
                                    </button>

                                    <div id="term-<?php echo $term; ?>" class="collapse" data-parent="#term-list">
                                        <?php foreach ($value['certs'] as $term => $value) { 
                                            echo $value['cert_html'];
                                        } ?>
                                    </div>
                                </div>

                            <?php } 
                        } ?>
                          
                    </div>
                </section>




                <?php if( have_rows('youtube_videos') ): ?>
                    <section class="row media-videos">
                        <div class="container">
                            <?php while ( have_rows('youtube_videos') ) : the_row(); 
                                $video_url = get_sub_field('youtube_url');
                                $image_id = get_sub_field('video_still');
                                include(locate_template('inc/partials/video-inline.php'));  
                            endwhile; ?>
                        </div>
                    </section>
                <?php endif; ?>

                <?php if( have_rows('articles') ): ?>
                    <section class="row media-articles post-grid">
                        <div class="container">
                       
                            <header>
                                <div class="d-flex justify-content-start">
                                    <div class="mr-auto"><h2><?php __( 'Read more about Zipline', TakeCareIo::THEME_SLUG );?></h2></div>
                                </div>
                            </header>

                            <div class="row justify-content-md-center">
                                <?php while ( have_rows('articles') ) : the_row(); 
                                    $title = get_sub_field('title');
                                    $url = get_sub_field('url');
                                    $site_title = get_sub_field('site_title') ?: str_ireplace('www.', '', parse_url($url)['host']);
                                    $featured_id = get_sub_field('featured_image');
                                    $featured  = wp_get_attachment_image( $featured_id, 'medium' );

                                    if(!$url || !$title) {
                                        continue;
                                    }
                                    ?>
                                    
                                    <article class="grid-item post col-sm-6 col-lg-4">
                                        <div class="card">
                                            <a target="_blank" href="<?php echo $url; ?>">
                                                <div class="thumb">  
                                                    <?php echo $featured; ?>
                                                </div> 
                                            </a>

                                            <div class="content">
                                               
                                                <h1><?php echo $title; ?></h1>
                                                <button class="btn btn-link"><?php echo sprintf("%s %s", __( 'Read on', TakeCareIo::THEME_SLUG ), $site_title ); ?></button>
                                            </div>
                                        </div>
                                    </article> 

                                <?php endwhile; ?>
                            </div>

                        </div>
                    </section>       
                <?php endif; ?>

                <section class="row">
                    <header class="container">
                        <?php echo sprintf("<h2>%s %s</h2>", __( 'Where to find', TakeCareIo::THEME_SLUG ), get_the_title() ); ?>
                    </header>
                    <div class="container-fluid">
      
                        <?php 
                        $focus_point = $address;
                        $popup_content = sprintf("<h3>%s</h3>", get_the_title() );
                        $zoom_level = 10;
                                    
                        include(locate_template('inc/partials/map-basic.php')); ?>
                    
                    </div>
                </section> 

            </article>
        
        <?php endwhile; endif; ?>

    </main><!-- #content -->

    
<?php get_footer();