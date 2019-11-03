<?php get_header(); ?>

    <main id="content" role="main" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/Blog">
    
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        
            <article id="post-<?php the_ID(); ?>" <?php post_class('post-content container'); ?> itemscope itemtype="http://schema.org/Article">

                <header>
                    <h1><?php the_title(); ?></h1>
                </header>
                
                <section>
                    <?php the_content();
                       
                    if( have_rows('inline_block_page_rows', 'option') ):
                        while ( have_rows('inline_block_page_rows', 'option') ) : the_row();
                            include(locate_template('inc/rows.php'));
                        endwhile;
                    endif; ?>
                </section>

                <?php if( have_rows('after_content_block_page_rows', 'option') ):
                    while ( have_rows('after_content_block_page_rows', 'option') ) : the_row();
                        include(locate_template('inc/rows.php'));
                    endwhile;
                endif; ?>
                
                <footer class="post-navigation" >
                    <hr>
                    <?php previous_post_link( '%link', '<i class="fa fa-angle-left" aria-hidden="true"></i> ' . __("Previous store", WhiteLabelTheme::THEME_SLUG ) ); ?> 
                    <?php next_post_link( '%link', __("Next store", WhiteLabelTheme::THEME_SLUG ) . ' <i class="fa fa-angle-right" aria-hidden="true"></i>' ); ?>
                </footer>

            </article>

          
        <?php endwhile; endif; ?>

    </main><!-- #content -->

    
<?php get_footer();