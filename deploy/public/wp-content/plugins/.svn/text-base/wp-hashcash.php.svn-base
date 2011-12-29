<?php
/*
Plugin Name: WordPress Hashcash
Plugin URI: http://elliottback.com/wp/archives/2005/05/11/wordpress-hashcash-20/
Description: Client-side javascript computes an md5 code, server double checks. Blocks all spam bots.  XHTML 1.1 compliant.
Author: Elliott Back
Author URI: http://elliottback.com
Version: 2.3
Hat tips:	Cecil Coupe - http://ccdl.dyndns.biz/werehosed/
		C.S. - http://www.cimmanon.org/
		Denis de Bernardy - http://www.semiologic.com/
		Diego Sevilla - http://neuromancer.dif.um.es/blog/
		Gene Shepherd - http://www.imporium.org/
		John F. - http://www.stonegauge.com/
		Magenson - http://blog.magenson.de/
		Matt Mullenweg - http://photomatt.net/
		Matt Warden - http://www.mattwarden.com/
		Paul Andrew Johnston - http://pajhome.org.uk/crypt/md5/
*/ 

/* Start the session, if not started */
$hashcash_session_id = session_id();
if(empty($hashcash_session_id)){
	session_start();
}

/**
 * Type: bool
 * Purpose: If true, sends logs to the admin email address
 */
define('HASHCASH_DEBUG', true);

/**
 * Type: long
 * Purpose: Stores up to HASHCASH_LONG_SIZE characters before 
 * sending logs to the admin email address
 */
define('HASHCASH_LOG_SIZE', 64000);

/**
 * Type: string
 * Purpose: Must be set to the name of your comments form action
 * for internal pattern matching purposes
 */
define('HASHCASH_FORM_ACTION', 'wp-comments-post.php');

/**
 * Type: string
 * Purpose: Must be set to the id of your comments form for
 * internal pattern matching purposes
 */
define('HASHCASH_FORM_ID', 'commentform');

/**
 * Type: bool
 * Purpose: If true, adds a "protected by" message to the form
 * that supports my work on this plugin, and my dedication to 
 * supporting it.
 */
define('HASHCASH_LINK', true);

/**
 * Type: bool
 * Purpose: If true, writes user-specific information to the
 * form (default).  Detects WP-Cache.
 */
if(WP_CACHE){
	define('HASHCASH_PER_USER', false);
} else {
	define('HASHCASH_PER_USER', true);
}

/**
 * Type: int
 * Purpose: An integer random to your installation, for use with
 * WP-Cache, a value only YOU can know. CHANGE IF USING WP 1.2!!
 */

if(get_bloginfo('version') < 1.5){
	define('HASHCASH_PER_USER_RAND', 98246);
} else {
	// Says Denis to save a query
	if (!get_option('wp_hashcash_version')){
		delete_option('wp_hashcash_rand');
		update_option('wp_hashcash_version', 2.3);
	}

	$curr = get_option('wp_hashcash_rand');
	if(empty($curr)){
		srand((double) microtime() * 1000000);
		update_option('wp_hashcash_rand', rand(10000000, 99999999));
	}
}

/**
 * Takes: An integer l and an array of strings exclude
 * Returns: A random unique string of length l
 */
function hashcash_random_string($l, $exclude = array()) {
	// Sanity check
	if($l < 1){
		return '';
	}

	srand((double) microtime() * 1000000);
	
	$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$chars = preg_split('//', $alphabet, -1, PREG_SPLIT_NO_EMPTY);
	$len = count($chars) - 1;
	
	$str = '';
	while(in_array($str, $exclude) || strlen($str) < 1){
		$str = '';
		while(strlen($str) < $l){
			$str .= $chars[rand(0, $len)];
		}
	}
	
	return $str;
}


/**
 * Takes: A string md5_function_name to call the md5 function
 * Returns: md5 javascript bits to be randomly spliced into the header
 */
