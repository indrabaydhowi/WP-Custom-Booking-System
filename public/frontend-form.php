/**
 * Snippet Kode WPCode - Shortcode [form_booking_travel] (Lengkap dengan Styling CSS Modern)
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Keamanan dari akses langsung
}

function render_form_booking_travel_shortcode( $atts ) {
    // Ambil ID post destinasi saat ini
    $post_id = get_the_ID();
    if ( ! $post_id ) {
        return '<p>Shortcode ini harus diletakkan pada halaman Single Destinasi.</p>';
    }

    // Ambil meta kapasitas armada dari post destinasi ini
    $kapasitas_meta = get_post_meta( $post_id, '_destinasi_kapasitas_armada', true );

    // Ekstraksi angka kapasitas (default 14 jika meta belum diset)
    $max_seats = 14;
    if ( preg_match( '/\d+/', $kapasitas_meta, $matches ) ) {
        $max_seats = intval( $matches[0] );
    }

    // URL admin-ajax.php bawaan WordPress
    $ajax_url = admin_url( 'admin-ajax.php' );

    ob_start();
    ?>
    <!-- 1. CSS STYLING FORMULIR BOOKING -->
    <style>
    #booking-container,
    .travel-booking-form-wrapper {
        max-width: 560px;
        margin: 30px auto;
        padding: 32px 28px;
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.03);
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        color: #1e293b;
        box-sizing: border-box;
    }

    #booking-container h3,
    .travel-booking-form-wrapper h3 {
        margin-top: 0;
        margin-bottom: 8px;
        font-size: 1.5rem;
        font-weight: 700;
        color: #0f172a;
        letter-spacing: -0.02em;
    }

    .form-group,
    #booking-container > div,
    .travel-booking-form-wrapper form > div {
        margin-bottom: 22px;
    }

    #booking-container label,
    .travel-booking-form-wrapper label {
        display: block;
        margin-bottom: 8px;
        font-size: 0.95rem;
        font-weight: 600;
        color: #334155;
    }

    #booking-container input[type="text"],
    #booking-container input[type="date"],
    #booking-container input[type="tel"],
    #booking-container input[type="number"],
    .travel-booking-form-wrapper input[type="text"],
    .travel-booking-form-wrapper input[type="date"],
    .travel-booking-form-wrapper input[type="tel"],
    .travel-booking-form-wrapper input[type="number"] {
        width: 100%;
        padding: 14px 16px;
        font-size: 1rem;
        color: #0f172a;
        background-color: #f8fafc;
        border: 1.5px solid #cbd5e1;
        border-radius: 10px;
        box-sizing: border-box;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        outline: none;
        -webkit-appearance: none;
    }

    #booking-container input:hover,
    .travel-booking-form-wrapper input:hover {
        border-color: #94a3b8;
        background-color: #ffffff;
    }

    #booking-container input:focus,
    .travel-booking-form-wrapper input:focus {
        border-color: #2563eb;
        background-color: #ffffff;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
    }

    #peta-kursi,
    #seat_map_container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(52px, 1fr));
        gap: 12px;
        padding: 18px;
        background-color: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        box-sizing: border-box;
        min-height: 85px;
    }

    #peta-kursi button,
    #seat_map_container button,
    #peta-kursi .seat-btn,
    #seat_map_container .seat-btn {
        aspect-ratio: 1 / 1;
        min-height: 52px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.05rem;
        font-weight: 700;
        border-radius: 10px;
        border: 1.5px solid #cbd5e1;
        background-color: #ffffff;
        color: #334155;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        user-select: none;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }

    #peta-kursi button:not(:disabled):hover,
    #seat_map_container button:not(:disabled):hover {
        background-color: #eff6ff;
        border-color: #3b82f6;
        color: #2563eb;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.2);
    }

    /* Kursi Terpilih (Hijau) */
    #peta-kursi button.selected,
    #seat_map_container button.selected {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%) !important;
        color: #ffffff !important;
        border-color: #15803d !important;
        box-shadow: 0 4px 10px rgba(34, 197, 94, 0.35) !important;
    }

    /* Kursi Terisi / Disabled (Merah) */
    #peta-kursi button:disabled,
    #seat_map_container button:disabled {
        background-color: #ef4444 !important;
        color: #ffffff !important;
        border-color: #dc2626 !important;
        opacity: 0.85;
        cursor: not-allowed !important;
        transform: none !important;
        box-shadow: none !important;
    }

    #booking-container button[type="submit"],
    .travel-booking-form-wrapper button[type="submit"],
    #btn_submit_booking {
        width: 100%;
        padding: 15px 24px;
        margin-top: 10px;
        font-size: 1.05rem;
        font-weight: 700;
        color: #ffffff;
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        border: none;
        border-radius: 10px;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    #booking-container button[type="submit"]:not(:disabled):hover,
    .travel-booking-form-wrapper button[type="submit"]:not(:disabled):hover,
    #btn_submit_booking:not(:disabled):hover {
        background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
        box-shadow: 0 6px 16px rgba(37, 99, 235, 0.35);
        transform: translateY(-1px);
    }

    #booking-container button[type="submit"]:not(:disabled):active,
    .travel-booking-form-wrapper button[type="submit"]:not(:disabled):active,
    #btn_submit_booking:not(:disabled):active {
        transform: scale(0.98);
    }

    #booking-container button[type="submit"]:disabled,
    .travel-booking-form-wrapper button[type="submit"]:disabled,
    #btn_submit_booking:disabled {
        background: #cbd5e1 !important;
        color: #94a3b8 !important;
        box-shadow: none !important;
        cursor: not-allowed !important;
        transform: none !important;
    }

    @media (max-width: 480px) {
        #booking-container,
        .travel-booking-form-wrapper {
            padding: 24px 16px;
            border-radius: 12px;
            margin: 15px auto;
        }
        
        #peta-kursi,
        #seat_map_container {
            grid-template-columns: repeat(auto-fill, minmax(46px, 1fr));
            gap: 8px;
            padding: 12px;
        }
        
        #peta-kursi button,
        #seat_map_container button {
            min-height: 46px;
            font-size: 0.95rem;
        }
    }
    </style>

    <!-- 2. STRUKTUR HTML FORMULIR BOOKING -->
    <div class="travel-booking-form-wrapper">
        <h3>Formulir Pemesanan Tiket</h3>
        <p style="font-size: 0.9rem; color: #64748b; margin-bottom: 20px;">Armada: <strong><?php echo esc_html( $kapasitas_meta ? $kapasitas_meta : "Hiace ($max_seats Seat)" ); ?></strong></p>

        <form id="travel-booking-form" method="POST">
            <input type="hidden" name="trip_id" id="booking_trip_id" value="<?php echo esc_attr( $post_id ); ?>">
            <input type="hidden" name="seat_number" id="selected_seat_number" value="" required>

            <div class="form-group">
                <label for="booking_date">Pilih Tanggal Keberangkatan:</label>
                <input type="date" id="booking_date" name="booking_date" min="<?php echo date('Y-m-d'); ?>" required>
            </div>

            <div class="form-group">
                <label>Pilih Nomor Kursi:</label>
                
                <div id="seat-legend" style="display: flex; gap: 15px; margin-bottom: 12px; font-size: 0.85rem; color: #475569;">
                    <span style="display: flex; align-items: center; gap: 5px;"><span style="width: 14px; height: 14px; background: #e2e8f0; border: 1px solid #cbd5e1; border-radius: 3px;"></span> Tersedia</span>
                    <span style="display: flex; align-items: center; gap: 5px;"><span style="width: 14px; height: 14px; background: #22c55e; border-radius: 3px;"></span> Dipilih</span>
                    <span style="display: flex; align-items: center; gap: 5px;"><span style="width: 14px; height: 14px; background: #ef4444; border-radius: 3px;"></span> Terisi</span>
                </div>

                <div id="seat_map_container">
                    <p style="grid-column: 1 / -1; text-align: center; color: #94a3b8; margin: 10px 0;">Silakan pilih tanggal keberangkatan terlebih dahulu untuk melihat peta kursi.</p>
                </div>
                <div id="seat-selected-info" style="margin-top: 8px; font-weight: 600; color: #16a34a; font-size: 0.95rem;"></div>
            </div>

            <div class="form-group">
                <label for="customer_name">Nama Lengkap:</label>
                <input type="text" id="customer_name" name="customer_name" placeholder="Masukkan nama Anda" required>
            </div>

            <div class="form-group">
                <label for="customer_phone">Nomor WhatsApp / HP:</label>
                <input type="tel" id="customer_phone" name="customer_phone" placeholder="Contoh: 081234567890" required>
            </div>

            <button type="submit" id="btn_submit_booking" disabled>
                Pesan Sekarang
            </button>
            <div id="booking-form-message" style="margin-top: 15px; font-weight: 600; text-align: center;"></div>
        </form>
    </div>

    <!-- 3. SCRIPT VANILLA JS INTERAKTIF -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const ajaxurl           = '<?php echo esc_url( $ajax_url ); ?>';
        const tripId            = <?php echo intval( $post_id ); ?>;
        const maxSeats          = <?php echo intval( $max_seats ); ?>;
        
        const bookingForm       = document.getElementById('travel-booking-form');
        const bookingDateInput  = document.getElementById('booking_date');
        const seatMapContainer  = document.getElementById('seat_map_container');
        const selectedSeatInput = document.getElementById('selected_seat_number');
        const seatSelectedInfo  = document.getElementById('seat-selected-info');
        const submitBtn         = document.getElementById('btn_submit_booking');
        const messageBox        = document.getElementById('booking-form-message');

        if (!bookingDateInput || !seatMapContainer || !bookingForm) return;

        bookingDateInput.addEventListener('change', function() {
            const selectedDate = this.value;

            selectedSeatInput.value = '';
            seatSelectedInfo.textContent = '';
            messageBox.textContent = '';
            submitBtn.disabled = true;

            if (!selectedDate) {
                seatMapContainer.innerHTML = '<p style="grid-column: 1 / -1; text-align: center; color: #94a3b8;">Silakan pilih tanggal keberangkatan terlebih dahulu.</p>';
                return;
            }

            seatMapContainer.innerHTML = '<p style="grid-column: 1 / -1; text-align: center; color: #3b82f6;">Memuat peta kursi...</p>';

            const formData = new FormData();
            formData.append('action', 'cek_kursi_terisi');
            formData.append('trip_id', tripId);
            formData.append('booking_date', selectedDate);

            fetch(ajaxurl, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(res => {
                seatMapContainer.innerHTML = '';

                if (res.success) {
                    const occupiedSeats = res.data || [];

                    for (let i = 1; i <= maxSeats; i++) {
                        const seatBtn = document.createElement('button');
                        seatBtn.type = 'button';
                        seatBtn.className = 'seat-btn';
                        seatBtn.textContent = i;
                        seatBtn.setAttribute('data-seat', i);

                        const isOccupied = occupiedSeats.includes(i) || occupiedSeats.includes(i.toString());

                        if (isOccupied) {
                            seatBtn.disabled = true;
                            seatBtn.title = 'Kursi ' + i + ' sudah dipesan';
                        } else {
                            seatBtn.addEventListener('click', function() {
                                const allButtons = seatMapContainer.querySelectorAll('.seat-btn:not(:disabled)');
                                allButtons.forEach(btn => btn.classList.remove('selected'));

                                this.classList.add('selected');

                                selectedSeatInput.value = i;
                                seatSelectedInfo.textContent = 'Kursi Terpilih: Nomor ' + i;

                                submitBtn.disabled = false;
                            });
                        }

                        seatMapContainer.appendChild(seatBtn);
                    }
                } else {
                    seatMapContainer.innerHTML = '<p style="grid-column: 1 / -1; text-align: center; color: #ef4444;">Gagal memuat ketersediaan kursi.</p>';
                }
            })
            .catch(err => {
                console.error('AJAX Error:', err);
                seatMapContainer.innerHTML = '<p style="grid-column: 1 / -1; text-align: center; color: #ef4444;">Terjadi kesalahan jaringan saat mengecek kursi.</p>';
            });
        });

        bookingForm.addEventListener('submit', function(e) {
            e.preventDefault();

            if (!selectedSeatInput.value) {
                alert('Silakan pilih nomor kursi terlebih dahulu!');
                return;
            }

            submitBtn.disabled = true;
            submitBtn.textContent = 'Memproses Pemesanan...';

            const customerNameVal = document.getElementById('customer_name').value;
            const bookingDateVal  = document.getElementById('booking_date').value;
            const selectedSeatVal = selectedSeatInput.value;

            const submitData = new FormData(bookingForm);
            submitData.append('action', 'submit_booking');

            fetch(ajaxurl, {
                method: 'POST',
                body: submitData
            })
            .then(response => response.json())
            .then(res => {
                if (res.success) {
                    alert(res.data.message || 'Pemesanan Berhasil!');
                    
                    messageBox.style.color = '#16a34a';
                    messageBox.textContent = res.data.message || 'Pemesanan Berhasil!';

                    const adminPhone = '6281234567890'; // Ubah nomor WA di sini

                    const waMessageText = `Halo Admin, saya telah memesan tiket Trip. Berikut datanya:\n` +
                                          `Nama: ${customerNameVal}\n` +
                                          `Tanggal: ${bookingDateVal}\n` +
                                          `Nomor Kursi: ${selectedSeatVal}\n` +
                                          `Mohon info pembayarannya.`;

                    const encodedText = encodeURIComponent(waMessageText);
                    const waUrl       = `https://wa.me/${adminPhone}?text=${encodedText}`;

                    window.open(waUrl, '_blank');

                    document.getElementById('customer_name').value = '';
                    document.getElementById('customer_phone').value = '';

                    bookingDateInput.dispatchEvent(new Event('change'));
                } else {
                    alert(res.data.message || 'Gagal menyimpan pemesanan.');
                    messageBox.style.color = '#ef4444';
                    messageBox.textContent = res.data.message || 'Gagal menyimpan pemesanan.';
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Pesan Sekarang';
                }
            })
            .catch(err => {
                console.error('Submit Error:', err);
                alert('Terjadi kesalahan sistem saat mengirim pemesanan.');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Pesan Sekarang';
            });
        });
    });
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode( 'form_booking_travel', 'render_form_booking_travel_shortcode' );
