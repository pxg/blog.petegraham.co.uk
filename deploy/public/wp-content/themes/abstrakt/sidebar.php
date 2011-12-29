	<div id="sidebar">
	
	<?php if ( !function_exists('dynamic_sidebar')
        || !dynamic_sidebar() ) : ?>
		
<?php if (function_exists('wp_theme_switcher')) { ?>
  <li><h2>Themes</h2>
        <?php wp_theme_switcher('dropdown'); ?>
  </li>
<?php } ?>
					
<!-- To edit Sidebar Links modify here-->



			<?php include (TEMPLATEPATH . '/searchform.php'); ?>
			
			<?php /* If this is a category archive */ if (is_category()) { ?>
			<p>You are currently browsing the archives for the <?php single_cat_title(''); ?> category.</p>
			
			<?php /* If this is a yearly archive */ } elseif (is_day()) { ?>
			<p>You are currently browsing the <a href="<?php echo get_settings('siteurl'); ?>"><?php echo bloginfo('name'); ?></a> weblog archives
			for the day <?php the_time('l, F jS, Y'); ?>.</p>
			
			<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
			<p>You are currently browsing the <a href="<?php echo get_settings('siteurl'); ?>"><?php echo bloginfo('name'); ?></a> weblog archives
			for <?php the_time('F, Y'); ?>.</p>

      <?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
			<p>You are currently browsing the <a href="<?php echo get_settings('siteurl'); ?>"><?php echo bloginfo('name'); ?></a> weblog archives
			for the year <?php the_time('Y'); ?>.</p>
			
		 <?php /* If this is a monthly archive */ } elseif (is_search()) { ?>
			<p>You have searched the <a href="<?php echo get_settings('siteurl'); ?>"><?php echo bloginfo('name'); ?></a> weblog archives
			for <strong>'<?php echo wp_specialchars($s); ?>'</strong>. If you are unable to find anything in these search results, you can try one of these links.</p>

			<?php /* If this is a monthly archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
			<p>You are currently browsing the <a href="<?php echo get_settings('siteurl'); ?>"><?php echo bloginfo('name'); ?></a> weblog archives.</p>

			<?php } ?>

<!-- Personally I don't like this feature and would use sidelevel menu for my pages list, if you wish to do the same remove the below code-->
              <ul>
			<li class="page_item"><a href="<?php bloginfo('url'); ?>">Home</a></li>
			<?php wp_list_pages('title_li='); ?>
		</ul>
		
		<!-- Remove till here if you dont like page listing feature-->

		<h2>Subscribe</h2>
			<p><?php bloginfo('name'); ?> syndicates its <a href="feed:<?php bloginfo('rss2_url'); ?>">weblog posts</a>
		and <a href="feed:<?php bloginfo('comments_rss2_url'); ?>">Comments</a> using a technology called 
		RSS (Real Simple Syndication). You can use a service like <a href="http://bloglines.com/">Bloglines</a> to get
		notified when there are new posts to this weblog.</p>
						
			<!-- IF YOU'RE GOING TO USE GOOGLE ADS, THIS IS A GOOD PLACE TO PUT THEM -->
			
		<h2><?php _e('Author'); ?></h2>
			<p>A little something about you, the author. Nothing lengthy, just an overview. 
			You have to edit the <code>sidebar.php</code> file in your theme folder to add content to this part.</p>
					

		<h2><?php _e('Archives'); ?></h2>
				<ul>

				<?php wp_get_archives('type=monthly'); ?>
				</ul>

		

		<h2><?php _e('Categories'); ?></h2>
				<ul>
				<?php list_cats(0, '', 'name', 'asc', '', 1, 0, 0, 1, 1, 1, 0,'','','','','') ?>
				</ul>
		

				<?php /* If this is the frontpage */ if ( is_home() || is_page() ) { ?>				

				<ul>
 				<?php get_links_list(); ?>
				</ul>


				
			<h2><?php _e('Meta'); ?></h2>
				<ul>

					<?php wp_register(); ?>
					<li><?php wp_loginout(); ?></li>
					<li><a href="http://validator.w3.org/check/referer" title="<?php _e('This page validates as XHTML 1.0 Transitional'); ?>"><?php _e('Valid <abbr title="eXtensible HyperText Markup Language">XHTML</abbr>'); ?></a></li>
					<li><a href="http://gmpg.org/xfn/"><abbr title="XHTML Friends Network">XFN</abbr></a></li>
					<li><a href="http://wordpress.org/" title="<?php _e('Powered by WordPress, state-of-the-art semantic personal publishing platform.'); ?>">WordPress</a></li>
					<?php wp_meta(); ?>

				</ul>
		
			<?php } ?>
		<?php endif; ?>
	</div>

