@extends('layout.layout')
@section("content")

<div class="min-h-screen bg-gray-50 py-5 px-5">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-5xl font-bold text-gray-900 mb-2 tracking-tight">Pesan Lapangan Futsal</h1>
            <p class="text-lg text-gray-600 font-normal">Pilih lapangan terbaik dan pesan dengan mudah</p>
        </div>

        <!-- Error Alert -->
        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-900 text-red-900 px-4 py-3.5 rounded-md mb-6 text-sm font-medium">
                <strong>❌ Ada kesalahan pada form Anda</strong>
                <ul class="mt-2 ml-4 list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Success Alert -->
        @if (session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-800 px-4 py-3.5 rounded-md mb-6 text-sm font-medium">
                ✅ {{ session('success') }}
            </div>
        @endif

        <!-- Info Alert -->
        @if (session('info'))
            <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-800 px-4 py-3.5 rounded-md mb-6 text-sm font-medium">
                ℹ️ {{ session('info') }}
            </div>
        @endif

        <!-- Error Alert (dari Blade) -->
        @if (session('error'))
            <div class="bg-red-50 border-l-4 border-red-900 text-red-900 px-4 py-3.5 rounded-md mb-6 text-sm font-medium">
                <strong>❌ Kesalahan</strong>
                <p class="mt-1">{{ session('error') }}</p>
            </div>
        @endif

        <form action="{{ route('boking.store') }}" method="POST" id="bookingForm">
            @csrf

            <!-- Main Grid Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-7 mb-10">
                
                <!-- Form Section - 1 Column -->
                <div class="lg:col-span-1 bg-white rounded-xl p-8 shadow-md border border-gray-200 hover:shadow-lg transition-shadow duration-300">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6 pb-4 border-b-2 border-gray-100 flex items-center gap-2.5">
                        Detail Pemesanan
                    </h2>

                    <!-- Region Info -->
                    <div class="mb-5 p-4 bg-blue-50 border-2 border-blue-200 rounded-lg">
                        <p class="text-sm text-gray-600 font-medium">Region Pemesanan</p>
                        <p class="text-lg font-bold text-blue-600 capitalize">
                            @if($region === 'bypass')
                                Bypass
                            @elseif($region === 'ulakkarang')
                                Ulak Karang
                            @elseif($region === 'batusangkar')
                                Batu Sangkar
                            @endif
                        </p>
                    </div>

                    <!-- Nama Pemesan -->
                    <div class="mb-5">
                        <label for="nama" class="block mb-2 text-gray-900 font-semibold text-sm">Nama Pemesan</label>
                        <input type="text" id="nama" name="nama" 
                            value="{{ Auth::check() ? Auth::user()->name : old('nama') }}"
                            placeholder="Masukkan nama lengkap Anda"
                            class="w-full px-3.5 py-2.5 border-2 border-gray-300 rounded-lg text-base transition-all duration-200 bg-gray-50 focus:border-blue-600 focus:bg-white focus:ring-3 focus:ring-blue-600/10 focus:outline-none"
                            required>
                        <div class="bg-blue-50 border-l-4 border-blue-600 px-3 py-2 rounded text-xs text-blue-900 font-medium mt-1.5">
                            ✓ Nama akan ditampilkan pada booking
                        </div>
                    </div>

                    <!-- Pilih Lapangan -->
                    <div class="mb-5">
                        <label for="lapangan_id" class="block mb-2 text-gray-900 font-semibold text-sm">Pilih Lapangan</label>
                        <select id="lapangan_id" name="lapangan_id" 
                            class="w-full px-3.5 py-2.5 border-2 border-gray-300 rounded-lg text-base transition-all duration-200 bg-gray-50 focus:border-blue-600 focus:bg-white focus:ring-3 focus:ring-blue-600/10 focus:outline-none"
                            required onchange="handleLapanganChange()">
                            <option value="">-- Pilih Lapangan --</option>
                            @foreach($lapangan as $item)
                                <option value="{{ $item->id }}" data-nama="{{ $item->namaLapangan }}" data-harga="{{ $item->harga }}"
                                    @if($selectedLapanganId && $selectedLapanganId == $item->id) selected @endif>
                                    {{ $item->namaLapangan }} - Rp {{ number_format($item->harga, 0, ',', '.') }}/jam
                                </option>
                            @endforeach
                        </select>
                        <div class="bg-blue-50 border-l-4 border-blue-600 px-3 py-2 rounded text-xs text-blue-900 font-medium mt-1.5">
                            Harga per jam sudah termasuk pajak
                        </div>
                    </div>

                    <!-- Hidden Inputs -->
                    <input type="hidden" id="lapangan" name="lapangan" value="">
                    <input type="hidden" id="region" name="region" value="{{ $region }}">
                    <input type="hidden" id="tanggal" name="tanggal" required>

                    <!-- Jam Mulai & Selesai -->
                    <div class="grid grid-cols-2 gap-3 mb-5">
                        <div>
                            <label for="jam_mulai" class="block mb-2 text-gray-900 font-semibold text-sm">Jam Mulai</label>
                            <input type="time" id="jam_mulai" name="jam_mulai" readonly
                                class="w-full px-3.5 py-2.5 border-2 border-gray-300 rounded-lg text-base transition-all duration-200 bg-gray-50 focus:border-blue-600 focus:bg-white focus:ring-3 focus:ring-blue-600/10 focus:outline-none"
                                required>
                        </div>
                        <div>
                            <label for="jam_selesai" class="block mb-2 text-gray-900 font-semibold text-sm">Jam Selesai</label>
                            <input type="time" id="jam_selesai" name="jam_selesai" readonly
                                class="w-full px-3.5 py-2.5 border-2 border-gray-300 rounded-lg text-base transition-all duration-200 bg-gray-50 focus:border-blue-600 focus:bg-white focus:ring-3 focus:ring-blue-600/10 focus:outline-none"
                                required>
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div class="mb-0">
                        <label for="catatan" class="block mb-2 text-gray-900 font-semibold text-sm">Catatan Tambahan (Opsional)</label>
                        <textarea id="catatan" name="catatan" 
                            placeholder="Contoh: butuh kursi tambahan, ada acara khusus, dll"
                            class="w-full px-3.5 py-2.5 border-2 border-gray-300 rounded-lg text-base transition-all duration-200 bg-gray-50 focus:border-blue-600 focus:bg-white focus:ring-3 focus:ring-blue-600/10 focus:outline-none resize-none"
                            rows="4"></textarea>
                        <div class="bg-blue-50 border-l-4 border-blue-600 px-3 py-2 rounded text-xs text-blue-900 font-medium mt-1.5">
                            Catatan akan membantu kami melayani Anda lebih baik
                        </div>
                    </div>
                </div>

                <!-- Calendar & Time Slots Section - 3 Columns -->
                <div class="lg:col-span-3 space-y-7">
                    
                    <!-- Calendar Card -->
                    <div class="bg-white rounded-xl p-8 shadow-md border border-gray-200 hover:shadow-lg transition-shadow duration-300">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6 pb-4 border-b-2 border-gray-100 flex items-center gap-2.5">
                            Pilih Tanggal
                        </h2>

                        <div class="flex justify-between items-center mb-6 gap-3">
                            <h3 class="text-base font-semibold text-gray-900 mr-auto">Pilih Tanggal Pemesanan</h3>
                            <div class="flex gap-2">
                                <button type="button" onclick="previousMonth()"
                                    class="px-3.5 py-2 bg-gray-100 text-gray-900 border-2 border-gray-300 rounded-lg cursor-pointer text-sm font-medium transition-all duration-200 hover:bg-gray-200 hover:border-blue-600 hover:text-blue-600">
                                    ← Sebelumnya
                                </button>
                                <button type="button" onclick="nextMonth()"
                                    class="px-3.5 py-2 bg-gray-100 text-gray-900 border-2 border-gray-300 rounded-lg cursor-pointer text-sm font-medium transition-all duration-200 hover:bg-gray-200 hover:border-blue-600 hover:text-blue-600">
                                    Selanjutnya →
                                </button>
                            </div>
                        </div>

                        <div id="calendarContainer">
                            <div class="text-center py-6 text-gray-400 text-sm">Memuat kalender...</div>
                        </div>
                    </div>

                    <!-- Time Slots Card -->
                    <div class="bg-white rounded-xl p-8 shadow-md border border-gray-200 hover:shadow-lg transition-shadow duration-300 hidden" id="timeSlotsFullSection">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6 pb-4 border-b-2 border-gray-100 flex items-center gap-2.5">
                            Pilih Waktu
                        </h2>

                        <div class="mt-7 pt-6 border-t-2 border-gray-100">
                            <div class="text-gray-900 font-semibold mb-4 text-base flex items-center gap-2">Jam Mulai Pemesanan</div>
                            <div class="loading active text-center py-6 text-blue-600 text-sm font-medium" id="timeSlotsLoading">Memuat jam tersedia...</div>
                            <div id="timeSlotsContainer"></div>
                            <div id="timeRangeInfo"></div>
                        </div>
                    </div>

                </div>

            </div>

            <!-- Summary Section -->
            <div class="bg-white rounded-xl p-8 shadow-md border border-gray-200 hover:shadow-lg transition-shadow duration-300">
                <h2 class="text-xl font-semibold text-gray-900 mb-6 pb-4 border-b-2 border-gray-100 flex items-center gap-2.5">
                    Ringkasan Pemesanan
                </h2>
                
                <div class="grid grid-cols-2 md:grid-cols-5 gap-6 mb-7">
                    <div class="flex flex-col gap-1.5">
                        <span class="text-xs text-gray-600 font-semibold uppercase tracking-wide">Lapangan</span>
                        <strong id="summaryLapangan" class="text-gray-900 text-base font-semibold">-</strong>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <span class="text-xs text-gray-600 font-semibold uppercase tracking-wide">Tanggal</span>
                        <strong id="summaryTanggal" class="text-gray-900 text-base font-semibold">-</strong>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <span class="text-xs text-gray-600 font-semibold uppercase tracking-wide">Waktu</span>
                        <strong id="summaryJam" class="text-gray-900 text-base font-semibold">-</strong>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <span class="text-xs text-gray-600 font-semibold uppercase tracking-wide">Durasi</span>
                        <strong id="summaryDurasi" class="text-gray-900 text-base font-semibold">-</strong>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <span class="text-xs text-gray-600 font-semibold uppercase tracking-wide">Harga per Jam</span>
                        <strong id="summaryHarga" class="text-gray-900 text-base font-semibold">-</strong>
                    </div>
                </div>

                <div class="flex justify-between items-center px-5 py-5 bg-gray-50 rounded-lg border-2 border-gray-200 mb-7">
                    <span class="text-sm text-gray-600 font-semibold">Total Pembayaran</span>
                    <span id="summaryTotal" class="text-blue-600 text-2xl font-bold">Rp 0</span>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="resetForm()"
                        class="flex-1 px-6 py-3 bg-gray-100 text-gray-900 border-2 border-gray-300 rounded-lg text-sm font-semibold cursor-pointer transition-all duration-200 uppercase tracking-wide hover:bg-gray-200 hover:border-blue-600 hover:text-blue-600">
                        Reset Form
                    </button>
                    <button type="submit" id="submitBtn" disabled
                        class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg text-sm font-semibold cursor-pointer transition-all duration-200 uppercase tracking-wide hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-600/30 hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                        Lanjut ke Pembayaran
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>

<script>
    // CSS untuk spinner loading
    const style = document.createElement('style');
    style.textContent = `
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        .loading {
            display: none;
            text-align: center;
            padding: 24px;
            color: #1a73e8;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .loading.active {
            display: block;
        }

        .loading::after {
            content: '';
            display: inline-block;
            animation: spin 1s linear infinite;
            border: 2.5px solid #e8e8e8;
            border-top-color: #1a73e8;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            margin-left: 8px;
            vertical-align: middle;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 6px;
            margin-bottom: 0;
        }

        .calendar-day-header {
            text-align: center;
            font-weight: 600;
            color: #666;
            padding: 10px 0;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .calendar-day {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            cursor: pointer;
            border: 1.5px solid #e8e8e8;
            font-weight: 600;
            transition: all 0.2s ease;
            font-size: 0.9rem;
            background: white;
            color: #1a1a1a;
        }

        .calendar-day:hover:not(.other-month):not(.booked) {
            border-color: #1a73e8;
            background: #f0f7ff;
        }

        .calendar-day.other-month {
            color: #ccc;
            cursor: default;
            background: #f9f9f9;
            border-color: #f0f0f0;
        }

        .calendar-day.booked {
            background: #fef2f2;
            color: #c41c3b;
            border-color: #fdc2c7;
            cursor: not-allowed;
            font-weight: 600;
        }

        .calendar-day.selected {
            background: #1a73e8;
            color: white;
            border-color: #1a73e8;
            box-shadow: 0 4px 12px rgba(26, 115, 232, 0.3);
        }

        .calendar-day.today {
            background: #e8f5e9;
            border-color: #4caf50;
            color: #2e7d32;
        }

        .time-slots-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(65px, 1fr));
            gap: 8px;
            margin-bottom: 16px;
        }

        .time-slot {
            padding: 10px 8px;
            border: 1.5px solid #e0e0e0;
            border-radius: 6px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 0.85rem;
            font-weight: 600;
            background: white;
            color: #1a1a1a;
        }

        .time-slot:hover:not(.unavailable) {
            border-color: #1a73e8;
            background: #f0f7ff;
            transform: translateY(-2px);
        }

        .time-slot.available {
            color: #1a1a1a;
            border-color: #e0e0e0;
        }

        .time-slot.unavailable {
            color: #ccc;
            border-color: #f0f0f0;
            background: #fafafa;
            cursor: not-allowed;
        }

        .time-slot.selected-start {
            background: #1a73e8;
            color: white;
            border-color: #1a73e8;
            box-shadow: 0 2px 8px rgba(26, 115, 232, 0.3);
        }

        .time-slot.selected-end {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
        }

        #timeRangeInfo {
            background: #e8f5e9;
            border-left: 4px solid #4caf50;
            padding: 12px 14px;
            border-radius: 6px;
            color: #2e7d32;
            font-size: 0.95rem;
            font-weight: 500;
            margin-top: 16px;
        }

        #timeRangeInfo em {
            color: #666;
            font-size: 0.9rem;
        }
    `;
    document.head.appendChild(style);
    let currentDate = new Date();
    let selectedDate = null;
    let selectedTimeStart = null;
    let selectedTimeEnd = null;
    let currentLapangan = null;
    let availableHours = [];

    // Initialize
    document.addEventListener('DOMContentLoaded', () => {
        renderCalendar();
        handleLapanganChange();
    });

    function handleLapanganChange() {
        const select = document.getElementById('lapangan_id');
        const option = select.options[select.selectedIndex];
        const lapanganNama = option.dataset.nama;
        const lapanganId = select.value;

        if (lapanganId) {
            document.getElementById('lapangan').value = lapanganNama;
            currentLapangan = {
                id: lapanganId,
                nama: lapanganNama,
                harga: parseFloat(option.dataset.harga)
            };
            renderCalendar();
            updateSummary();
        } else {
            currentLapangan = null;
            selectedDate = null;
            selectedTimeStart = null;
            selectedTimeEnd = null;
            document.getElementById('timeSlotsFullSection').style.display = 'none';
            document.getElementById('tanggal').value = '';
            document.getElementById('jam_mulai').value = '';
            document.getElementById('jam_selesai').value = '';
            renderCalendar();
            updateSummary();
        }
    }

    function updateCalendar() {
        renderCalendar();
    }

    function renderCalendar() {
        const container = document.getElementById('calendarContainer');
        
        // Jika belum pilih lapangan, tampilkan pesan
        if (!currentLapangan) {
            container.innerHTML = '<div class="text-center py-10 text-gray-400 text-sm font-medium">Pilih lapangan terlebih dahulu untuk melihat ketersediaan</div>';
            return;
        }

        const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();
        
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const daysInMonth = lastDay.getDate();
        const startingDayOfWeek = firstDay.getDay();

        let html = `<h3 class="mb-3.75 text-gray-900 font-semibold">${monthNames[month]} ${year}</h3>`;
        html += '<div class="calendar-grid">';
        
        // Day headers
        const dayHeaders = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
        dayHeaders.forEach(day => {
            html += `<div class="calendar-day-header">${day}</div>`;
        });

        // Empty cells for days before month starts
        for (let i = 0; i < startingDayOfWeek; i++) {
            html += '<div class="calendar-day other-month"></div>';
        }

        // Days of month
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        // Format tanggal lokal tanpa timezone conversion
        const todayStr = String(today.getFullYear()).padStart(4, '0') + '-' +
                         String(today.getMonth() + 1).padStart(2, '0') + '-' +
                         String(today.getDate()).padStart(2, '0');

        for (let day = 1; day <= daysInMonth; day++) {
            const date = new Date(year, month, day);
            
            // Format tanggal LOKAL tanpa menggunakan toISOString() yang bisa timezone-offset
            const dateStr = String(date.getFullYear()).padStart(4, '0') + '-' +
                           String(date.getMonth() + 1).padStart(2, '0') + '-' +
                           String(date.getDate()).padStart(2, '0');
            
            let classes = 'calendar-day';
            let isClickable = false;

            if (dateStr === todayStr) {
                classes += ' today';
                isClickable = true;
            } else if (dateStr < todayStr) {
                classes += ' other-month';
                isClickable = false;
            } else {
                // Tanggal di masa depan selalu clickable (cek jam availability di saat pemilihan jam)
                isClickable = true;
            }

            if (dateStr === selectedDate) {
                classes += ' selected';
            }
            
            html += `<div class="${classes}" ${isClickable ? `onclick="selectDate('${dateStr}')" style="cursor: pointer;"` : ''}>${day}</div>`;
        }

        html += '</div>';
        container.innerHTML = html;
    }

    function updateCalendar() {
        renderCalendar();
    }

    function selectDate(dateStr) {
        selectedDate = dateStr;
        selectedTimeStart = null;
        selectedTimeEnd = null;
        document.getElementById('tanggal').value = dateStr;
        renderCalendar();
        
        if (currentLapangan) {
            loadAvailableHours();
            document.getElementById('timeSlotsFullSection').style.display = 'block';
        }
        
        updateSummary();
    }

    function loadAvailableHours() {
        if (!currentLapangan || !selectedDate) return;

        const loading = document.getElementById('timeSlotsLoading');
        loading.classList.add('active');

        const url = new URL('{{ route("api.available-hours") }}', window.location.origin);
        url.searchParams.append('lapangan_id', currentLapangan.id);
        url.searchParams.append('tanggal', selectedDate);

        fetch(url.toString())
            .then(r => r.json())
            .then(data => {
                availableHours = data.hours || [];
                renderTimeSlots();
                loading.classList.remove('active');
            })
            .catch(err => {
                console.error('Error loading available hours:', err);
                alert('Gagal memuat jam yang tersedia. Silakan coba lagi.');
                loading.classList.remove('active');
            });
    }

    function renderTimeSlots() {
        const container = document.getElementById('timeSlotsContainer');
        let html = '';

        availableHours.forEach(slot => {
            const isAvailable = slot.available;
            const isSelected = (slot.hour === selectedTimeStart || slot.hour === selectedTimeEnd);
            const classes = `time-slot ${isAvailable ? 'available' : 'unavailable'} 
                ${slot.hour === selectedTimeStart ? 'selected-start' : ''} 
                ${slot.hour === selectedTimeEnd ? 'selected-end' : ''}`;

            html += `<div class="${classes}" 
                ${isAvailable ? `onclick="selectTimeStart('${slot.hour}')"` : ''}>
                ${slot.hour}
            </div>`;
        });

        container.innerHTML = html;
    }

    function selectTimeStart(hour) {
        selectedTimeStart = hour;
        document.getElementById('jam_mulai').value = hour;
        renderTimeSlots();
        showTimeEndOptions();
        updateSummary();
    }

    function showTimeEndOptions() {
        if (!selectedTimeStart) return;

        const container = document.getElementById('timeSlotsContainer');
        const startIndex = availableHours.findIndex(h => h.hour === selectedTimeStart);
        
        let html = '';
        availableHours.forEach((slot, index) => {
            const isAfterStart = index > startIndex;
            const isAvailable = slot.available && isAfterStart;
            const isSelected = slot.hour === selectedTimeEnd;
            const classes = `time-slot ${isAvailable ? 'available' : 'unavailable'} 
                ${isSelected ? 'selected-end' : ''}`;

            html += `<div class="${classes}" 
                ${isAvailable ? `onclick="selectTimeEnd('${slot.hour}')"` : ''}>
                ${slot.hour}
            </div>`;
        });

        container.innerHTML = html;
        renderTimeInfo();
    }

    function selectTimeEnd(hour) {
        selectedTimeEnd = hour;
        document.getElementById('jam_selesai').value = hour;
        updateSummary();
        renderTimeInfo();
    }

    function renderTimeInfo() {
        const info = document.getElementById('timeRangeInfo');
        if (selectedTimeStart && selectedTimeEnd) {
            const startHour = parseInt(selectedTimeStart);
            const endHour = parseInt(selectedTimeEnd);
            const durasi = endHour - startHour;
            info.innerHTML = `<strong>Jam dipilih:</strong> ${selectedTimeStart} - ${selectedTimeEnd} (${durasi} jam)`;
        } else if (selectedTimeStart) {
            info.innerHTML = `<strong>Jam mulai:</strong> ${selectedTimeStart} <br><em style="color: #666; font-size: 0.9rem;">Pilih jam selesai di atas</em>`;
        } else {
            info.innerHTML = '';
        }
    }

    function updateSummary() {
        const lapanganSelect = document.getElementById('lapangan_id');
        const lapanganOption = lapanganSelect.options[lapanganSelect.selectedIndex];
        const lapanganNama = lapanganOption.dataset.nama || '-';
        const harga = parseFloat(lapanganOption.dataset.harga) || 0;

        document.getElementById('summaryLapangan').textContent = lapanganNama || '-';
        document.getElementById('summaryTanggal').textContent = selectedDate ? new Date(selectedDate).toLocaleDateString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        }) : '-';

        if (selectedTimeStart && selectedTimeEnd) {
            const startHour = parseInt(selectedTimeStart);
            const endHour = parseInt(selectedTimeEnd);
            const durasi = endHour - startHour;
            document.getElementById('summaryJam').textContent = `${selectedTimeStart} - ${selectedTimeEnd}`;
            document.getElementById('summaryDurasi').textContent = `${durasi} jam`;
            document.getElementById('summaryHarga').textContent = `Rp ${number_format(harga, 0, ',', '.')}`;
            document.getElementById('summaryTotal').textContent = `Rp ${number_format(harga * durasi, 0, ',', '.')}`;
            document.getElementById('submitBtn').disabled = false;
        } else {
            document.getElementById('summaryJam').textContent = '-';
            document.getElementById('summaryDurasi').textContent = '-';
            document.getElementById('summaryHarga').textContent = 'Rp 0';
            document.getElementById('summaryTotal').textContent = 'Rp 0';
            document.getElementById('submitBtn').disabled = true;
        }
    }

    function previousMonth() {
        currentDate = new Date(currentDate.getFullYear(), currentDate.getMonth() - 1);
        selectedDate = null;
        selectedTimeStart = null;
        selectedTimeEnd = null;
        document.getElementById('timeSlotsFullSection').style.display = 'none';
        renderCalendar();
        updateSummary();
    }

    function nextMonth() {
        currentDate = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1);
        selectedDate = null;
        selectedTimeStart = null;
        selectedTimeEnd = null;
        document.getElementById('timeSlotsFullSection').style.display = 'none';
        renderCalendar();
        updateSummary();
    }

    function resetForm() {
        document.getElementById('bookingForm').reset();
        currentLapangan = null;
        selectedDate = null;
        selectedTimeStart = null;
        selectedTimeEnd = null;
        currentDate = new Date();
        document.getElementById('timeSlotsFullSection').style.display = 'none';
        renderCalendar();
        updateSummary();
    }

    function number_format(number, decimals, dec_point, thousands_sep) {
        var n = number;
        var prec = decimals;
        var sep = (typeof thousands_sep == 'undefined') ? ',' : thousands_sep;
        var dec = (typeof dec_point == 'undefined') ? '.' : dec_point;
        var s = '';
        var toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return (Math.round(n * k) / k).toString();
        };
        s = (prec ? toFixedFix(n, prec) : Math.round(n)).toString().split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }
</script>

@endsection
