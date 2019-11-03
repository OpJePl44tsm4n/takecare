<?php get_header(); ?>

    <main id="content" role="main" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/Blog">

    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class('page-content'); ?> itemscope itemtype="http://schema.org/Article">
                
            <?php if( have_rows('page_rows') ):
                while ( have_rows('page_rows') ) : the_row();

                   include(locate_template('inc/rows.php'));

                endwhile;

            else :

               the_content();

            endif; ?>

        </article>


    <?php endwhile; endif; ?>

    </main><!-- #content -->

    
<?php get_footer(); ?>