function hashcash_get_md5_javascript($md5_function_name){
	$p = '';
	$s = '';

	$names = array();
	$excl = array('a', 's', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j',
			'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u',
			'v', 'w', 'x', 'y', 'z', 'num', 'cnt', 'str', 'bin', 
			'length', 'len', 'var', 'Array', 'mask', 'return', 'msw',
			'lsw', 'olda', 'oldb', 'oldc', 'oldd', 'function', 'new');
	for($i = 0; $i < 17; $i++){
		$t = hashcash_random_string(rand(1,6), $excl);
		$names [] = $t;
		$excl [] = $t;
	}

	$bits = array();
	$bits [] = $p . 'function ' . $md5_function_name . '(s){return ' . $names[5] . '(' . $names[6] . '(' . $names[7] . '(s),s.length*8));}' . $s;
	$bits [] = $p . 'function ' . $names[6] . '(x,len){x[len>>5]|=0x80<<((len)%32);x[(((len+64)>>>9)<<4)+14]=len;var a=1732584193;var b=-271733879;var c=-1732584194;var d=271733878;for(var i=0;i<x.length;i+=16){var olda=a;var oldb=b;var oldc=c;var oldd=d;a=' . $names[8] . '(a,b,c,d,x[i+0],7,-680876936);d=' . $names[8] . '(d,a,b,c,x[i+1],12,-389564586);c=' . $names[8] . '(c,d,a,b,x[i+2],17,606105819);b=' . $names[8] . '(b,c,d,a,x[i+3],22,-1044525330);a=' . $names[8] . '(a,b,c,d,x[i+4],7,-176418897);d=' . $names[8] . '(d,a,b,c,x[i+5],12,1200080426);c=' . $names[8] . '(c,d,a,b,x[i+6],17,-1473231341);b=' . $names[8] . '(b,c,d,a,x[i+7],22,-45705983);a=' . $names[8] . '(a,b,c,d,x[i+8],7,1770035416);d=' . $names[8] . '(d,a,b,c,x[i+9],12,-1958414417);c=' . $names[8] . '(c,d,a,b,x[i+10],17,-42063);b=' . $names[8] . '(b,c,d,a,x[i+11],22,-1990404162);a=' . $names[8] . '(a,b,c,d,x[i+12],7,1804603682);d=' . $names[8] . '(d,a,b,c,x[i+13],12,-40341101);c=' . $names[8] . '(c,d,a,b,x[i+14],17,-1502002290);b=' . $names[8] . '(b,c,d,a,x[i+15],22,1236535329);a=' . $names[9] . '(a,b,c,d,x[i+1],5,-165796510);d=' . $names[9] . '(d,a,b,c,x[i+6],9,-1069501632);c=' . $names[9] . '(c,d,a,b,x[i+11],14,643717713);b=' . $names[9] . '(b,c,d,a,x[i+0],20,-373897302);a=' . $names[9] . '(a,b,c,d,x[i+5],5,-701558691);d=' . $names[9] . '(d,a,b,c,x[i+10],9,38016083);c=' . $names[9] . '(c,d,a,b,x[i+15],14,-660478335);b=' . $names[9] . '(b,c,d,a,x[i+4],20,-405537848);a=' . $names[9] . '(a,b,c,d,x[i+9],5,568446438);d=' . $names[9] . '(d,a,b,c,x[i+14],9,-1019803690);c=' . $names[9] . '(c,d,a,b,x[i+3],14,-187363961);b=' . $names[9] . '(b,c,d,a,x[i+8],20,1163531501);a=' . $names[9] . '(a,b,c,d,x[i+13],5,-1444681467);d=' . $names[9] . '(d,a,b,c,x[i+2],9,-51403784);c=' . $names[9] . '(c,d,a,b,x[i+7],14,1735328473);b=' . $names[9] . '(b,c,d,a,x[i+12],20,-1926607734);a=' . $names[10] . '(a,b,c,d,x[i+5],4,-378558);d=' . $names[10] . '(d,a,b,c,x[i+8],11,-2022574463);c=' . $names[10] . '(c,d,a,b,x[i+11],16,1839030562);b=' . $names[10] . '(b,c,d,a,x[i+14],23,-35309556);a=' . $names[10] . '(a,b,c,d,x[i+1],4,-1530992060);d=' . $names[10] . '(d,a,b,c,x[i+4],11,1272893353);c=' . $names[10] . '(c,d,a,b,x[i+7],16,-155497632);b=' . $names[10] . '(b,c,d,a,x[i+10],23,-1094730640);a=' . $names[10] . '(a,b,c,d,x[i+13],4,681279174);d=' . $names[10] . '(d,a,b,c,x[i+0],11,-358537222);c=' . $names[10] . '(c,d,a,b,x[i+3],16,-722521979);b=' . $names[10] . '(b,c,d,a,x[i+6],23,76029189);a=' . $names[10] . '(a,b,c,d,x[i+9],4,-640364487);d=' . $names[10] . '(d,a,b,c,x[i+12],11,-421815835);c=' . $names[10] . '(c,d,a,b,x[i+15],16,530742520);b=' . $names[10] . '(b,c,d,a,x[i+2],23,-995338651);a=' . $names[11] . '(a,b,c,d,x[i+0],6,-198630844);d=' . $names[11] . '(d,a,b,c,x[i+7],10,1126891415);c=' . $names[11] . '(c,d,a,b,x[i+14],15,-1416354905);b=' . $names[11] . '(b,c,d,a,x[i+5],21,-57434055);a=' . $names[11] . '(a,b,c,d,x[i+12],6,1700485571);d=' . $names[11] . '(d,a,b,c,x[i+3],10,-1894986606);c=' . $names[11] . '(c,d,a,b,x[i+10],15,-1051523);b=' . $names[11] . '(b,c,d,a,x[i+1],21,-2054922799);a=' . $names[11] . '(a,b,c,d,x[i+8],6,1873313359);d=' . $names[11] . '(d,a,b,c,x[i+15],10,-30611744);c=' . $names[11] . '(c,d,a,b,x[i+6],15,-1560198380);b=' . $names[11] . '(b,c,d,a,x[i+13],21,1309151649);a=' . $names[11] . '(a,b,c,d,x[i+4],6,-145523070);d=' . $names[11] . '(d,a,b,c,x[i+11],10,-1120210379);c=' . $names[11] . '(c,d,a,b,x[i+2],15,718787259);b=' . $names[11] . '(b,c,d,a,x[i+9],21,-343485551);a=' . $names[13] . '(a,olda);b=' . $names[13] . '(b,oldb);c=' . $names[13] . '(c,oldc);d=' . $names[13] . '(d,oldd);}return Array(a,b,c,d);}' . $s;
	$bits [] = $p . 'function ' . $names[12] . '(q,a,b,x,s,t){return ' . $names[13] . '(' . $names[16] . '(' . $names[13] . '(' . $names[13] . '(a,q),' . $names[13] . '(x,t)),s),b);}function ' . $names[8] . '(a,b,c,d,x,s,t){return ' . $names[12] . '((b&c)|((~b)&d),a,b,x,s,t);}' . $s;
	$bits [] = $p . 'function ' . $names[9] . '(a,b,c,d,x,s,t){return ' . $names[12] . '((b&d)|(c&(~d)),a,b,x,s,t);}' . $s;
	$bits [] = $p . 'function ' . $names[10] . '(a,b,c,d,x,s,t){return ' . $names[12] . '(b ^ c ^ d,a,b,x,s,t);}' . $s;
	$bits [] = $p . 'function ' . $names[11] . '(a,b,c,d,x,s,t){return ' . $names[12] . '(c ^(b|(~d)),a,b,x,s,t);}' . $s;
	$bits [] = $p . 'function ' . $names[13] . '(x,y){var lsw=(x&0xFFFF)+(y&0xFFFF);var msw=(x>>16)+(y>>16)+(lsw>>16);return(msw<<16)|(lsw&0xFFFF);}' . $s;
	$bits [] = $p . 'function ' . $names[16] . '(num,cnt){return(num<<cnt)|(num>>>(32-cnt));}' . $s;
	$bits [] = $p . 'function ' . $names[7] . '(str){var bin=Array();var mask=(1<<8)-1;for(var i=0;i<str.length*8;i+=8)bin[i>>5]|=(str.charCodeAt(i/8)&mask)<<(i%32);return bin;}' . $s;
	$bits [] = $p . 'function ' . $names[5] . '(' . $names[15] . '){var ' . $names[14] . '="0123456789abcdef";var str="";for(var i=0;i<' . $names[15] . '.length*4;i++){str+=' . $names[14] . '.charAt((' . $names[15] . '[i>>2]>>((i%4)*8+4))&0xF)+' . $names[14] . '.charAt((' . $names[15] . '[i>>2]>>((i%4)*8))&0xF);}return str;}' . $s;

	return $bits;
}

