<?php
/*
Plugin Name: LiveCalendar
Plugin URI: http://www.jonabad.com/livecalendar/
Description: Calendar widget energized with the xmlhttprequest magic. Allows calendar navigation without refreshing the entire page. If you have questions, try the <a href="http://www.jonabad.com/forums/forum.php?id=1">LiveCalendar Forums</a>.
Author: Jon Abad
Author URI: http://www.jonabad.com
Version: 1.8.5
*/

// Original implementation by Kae Verens, polishing and plugin packaging by Jon Abad.
// IE compat scripts by Kae, many improvements by Mathieu Bredif.
// Month navigation refined and improvements by ste\/e (steve@68k.org).
// A few improvements and much bug squashing by Will McClendon.

/* ------------------------------------------------------------------------
                     b e g i n   c o n f i g u r a t i o n                 
   ------------------------------------------------------------------------ */

/*
spinner image
-------------

   this should point to the image filename of an animated gif that will
   be displayed when livecalendar is waiting for information from the database.
   currently, these can be one of the following:

        wait1.gif   8 line circular spinner 
        wait2.gif   12 point circular spinner
        wait3.gif   solid ring circular spinner

   if you would like to add your own, please put it in the livecalendar directory
   and reference it below.
*/

define(livecal_spinner,'wait1.gif');

/*
weekday labels
--------------

   this setting controls how livecalendar shows the weekdays on the top of the table.
   this can be one of the following:

        'initial'   one character weekday (S)
        'abbrev'    abbreviated weekday (Sun)
        'full'      full weekday (Sunday)
*/

define(livecal_labeltype,'initial');

/* ------------------------------------------------------------------------
                       e n d   c o n f i g u r a t i o n                   
   ------------------------------------------------------------------------ */

# only change these if you have a reason to :)
define(livecal_dir,'/wp-content/plugins/livecalendar/'); #<--with trailing slash


//define function to spit out include in head
function livecal_head() {
   $query = '';
   if (isset($_GET['w'])) $query = "?w=".intval($_GET['w']);
   if (isset($_GET['m'])) $query = "?m=".intval($_GET['m']);
   echo "
        <script type=\"text/javascript\" src=\"" . get_settings("siteurl") . livecal_dir . "xmlhttprequest.js\"></script>
        <script type=\"text/javascript\" src=\"" . get_settings("siteurl") . livecal_dir . "kcalendarscript.php" . $query . "\"></script>
   ";
   }

//add action when the head is written
add_action('wp_head', 'livecal_head');
?>
