<?php
require('../../../wp-blog-header.php');
header('Content-type: text/javascript');

/*
 created by Kae - kae@verens.com
 I can't be bothered with crappy copyright notices.
 I wrote this. Feel free to use it.
 Please retain this notice.
###########
Edits by mathieu bredif - mathieu.bredif_wp@m4x.org
 - linkification of the month caption to its archive.
 - Native localization (days, months, their abbreviation, first day of the week, link titles)
 - Prevented posts in the future to be displayed. 
 - caching fixed
 - fixed the little spinner behaviour (there were problems with caching, months with no posts...)
 - fixed the displacement downward of the agenda.
 - the calendar is almost as close to the "get_calendar" output as possible, so the css styles should work well.
 - if javascript is available, it just finds the wp-calendar element and replaces it, no changes are required whatsoever.
 - livecalendar folder moved to the plugins directory
Edits by kae verens - kae@verens.com
 - header foxed to send correct mime-type
 - Konqueror compat
Edits by jon abad - jonabad@gmail.com
 - Started week on sunday
 - Prevented "next month" link when current month is being displayed. 
 - Coded in a root variable to properly write links
 - put in a little spinner to provide visual confirmation that the calendar is retrieving data.
 - taught it to read the siteurl by itself.
Edits by will mcclendon - willis@willisburg.org
 August 2005 v.1.7
 - added weekday abbreviation option
 - added SEO friendly permalink structure from a posted hack by Reven (www.reven.com)
 - fixed missing month name for KHTML based browsers
 - Safari compatability
 - inserted setting options for both weekday display and permalink structure with documentation
 - added year navigation
 - removed window.status=cal_cell.id from cal_body to eliminate javascript output in IE and Konqueror status bar
 November 2005 v.1.8, 1.8.5
 - fixed several syntax errors and squashed bugs
 - cleaned up code structure and added additional notation
Edits by ste\/e - steve@68k.org
 November 2005 v.1.8, 1.8.5
 - fixed several class/ids to better coincide with get_calendar().
   _create_links() and _build() now appropriately use 'pad' classes and the #today id.
 - don't show prev month/year links before blog's first post
 - commented out $prev/$next links until they're actually used to avoid extra db calls
 - added automatic support for FancyTooltips plugin (http://victr.lm85.com/projects/fancytooltips/)
 - added automatic permalink configuration
*/

global $wpdb, $m, $monthnum, $year, $timedifference, $month, $month_abbrev, $weekday, $weekday_initial, $weekday_abbrev, $posts, $wp_rewrite, $current_plugins;


// find the year and month of the first post
$min_date = $wpdb->get_var("SELECT date_format(post_date,'%Y,%c') FROM $wpdb->posts WHERE post_status = 'publish' ORDER BY post_date ASC LIMIT 1");

// die if we don't have any posts for some reason
if (!$min_date) return; //todo - fix <br /> syntax error when this script exits

list($min_year, $min_month) = explode(',',$min_date);

if (isset($_GET['w'])) {
   $w = ''.intval($_GET['w']);
   }
if (isset($_GET['m'])) {
   $m = ''.intval($_GET['m']);
   }

// let's figure out when we are
if (!empty($monthnum) && !empty($year)) {
   $thismonth = ''.zeroise(intval($monthnum), 2);
   $thisyear = ''.intval($year);
   }
elseif (!empty($w)) {
   // We need to get the month from MySQL
   $thisyear = ''.intval(substr($m, 0, 4));
   $d = (($w - 1) * 7) + 6; //it seems MySQL's weeks disagree with PHP's
   $thismonth = $wpdb->get_var("SELECT DATE_FORMAT((DATE_ADD('${thisyear}0101', INTERVAL $d DAY) ), '%m')");
   }
elseif (!empty($m)) {
   $calendar = substr($m, 0, 6);
   $thisyear = ''.intval(substr($m, 0, 4));
   if (strlen($m) < 6) {
       $thismonth = '01';
       }
   else {
       $thismonth = ''.zeroise(intval(substr($m, 4, 2)), 2);
       }
   }
else {
   $thisyear  = gmdate('Y', current_time('timestamp') + get_settings('gmt_offset') * 3600);
   $thismonth = gmdate('m', current_time('timestamp') + get_settings('gmt_offset') * 3600);
   }

// let's get some configuration settings from wordpress
$start_of_the_week = intval(get_settings('start_of_week'));
$siteurl      	   = get_settings('siteurl');
$trans 		   = array_flip(get_html_translation_table(HTML_ENTITIES));
$trans["&nbsp;"]   = " "; // the usual unbreakable space shows up as a "?" in the title tooltips.