/**
 * Takes: <<void>>
 * Returns: the hashcash special code, based on the session or ip
 */
function hashcash_special_code(){
	if(HASHCASH_PER_USER) {
		$key = strip_tags(session_id());
	
		if(!$key){
			$key = $_SERVER['REMOTE_ADDR'];
		}
	
		return md5($key . ABSPATH . $_SERVER['HTTP_USER_AGENT'] . date("F j, Y, g a"));
	} else {
		if(get_bloginfo('version') < 1.5){
			return md5(ABSPATH . get_bloginfo('version') . HASHCASH_PER_USER_RAND);
		} else {
			return md5(ABSPATH . get_bloginfo('version') . get_option('wp_hashcash_rand'));
		}
	}
}

/**
 * Takes: <<void>>
 * Returns: the hashcash special field value
 */
function hashcash_field_value(){
	global $posts;
	return $posts[0]->ID * strlen(ABSPATH);
}

/**
 * Takes: String name of function
 * Returns:  Javascript to compute field value
 */
function hashcash_field_value_js($val_name){
	$js = 'function ' . $val_name . '(){';
	
	$type = rand(0, 5);
	switch($type){
		/* Addition of n times of field value / n, + modulus */
		case 0:
			$eax = hashcash_random_string(rand(8,10));
			$val = hashcash_field_value();
			$inc = rand(1, $val - 1);
			$n = floor($val / $inc);
			$r = $val % $inc;
			
			$js .= "var $eax = $inc; ";
			for($i = 0; $i < $n - 1; $i++){
				$js .= "$eax += $inc; ";
			}
			
			$js .= "$eax += $r; ";
			$js .= "return $eax; ";
		
			break;
		
		/* Conversion from binary */
		case 1:
			$eax = hashcash_random_string(rand(8,10));
			$ebx = hashcash_random_string(rand(8,10));
			$ecx = hashcash_random_string(rand(8,10));
			$val = hashcash_field_value();
			$binval = strrev(base_convert($val, 10, 2));

			$js .= "var $eax = \"$binval\"; ";
			$js .= "var $ebx = 0; ";
			$js .= "var $ecx = 0; ";
			$js .= "while($ecx < $eax.length){ ";
			$js .= "if($eax.charAt($ecx) == \"1\") { ";
			$js .= "$ebx += Math.pow(2, $ecx); ";
			$js .= "} ";
			$js .= "$ecx++; ";
			$js .= "} ";
			$js .= "return $ebx; ";
			
			break;

		/* Multiplication of square roots */
		case 2:
			$val = hashcash_field_value();
			$sqrt = floor(sqrt($val));
			$r = $val - ($sqrt * $sqrt);
			$js .= "return $sqrt * $sqrt + $r; ";
			break;

		/* Closest sum up to n */
		case 3:
			$val = hashcash_field_value();
			$n = floor((sqrt(8*$val+1)-1)/2);
			$sum = $n * ($n + 1) / 2;
			$r = $val - $sum;
			$eax = hashcash_random_string(rand(8,10));

			$js .= "var $eax = $r; ";
			for($i = 0; $i <= $n; $i++){
				$js .= "$eax += $i; ";
			}
			$js .= "return $eax; ";
			break;

		/* Closest sum up to n #2 */
		case 4:
			$val = hashcash_field_value();
			$n = floor((sqrt(8*$val+1)-1)/2);
			$sum = $n * ($n + 1) / 2;
			$r = $val - $sum;

			$js .= "return $r ";
			for($i = 0; $i <= $n; $i++){
				$js .= "+ $i ";
			}
			$js .= ";";
			break;

		/* Closest sum up to n #3 */
		case 5:
			$val = hashcash_field_value();
			$n = floor((sqrt(8*$val+1)-1)/2);
			$sum = $n * ($n + 1) / 2;
			$r = $val - $sum;
			$eax = hashcash_random_string(rand(8,10));

			$js .= "var $eax = $r; var i; ";
			$js .= "for(i = 0; i <= $n; i++){ ";
			$js .= "$eax += i; ";
			$js .= "} ";
			$js .= "return $eax; ";
			break;
	}
	
	$js .= "} ";
	return $js;
}

