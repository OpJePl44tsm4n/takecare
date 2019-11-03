<h2><?php the_sub_field('title'); ?></h2>

<?php if( have_rows('questions') ): 
    $count = 0; ?> 
   
    <ul class="faq" id="accordion-<?php echo $row_count . $column_counter; ?>">
        <?php while ( have_rows('questions') ) : 
            the_row();
            $count++; 
            $faq_id = 'faq-'. $row_count . $column_counter . $count; ?>

            <li>
                <button class="btn collapsed" 
                        data-toggle="collapse" 
                        data-target="#answer-<?php echo $faq_id; ?>" 
                        aria-expanded="false" 
                        aria-controls="answer-<?php echo $faq_id; ?>"> <?php the_sub_field('question'); ?></button>
                
                <div id="answer-<?php echo $faq_id; ?>" class="collapse answer" data-parent="#accordion-<?php echo $row_count . $column_counter; ?>">
                    <?php the_sub_field('answer'); ?>
                </div>
            </li>

        <?php endwhile; ?>
    </ul>

<?php endif; ?>