// set up $weekay_disp based off of user settings.
switch(livecal_labeltype) {
    case 'full':
        $weekday_disp = $weekday;
        break;
    default: 
        $weekday_disp = ${'weekday_'.livecal_labeltype};
        break;
}

// set up spinner based off of user preference.
$spinner_img = livecal_dir . livecal_spinner;

// let's see if fancy tooltips is installed
$has_fp = 'false';
if (is_array($current_plugins)) {
   if ( in_array('fancytooltips.php',$current_plugins) )
   $has_fp = 'true';
   }

// lets determine the permalink structure for the current wp install
$permastruct = $wp_rewrite->get_date_permastruct();
if (!$permastruct) $permastruct = "/?m=%year%%monthnum%%day%";
$month_permastruct = $wp_rewrite->get_month_permastruct();
if (!$month_permastruct) $month_permastruct = "/?m=%year%%monthnum%";

// define time and relevant localization php variables in javascript
echo "
var livecal_dir  = '".livecal_dir."'
var week_begins  = $start_of_the_week;
var thismonth    = $thismonth;
var thisyear     = $thisyear;
var min_month    = $min_month;
var min_year     = $min_year;
var has_fp       = $has_fp;
var siteurl      = '$siteurl';
var firstpost	 = '$first_post';
var permastruct  = '$permastruct';
var m_permastruct= '$month_permastruct';
var spinner_img  = '$spinner_img';
var dateDayDisp  = new Array('" . implode("', '", $weekday_disp   ) . "');
var dateMon      = new Array('" . implode("', '", $month          ) . "');
var dateMonShort = new Array('" . implode("', '", $month_abbrev   ) . "');
var archives     = '" . strtr(__("Archives:"),$trans) . "';
var viewpostsfor_= '" . strtr(__("View posts for %1\$s %2\$s"),$trans) . "';
";
?>

// extra fiddly bits of needed code before getting to the meat
function viewpostsfor(year,month) {
   return viewpostsfor_.replace("%1\$s",dateMon[month]).replace("%2\$s",year);
   }

var unixmonth = new Date(thisyear,thismonth-1,1);
unixmonth.setYear(thisyear); // to be sure it's a 4 digit value.

var entries_cache=new Array();
var tocall;

function addEvent(el,ev,fn) {
   if (el.attachEvent)el.attachEvent('on'+ev,fn);
   else if (el.addEventListener)el.addEventListener(ev,fn,false);
   }

if (typeof window.XMLHttpRequest!="undefined") {
   if (navigator.userAgent.indexOf('KHTML')>0) {
      setTimeout('kcalendar_refresh()',2000);
      }
   else {
      addEvent(window,'load',kcalendar_refresh);
      }
   }