/**
 * Takes: An array matching the form
 * Returns: The form code, with input elements disabled
 */
function hashcash_disable_callback($matches){
	$text = $matches[0];
	return preg_replace('/<input([^>]*?id="(submit|author|email|url)")/si', '<input disabled="disabled"$1', $text);
}

/**
 * Takes: An array matching the form
 * Returns:  The form code, with a protected by link
 */
function hashcash_link_callback($matches){
	$text = $matches[0];
	$r = rand(0, 4);
	switch($r){
	case 0:
		return str_replace('</form>',  '<p>Protected by <a href="http://elliottback.com/" title="Elliott Back\'s Antispam Protection">WP-Hashcash</a>.</p>' . "\n" . '</form>', $text);
		break;
	case 1:
		return str_replace('</form>',  '<p><a href="http://elliottback.com/" title="Elliott Back">WP-Hashcash</a>: protecting you from spam.</p>' . "\n" . '</form>', $text);
		break;
	case 2:
		return str_replace('</form>',  '<p>Powered by <a href="http://elliottback.com/" title="Elliott Back, Spam Protection">WP-Hashcash</a>.</p>' . "\n" . '</form>', $text);
		break;
	case 3:
		return str_replace('</form>',  '<p>I\'m <a href="http://elliottback.com/" title="Elliott Back">WP-Hashcash</a>.  I eat spam.</p>' . "\n" . '</form>', $text);
		break;
	case 4:
		return str_replace('</form>',  '<p>What\'s a blog without spam? <a href="http://elliottback.com/" title="Elliott Back + Spam Protection">WP-Hashcash</a>.</p>' . "\n" . '</form>', $text);
		break;
	}
}

