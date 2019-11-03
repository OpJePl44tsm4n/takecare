<?php 

 	 // show the extra content in between
    if( have_rows('social_proof', 'option') ): ?>
		
		<div class="social_proof">

        <?php while ( have_rows('social_proof', 'option') ) : the_row();
            
            if( get_row_layout() == 'social_rating' ): 
            	$platform = get_sub_field('platform');
            	$platform_logo_id = get_sub_field('platform_logo'); 
            	$image = wp_get_attachment_image( $platform_logo_id, 'thumb' );
            	$rating_page_link = get_sub_field('rating_page_link'); 
                $onclick = " onclick=\"javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=520,width=900');return false;\" ";
                $link_open = $rating_page_link ? sprintf('<a target="_blank" href="%s" %s>', $rating_page_link, $onclick) : '';
                $link_close = $rating_page_link ? '</a>' : ''; 
            	$rating_score = get_sub_field('rating_score'); 
            	$rating_max = get_sub_field('rating_max'); 

            	echo sprintf('<div class="social_rating">
            					%s
            						<span class="rating %s"><span class="score">%s</span>/%s</span> <span class="platform">%s %s</span>
									%s
								%s	
            				</div>',
            		$link_open,
                    $platform,
            		$rating_score, 
            		$rating_max,
            		__('via', WhiteLabelTheme::THEME_SLUG),		  
            		$platform,
            		$image,
                    $link_close 
            	);

            elseif( get_row_layout() == 'company_tested' ): 
            	$label_text = get_sub_field('label_text');
            	$platform_logo_id = get_sub_field('company_logo'); 
            	$image = wp_get_attachment_image( $platform_logo_id, 'thumb' );
            	$rating_page_link = get_sub_field('company_test_result_link');  
                $link_open = $rating_page_link ? sprintf('<a target="_blank" href="%s">', $rating_page_link) : '';
                $link_close = $rating_page_link ? '</a>' : '';
                
            	echo sprintf('<div class="company_tested">
            					%s
                                <span class="label">%s</span> %s
								%s	
            				</div>',
                    $link_open,
            		$label_text,
            		$image,
                    $link_close
            	);
            endif; 

        endwhile; ?>
    
    	</div>

    <?php endif; 