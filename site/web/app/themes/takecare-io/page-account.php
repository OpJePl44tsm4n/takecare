<?php /* Template Name: Account */ ?>
<?php get_header(); ?>

    <main id="account" role="main">
            
        <div id="page-<?php the_ID(); ?>" <?php post_class('page-content'); ?>>
            
            <section class="row">
                <div class="container-fluid content-block">
                    <div class="p-4">
                        <user-auth></user-auth>
                    </div>
                    
                </div>
            </section>       
    
        </div>

    </main><!-- #content -->

    
<?php get_footer();