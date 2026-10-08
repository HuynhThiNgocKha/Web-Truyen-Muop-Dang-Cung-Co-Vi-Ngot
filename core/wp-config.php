<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'webtruyen_zhihu' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          '<E(xM=[]yN6g9brqsQVAja)@`Mjh:LBu*y38%O)7WFlI;TK}eUJ,yfNT4y<?BXR6' );
define( 'SECURE_AUTH_KEY',   ']4+S9O 8]]MH,/EWgW~,gJFA7dyyCthvKza,r8#LG`C3!xNR?@#{KB,i#(w,,w3H' );
define( 'LOGGED_IN_KEY',     'H!(T;]{&`-J,Q;Y;?Z[~?V2#AV#)OD%_VjX:R6/zBUQ}lrOI(4-|&4G#sAyH;TXu' );
define( 'NONCE_KEY',         'X7x:~epVNNGuWt?WEy^=R-DE6S269WI8-fjW4ZKcBY[L5Cr[fN_L}rHTP>-b}Nk?' );
define( 'AUTH_SALT',         'Z$~N:FH79%/>Cl,i^uB!c@>n3f)H3|fa6Ovc~B|u2hTtl*{QW`Nq[jHw)uAOgU.X' );
define( 'SECURE_AUTH_SALT',  '#,&iQ|(@O|+]JxtpvQF1Jlg=/j%qRx%[i5Sqme.NC<A>OI.X[QD/fJxfaN2CTKY=' );
define( 'LOGGED_IN_SALT',    'd>-Dpx(n.EIP{,_pdx_5</4u|VC;ESV)VjOx)*tYs<O2R}iWWr=Wia6v k2Ixgkb' );
define( 'NONCE_SALT',        '4K6B$ |.e7ZB7#o`qF5U1;W1clR0I8<_qsguAF9mnQH{HftY0Q&:e=^KiT:fEuSX' );
define( 'WP_CACHE_KEY_SALT', '}GTVs<UB&<FZ7j*crP,!.)_7gv;o]Q[hO)iVR<zdOl[$#8d>x[<Lg1.e*j{f.6Gv' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

// Dynamic Home, SiteURL, and Content paths for subdirectory core architecture & Mobile LAN access
$protocol = ( ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off' ) ? 'https://' : 'http://';
$host = isset( $_SERVER['HTTP_HOST'] ) ? $_SERVER['HTTP_HOST'] : 'localhost:8080';
if ( ! defined( 'WP_HOME' ) ) {
	define( 'WP_HOME', $protocol . $host );
}
if ( ! defined( 'WP_SITEURL' ) ) {
	define( 'WP_SITEURL', $protocol . $host . '/core' );
}
if ( ! defined( 'WP_CONTENT_DIR' ) ) {
	define( 'WP_CONTENT_DIR', dirname( __DIR__ ) . '/wp-content' );
}
if ( ! defined( 'WP_CONTENT_URL' ) ) {
	define( 'WP_CONTENT_URL', $protocol . $host . '/wp-content' );
}

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
