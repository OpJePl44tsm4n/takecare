<?php

$row_title	= get_sub_field('title');
$kiyoh_json = get_option( 'kiyoh_reviews_json' ); 
$kiyoh_obj =  $kiyoh_json ? json_decode($kiyoh_json,  true) : []; 
?>

<section class="row review-grid">
	<div class="container">
		<header>
			<div class="d-md-flex justify-content-start">
				<div class="mr-auto">
					<?php echo $row_title; ?>
				</div>
			</div>	
		</header>

		<div class="review_container">
			<?php

			if (isset($kiyoh_obj['review_list'])) { 
				// Gets data from XML file
				$review_list = $kiyoh_obj['review_list']['review']; 
				$review_count = 0;

				foreach ($review_list as $key => $value) { 

					if ($value['customer']['name'] == '') { // Shows only positive reviews
						continue; 
					}

					if ($review_count > 3) { // Show 4 reviews on the page
						break;
					}

					$review_count++;

					$date	= $value['customer']['date']; // Date and Time: Y/M/D + H:M:S

					$date = new Datetime($date);
					$date = $date->format('d/m/Y');
					$score = $value['total_score'];
					$name = !empty($value['customer']['name']) ? $value['customer']['name'] : __('Customer', TakeCareIo::THEME_SLUG );
					$posrev = !empty($value['positive']) ? $value['positive'] : __('No comment.', TakeCareIo::THEME_SLUG );

					// Rating system 1-10 to star rating system 1-5 stars
					$calculated = $score / 2;  ?>

					<div class="reviews" itemprop="review" itemscope="itemscope" itemtype="http://schema.org/Review">
						<span class="rev_name" itemprop="author"><?php echo $name; ?>:</span>
						<div itemprop="reviewRating" itemscope="" itemtype="http://schema.org/Rating">
							<span itemprop="ratingValue">
								<?php 
								for ($i = 1; $i <= $calculated; $i++) { // Turns whole numbers into a full star
									echo '<i class="fa fa-star"></i>';
								}

								if (is_float($calculated)) { // Turns half numbers into s half star
									echo '<i class="fa fa-star-half"></i>';
								}
								?>
							</span>
							<meta itemprop="bestRating" content="10">
						</div>
						<p class="rev_date">
							<meta itemprop="datePublished" content="<?php echo $date; ?>">
							<?php echo $date; ?>
						</p>
						<div itemprop="reviewBody">
							"<?php echo $posrev; ?>"

						</div>
						<?php /*<div itemprop="reviewScore">
							<?php echo number_format((float)$score, 1, '.', ''); ?>
						</div> */ ?>
					</div>

				<?php }
			} ?> 
				
		</div>
		<?php /* <?php if (isset($kiyoh_obj['company'])) {  
			$company_info = $kiyoh_obj['company']; ?>

			<div class="kiyoh_stamp">
				<h2><?php echo number_format((float)$company_info['total_score'], 1, '.', ''); ?> / 10.0</h2>
				<?php echo sprintf('<p><strong>%s</strong> %s</p>', '100%', __('recommends this company',  TakeCareIo::THEME_SLUG)); ?>
				<?php echo sprintf('<p class="p_grey">(%s %s)</p>', $company_info['total_reviews'], __('reviews',  TakeCareIo::THEME_SLUG)); ?> 
				<a class="btn btn-link" href="https://www.kiyoh.nl/gkazas/"><?php _e('Independent reviews from',  TakeCareIo::THEME_SLUG); ?><img src="<?php echo trailingslashit(get_stylesheet_directory_uri()) . 'assets/dist/img/kiyoh_logo.png'; ?>" class="kiyoh_logo"></a>
				
			</div>
		<?php } ?> */?>
	</div>
</section>