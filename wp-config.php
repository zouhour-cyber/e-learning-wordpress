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
define( 'DB_NAME', 'e-learningzouhour' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

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
define( 'AUTH_KEY',         '^c`Vm&C}RR?8/@e!er9Q^[A};A1iK.$=37SPb[u]u31O1rZpAz=BSA+VoYy?X:3U' );
define( 'SECURE_AUTH_KEY',  'XmASQ$8Gz;OIT..t@tEPRb3k]Irm%d:g_S+&}3#a-,sT{3l@7wsrvG|T[8SoZu*%' );
define( 'LOGGED_IN_KEY',    'JrW(6;JwIl/,+=Rpiv7T!h?(w%0L.^#~j!I{t1Z6yp4ODpeHg|qhtnTlPrL82S@1' );
define( 'NONCE_KEY',        'd|j-yK&F;@R2NV-wxVWA;]tjkuuz`u3,|Y&JDr<QR[Lb&(~f)d_%uLygWcLg4v[w' );
define( 'AUTH_SALT',        '52jWfe1b&~G+(iAzPKPAs;cl_fR%>ZK+Gp)@NIgl;KWE0^Myd!uK4{F2Ni;T~k4O' );
define( 'SECURE_AUTH_SALT', '6ZhO5+.T5>b[ZwcDbslwE0KkX3+|ZL@=d8lYFeQ/6v71Q8P<=hxrpXC#J51{c/3N' );
define( 'LOGGED_IN_SALT',   'ojy6Gj1.&DWm}Q>|8+Hpib!E5^5Q*M 5MMPJx8l5z.yJOsrv;YX4DhJ (&[0a5Of' );
define( 'NONCE_SALT',       'Mctp5h$:|Z7tpVtP9)8@ptS9W,B[B)~On!3?{Zvn!6hdG/,2<e4*Y>F f_-pX@Z6' );

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
define( 'FS_METHOD', 'direct' );
