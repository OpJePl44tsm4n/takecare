<?php get_header(); ?>

    <main id="content" role="main">
            
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); 

            $logo_id = get_field('company_logo');
            $logo = wp_get_attachment_image( $logo_id, 'thumb' );
            $featured_id = get_field('main_header_image');
            $featured = wp_get_attachment_image( $featured_id, 'full' );
            $website = get_field('website');
            $address_obj = get_field('adress'); 
            
            $mission = get_field('mission');
            $vision = get_field('vision'); 
            $impact = get_field('impact'); 


            $credits = sprintf('<span class="credits">%s: %s</span>', __('Source', TakeCareIo::THEME_SLUG ), get_the_title() ); ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class('page-content'); ?> >
                <section class="header container">
                    <div class="row swap-order">

                        <div class="grid-item col-md-5">
                            <div class="info">
                                <div class="logo">
                                    <?php echo $logo; ?>
                                </div>

                            <?php 
                                the_title( '<h1>', '</h1>' );

                                $categories = get_the_category();
                                $separator = ' ';
                                $output = '';
                                if ( ! empty( $categories ) ) {
                                    foreach( $categories as $category ) {
                                        $output .= '<a class="main-category" href="' . esc_url( get_category_link( $category->term_id ) ) . '" alt="' . esc_attr( sprintf( __( 'View all compannies in %s', TakeCareIo::THEME_SLUG ), $category->name ) ) . '">' . esc_html( $category->name ) . '</a>' . $separator;
                                    }
                                    echo trim( $output, $separator );
                                } ?>

                                <p class="description"><?php echo get_the_excerpt(); ?></p>
                                
                                <?php 
                                if( isset($address_obj['city']) ){ 
                                    $adress = isset($address_obj['city']) ? $address_obj['city'] . ',</br>' : ''; 
                                    $adress = isset($address_obj['country']) ? $adress . $address_obj['country'] . '</br>' : $adress;

                                    if ( $adress ){
                                        echo '<small class="adress">' . $adress . '</small>';
                                    }
                                }    
                                elseif( isset($address_obj['address']) ){ ?>
                                    <small class="adress">
                                       <?php  $address = explode(',', $address_obj['address']); 
                                            foreach ($address as $key => $value) {
                                                echo $value . '</br>';
                                            }
                                       ?>
                                    </small>
                                <?php } 

                                    $socials = ['linkedin', 'twitter', 'facebook', 'instagram']; 
                                    $list_items = '';

                                    foreach ($socials as $social) {
                                        if ($url = get_field( $social ) ) {  
                                            $list_items .= '<li><a class="social-btn" href="'. $url .'"><i class="fab fa-' . $social . '"></i></a></li>'; 
                                        }
                                    }
                                 
                                    if ( $list_items ) {
                                        echo '<ul class="socials">' . $list_items . '</ul>'; 
                                    }

                                ?>
                            </div>    
                        </div>
                        
                        <?php if($featured): ?>
                            <div class="grid-item col-md-7 featured">
                                <div class="thumb">
                                    <?php echo $featured; 
                                          echo $credits; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                    </div>  
                </section>  
                
                <section class="row">
                    <div class="container content-sm">
                        <?php 
                            $goals = [
                                [   'id' => 'mission',
                                    'value' => $mission
                                ],
                                [   'id' => 'vision',
                                    'value' => $vision
                                ],
                                [   'id' => 'impact',
                                    'value' => $impact
                                ]
                            ];

                            echo '<ul id="goals">';
                            foreach ($goals as $goal) {
                                if($goal['value'] !== ''){
                                    printf('<li id="%s">%s</li>', $goal['id'], $goal['value']);
                                }
                            }
                            echo '</ul>';
                            
                            the_content(); 

                            if( $website ) {
                                echo sprintf('<a class="site-link btn btn-primary" target="_blank" href="%s">%s %s %s <i class="fas fa-external-link-alt"></i></a>', 
                                    $website,
                                    __( 'Go to the', TakeCareIo::THEME_SLUG ),
                                    get_the_title(),
                                    __( 'website', TakeCareIo::THEME_SLUG )
                                );
                            }
                        ?>

                        <div id="term-list">
                            <?php $terms = wp_get_post_terms( $post->ID, 'certificate', array( 'orderby' => 'term_order') );

                            if($terms) {   
                                $terms_list = [];                   
                                foreach ( $terms as $term ) {
                                    $parent = $term->parent;

                                    if ($parent === 0) {
                                        $terms_list[$term->term_id]['color'] = get_field('category_color', $term->taxonomy . '_' . $term->term_id);
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
                       

                                foreach ($terms_list as $term => $value) {
                                    if(isset($value['certs'])) {
                                        $cert_count = count($value['certs']); ?>
                                        
                                        <div class="term-collapse">
                                            <button class="btn btn-collapse collapsed" 
                                                style="color:<?php echo $value['color']; ?>;"
                                                data-toggle="collapse" 
                                                data-target="#term-<?php echo $term; ?>" 
                                                aria-expanded="false" 
                                                aria-controls="term-<?php echo $term; ?>"><?php echo get_the_title(); ?> <?php echo str_replace( '{count}', $cert_count, $value['link_text'] ); ?>
                                            </button>

                                            <div id="term-<?php echo $term; ?>" class="collapse" data-parent="#term-list">
                                                <?php foreach ($value['certs'] as $term => $value) { 
                                                    echo $value['cert_html'];
                                                } ?>
                                            </div>
                                        </div>
                                    <?php } 
                                } 

                            } ?>
                              
                        </div>

                    </div>

                    
                </section>




                <?php if( have_rows('youtube_videos') ): ?>
                    <section class="row media-videos">
                        
                            <?php
                            $row_count = 0;
                            $rows = get_field('youtube_videos');
                            if (is_array($rows)) {
                                $row_count = count($rows);
                            }  

                            if( $row_count > 1 ): ?>
                                <div class="container-fluid">

                                    <?php 
                                    $carousel_slides = '';
                                    $carousel_indicators = '';
                                    $count = 0; 

                                    while ( have_rows('youtube_videos') ) : the_row(); 
                                        $video_url = get_sub_field('youtube_url');
                                        $image_id = get_sub_field('video_still') ?: $featured_id;
                                        $video_description = get_sub_field('video_description');
                                        $active = ($count === 0) ? 'active' : '';

                                        if(!$video_url) {
                                            continue;
                                        }

                                        ob_start(); 
                                        include(locate_template('inc/partials/video-inline.php')); 
                                        $video = ob_get_clean();

                                        $carousel_slides .= sprintf('<div class="carousel-item %s">
                                                %s
                                            </div>',
                                            $active,
                                            $video
                                        );

                                        $carousel_indicators .= '<li class="slider-nav" data-target="#videoCarousel" data-slide-to="'. $count .'" class="'. $active .'">'. ($count + 1) .'</li>';
                                        $count++;
                                    endwhile; ?>

                                    <div id="videoCarousel" class="carousel slide" data-ride="carousel">
                                        <div class="carousel-inner"><?php echo  $carousel_slides; ?></div>

                                        <a class="carousel-control-prev" href="#videoCarousel" role="button" data-slide="prev"></a>
                                        <a class="carousel-control-next" href="#videoCarousel" role="button" data-slide="next"></a>

                                        <div class="carousel-controls">
                                            <a class="control-btn control-prev" href="#videoCarousel" role="button" data-slide="prev">
                                                <i class="fa fa-angle-left"></i>
                                            </a>
                                            <ol class="carousel-indicators">
                                                <?php echo $carousel_indicators; ?>
                                            </ol>
                                            <a class="control-btn control-next" href="#videoCarousel" role="button" data-slide="next">
                                                <i class="fa fa-angle-right"></i>
                                            </a>
                                        </div>
                                    </div> 
                                </div>  
                            <?php else: ?> 
                                <div class="container">
                                    <?php while ( have_rows('youtube_videos') ) : the_row(); 
                                        $video_url = get_sub_field('youtube_url');
                                        $image_id = get_sub_field('video_still') ?: $featured_id; 
                                        $video_description = get_sub_field('video_description');
                                            
                                        if(!$video_url) {
                                            continue;
                                        }

                                        include(locate_template('inc/partials/video-inline.php'));  
                                    endwhile; ?>
                                </div>    
                            <?php endif; ?>
                        </div>
                    </section>
                <?php endif; ?>
                
                 <?php if( have_rows('company_images') ): ?>
                    <section class="row image-gallery">
                        <div class="container">
                            <div class="row">
                            <?php 
                                $img_count = 0; 
                                while ( have_rows('company_images') ) : the_row(); 
                                    $image_id = get_sub_field('image');
                                    $img = wp_get_attachment_image( $image_id, 'full' );
                                    $class = 'col-md-6';

                                    if($img_count % 3 == 0){
                                        $class = 'col-md-10';
                                    }
                                    
                                    printf('<div class="%s img"><div class="thumb">%s %s</div></div>', $class, $img, $credits);

                                    $img_count++;
                                endwhile;
                            ?>
                            </div>
                        </div>
                    </section>
                <?php endif; ?>

                <?php if( have_rows('articles') ): ?>
                    <section class="row media-articles post-grid tertiary-bg-color">
                        <div class="container">
                       
                            <header>
                                <div class="d-flex justify-content-start">
                                    <div class="mr-auto"><h2><?php echo sprintf( __( 'Read more about %s', TakeCareIo::THEME_SLUG ), get_the_title() );?></h2></div>
                                </div>
                            </header>

                            <div class="row justify-content-md-center">
                                <?php 
                               
                                    
                                while ( have_rows('articles') ) : the_row(); 
                                    $title = get_sub_field('title');
                                    $url = get_sub_field('url');
                                    $img_id = get_sub_field('featured_image');
                                    $article_excerpt = get_sub_field('article_description');
                                    
                                    if( !$url ) {
                                        continue;
                                    }
                            
                            
                                    $featured  = wp_get_attachment_image( $img_id, 'medium' );
                                    $site_title = get_sub_field('site_title') ?: str_ireplace('www.', '', parse_url($url)['host']); ?>
                                    
                                    <article class="grid-item post col-sm-6 col-lg-4">
                                        <div class="card">
                                            <a target="_blank" href="<?php echo $url; ?>">
                                                <div class="thumb">  
                                                    <?php echo $featured; ?>
                                                </div>
                                    
                                                <div class="content">
                                                   
                                                    <h1><?php echo $title; ?></h1>
                                                    <p><?php echo $article_excerpt; ?></p>
                                                    <button class="btn btn-link"><?php echo sprintf("%s %s", __( 'Read on', TakeCareIo::THEME_SLUG ), $site_title ); ?></button>
                                                </div>
                                            </a>
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
                        $address = ''; 

                        if ( isset($address_obj['address']) ){ 
                            foreach (explode(',', $address_obj['address']) as $key => $value) {
                                $address .=  $value . '</br>';
                            }
                        }

                        $address = $address ? sprintf('<p class="adress">%s</p>', $address) : '';
                        $focus_point = $address_obj;   
                        $popup_content = sprintf('<div class="img-container">%s</div><div class="content"><h3>%s</h3>%s</div>', $logo, get_the_title(), $address );
                        $zoom_level = 10;
                                    
                        include(locate_template('inc/partials/map-basic.php')); ?>
                    
                    </div>
                </section> 

            </article>
        
        <?php endwhile; endif; ?>

    </main><!-- #content -->

    
<?php get_footer();