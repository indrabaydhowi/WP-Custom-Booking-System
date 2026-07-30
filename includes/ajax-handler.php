/**
 * Snippet Kode WPCode / Code Snippets - AJAX Handler Submit Booking (Langkah 5)
 * 
 * Menangkap submit formulir pemesanan travel dan menyimpan data ke tabel wp_booking_trip_bookings.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Keamanan dari akses langsung
}

function ajax_submit_booking_handler() {
    global $wpdb;

    // 1. Ambil dan bersihkan data input dari POST request
    $trip_id        = isset( $_POST['trip_id'] ) ? intval( $_POST['trip_id'] ) : 0;
    $booking_date   = isset( $_POST['booking_date'] ) ? sanitize_text_field( $_POST['booking_date'] ) : '';
    $seat_number    = isset( $_POST['seat_number'] ) ? intval( $_POST['seat_number'] ) : 0;
    $customer_name  = isset( $_POST['customer_name'] ) ? sanitize_text_field( $_POST['customer_name'] ) : '';
    $customer_phone = isset( $_POST['customer_phone'] ) ? sanitize_text_field( $_POST['customer_phone'] ) : '';

    // 2. Validasi Kelengkapan Field Data
    if ( ! $trip_id || empty( $booking_date ) || ! $seat_number || empty( $customer_name ) || empty( $customer_phone ) ) {
        wp_send_json_error( array(
            'message' => 'Semua kolom formulir (Tanggal, Nomor Kursi, Nama, dan No HP) wajib diisi!'
        ) );
    }

    // 3. Menentukan Nama Tabel (Prefix WP: wp_booking_trip_bookings atau wp_trip_bookings)
    $table_name = $wpdb->prefix . 'booking_trip_bookings';
    if ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table_name ) ) !== $table_name ) {
        $table_name = $wpdb->prefix . 'trip_bookings';
    }

    // 4. Validasi Ganda (Mencegah Bentrok / Double Booking pada detik yang sama)
    $is_booked = $wpdb->get_var( $wpdb->prepare(
        "SELECT COUNT(*) FROM {$table_name} WHERE trip_id = %d AND booking_date = %s AND seat_number = %d",
        $trip_id,
        $booking_date,
        $seat_number
    ) );

    if ( $is_booked > 0 ) {
        wp_send_json_error( array(
            'message' => 'Maaf! Nomor kursi ' . $seat_number . ' pada tanggal tersebut baru saja dipesan orang lain.'
        ) );
    }

    // 5. Insert Data ke Database dengan $wpdb->insert()
    $inserted = $wpdb->insert(
        $table_name,
        array(
            'trip_id'        => $trip_id,
            'booking_date'   => $booking_date,
            'seat_number'    => $seat_number,
            'customer_name'  => $customer_name,
            'customer_phone' => $customer_phone,
            'created_at'     => current_time( 'mysql' ),
        ),
        array(
            '%d', // trip_id
            '%s', // booking_date
            '%d', // seat_number
            '%s', // customer_name
            '%s', // customer_phone
            '%s', // created_at
        )
    );

    // 6. Cek Hasil Insert dan Kirim Respon JSON
    if ( $inserted ) {
        wp_send_json_success( array(
            'message' => 'Pemesanan Berhasil! Kursi nomor ' . $seat_number . ' telah dikunci untuk Anda.'
        ) );
    } else {
        wp_send_json_error( array(
            'message' => 'Terjadi kesalahan sistem saat menyimpan pemesanan ke database.'
        ) );
    }
}

// Hook AJAX untuk user login & non-login (publik)
add_action( 'wp_ajax_submit_booking', 'ajax_submit_booking_handler' );
add_action( 'wp_ajax_nopriv_submit_booking', 'ajax_submit_booking_handler' );
