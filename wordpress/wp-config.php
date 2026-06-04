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
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */
 define('FS_METHOD', 'direct');
// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** Database username */
define( 'DB_USER', 'cordystackx' );

/** Database password */
define( 'DB_PASSWORD', 'uvp65200' );

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
define( 'AUTH_KEY',         'FEY*BEw:/^5L{(iZ]&AZ3jf,`+x9,Hv}S:9KEr3iCJ^G!^/X[k%)T80K`We-ahz9' );
define( 'SECURE_AUTH_KEY',  'fhK5`ew-h4ca{+?fx7m<UfyLbH7T5rYR-K/<~c;v}VSfe+ZwY:PALRh]_@YD:[TK' );
define( 'LOGGED_IN_KEY',    'Kf#x)7/f9B)`XGDR#2F}q}.69dMEq/4bFTL?%Bd:1N/Vr*|tD@q:ZCUrb*9rz4Ap' );
define( 'NONCE_KEY',        'nFcrieU15XD5=]Ev#3rP#kMpG&iF7Wy~_w3No~?k^<4FGdkvGHby$_`uQHUyHNt-' );
define( 'AUTH_SALT',        'bxeT*a?v0!b`x0[vl5ZHXB`BWt^m:t(AZq6F`ry~AOV%UW(seYXt`)c<POl..~Xk' );
define( 'SECURE_AUTH_SALT', '3]>y5v.}of&CRU{:wpPm?r1i,^j3[An`2B4c1#L*SPNe8cvCO@{*N.6S?2QdPAlU' );
define( 'LOGGED_IN_SALT',   'u;aPVFU)1s:un=*Q @tRWZ$p?xB>n6^.T6j=o^_uRg$T!>Z:CPUM7bYjIWHH-wz,' );
define( 'NONCE_SALT',       'BC/@=bhiNPSA2x^X@tSnkqq^;t~ka|MB.n=,p8F$W7fTWmdEUqODM.:0rT&A#>dh' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
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

