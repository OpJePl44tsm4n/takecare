<?php get_header(); ?>

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
            echo 'company_logo';   var_dump(get_post_meta($post_id,'company_logo', true));
            echo 'featured_background_image';   var_dump(get_post_meta($post_id,'featured_background_image', true));
            echo 'website';   var_dump(get_post_meta($post_id,'website', true));
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
                
                <?php if( have_rows('page_rows') ):
                    $row_count = 0;
                    while ( have_rows('page_rows') ) : the_row();

                        include(locate_template('inc/rows.php'));
                        $row_count++;
                        
                    endwhile;
 
                else: ?>
                
                    <section class="row">
                        <div class="container content-block">
                            <?php the_content(); ?>
                        </div>
                    </section>        

                <?php endif; ?>
        
            </article>
        
        <?php endwhile; endif; ?>

    </main><!-- #content -->

    
<?php get_footer();