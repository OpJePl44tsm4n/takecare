

        <footer id="footer" itemscope="itemscope" itemtype="http://schema.org/WPFooter">
            <div class="container">
                <div class="row justify-content-around">
                    <?php 
                        if (!is_front_page() || (is_front_page() && $slug === 'home') ) : 
                            if ( is_active_sidebar( 'footer-widget' ) ) {
                                dynamic_sidebar( 'footer-widget' ); 
                            }
                        endif; 
                    ?> 
                </div>
            </div>
        </footer>

        <?php
            include(locate_template('inc/popup.php'));
            wp_footer(); 
        ?>

    </body>

</html>