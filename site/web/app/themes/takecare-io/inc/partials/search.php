<h2><?php echo $title; ?></h2>
<form class="inline-search search-form" role="search" method="get" id="headerSearchform" action="<?php echo home_url(); ?>?s=">
	<input type="text" value="<?php echo get_search_query(); ?>" name="s" id="s" placeholder="<?php echo $search_placeholder; ?>" /> 
	<input type="hidden" class="field" name="post_type" id="post_type" value="company">
	<input type="submit" id="searchsubmit" class="btn btn-primary" value="<?php _e("Search", TakeCareIo::THEME_SLUG ) ?>" />
</form>