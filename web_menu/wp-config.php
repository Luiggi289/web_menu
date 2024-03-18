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
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'bd_menu' );

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
define( 'AUTH_KEY',         'lchGTp](m~KW=+b2** k[CUayqJIbF{mB.y^qJJgX}M#xNv9{-eIU60U1Rb$Atts' );
define( 'SECURE_AUTH_KEY',  'wfC8%A__>cKV5(39S8|Ybx&YlhWBo(V)qP6onu~&&:1Kif7iR*Ht7851?w0sWRz-' );
define( 'LOGGED_IN_KEY',    'j89OdGHUo{6UD_;x$c7X`#|E=QZaF)2)QjhM*2pH~tv25;%GN(Z,kOsapE0PA2d,' );
define( 'NONCE_KEY',        '.,gB_cXF7aD&!q=ha%8<)slYS|MX<Zn>4``RE(;jk;`1$%on7$ !&u$P$78e|)AH' );
define( 'AUTH_SALT',        'SO]NlZlEdxk?O~Pc@&dfRehudB]mB`4P.797Z%Q,mMo.+(rJNu&]cowj-A(/o|Ae' );
define( 'SECURE_AUTH_SALT', ',0b#j;&>uVvqLwm=}TufKnfaXzxL{g5.33W0fpGnxOiy.`b@Avm3ep2~cv+J<$Hz' );
define( 'LOGGED_IN_SALT',   '{SAs3.Kka sSfK;_E0:wE4o$oNITGj[mmbGU,.o;D{FQqD#u=&RRP{>@]{VZ>`J,' );
define( 'NONCE_SALT',       'F<C9zBuj37,jjS^@93+s? bD?+N0cIaY)P1>QOkA~*}t(yj&u-K4OobC_(|OM=;B' );

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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
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
