<?php get_header(); ?>

    <main id="content" role="main" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/Blog">
    
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    
        <article id="post-<?php the_ID(); ?>" <?php post_class('page-content container'); ?> itemscope itemtype="http://schema.org/Article">
            
            <header>
                <h1><?php the_title(); ?></h1>
                <p class="sub"><?php echo the_date('j M \'j'); ?></p>
            </header>
            
            <section>
                <?php the_content(); ?>
            </section>

            <footer>
                <?php previous_post_link( '%link', '<i class="fa fa-angle-left" aria-hidden="true"></i> ' . __("Previous", TakeCareIo::THEME_SLUG ) ); ?> 
                <?php next_post_link( '%link', __("Next", TakeCareIo::THEME_SLUG ) . ' <i class="fa fa-angle-right" aria-hidden="true"></i>' ); ?>
            </footer>

        </article>

        

    <?php endwhile; endif; ?>

    </main><!-- #content -->

    
<?php get_footer();