/**
 * Takes: An array matching the form
 * Returns:  The form code, with a noscript attribution
 */
function hashcash_script_callback($matches){
	$text = $matches[0];
	return str_replace('<form', '<noscript><p>WP-Hashcash by <a href="http://elliottback.com/" title="Elliott Back\'s Blog">Elliott Back</a> protects <strong>you</strong> from spam. Please enable javascript and reload this page to add your comment.</p></noscript>' . "\n" . '<form', $text);
}

/**
 * Takes: An array matching the form
 * Returns:  The form code, with appropriate javascript action
 */
function hashcash_add_action_callback($matches){
	global $hashcash_form_action;
	return str_replace('<form', '<form onsubmit="' . $hashcash_form_action . '(\'' . hashcash_special_code() . '\');" ', $matches[0]);
}

/**
 * Takes: A WordPress single page
 * Returns: The same page with a random hidden field and others added.
 * This is the workhorse of WP-Hashcash
 */
function hashcash_add_hidden_tag($page) {
	global $posts, $single, $hashcash_form_action, $post;

	if ($single && $post->comment_status == 'open'){
		$field_id = hashcash_random_string(rand(6,18));
		$field_name = hashcash_random_string(rand(6,18));
		$hashcash_form_action = hashcash_random_string(rand(6,18));
		$md5_name = hashcash_random_string(rand(6,18));
		$val_name = hashcash_random_string(rand(6,18));
		$eElement = hashcash_random_string(rand(6,18));
		$in_str = hashcash_random_string(rand(6,18));
		$fn_enable_name = hashcash_random_string(rand(6,18));
		
		/**
		 * 1) Hidden hashcode
		 */

		// Write in hidden field
		$page = str_replace('<input type="hidden" name="comment_post_ID"', '<input type="hidden" id="' . $field_id . '" name="' . $field_name . '" value="' . rand(100, 99999999) . '" /> <input type="hidden" name="comment_post_ID"', $page);
	
		// The form action
		$page = preg_replace_callback('/<form[^>]*?' . HASHCASH_FORM_ACTION .  '.*?<\/form>/si', 'hashcash_add_action_callback', $page);
		
		// The javascript
		$hashcash_bits = hashcash_get_md5_javascript($md5_name);
		$hashcash_bits [] = "function $hashcash_form_action($in_str){ "
			. "$eElement = document.getElementById(\"$field_id\"); "
			. "if(!$eElement){ return false; } else { $eElement" . ".name = $md5_name($in_str); $eElement" . ".value = $val_name(); return true; }}";
	
		$hashcash_bits [] = hashcash_field_value_js($val_name);

		/**
		 * 2) Javascript enabled form fields
		 */

		// Disable form fields
		$page = preg_replace_callback('/<form[^>]*?' . HASHCASH_FORM_ACTION .  '.*?<\/form>/si', 'hashcash_disable_callback', $page);
		
		// Try to enable all form fields from javascript
		$fields = array('submit', 'author', 'email', 'url');
		$page = str_replace('<body', '<body onload="' . $fn_enable_name . '();"', $page);
		
		$script = 'function ' . $fn_enable_name . '(){';
		shuffle($fields);
		foreach($fields as $field){
			$field_temp = hashcash_random_string(rand(6,18));
			$script .= "$field_temp = document.getElementById('$field'); if(!$field_temp){} else { $field_temp.disabled = false; } ";
		}

		// Other things that happen onload()
		$script .= "document.getElementById('" . HASHCASH_FORM_ID . "').style.display = 'block';";	

		// Terminator
		$script .= '}';
		$hashcash_bits [] = $script;

		/**
		 * 3)  Hide form for non-users of javascript 
		 */
		
		$page = preg_replace_callback('/<form[^>]*?' . HASHCASH_FORM_ACTION .  '.*?<\/form>/si', 'hashcash_script_callback', $page);
		$page = str_replace('</head>', '<style type="text/css">#' . HASHCASH_FORM_ID . '{ display: none; }</style>' . "\n" . '</head>', $page);

		/**
		 * 4)  Write all the javascript bits to various lines of <head>
		 */
		
		shuffle($hashcash_bits);
		$js = '<script type="text/javascript">' . "\n"
			. '<!--' . "\n"
			. implode(" ", $hashcash_bits) . "\n"
			. '-->' . "\n"
			. '</script>' . "\n";
		$page = str_replace('</head>', $js . '</head>', $page);

		/**
		 * 5)  Powered by WP-Hashcash
		 */

		if(HASHCASH_LINK)
			$page = preg_replace_callback('/<form[^>]*?' . HASHCASH_FORM_ACTION .  '.*?<\/form>/si', 'hashcash_link_callback', $page);
	}
	
	return $page;
}

