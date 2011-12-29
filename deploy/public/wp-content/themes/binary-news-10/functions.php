<?php
if ( function_exists('register_sidebar') )
    register_sidebar(array(
        'before_widget' => '',
    'after_widget' => '',
 'before_title' => '<h2>',
        'after_title' => '</h2>',
    ));


// WP-binary Pages Box 
 function widget_binary_pages() {
?>

<h2><?php _e('Pages'); ?></h2>
   <ul>
<li class="page_item"><a href="<?php bloginfo('url'); ?>">Home</a></li>

<?php wp_list_pages('title_li='); ?>

 </ul>

<?php
}
if ( function_exists('register_sidebar_widget') )
    register_sidebar_widget(__('Pages'), 'widget_binary_pages');


// WP-binary Search Box 
 function widget_binary_search() {
?>
 

 <h2><?php _e('Search Posts'); ?></h2>
 
 
    <ul>
<li>
   <form id="searchform" method="get" action="<?php bloginfo('url'); ?>/index.php">
 
            <input type="text" name="s" size="18" /><br>

     
            <input type="submit" id="submit" name="Submit" value="Search" />
      
     
 </form>

 
</li>
</ul>

<?php
}
if ( function_exists('register_sidebar_widget') )
    register_sidebar_widget(__('Search'), 'widget_binary_search');


 function widget_links_with_style() {
   global $wpdb;
   $link_cats = $wpdb->get_results("SELECT cat_id, cat_name FROM $wpdb->linkcategories");
   foreach ($link_cats as $link_cat) {
  ?>

  <h2><?php echo $link_cat->cat_name; ?></h2>

   <ul>
   <?php get_links($link_cat->cat_id, '<li>', '</li>', '<br />', FALSE, 'rand', TRUE,  TRUE, -1, TRUE); ?>
   </ul>

   <?php } ?>
   <?php }
   if ( function_exists('register_sidebar_widget') )
   register_sidebar_widget(__(' Links With Style'), 'widget_links_with_style');
   


?>