=== nginx Compatibility ===
Contributors: vladimir_kolesnikov
Donate link: http://blog.sjinks.pro/feedback/
Tags: nginx, pretty permalinks, FastCGI
Requires at least: 2.5
Tested up to: 3.2
Stable tag: 0.2.5

The plugin makes WordPress more friendly to nginx.

== Description ==

The plugin solves two problems:

1. When WordPress detects that FastCGI PHP SAPI is in use, it
[disregards the redirect status code](http://blog.sjinks.pro/wordpress/510-wordpress-fastcgi-and-301-redirect/)
passed to `wp_redirect`. Thus, all 301 redrects become 302 redirects
which may not be good for SEO. The plugin overrides `wp_redirect` when it detects
that nginx is used.
1. When WordPress detects that `mod_rewrite` is not loaded (which is the case for nginx as
it does not load any Apache modules) it falls back to [PATHINFO permalinks](http://codex.wordpress.org/Using_Permalinks#PATHINFO:_.22Almost_Pretty.22)
in Permalink Settings page. nginx itself has built-in support for URL rewriting and does not need
PATHINFO permalinks. Thus, when the plugin detects that nginx is used, it makes WordPress think
that `mod_rewrite` is loaded and it is OK to use pretty permalinks.

The plugin does not require any configuration. It just does its work.
You won't notice it — install and forget.

**WARNING:** nginx must be configured properly to support permalinks.

== Installation ==

1. Upload `nginx-compatibility` folder to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress. The plugins comes in two flavors: PHP4 (experimental) and PHP5. Please activate
the flavor that matches your PHP version.
1. That's all :-)

== nginx Configuration ==

**nginx 0.7.32 and higher:**

`
server {
    server_name mysite.com;

    root /path/to/blog;

    index index.php;

    error_page 404 = @wordpress;
    log_not_found off;

    location ^~ /files/ {
        rewrite /files/(.+) /wp-includes/ms-files.php?file=$1 last;
    }

    location @wordpress {
        fastcgi_pass ...;
        fastcgi_param SCRIPT_FILENAME $document_root/index.php;
        include /etc/nginx/fastcgi_params;
        fastcgi_param SCRIPT_NAME /index.php;
    }

    location ~ \.php$ {
        try_files $uri @wordpress;
        fastcgi_index index.php;
        fastcgi_pass ...;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include /etc/nginx/fastcgi_params;
    }

    location ^~ /blogs.dir/ {
        internal;
        root /path/to/blog/wp-content;
    }
}
`

**Older versions:**

`
server {
    server_name mysite.com;

    root /path/to/blog;

    index index.php;

    log_not_found off;
    error_page 404 = @wordpress;

    location ^~ /files/ {
        rewrite /files/(.+) /wp-includes/ms-files.php?file=$1 last;
    }

    location @wordpress {
        fastcgi_pass ...;
        fastcgi_param SCRIPT_FILENAME $document_root/index.php;
        include /etc/nginx/fastcgi_params;
        fastcgi_param SCRIPT_NAME /index.php;
    }

    location ~ \.php$ {
        if (!-e $request_filename) {
            rewrite ^(.+)$ /index.php break;
            break;
        }

        fastcgi_index index.php;
        fastcgi_pass ...;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include /etc/nginx/fastcgi_params;
    }

    location ^~ /blogs.dir/ {
        internal;
        root /path/to/blog/wp-content;
    }
}
`

Of course, do not forget to replace `...` in `fastcgi_pass` with the address/socket
php-cgi is listening on and replace /path/to/blog with the actual path.

Also please note that the path in `SCRIPT_NAME` should be relative to the `DOCUMENT_ROOT` (`root` directive).
Thus, if your WordPress blog resides in `http://example.com/blog/`, `root` is set to `/path.to/example.com`,
`SCRIPT_NAME` in `location @wordpress` will be `/blog/index.php`.

**[Multi-Site Configuration](http://blog.sjinks.pro/wordpress/874-wpms-nginx-accel-redirect/):** the above configs work perfectly with WordPress MultiSite. To make downloads faster, consider adding this line to `wp-config.php`:

`define('WPMU_ACCEL_REDIRECT', true);`

**Need help with configuring nginx?** Contact me: vkolesnikov at odesk dot com, I will try to help you.

== Frequently Asked Questions ==

None yet. Be the first to ask.

== Changelog ==

= 0.2.5 =
* Added code to prevent path disclosure

= 0.2.4 =
* Updated nginx Configuration section to reflect the changes necessary for WordPress 3.0+

= 0.2.3 =
* Detects nginx when `server_tokens` is off (props Serge Pokhodyaev)

= 0.2.2 =
* Better HTTPS handling
* Updated "nginx Configuration" section
* Fixed a bug with multiple calls to `wp_redirect()` (props KeRNel_x86)

= 0.2.1 =
* Code refactoring using [PHP code optimization methods](http://blog.sjinks.pro/php/651-php-code-beauty-impacts-performance-part-2/ "PHP Code Beauty Impacts Performance")
* Supported WP versions: 2.5-2.9

= 0.2 =
* Added experimental PHP4-compatble version of the plugin

= 0.1.1 =
* Added status code check to `wp_redirect`

= 0.1 =
* First public release

== Screenshots ==

None