/**
 * Takes: <<void>>
 * Returns: Buffered page output w/ hashcash inserted
 */
function hashcash_call_stopgap() {
	ob_start('hashcash_add_hidden_tag');
}

add_action('wp_head', 'hashcash_call_stopgap');

/**
 * Takes: The text of a comment
 * Returns: <<void>>, writes comment to log
 */
function write_comment_log($comment){
	
	/* Information to write to log */
	$user = array();
	$user[] = date("F j, Y, g:i a");
	$user[] = $_SERVER['REMOTE_ADDR'];
	$user[] = $_SERVER['HTTP_USER_AGENT'];
	$user[] = $_SERVER['HTTP_REFERER'];
	$user[] = $_POST['author'];
	$user[] = $_POST['email'];
	$user[] = $_POST['url'];
	$user[] = preg_replace('/[\n\r]+/','<br />', $comment);
	$user[] = $_POST['comment_post_ID'];

	$lines = join($user, "\n");

	/* In 1.5, use options. */
	$path = ABSPATH . "wp-content/plugins/wp-hashcash.log";
	if(get_bloginfo('version') < 1.5){
		/* Open the file */
		$file = fopen($path, 'a+');
		if(!$file) die("File \"$path\" failed to open");
		
		/* Save the log */
		$status = fwrite($file, "\n" . $lines);

		/* Close the file */
		fclose($file);
		if(!status) die("Spam-log write failed...");

		/* Read the file */
		$new = file($path);
	} else {
		add_option('wp_hashcash_log', '', 'Log option for the wp-hashcash plugin', 'no');
		$current = get_option('wp_hashcash_log');
		$new = $current . "\n" . $lines;
		update_option('wp_hashcash_log', $new);
	}

	/* If we're here, the file exists.  Check size, email every 64kb */
	if( strlen($new) > HASHCASH_LOG_SIZE ) {
		$header = "<html><head><style>tr {	margin: 0px 0px 5px 20px; }</style></head><body><h2>Spam Report:</h2>";
		$footer = "</body></html>";

		// Process log
		$log = $header;
		$i = false;
		
		$temp  = explode("\n", $new);

		$count = count($temp) / 9;
		$log  .= "<p>There were $count spam...</p>";
		
		// Table
		$log .= "<table>";
		for ($j = 0; $j < count($temp) - 1; $j++) {
			if ($i)
				$log .= '<tr style="background-color: #eee">';
			else
				$log .= '<tr>';

			$log .= "<td>";
			$log .= $temp[$j]; $j++;
			$log .= "<blockquote>";
			$log .= "<strong>IP:</strong> $temp[$j]<br />"; $j++;
			$log .= "<strong>User-Agent:</strong> $temp[$j]<br />"; $j++;
			$log .= "<strong>Referer:</strong> <a href=\"$temp[$j]\">$temp[$j]</a><br />"; $j++;
			$log .= "<strong>Author:</strong> $temp[$j]<br />"; $j++;
			$log .= "<strong>Email:</strong> <a href=\"mailto:$temp[$j]\">$temp[$j]</a><br />"; $j++;
			$log .= "<strong>URL:</strong> <a href=\"$temp[$j]\">$temp[$j]</a><br />"; $j++;
			$log .= "<br />";
			$log .= $temp[$j]; $j++;
			$log .= "<br /><br />";
			$log .= "on post <a href=\"" . get_settings('siteurl') . "/index.php?p=" . $temp[$j] . "\">" . $temp[$j] . "</a>";
			$log .= "</blockquote>";
			$log .="</td>";
			$log .= "</tr>";
			$i = !$i;
		}
		$log .= "</table>";

		// Footer
		$log .= $footer;

		// Send email
		$headers = "Content-type: text/html; charset=" . get_settings('blog_charset') . "\r\n";
		mail(get_settings('admin_email'), '[' . get_settings('blogname') . '] Spam Report', $log, $headers);

		// Clear file
		if(get_bloginfo('version') < 1.5){
			$file = fopen($path, 'w');
			if(!file){
                  		die("Unable to truncate old log file");
			} else{
                  		fclose($file);
			}
		} else {
			update_option('wp_hashcash_log', '');
		}
	}
}

