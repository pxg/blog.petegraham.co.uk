<?
/*
 created by Kae - kae@verens.com
 I can't be bothered with crappy copyright notices.
 I wrote this. Feel free to use it.
 Please retain this notice.
*/
include_once('../../../wp-blog-header.php');

/* 
   this page is in charge of getting what posts exist for a given month year.
   in addition, it has been extended to get the previous/next month where a post
   exists.

   it would be possible to optimize the queries here, but it would involve subqueries
   or unions, which don't exist in older mysql servers which are still in production.
   thus, there are three queries done each time this page is hit. two for the previous/
   next month info, and one to get all the posts for the current month.

   -ste\/e
*/

# this page requires the following GET variables to be passed in the query string
foreach( array('month','year','min_month','min_year') as $var ) 
    $$var = $_GET[$var];

if( $month < 10 ) $month = "0$month";

$now = current_time('mysql');
list($now_year,$now_month,,,) = preg_split("/[-\s]/",$now);

// get prev month with a post
if( $year > $min_year || $year == $min_year && $month > $min_month ) {
    $prev = $wpdb->get_var("select date_format(post_date,'%Y,%c') from ".$table_prefix."posts where
post_status = 'publish' and post_date < '$year-$month-01' order by post_date
desc limit 1");
    echo "prev: $prev\n";
}
else
    echo "prev: none\n";

// get next month with a post
if( $year < $now_year || $year == $now_year && $month < $now_month) {
    $next = $wpdb->get_var("select date_format(post_date,'%Y,%c') from ".$table_prefix."posts where
post_status = 'publish' and post_date >= '$year-".($month+1). "-01' order by post_date asc limit 1");
    echo "next: $next\n";
}
else
    echo "next: none\n";

// get all posts for selected month
$q=mysql_query("select post_date,post_title from ".$table_prefix."posts where post_date < '$now' and post_status='publish' and post_date like '".$_GET['year']."-".$month."-%' order by post_date");
$date='';
while ($r=mysql_fetch_array($q)) {
   $r['post_date']=preg_replace('/ .*/','',$r['post_date']);
   if ($r['post_date']!=$date) {
      if ($date!='')echo "\n";
      $date=$r['post_date'];
      echo $date.': ';
      }
   else echo ', ';
   echo $r['post_title'];
   }
?>
