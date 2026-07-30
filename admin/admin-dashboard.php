/**
 * Snippet Kode WPCode - Data Pesanan & Fitur Hapus Pesanan AJAX
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// 1. AJAX Handler Backend Hapus Pesanan
function ajax_hapus_pesanan_trip_handler() {
    global $wpdb;

    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'message' => 'Akses ditolak.' ) );
    }

    $id_booking = isset( $_POST['id_booking'] ) ? intval( $_POST['id_booking'] ) : 0;

    if ( ! $id_booking ) {
        wp_send_json_error( array( 'message' => 'ID Booking tidak valid.' ) );
    }

    $table_name = $wpdb->prefix . 'booking_trip_bookings';
    if ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table_name ) ) !== $table_name ) {
        $table_name = $wpdb->prefix . 'trip_bookings';
    }

    $deleted = $wpdb->delete(
        $table_name,
        array( 'id' => $id_booking ),
        array( '%d' )
    );

    if ( false !== $deleted ) {
        wp_send_json_success( array( 'message' => 'Pesanan berhasil dihapus.' ) );
    } else {
        wp_send_json_error( array( 'message' => 'Gagal menghapus pesanan.' ) );
    }
}
add_action( 'wp_ajax_hapus_pesanan_trip', 'ajax_hapus_pesanan_trip_handler' );


// 2. Mendaftarkan Submenu "Data Pesanan" di Dashboard Admin
function register_booking_admin_menu() {
    add_submenu_page(
        'edit.php?post_type=destinasi',
        'Daftar Pesanan Travel',
        'Data Pesanan',
        'manage_options',
        'data-pesanan-travel',
        'render_data_pesanan_admin_page'
    );
}
add_action( 'admin_menu', 'register_booking_admin_menu' );


// 3. Tampilan Tabel Admin + Script Vanilla JS Hapus
function render_data_pesanan_admin_page() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'booking_trip_bookings';
    if ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table_name ) ) !== $table_name ) {
        $table_name = $wpdb->prefix . 'trip_bookings';
    }

    $results  = $wpdb->get_results( "SELECT * FROM {$table_name} ORDER BY id DESC" );
    $ajax_url = admin_url( 'admin-ajax.php' );
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline">Daftar Pesanan Travel</h1>
        <hr class="wp-header-end">

        <p>Berikut adalah seluruh data pemesanan tiket travel yang masuk melalui sistem booking:</p>

        <table class="wp-list-table widefat fixed striped table-view-list" style="margin-top: 15px;">
            <thead>
                <tr>
                    <th scope="col" style="width: 50px;">ID</th>
                    <th scope="col">Destinasi (Trip)</th>
                    <th scope="col">Tanggal Keberangkatan</th>
                    <th scope="col" style="width: 100px;">No. Kursi</th>
                    <th scope="col">Nama Pemesan</th>
                    <th scope="col">No. WhatsApp / HP</th>
                    <th scope="col">Waktu Pemesanan</th>
                    <th scope="col" style="width: 90px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ( ! empty( $results ) ) : ?>
                    <?php foreach ( $results as $row ) : ?>
                        <?php 
                            $trip_title = get_the_title( $row->trip_id );
                            if ( ! $trip_title ) {
                                $trip_title = 'ID Destinasi #' . $row->trip_id;
                            }
                        ?>
                        <tr id="booking-row-<?php echo esc_attr( $row->id ); ?>">
                            <td><strong>#<?php echo esc_html( $row->id ); ?></strong></td>
                            <td>
                                <strong>
                                    <a href="<?php echo esc_url( get_edit_post_link( $row->trip_id ) ); ?>">
                                        <?php echo esc_html( $trip_title ); ?>
                                    </a>
                                </strong>
                            </td>
                            <td>
                                <span style="background: #e0f2fe; color: #0369a1; padding: 4px 8px; border-radius: 4px; font-weight: 600;">
                                    <?php echo esc_html( date( 'd M Y', strtotime( $row->booking_date ) ) ); ?>
                                </span>
                            </td>
                            <td>
                                <span style="background: #dcfce7; color: #15803d; padding: 4px 10px; border-radius: 4px; font-weight: bold;">
                                    Kursi <?php echo esc_html( $row->seat_number ); ?>
                                </span>
                            </td>
                            <td><?php echo esc_html( $row->customer_name ); ?></td>
                            <td>
                                <a href="https://wa.me/<?php echo esc_attr( preg_replace( '/[^0-9]/', '', $row->customer_phone ) ); ?>" target="_blank" style="color: #25d366; font-weight: 600; text-decoration: none;">
                                    📱 <?php echo esc_html( $row->customer_phone ); ?>
                                </a>
                            </td>
                            <td><?php echo esc_html( date( 'd/m/Y H:i', strtotime( $row->created_at ) ) ); ?></td>
                            <td style="text-align: center;">
                                <button type="button" class="button button-small btn-hapus-booking" data-id="<?php echo esc_attr( $row->id ); ?>" style="color: #ef4444; border-color: #ef4444; background: #fff;">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 20px; color: #64748b;">
                            <em>Belum ada data pesanan yang masuk.</em>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const ajaxurl = '<?php echo esc_url( $ajax_url ); ?>';
        const deleteButtons = document.querySelectorAll('.btn-hapus-booking');

        deleteButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                const bookingId = this.getAttribute('data-id');

                if (confirm('Yakin ingin menghapus data pesanan #' + bookingId + '?')) {
                    const originalText = this.textContent;
                    this.textContent = '...';
                    this.disabled = true;

                    const formData = new FormData();
                    formData.append('action', 'hapus_pesanan_trip');
                    formData.append('id_booking', bookingId);

                    fetch(ajaxurl, {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(res => {
                        if (res.success) {
                            location.reload();
                        } else {
                            alert(res.data.message || 'Gagal menghapus pesanan.');
                            button.textContent = originalText;
                            button.disabled = false;
                        }
                    })
                    .catch(err => {
                        console.error('Delete Error:', err);
                        alert('Terjadi kesalahan jaringan saat menghapus.');
                        button.textContent = originalText;
                        button.disabled = false;
                    });
                }
            });
        });
    });
    </script>
    <?php
}