/**
 * Takes: The text of a comment
 * Returns: The comment iff it matches the hidden md5'ed tag
 */
function hashcash_check_hidden_tag($comment) {
	// Our special codes, fixed to check the previous hour
	$special = array();

	if(HASHCASH_PER_USER){
		$special[] = md5($_SERVER['REMOTE_ADDR'] . ABSPATH . $_SERVER['HTTP_USER_AGENT'] . date("F j, Y, g a"));
		$special[] = md5($_SERVER['REMOTE_ADDR'] . ABSPATH . $_SERVER['HTTP_USER_AGENT'] . date("F j, Y, g a", time()-(60*60)));
		$special[] = md5(strip_tags(session_id()) . ABSPATH . $_SERVER['HTTP_USER_AGENT'] . date("F j, Y, g a"));
		$special[] = md5(strip_tags(session_id()) . ABSPATH . $_SERVER['HTTP_USER_AGENT'] . date("F j, Y, g a", time()-(60*60)));
	} else {
		if(get_bloginfo('version') < 1.5){
			$special[] = md5(ABSPATH . get_bloginfo('version') . HASHCASH_PER_USER_RAND);
		} else {
			$special[] = md5(ABSPATH . get_bloginfo('version') . get_option('wp_hashcash_rand'));
		}
	}

	foreach($special as $val){
		if($_POST[md5($val)] == ($_POST['comment_post_ID'] * strlen(ABSPATH))){
			return $comment;
		}
	}

	// If here, the comment has failed the check
	if( HASHCASH_DEBUG )
		write_comment_log($comment);

	// Be more user friendly if we detect spam, and it sends a referer
	if(strlen(trim($_SERVER['HTTP_REFERER'])) > 0 && preg_match('|' . get_bloginfo('url') . '|i', $_SERVER['HTTP_REFERER']))
		echo '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head profile="http://gmpg.org/xfn/11">
		<title>WP-Hashcash Check Failed</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<style type="text/css">
			body {
				font-family: Arial, Verdana, Helvetica;
				color: #3F3F3F;
			}

			h1 {
				margin: 0px;
				color: #6A8E1C;
				font-size: 1.8em;
			}
	
			a:link {
				color: #78A515;
				font-weight: bold;
				text-decoration: none;
			}
	
			a:visited { color: #999; }
	
			a:hover, a:active {
				background: #78A515;
				color: #fff;
				text-decoration: none;
			}
		</style>
	</head>

	<body>
		<div style="margin: 0 auto; margin-top:50px; padding: 20px; text-align: left; width: 400px; border: 1px solid #78A515;">
			<h1>WP-Hashcash Check Failed</h1>
			
			<p>Your client has failed to compute the special javascript hashcode required to comment on this blog. 
			If you believe this to be in error, please contact the blog administrator, and check for javascript, 
			validation, or php errors.  It is also possible that you are trying to spam this blog.</p>

			<p>If you are using Google Web Accelerator, a proxy, or some other caching system, WP-Hashcash may not let you comment. 
			There are known issues with caching that are fundamentally insoluble, because the page being written to you must be generated freshly. 
			Turn off your caching software and reload the page. If you are using a proxy, commenting should work, but it is untested.</p>';

/* Possible sources of error */

if(!session_id()){
	echo '<p style="border: 2px solid red; color:red; padding:4px;">Unable to generate you a Session ID, falling back on your remote address,
which appears to be ' . $_SERVER['REMOTE_ADDR'] . '.  If this is not your remote address, this is the cause of the error.</p>';
}

if(!session_id() && strlen($_SERVER['REMOTE_ADDR']) < 1){
	echo '<p style="border: 2px solid red; color:red; padding:4px;">Your remote address is null.</p>';
}

if(!in_array($_POST['comment_post_ID'] * strlen(ABSPATH), $_POST)){
	echo '<p style="border: 2px solid red; color:red; padding:4px;">The value you submitted (' . $_POST[md5($val)] . ') 
		is incorrect.  Check the javascript to assure that the value part of the (hash, value) pair is being correctly 
		generated.</p>';
}

$hashash = false;
foreach($special as $spec){
	if(array_key_exists($spec, $_POST))
		$hashash = true;
}

if($hashash) {
	echo '<p style="border: 2px solid red; color:red; padding:4px;">The hash does not exist.  
		Check the javascript to assure that the md5 hash part of the (hash, value) pair is
		being correctly generated.</p>';
}

if(WP_CACHE && HASHCASH_PER_USER){
	echo '<p style="border: 2px solid red; color:red; padding:4px;">WP-Cache is detected, but for 
	some reason, HASHCASH_PER_USER is set to true.</p>';
}

echo'			<p>This comment has been logged, and will not be displayed on the blog.</p>
		</div>
	</body>
</html>';

	die();
}

add_filter('post_comment_text', 'hashcash_check_hidden_tag');

?>