/**
 * Snippet Kode WPCode / Code Snippets - Tahap 2
 * 
 * 1. Pendaftaran Custom Post Type (CPT) 'Destinasi' (slug: destinasi)
 * 2. Pembuatan Meta Box 'Kapasitas Armada' (Dropdown)
 * 3. Penyimpanan Data Meta Box (save_post)
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Keamanan dari akses langsung
}

// -------------------------------------------------------------------------
// 1. Mendaftarkan Custom Post Type (CPT) 'Destinasi'
// -------------------------------------------------------------------------
function register_destinasi_cpt() {
    $labels = array(
        'name'               => 'Destinasi',
        'singular_name'      => 'Destinasi',
        'menu_name'          => 'Destinasi',
        'add_new'            => 'Tambah Destinasi Baru',
        'add_new_item'       => 'Tambah Destinasi Baru',
        'edit_item'          => 'Edit Destinasi',
        'new_item'           => 'Destinasi Baru',
        'view_item'          => 'Lihat Destinasi',
        'search_items'       => 'Cari Destinasi',
        'not_found'          => 'Tidak ada destinasi ditemukan',
        'not_found_in_trash' => 'Tidak ada destinasi di tempat sampah',
    );

    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'has_archive'         => true,
        'publicly_queryable'  => true,
        'query_var'           => true,
        'rewrite'             => array( 'slug' => 'destinasi' ),
        'capability_type'     => 'post',
        'hierarchical'        => false,
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-bus',
        'supports'            => array( 'title', 'editor', 'thumbnail' ), // Judul, Editor Konten, dan Gambar Utama
        'show_in_rest'        => true, // Mendukung Gutenberg Block Editor
    );

    register_post_type( 'destinasi', $args );
}
add_action( 'init', 'register_destinasi_cpt' );


// -------------------------------------------------------------------------
// 2. Menambahkan Meta Box 'Detail Armada Destinasi' pada CPT Destinasi
// -------------------------------------------------------------------------
function add_destinasi_meta_boxes() {
    add_meta_box(
        'destinasi_details_meta_box',          // ID unik Meta Box
        'Detail Armada Destinasi',             // Judul Meta Box yang tampil di Editor
        'render_destinasi_meta_box_callback',  // Callback Tampilan HTML
        'destinasi',                           // Nama CPT (Destinasi)
        'normal',                              // Posisi (normal / utama)
        'high'                                 // Prioritas urutan tampilan
    );
}
add_action( 'add_meta_boxes', 'add_destinasi_meta_boxes' );


// -------------------------------------------------------------------------
// 3. Callback Form Meta Box (Dropdown Kapasitas Armada)
// -------------------------------------------------------------------------
function render_destinasi_meta_box_callback( $post ) {
    // Nonce field untuk verifikasi keamanan saat submit
    wp_nonce_field( 'save_destinasi_meta_box_data', 'destinasi_meta_box_nonce' );

    // Ambil nilai meta box yang tersimpan sebelumnya
    $current_kapasitas = get_post_meta( $post->ID, '_destinasi_kapasitas_armada', true );

    // Opsi Pilihan Armada
    $options = array(
        'Hiace (14 Seat)'      => 'Hiace (14 Seat)',
        'Bus Medium (35 Seat)' => 'Bus Medium (35 Seat)',
        'Big Bus (45 Seat)'    => 'Big Bus (45 Seat)',
    );

    ?>
    <div style="padding: 10px 0;">
        <p>
            <label for="destinasi_kapasitas_armada" style="font-weight: bold; display: block; margin-bottom: 5px;">
                Kapasitas Armada:
            </label>
            <select name="destinasi_kapasitas_armada" id="destinasi_kapasitas_armada" style="width: 100%; max-width: 400px; padding: 6px;">
                <option value="">-- Pilih Kapasitas Armada --</option>
                <?php foreach ( $options as $value => $label ) : ?>
                    <option value="<?php echo esc_attr( $value ); ?>" <?php selected( $current_kapasitas, $value ); ?>>
                        <?php echo esc_html( $label ); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
    </div>
    <?php
}


// -------------------------------------------------------------------------
// 4. Menyimpan Data Meta Box Saat Post Disimpan (save_post_destinasi)
// -------------------------------------------------------------------------
function save_destinasi_meta_box_data( $post_id ) {
    // 1. Verifikasi Keamanan Nonce
    if ( ! isset( $_POST['destinasi_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['destinasi_meta_box_nonce'], 'save_destinasi_meta_box_data' ) ) {
        return;
    }

    // 2. Cegah eksekusi saat Autosave WordPress
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // 3. Verifikasi Hak Akses User untuk Edit Post Ini
    if ( isset( $_POST['post_type'] ) && 'destinasi' === $_POST['post_type'] ) {
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }

    // 4. Simpan / Update Post Meta dengan Sanitasi
    if ( isset( $_POST['destinasi_kapasitas_armada'] ) ) {
        $sanitized_value = sanitize_text_field( $_POST['destinasi_kapasitas_armada'] );
        update_post_meta( $post_id, '_destinasi_kapasitas_armada', $sanitized_value );
    }
}
add_action( 'save_post_destinasi', 'save_destinasi_meta_box_data' );