// let's create the calendar table array
function kcalendar_build(year,month,day) {
   var today=new Date;
   shownDate=new Date(year,month,day);
   cal=document.createElement('table');
   cal.id="wp-calendar";

// draw month name
   caption=document.createElement('caption');
   caption.appendChild(document.createTextNode(dateMon[shownDate.getMonth()]+' '+shownDate.getFullYear()));
   cal.appendChild(caption);
   caption.id="wp-calendar_caption";
 
// draw day headers
   cal_head = document.createElement('THEAD');
   cal.appendChild(cal_head); 
   cal_row=cal_head.insertRow(0);
   cal_row.id="calendar_daysRow";
   for (i=0;i<7;i++) {
      cal_cell = document.createElement('TH');
      cal_row.appendChild(cal_cell);
      var wd = dateDayDisp[(i+week_begins)%7];
      cal_cell.appendChild(document.createTextNode(wd));
      cal_cell.abbr =wd;
      cal_cell.title=wd;
      cal_cell.scope="col";
      }
 
// draw navigation row month
   cal_foot = document.createElement('TFOOT');
   cal.appendChild(cal_foot);
   cal_row=cal_foot.insertRow(0);

// create previous month nav if we have a post from then
   cal_cell=cal_row.insertCell(0);
   cal_cell.colSpan=3;
   cal_cell.id = 'prev';
   cal_cell.className = 'pad';

// create spinner element
   cal_cell=cal_row.insertCell(1);
   cal_cell.className = 'pad';
   spinner = document.createElement("IMG");
   spinner.id="calendar_spin";
   spinner.src = siteurl + spinner_img;
   cal_cell.appendChild(spinner);

// create next month nav if we have a post from then
   cal_cell=cal_row.insertCell(2);
   cal_cell.colSpan=3;
   cal_cell.id="next";
   cal_cell.className='pad';

// draw navigation row year
   cal_row=cal_foot.insertRow(1);

// create previous year nav if we have a post from then
   cal_cell=cal_row.insertCell(0);
   cal_cell.colSpan=3;
   cal_cell.id='prev_year';
   cal_cell.className='pad';

// create filler space
   cal_cell=cal_row.insertCell(1);
   cal_cell.className='pad';

// create next year nav if we have a post from then
   cal_cell=cal_row.insertCell(2);
   cal_cell.colSpan=3;
   cal_cell.id='next_year';
   cal_cell.className = 'pad';

// create calendar body
   cal_body = document.createElement('TBODY');
   cal.appendChild(cal_body);

// get date of first cell
   firstcelldate=new Date(shownDate.getFullYear(),shownDate.getMonth(),1);
   cellDate=1-firstcelldate.getDay()+week_begins;
   if (cellDate>1)cellDate-=7;

// draw rest of month
   days_in_last_month=kcalendar_daysInMonth(shownDate.getFullYear(),shownDate.getMonth()-1);
   for (i=0;i<6;i++) {
      cal_row=cal_body.insertRow(i);
      for(j=0;j<7;j++) {
         cal_cell=cal_row.insertCell(j);
         class_to_show='';
         if (cellDate<1) {
            class_to_show='pad';
            num_to_show='';//days_in_last_month+cellDate
            }
         else if (cellDate>kcalendar_daysInMonth(shownDate.getFullYear(),shownDate.getMonth())) {
            class_to_show='pad';
            num_to_show='';//cellDate-kcalendar_daysInMonth(shownDate.getFullYear(),shownDate.getMonth());
            }
         else {
            num_to_show=cellDate;
            class_to_show='';
            if (shownDate.getFullYear()==today.getFullYear() && shownDate.getMonth()==today.getMonth() && cellDate==today.getDate()) {
               cal_cell.id='today';
               class_to_show+=" today"
               }
            }
         cal_cell.appendChild(document.createTextNode(num_to_show));
         cal_cell.className=class_to_show;
         if( cal_cell.id != 'today' )
         cal_cell.id="kcalendar_"+shownDate.getFullYear()+"_"+(shownDate.getMonth()+1)+"_"+cellDate;
         cellDate++;
         }
      }
   tocall='kcalendar_'+(shownDate.getFullYear())+"_"+(shownDate.getMonth());
   return cal;
   }

