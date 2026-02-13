<!doctype html>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Pembayaran Booking Futsal</title>
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Midtrans Sandbox -->
  <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
  </script>
  <!-- Production: src="https://app.midtrans.com/snap/snap.js" -->
</head>

<body class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 min-h-screen">

  <!-- Booking History Section (untuk user yang sudah login) -->
  @auth
    @if($allBookings && $allBookings->count() > 0)
      @include('payment.invoice-list', ['allBookings' => $allBookings])
    @endif
  @endauth

  <!-- Payment Modal (Professional Redesign) -->
  <div id="paymentModal" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl">

      <!-- Header -->
      <div class="sticky top-0 bg-slate-900 px-6 py-5 flex justify-between items-center rounded-t-2xl">
        <h2 class="text-xl font-bold text-white">Pembayaran Booking Futsal</h2>
        <button onclick="closePaymentModal()" class="text-white/70 hover:text-white text-xl">
          ✕
        </button>
      </div>

      <!-- Body -->
      <div class="p-6 space-y-6">

        <!-- Booking Info -->
        <div class="border border-slate-200 rounded-xl p-5">
          <h3 class="font-semibold text-slate-900 mb-4">Detail Booking</h3>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
              <p class="text-slate-500">Nama Lapangan</p>
              <p id="modal-lapangan" class="font-semibold text-slate-900">-</p>
            </div>
            <div>
              <p class="text-slate-500">Order ID</p>
              <p id="modal-order-id" class="font-mono text-xs text-slate-700">-</p>
            </div>
            <div>
              <p class="text-slate-500">Tanggal</p>
              <p id="modal-tanggal" class="font-semibold text-slate-900">-</p>
            </div>
            <div>
              <p class="text-slate-500">Waktu</p>
              <p id="modal-waktu" class="font-semibold text-slate-900">-</p>
            </div>
          </div>
        </div>

        <!-- Price Summary -->
        <div class="bg-slate-100 rounded-xl p-5 border border-slate-200">
          <div class="flex justify-between text-sm mb-2">
            <span class="text-slate-600">Harga per jam</span>
            <span id="modal-harga-per-jam" class="font-semibold">-</span>
          </div>
          <div class="flex justify-between text-sm mb-4">
            <span class="text-slate-600">Durasi</span>
            <span id="modal-durasi" class="font-semibold">-</span>
          </div>

          <div class="border-t pt-4 flex justify-between items-center">
            <span class="text-lg font-semibold text-slate-900">Total</span>
            <span id="modal-total" class="text-2xl font-extrabold text-emerald-600">-</span>
          </div>
        </div>

        <!-- Hidden Inputs -->
        <input type="hidden" id="modal-booking-data">
        <input type="hidden" id="modal-order-id-value">
        <input type="hidden" id="modal-total-value">
      </div>

      <!-- Footer -->
      <div class="sticky bottom-0 bg-slate-50 border-t px-6 py-4 flex gap-3 rounded-b-2xl">
        <button onclick="closePaymentModal()"
          class="flex-1 py-3 rounded-lg border border-slate-300 font-semibold text-slate-700 hover:bg-slate-100">
          Batal
        </button>

        <button id="modal-pay-button"
          class="flex-1 py-3 rounded-lg bg-emerald-600 text-white font-semibold hover:bg-emerald-700 shadow-md">
          🔒 Lanjut ke Pembayaran
        </button>
      </div>

    </div>
  </div>

  <script>
    // Wait for Snap script to be ready
    function waitForSnap() {
      return new Promise((resolve) => {
        if (window.snap) {
          resolve();
        } else {
          const checkSnap = setInterval(() => {
            if (window.snap) {
              clearInterval(checkSnap);
              resolve();
            }
          }, 100);
          // Timeout after 5 seconds
          setTimeout(() => {
            clearInterval(checkSnap);
            resolve(); // Resolve anyway so we can show error
          }, 5000);
        }
      });
    }

    // Auto-open payment modal when page loads with booking data
    document.addEventListener('DOMContentLoaded', function() {
      @if($bookingData)
        const bookingData = @json($bookingData);
        if (bookingData && bookingData.order_id) {
          console.log('Auto-opening payment modal with booking data:', bookingData);
          openPaymentModal(bookingData);
        }
      @endif
    });

    // Function untuk buka payment modal
    function openPaymentModal(bookingData) {
      const modal = document.getElementById('paymentModal');

      // Gunakan order_id yang sudah ada dari booking (jangan generate ulang)
      const orderId = bookingData.order_id;

      // Set data ke modal
      document.getElementById('modal-lapangan').textContent = bookingData.lapangan || '-';
      document.getElementById('modal-order-id').textContent = orderId;
      document.getElementById('modal-tanggal').textContent = new Date(bookingData.tanggal).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
      document.getElementById('modal-waktu').textContent = `${bookingData.jam_mulai} - ${bookingData.jam_selesai} (${bookingData.durasi} jam)`;
      document.getElementById('modal-harga-per-jam').textContent = `Rp ${Number(bookingData.harga_per_jam).toLocaleString('id-ID')}`;
      document.getElementById('modal-durasi').textContent = `${bookingData.durasi} jam`;
      document.getElementById('modal-total').textContent = `Rp ${Number(bookingData.total_harga).toLocaleString('id-ID')}`;

      // Set hidden values (gunakan order_id yang sama, bukan yang baru)
      document.getElementById('modal-booking-data').value = JSON.stringify(bookingData);
      document.getElementById('modal-order-id-value').value = orderId;
      document.getElementById('modal-total-value').value = bookingData.total_harga;

      modal.classList.remove('hidden');
    }

    // Function untuk tutup payment modal
    function closePaymentModal() {
      const modal = document.getElementById('paymentModal');
      modal.classList.add('hidden');
    }

    // Handle modal pay button click
    document.getElementById('modal-pay-button').addEventListener('click', async () => {
      try {
        const payButton = document.getElementById('modal-pay-button');
        payButton.disabled = true;
        payButton.textContent = '⏳ Mempersiapkan pembayaran...';

        // Wait for Snap to be ready
        await waitForSnap();

        if (!window.snap) {
          console.error('Midtrans Snap script failed to load');
          alert('❌ Error: Midtrans Snap belum dimuat.\n\nSilakan:\n1. Refresh halaman\n2. Periksa koneksi internet\n3. Hubungi support jika masalah berlanjut');
          payButton.disabled = false;
          payButton.textContent = '🔒 Lanjut ke Pembayaran';
          return;
        }

        console.log('Snap is ready:', !!window.snap);

        const baseOrderId = document.getElementById('modal-order-id-value').value;
        const totalHarga = parseInt(document.getElementById('modal-total-value').value) || 0;
        const bookingDataRaw = JSON.parse(document.getElementById('modal-booking-data').value);

        // Generate unique order_id untuk setiap attempt (Midtrans tidak boleh duplicate order_id)
        // Format: BOOKING-lapangan_id-timestamp-random
        const timestamp = Date.now();
        const randomSuffix = Math.random().toString(36).substr(2, 9);
        const uniqueOrderId = `${baseOrderId}-${timestamp}-${randomSuffix}`;

        console.log('Base Order ID:', baseOrderId);
        console.log('Unique Order ID:', uniqueOrderId);
        console.log('Total Harga:', totalHarga);

        if (!baseOrderId || totalHarga === 0) {
          alert('❌ Data tidak lengkap. Silakan coba lagi.');
          payButton.disabled = false;
          payButton.textContent = '🔒 Lanjut ke Pembayaran';
          return;
        }

        payButton.textContent = '⏳ Menghubungi Midtrans...';

        // Request token dari server dengan unique order_id
        console.log('Requesting token from API...');
        const res = await fetch("/api/payment-token", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify({
            order_id: uniqueOrderId,  // Kirim unique order_id
            base_order_id: baseOrderId,  // Kirim base order_id untuk tracking
            gross_amount: totalHarga,
            booking_data: bookingDataRaw
          })
        });

        console.log('API Response Status:', res.status);

        if (!res.ok) {
          const errorData = await res.json().catch(() => ({}));
          const errorMsg = errorData.error || `HTTP Error ${res.status}: Gagal membuat token pembayaran`;
          console.error('❌ Token API Error:', errorMsg, errorData);
          alert('❌ Gagal mendapatkan token pembayaran:\n\n' + errorMsg + '\n\nCek:\n- Koneksi internet\n- Kredensial Midtrans di .env\n- Server logs');
          payButton.disabled = false;
          payButton.textContent = '🔒 Lanjut ke Pembayaran';
          return;
        }

        const data = await res.json();
        console.log('Token response:', !!data.token);

        if (!data.token) {
          const errorMsg = data.error || 'Gagal membuat token pembayaran. Silakan coba lagi.';
          console.error('❌ No token in response:', data);
          alert('❌ Error: ' + errorMsg + '\n\nSilakan:\n- Refresh halaman dan coba lagi\n- Hubungi support jika masalah berlanjut');
          payButton.disabled = false;
          payButton.textContent = '🔒 Lanjut ke Pembayaran';
          return;
        }

        payButton.textContent = '⏳ Membuka payment gateway...';
        console.log('Calling snap.pay with token...');

        // Buka Midtrans Snap dengan unique order_id
        window.snap.pay(data.token, {
          onSuccess: function (result) {
            console.log('✅ Pembayaran sukses:', result);
            payButton.textContent = '✅ Pembayaran Berhasil!';
            
            // Kirim verified transaction ke server untuk double-check
            fetch('/midtrans/verify-payment', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
              },
              body: JSON.stringify({
                order_id: uniqueOrderId,  // Unique order_id dari attempt ini
                base_order_id: baseOrderId,  // Base order_id untuk link ke booking
                transaction_id: result.transaction_id,
                transaction_status: result.transaction_status
              })
            }).then(res => res.json())
              .then(data => {
                console.log('✅ Verification response:', data);
                // Tunggu sebentar kemudian redirect ke success page
                setTimeout(() => {
                  window.location.href = '/payment/success?order_id=' + baseOrderId;
                }, 1500);
              })
              .catch(err => {
                console.error('⚠️ Verification error (tetap lanjut):', err);
                // Tetap redirect meski verify gagal, Midtrans webhook akan handle
                setTimeout(() => {
                  window.location.href = '/payment/success?order_id=' + baseOrderId;
                }, 1500);
              });
          },
          onPending: function (result) {
            console.log('⏳ Pembayaran sedang diproses:', result);
            payButton.disabled = true;
            payButton.textContent = '⏳ Pembayaran sedang diproses...';
          },
          onError: function (result) {
            console.error('❌ Pembayaran error:', result);
            alert('❌ Pembayaran gagal:\n\n' + (result.status_message || 'Terjadi kesalahan. Silakan coba lagi.'));
            payButton.disabled = false;
            payButton.textContent = '🔒 Lanjut ke Pembayaran';
          },
          onClose: function () {
            console.log('ℹ️ Popup ditutup oleh user');
            payButton.disabled = false;
            payButton.textContent = '🔒 Lanjut ke Pembayaran';
            
            // Tanyakan kepada user apakah ingin retry atau cancel
            const userChoice = confirm('Pembayaran dibatalkan. Klik OK untuk mencoba lagi atau Cancel untuk keluar.');
            if (!userChoice) {
              closePaymentModal();
            }
          }
        });
      } catch (error) {
        console.error('❌ Payment Error:', error);
        console.error('Error Details:', error.message, error.stack);
        alert('❌ Terjadi kesalahan:\n\n' + error.message + '\n\nCek browser console (F12) untuk detail error.');
        const payButton = document.getElementById('modal-pay-button');
        payButton.disabled = false;
        payButton.textContent = '🔒 Lanjut ke Pembayaran';
      }
    });
  </script>
</body>

</html>