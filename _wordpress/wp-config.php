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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'erpsinc_wp' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'lpRT/m0dJsTY$+.*/kYhGX^8v_K.#R!>n`-@5#ZWSsiOpes<1I-f{`FeCL8MN_g1' );
define( 'SECURE_AUTH_KEY',  '3BsjkdvE6rkXApfN}Moy UCz,|QARaZK]D3l.9pmL+RqEMs{4FPt[w#u 5xIF%qE' );
define( 'LOGGED_IN_KEY',    '&M8C&9+998<iL8I}&B45Gf^H#~|rn /*}yiD/v.k)T!<UxV&ha>P5&BIuEtE!j-G' );
define( 'NONCE_KEY',        'bvK57-{lBp@EoI6`J )!EB$L%7:gaq>*^YD@)(1H!bNu.Oi}ZHP#{go8c:O07Tnx' );
define( 'AUTH_SALT',        'M=z1L>[g$bMO]@A*8,ZI,gu~dljm~hIOLUX:pWH!*BpSS+{#kfsH^9Kz<Ao.7d29' );
define( 'SECURE_AUTH_SALT', 'kOy,#gB/>S_Jk/QOR^ijH>*RZ]@*N*[Xi$?a c@*Ub~1&}-a;<vXWzl$giNpLo=d' );
define( 'LOGGED_IN_SALT',   'Pw%!mx!=4jH>mN<`H5mYA  5o/T61[7(qd~3!@{K7v<G=PlfC=&|-P=ZE2g|kaKM' );
define( 'NONCE_SALT',       'MEBG*<O f ?f+@p-<8l$lHMBX`{;jLY[db.`QsrUduxw)_N0dKc}<mie,;zz*yiY' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