// identify posts for dates listed
function kcalendar_create_links(arr, shown_year,shown_month) {

// add prev month/year links if necessary
   split = arr[0].split(/: /);
   if (split[1] != 'none') {
      prev_date = split[1].split(/,/);
      cell = document.getElementById('prev');
        
// kill the kid.
      if (cell.childNodes.length>0)
         cell.removeChild(cell.childNodes[0]);

// create a new one.
      link = document.createElement('a');
      link.appendChild(
         document.createTextNode(String.fromCharCode(171)+" " +
         dateMonShort[prev_date[1]-1])
      );
      link.href="javascript:kcalendar_refresh("+prev_date[0]+","+prev_date[1]+");";
      link.title = viewpostsfor(prev_date[0],prev_date[1]-1)
      cell.appendChild(link);
      cell.abbr=dateMonShort[prev_date[1]-1];
      cell.className = '';

      if ((prev_date[0] == shown_year && min_year < shown_year) || prev_date[0] < shown_year ) {
            cell = document.getElementById('prev_year');

// speed em up to 88 mph
            year = (prev_date[0] < shown_year-1) ? prev_date[0] : shown_year-1;

// but block the flux capacitor
            month = (year == min_year && shown_month < min_month) ? min_month : shown_month+1;

// kill the kid.
            if (cell.childNodes.length>0)
               cell.removeChild(cell.childNodes[0]);

// create a new one.
            link = document.createElement('a');
            link.appendChild(
               document.createTextNode(String.fromCharCode(171)+" " + year)
            );
            link.href="javascript:kcalendar_refresh("+year+","+month+");";
            link.title = viewpostsfor(year,month-1)
            cell.appendChild(link);
            cell.className = '';
         }

      }

// add next month/year links if necessary
   split = arr[1].split(/: /);
   if (split[1] != 'none') {
      cell = document.getElementById('next');
      next_date = split[1].split(/,/);

// kill the kid.
      if (cell.childNodes.length>0)
         cell.removeChild(cell.childNodes[0]);

// create a new one.
      link = document.createElement('a');
      link.appendChild(
         document.createTextNode(dateMonShort[next_date[1]-1]+ " " +
         String.fromCharCode(187))
      );
      link.href="javascript:kcalendar_refresh("+next_date[0]+","+next_date[1]+");";
      link.title = viewpostsfor(next_date[0],next_date[1]-1)
      cell.appendChild(link);
      cell.abbr=dateMonShort[next_date[1]-1];
      cell.className = '';

      if ( (next_date[0] == shown_year && thisyear > shown_year) || next_date[0] > shown_year) {
         cell = document.getElementById('next_year');

// speed em up to 88 mph
         year = (next_date[0] > shown_year+1) ? next_date[0] : shown_year+1;

// but block the flux capacitor
         month = (year == thisyear && shown_month > thismonth) ? thismonth : shown_month+1;

// kill the kid.
         if (cell.childNodes.length>0)
            cell.removeChild(cell.childNodes[0]);

// create a new one.
            link = document.createElement('a');
            link.appendChild(
               document.createTextNode(year + " " + String.fromCharCode(187))
            );
         link.href="javascript:kcalendar_refresh("+year+","+month+");";
         link.title = viewpostsfor(year,month-1)
         cell.appendChild(link);
         cell.className = '';
         }
      }

// don't do anything if we don't have any links
// this should only happen when someone jumps years
   if (arr[2] == '') return;

// now create links for each post
   for (i=2;i<arr.length;i++) {
      split=arr[i].split(/: /);
      id='kcalendar_'+split[0].replace(/-/g,'_');
      id=id.replace(/_0/g,'_');
      el=document.getElementById(id);
      if (!el) {
         el=document.getElementById('today');
         }
      text=el.childNodes[0];
      el2=document.createElement('a');
      el2.appendChild(text);
      el2.title=split[1];
      datenums = split[0].split(/-/);
      el2.href = siteurl + permastruct;
      el2.href = el2.href.replace(/%year%/,datenums[0]);
      el2.href = el2.href.replace(/%monthnum%/,datenums[1]);
      el2.href = el2.href.replace(/%day%/,datenums[2]);
      el.appendChild(el2);
      }
   el=document.getElementById('wp-calendar_caption');
   split=arr[2].split(/: /);
   if (el && split.length>1) {
      split=split[0].split(/-/);
      text=el.childNodes[0];
      el2=document.createElement('a');
      el2.title= archives+' '+dateMon[split[1]-1]+ ' ' +split[0];
      el2.appendChild(text);
      el2.href = siteurl + m_permastruct;
      el2.href = el2.href.replace(/%year%/,split[0]);
      el2.href = el2.href.replace(/%monthnum%/,split[1]);
// create fancy tooltips if the plugin is enabled
      if ( has_fp == true ) FancyTooltips.autoCreation();
      el.appendChild(el2);
      }
   }

function kcalendar_daysInMonth(year,month) {
   if (month<0) {
      month+=12;year--;
      }
   if (month==3||month==5||month==8||month==10)return 30;
   if (month!=1)return 31;
   if (!(year%4))return 29;
   return 28;
   }
function kcalendar_refresh(year,month) {
   if (isNaN(year)) {
      year=unixmonth.getFullYear();
      month=unixmonth.getMonth();
      }
   else {
      month--;
      }
   while (month<0 ) {
      month+=12;year--;
      }
   while (month>11) {
      month-=12;year++;
      }
   el=document.getElementById('calendar');
   if (!el) {
      el2=document.getElementById('wp-calendar');
      if (el2) {
         el=document.createElement('div');
         el.id='calendar';
         el3=el2.parentNode;
         el3.insertBefore(el,el2);
         el3.removeChild(el2);
         el.appendChild(el2);
         }
      }
   if (!el) return;
   els=el.childNodes;
   for (i=el.childNodes.length-1;i>-1;i--)el.removeChild(els[i]);
   cal=kcalendar_build(year,month,1);
   el.appendChild(cal);

// apply link to post dates
   if (entries_cache[tocall]) {
      kcalendar_create_links(entries_cache[tocall],year,month);
      el=document.getElementById('calendar_spin');
      if (el) el.parentNode.removeChild(el);
      }
   else {
      var req = new XMLHttpRequest();
      if (req) {
         req.onreadystatechange=function() {
            if(req.readyState==4&&req.status==200) {
               entries_cache[tocall]=(req.responseText)?
               req.responseText.split(/\n/):
               [];
               kcalendar_create_links(entries_cache[tocall],year,month);
               el=document.getElementById('calendar_spin');
               if(el) el.parentNode.removeChild(el);
               }
            };
         req.open('GET', siteurl + livecal_dir + 'kcalendar.php?year='+shownDate.getFullYear()+'&month='+(shownDate.getMonth()+1)+'&min_year='+min_year+'&min_month='+min_month);
         if(navigator.userAgent.indexOf('KHTML')==-1)req.send(null);
         else req.send();
         }
      }
   }
// Sorry, Champ... I think I ate your chocolate squirrel.