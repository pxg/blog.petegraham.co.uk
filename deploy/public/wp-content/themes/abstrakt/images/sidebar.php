<div id="lftcol">

<h2>Asides</h2>
<?php if ( !function_exists('dynamic_sidebar')
        || !dynamic_sidebar() ) : ?>

<?php if (function_exists('wp_theme_switcher')) { ?>

<h3>Themes</h3>

<?php wp_theme_switcher('dropdown'); ?>

<?php } ?>


<?php

$today = current_time('mysql', 1);

if ( $recentposts = $wpdb->get_results("SELECT ID, post_title FROM $wpdb->posts WHERE post_status = 'publish' AND post_date_gmt < '$today' ORDER BY post_date DESC LIMIT 10")):

?>

<h3><?php _e("Recent Posts"); ?></h3>

<ul>

<?php

foreach ($recentposts as $post) {

if ($post->post_title == '')

$post->post_title = sprintf(__('Post #%s'), $post->ID);

echo "<li><a href='".get_permalink($post->ID)."'>";

the_title();

echo '</a></li>';

}

?>

</ul>

<?php endif; ?>

<h3>Sponsors</h3>
<ul>
<?php tla_ads() ?>
</ul>

<!-- START PAGES LIST -->

<h3><?php _e('Pages:'); ?></h3>

<ul>

<li class="page_item"><a href="<?php bloginfo('url'); ?>">Home</a></li>

<?php wp_list_pages('title_li='); ?>

</ul>




<!-- END PAGES LIST -->


<h3>Categories</h3>

<ul>

<?php wp_list_cats('sort_column=name&hierarchical=0'); ?>

</ul>

 <?php if ( is_home() ) { ?>

<h3><?php _e('Blogroll'); ?></h3>

<ul>

<?php get_links(-1, '<li>', '</li>', '', FALSE, 'name', FALSE, FALSE, -1, FALSE); ?>

</ul>

<?php } ?>

 <h3>Archives</h3>

<ul>

<?php wp_get_archives('type=monthly'); ?>

</ul>

 


<h3><?php _e('Meta:'); ?></h3>

<ul>
<li><a href="http://www.wordpress.org">Powered by Wordpress</a></li>
<li><a href="<?php bloginfo('rss2_url'); ?>" title="<?php _e('Syndicate this site using RSS'); ?>"><?php _e('<abbr title="Really Simple Syndication">RSS</abbr>'); ?></a></li>

<li><a href="<?php bloginfo('comments_rss2_url'); ?>" title="<?php _e('The latest comments to all posts in RSS'); ?>"><?php _e('Comments <abbr title="Really Simple Syndication">RSS</abbr>'); ?></a></li>

<li><a href="http://validator.w3.org/check/referer" title="<?php _e('This page validates as XHTML 1.0 Transitional'); ?>"><?php _e('Valid <abbr title="eXtensible HyperText Markup Language">XHTML</abbr>'); ?></a></li>

<li><a href="http://gmpg.org/xfn/"><abbr title="XHTML Friends Network">XFN</abbr></a></li>

</ul>


<?php endif; ?>
</div>
