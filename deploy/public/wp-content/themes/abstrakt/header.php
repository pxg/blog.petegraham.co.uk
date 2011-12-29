<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

	<title><?php bloginfo('name'); ?> <?php if ( is_single() ) { ?> &raquo; Blog Archive <?php } ?> <?php wp_title(); ?></title>
	
	<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" /> <!-- leave this for stats -->

	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
	<link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />
	<link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

	<style type="text/css" media="screen">
	
		/* BEGIN IMAGE CSS */
			/*	To accomodate differing install paths of WordPress, images are referred only here,
				and not in the wp-layout.css file. If you prefer to use only CSS for colors and what
				not, then go right ahead and delete the following lines, and the image files. */
			
		body	 	{ background: url("<?php bloginfo('stylesheet_directory'); ?>/images/abstrakt-background.jpg"); }	<?php /* Checks to see whether it needs a sidebar or not */ if ((! $withcomments) && (! is_single()) && (! is_page())) { ?>
			#page		{ background: url("<?php bloginfo('stylesheet_directory'); ?>/images/abstrakt-body.jpg") repeat-y top; border: none; margin: 0 auto; } <?php } else { // No sidebar ?>
			#page		{ background: url("<?php bloginfo('stylesheet_directory'); ?>/images/abstrakt-body.jpg") repeat-y top; border: none; margin: 0 auto; } <?php } ?>
			#header 	{ background: url("<?php bloginfo('stylesheet_directory'); ?>/images/abstrakt-masthead.jpg") no-repeat bottom center; }
			#footer 	{ background: url("<?php bloginfo('stylesheet_directory'); ?>/images/abstrakt-footer.jpg") no-repeat bottom; border: none;}
			
			
			/*	Because the template is slightly different, size-wise, with images, this needs to be set here
				If you don't want to use the template's images, you can also delete the following two lines. */
			
			#header 	{ margin: 0 !important; margin: 0 0 0 1px; padding: 0; height: 180px; width: 760px; }
			#headerimg 	{ margin: 0; height: 180px; width: 740px; } 
		/* END IMAGE CSS */
		
	</style>

	<?php wp_get_archives('type=monthly&format=link'); ?>

	<?php wp_head(); ?>
</head>
<body>

<div id="page">


<div id="header">
	<div id="headerimg">
		<h1><a href="<?php echo get_settings('home'); ?>"><?php bloginfo('name'); ?></a></h1>
		<div class="description"><?php bloginfo('description'); ?></div>
	</div>
</div>
<!-- To edit Top Level Menu Navigation Edit Below-->
<div id="menu">
<a href="<?php bloginfo('url'); ?>">home</a> <a href="#">about</a> <a href="#">photos</a> <a href="#">portfolio</a><a href="#">contact</a>  
</div>


<hr />
