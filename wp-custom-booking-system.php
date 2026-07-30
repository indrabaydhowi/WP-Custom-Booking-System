<?php
/**
 * Plugin Name: WP Custom Booking System
 * Plugin URI: https://example.com/
 * Description: Sistem pemesanan kustom yang dirancang khusus untuk ekosistem WordPress. Mengutamakan performa, keamanan, dan kemudahan manajemen data.
 * Version: 1.0.0
 * Author: Portfolio Developer
 * Text Domain: wp-custom-booking-system
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Define plugin constants
define( 'WPCBS_VERSION', '1.0.0' );
define( 'WPCBS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WPCBS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Include core backend logic
require_once WPCBS_PLUGIN_DIR . 'includes/cpt-destinasi.php';
require_once WPCBS_PLUGIN_DIR . 'includes/ajax-handler.php';

// Include admin panel files
if ( is_admin() ) {
    require_once WPCBS_PLUGIN_DIR . 'admin/admin-dashboard.php';
}

// Include frontend views and logic
require_once WPCBS_PLUGIN_DIR . 'public/frontend-form.php';
