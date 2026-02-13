let currentMonth = new Date().getMonth();
let currentYear = new Date().getFullYear();

// ✅ Konversi booking data menjadi format untuk kalender
let scheduleData = {};

// ✅ Function untuk load booking data dari API
async function loadBookingData() {
    try {
        // Jika region belum didefinisikan, return
        if (typeof region === 'undefined') {
            console.warn('Region not defined, skipping API call');
            renderCalendar(); // Tetap render kalender meski tanpa data
            return;
        }
        
        console.log(`Loading bookings for region: ${region}, month: ${currentMonth + 1}, year: ${currentYear}`);
        const response = await fetch(`/api/booked-slots?tahun=${currentYear}&bulan=${currentMonth + 1}&region=${region}`);
        if (!response.ok) {
            console.error('Failed to fetch booking data:', response.statusText);
            renderCalendar();
            return;
        }
        
        const bookings = await response.json();
        console.log('Bookings received:', bookings);
        scheduleData = {};
        
        // Format data ke dalam scheduleData
        bookings.forEach(booking => {
            const dateKey = booking.tanggal;
            
            if (!scheduleData[dateKey]) {
                scheduleData[dateKey] = [];
            }
            
            scheduleData[dateKey].push({
                status: 'booked',
                jam_mulai: booking.jam_mulai,
                jam_selesai: booking.jam_selesai,
                lapangan_id: booking.lapangan_id,
                lapangan_nama: booking.lapangan_nama,
                nama_pemesan: booking.nama_pemesan
            });
        });
        
        renderCalendar();
        console.log('Booking data loaded successfully from API');
    } catch (error) {
        console.error('Error loading booking data from API:', error);
        // Fallback ke renderCalendar jika ada error
        renderCalendar();
    }
}

function renderCalendar() {
    const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    const monthYearElement = document.getElementById('monthYear');
    const calendarGrid = document.getElementById('calendarGrid');
    
    if (!monthYearElement || !calendarGrid) {
        console.error('Calendar elements not found. monthYear:', monthYearElement, 'calendarGrid:', calendarGrid);
        return;
    }

    monthYearElement.textContent = `${monthNames[currentMonth]} ${currentYear}`;

    const firstDay = new Date(currentYear, currentMonth, 1).getDay();
    const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

    calendarGrid.innerHTML = '';

    for (let i = 0; i < firstDay; i++) {
        calendarGrid.innerHTML += '<div></div>';
    }

    for (let day = 1; day <= daysInMonth; day++) {
        // Format date sebagai YYYY-MM-DD untuk match dengan data dari server
        const formattedDay = String(day).padStart(2, '0');
        const formattedMonth = String(currentMonth + 1).padStart(2, '0');
        const dateKey = `${currentYear}-${formattedMonth}-${formattedDay}`;
        
        const isBooked = scheduleData[dateKey] && scheduleData[dateKey].length > 0;
        const bgColor = isBooked ? 'bg-red-400 hover:bg-red-500' : 'bg-green-400 hover:bg-green-500';

        calendarGrid.innerHTML += `
                        <button onclick="showDateDetail('${dateKey}')" 
                            class="p-3 rounded text-white font-semibold ${bgColor} transition cursor-pointer">
                            ${day}
                        </button>
                    `;
    }
    
    console.log('Calendar rendered for', monthNames[currentMonth], currentYear);
}

function showDateDetail(dateKey) {
    const [year, month, day] = dateKey.split('-');
    const bookings = scheduleData[dateKey] || [];

    const monthNames = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    let detailHTML = `<p class="font-bold text-gray-800">${parseInt(day)} ${monthNames[parseInt(month)]} ${year}</p>`;

    if (bookings.length === 0) {
        detailHTML += '<p class="text-green-600 font-semibold mt-2">✓ Semua lapangan kosong</p>';
    } 
    else {
        bookings.forEach((booking, idx) => {
            detailHTML += `<div class="mt-2 p-2 bg-red-100 border-l-4 border-red-500">
                <p class="text-red-700 font-semibold text-sm">${booking.lapangan_nama || 'Lapangan'}</p>
                <p class="text-red-600 text-sm">Pemesan: ${booking.nama_pemesan || 'N/A'}</p>
                <p class="text-red-600 text-sm">Jam: ${booking.jam_mulai} - ${booking.jam_selesai}</p>
            </div>`;
        });
    }
    document.getElementById('dateDetail').innerHTML = detailHTML;
}

function prevMonth() {
    currentMonth--;
    if (currentMonth < 0) {
        currentMonth = 11;
        currentYear--;
    }
    loadBookingData();
}

function nextMonth() {
    currentMonth++;
    if (currentMonth > 11) {
        currentMonth = 0;
        currentYear++;
    }
    loadBookingData();
}

// ✅ Load booking data saat halaman sudah siap
document.addEventListener('DOMContentLoaded', function() {
    loadBookingData();
});

// Jika DOMContentLoaded sudah fired sebelum script ini
if (document.readyState === 'complete' || document.readyState === 'interactive') {
    loadBookingData();
}

let currentSlide = 0;
const dots = document.querySelectorAll('.dot');
const totalSlides = 2;
const sliderTrack = document.getElementById('sliderTrack');
let autoSlideInterval;

function moveSlide(direction) {
    currentSlide += direction;
    if (currentSlide >= totalSlides) currentSlide = 0;
    if (currentSlide < 0) currentSlide = totalSlides - 1;

    updateSlider();
    resetAutoSlide();
}

function goToSlide(index) {
    currentSlide = index;
    updateSlider();
    resetAutoSlide();
}

function updateSlider() {
    const offset = -currentSlide * 100;
    sliderTrack.style.transform = `translateX(${offset}%)`;

    // Update dots
    dots.forEach((dot, index) => {
        if (index === currentSlide) {
            dot.classList.add('active');
        } else {
            dot.classList.remove('active');
        }
    });
}

function autoSlide() {
    currentSlide++;
    if (currentSlide >= totalSlides) currentSlide = 0;
    updateSlider();
}

function startAutoSlide() {
    autoSlideInterval = setInterval(autoSlide, 5000);
}

function resetAutoSlide() {
    clearInterval(autoSlideInterval);
    startAutoSlide();
}
startAutoSlide();

document.addEventListener('DOMContentLoaded', function () {
    const profileBtn = document.getElementById('profileBtn');
    const dropdownMenu = document.getElementById('dropdownMenu');
    const dropdownWrapper = document.getElementById('profileDropdown');

    profileBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        dropdownMenu.classList.toggle('hidden');
    });

    document.addEventListener('click', function (e) {
        if (!dropdownWrapper.contains(e.target)) {
            dropdownMenu.classList.add('hidden');
        }
    });
    
});