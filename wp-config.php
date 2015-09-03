<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
//define( 'WPCACHEHOME', '/home/content/89/9683689/html/new-phonicsschool/wp-content/plugins/wp-super-cache/' ); //Added by WP-Cache Manager
define('DB_NAME', 'phonicsswp320');


/** MySQL database username */
define('DB_USER', 'phonicsswp320');


/** MySQL database password */
define('DB_PASSWORD', 'wnFs50bRxrjJk2p!');


/** MySQL hostname */
define('DB_HOST', 'phonicsswp320.db.9683689.hostedresource.com');


/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');


/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '8|vL5dj/@-_w -_2%uR.~mZw;BBc=Y>Ji4GNc_+A>/82OB|KGJmVz0gYDA*{:*)i');

define('SECURE_AUTH_KEY',  ')[(o-|BQ8DN8}u+{L!o^5e0|5PAleTOU Xu;wU(9gBok. SsE5Rw+ @u|>~4215d');

define('LOGGED_IN_KEY',    'yOd9Z;-8-`7y2D+rJIg nVjNT,vR~HirC*bntEhV~#I9gWgHYjlflE+FR%5ejX?w');

define('NONCE_KEY',        '+G=5~dk&5C/(3+TL;p+LO?TzzZy@Tv<,sV5]9v pyD5nWZO7FH**+dp<KOwDSFqm');

define('AUTH_SALT',        'q5of8&cn_w=L*uT(H2Nc7[#cQ,4GL[3_R-lO9L-~x=@<PfKg7`wY07>1baf ,f0f');

define('SECURE_AUTH_SALT', 'HampnJk7NU&Ke&-]=!N?AYf}sa|irpBxc=t]orQZN)[eg7Y_49 `hW(vjxn|QR?<');

define('LOGGED_IN_SALT',   'y++|Q)J>-~^DA1Uj:J5;Xz=gJj:gK ^l&4E*k/btu-NEea`]H[CMcA#TBl]0S^^}');

define('NONCE_SALT',       'q]mfVrT9[1.pG0kp}V/y~YXGhLRL%bKa!,QFDe!/z>V0(hU{ZV~#-J=hQO<X25]~');


/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';


/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);
define('DISABLE_CACHE', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
