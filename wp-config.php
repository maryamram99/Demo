<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

@ini_set('upload_max_size' , '256M' );
// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'juj' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         'b72CrCD2[DkpoQ26}qjGf/>!aLRMatCTibZbJ2,Af$PC!i~9M8Of88wX?Prqm|p[' );
define( 'SECURE_AUTH_KEY',  '/h?[z|9Av6JrUitQ4FWeC5_21A@>S)a:)iS k|x.3fnkf`t<Bu8y39d,QHrWM0t&' );
define( 'LOGGED_IN_KEY',    '@L_LUl:f$<u0hPCp_74(EE)nxZj^RhxGQcK8Kh);MEVDr :l,FT^{x<awzinf]rC' );
define( 'NONCE_KEY',        'NM|E:-6.%LuSGe7dKtl=Pgxg*|V5CwM!iUM]evuho<bH:l3Yii|)b/<K9=(?>0[M' );
define( 'AUTH_SALT',        ',lFR+1U)FT6$)E,`o|Vle+UK7;Wk~Q(7|2SYn<rZG M^_1N{zq[e_6V[$jXqK`R@' );
define( 'SECURE_AUTH_SALT', '@ -jvY(,5eiXNrgA}7FuT(U2h~;*aIve0=b!D$^4f0D7XDt+b<]eZIO]Gl5MZ4Z!' );
define( 'LOGGED_IN_SALT',   ')qk(^6jdw?7@;L{jsi&n+@#ce{ZAK d$)kfy=rB3^0npxBl[n9I4Bu`^QRrCZtAS' );
define( 'NONCE_SALT',       'BrjW_2>T43Op=BS+OHbcdaZx$yv+g#~?$s:}H,9SrG]//~8K&T`YzY}&>`WzwL,L' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
