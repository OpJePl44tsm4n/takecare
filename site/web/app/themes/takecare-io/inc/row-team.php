<?php if( have_rows('team_members') ){ ?>

	<section class="row team-members" > 
		<div class="container">
			<div class="row">
				
				<?php while ( have_rows('team_members') ) : the_row(); 
					$image_id = get_sub_field('image');
					$image = wp_get_attachment_image( $image_id, 'thumb', "", array( "class" => "rounded-circle" )  );
					$name = get_sub_field('name');
					$job_title = get_sub_field('job_title');
					$linkedin = get_sub_field('linkedin_url') ? '<a target="_blank" href="'. get_sub_field('linkedin_url') .'" class="linkedin"><i class="fab fa-linkedin"></i></a>' : ''; ?>

					<article class="team-member col-xs-1 col-sm-4 col-md-3">
			            <div class="member-card">
			                <div class="side front">
			                	<?php echo $image; ?>
			                	<h4><?php echo $name; ?></h4>
			                	<p><?php echo $job_title; ?></p>
			                	<?php echo $linkedin; ?>
			                </div>
				        </div>
			        </article>

				<?php endwhile; ?>

			</div>
		</div>
	</section>

<?php }
