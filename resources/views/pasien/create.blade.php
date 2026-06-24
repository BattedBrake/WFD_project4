<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Reservasi - MediReserv</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { font-family: 'Inter', sans-serif; }

        /* Custom select arrow */
        .custom-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
        }

        .custom-select:disabled {
            background-color: #f8fafc;
            cursor: not-allowed;
            color: #cbd5e1;
        }

        /* Step indicator */
        .step-line { flex: 1; height: 2px; background: #e2e8f0; }
        .step-line.active { background: #3b82f6; }

        /* Preview card animation */
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .preview-card { animation: slideDown .2s ease; }

        /* Sidebar */
        input#sidebar-toggle:checked ~ div aside { transform: translateX(0); }
    </style>
</head>
<body class="bg-slate-50 font-sans antialiased">

<input type="checkbox" id="sidebar-toggle" class="hidden">
<label for="sidebar-toggle" class="fixed inset-0 z-30 hidden bg-slate-900/50 peer-checked:block md:hidden" id="sidebar-overlay"></label>

<div class="flex h-screen overflow-hidden">

    {{-- ============================
         SIDEBAR
    ============================ --}}
    <aside id="sidebar"
           class="fixed inset-y-0 left-0 z-40 flex w-64 -translate-x-full flex-col justify-between bg-slate-900 text-white transition-transform duration-200 md:static md:translate-x-0">
        <div class="p-5">
            <div class="flex items-center gap-3 mb-8">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <span class="grid h-9 w-9 place-items-center rounded-xl bg-blue-500 text-white">
                        <i class="fa-solid fa-stethoscope"></i>
                    </span>
                    <span class="text-lg font-bold tracking-wider">MediReserv</span>
                </a>
            </div>

            <nav class="space-y-1">
                <a href="{{ route('pasien.dashboard') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white font-medium transition">
                    <i class="fa-solid fa-house w-5"></i> Dashboard
                </a>
                <a href="{{ route('reservations.create') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl bg-blue-600 text-white font-medium transition shadow-md shadow-blue-600/20">
                    <i class="fa-solid fa-calendar-plus w-5"></i> Buat Reservasi
                </a>
                <a href="{{ route('reservations.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white font-medium transition">
                    <i class="fa-solid fa-clock-rotate-left w-5"></i> Riwayat Konsultasi
                </a>
            </nav>
        </div>

        <div class="p-5 border-t border-slate-800">
            <div class="flex items-center gap-3 mb-4 px-2">
                <div class="grid h-8 w-8 place-items-center rounded-full bg-blue-500/20 text-blue-400 text-xs font-bold flex-shrink-0">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-slate-500">Pasien</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-red-400 hover:bg-red-500/10 hover:text-red-300 font-medium transition text-sm">
                    <i class="fa-solid fa-right-from-bracket w-5"></i> Keluar
                </button>
            </form>
        </div>
    </aside>



    <div class="flex min-w-0 flex-grow flex-col overflow-y-auto">

        <header class="bg-white border-b border-slate-200 px-4 py-4 flex justify-between items-center sticky top-0 z-10 sm:px-8">
            <div class="flex items-center gap-3">
                <label for="sidebar-toggle"
                       class="grid h-10 w-10 place-items-center rounded-xl border border-slate-200 text-slate-600 md:hidden cursor-pointer">
                    <i class="fa-solid fa-bars"></i>
                </label>
                <div>
                    <h1 class="text-lg font-bold text-slate-800">Buat Reservasi</h1>
                    <p class="text-xs text-slate-400 hidden sm:block">Pilih dokter, tanggal, dan jam praktik</p>
                </div>
            </div>
            <p class="text-sm font-semibold text-slate-700">{{ Auth::user()->name }}</p>
        </header>


        <main class="flex-1 p-5 sm:p-8">
            <div class="max-w-2xl mx-auto">

                @if($errors->any())
                <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3.5">
                    <div class="flex items-start gap-2.5">
                        <i class="fa-solid fa-circle-xmark text-red-500 mt-0.5 flex-shrink-0"></i>
                        <div>
                            <p class="text-sm font-semibold text-red-700 mb-1">Terjadi kesalahan:</p>
                            @foreach($errors->all() as $error)
                                <p class="text-sm text-red-600">• {{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                @if($schedules->isEmpty())
                <div class="rounded-2xl border border-amber-200 bg-amber-50 p-8 text-center">
                    <div class="mx-auto mb-4 grid h-14 w-14 place-items-center rounded-2xl bg-amber-100 text-amber-500">
                        <i class="fa-solid fa-calendar-xmark text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-slate-800 mb-1">Belum ada jadwal tersedia</h3>
                    <p class="text-sm text-slate-500">Tidak ada jadwal dokter yang tersedia saat ini. Silakan coba lagi nanti.</p>
                    <a href="{{ route('pasien.dashboard') }}"
                       class="mt-5 inline-flex items-center gap-2 rounded-xl bg-amber-500 px-5 py-2.5 text-sm font-bold text-white hover:bg-amber-600 transition">
                        <i class="fa-solid fa-arrow-left text-xs"></i> Kembali ke Dashboard
                    </a>
                </div>
                @else

                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">

                    {{-- Card header --}}
                    <div class="bg-gradient-to-r from-blue-600 to-sky-500 px-6 py-5">
                        <h2 class="text-lg font-bold text-white">Formulir Reservasi Dokter</h2>
                        <p class="text-sm text-blue-100 mt-0.5">Lengkapi langkah-langkah di bawah untuk membuat reservasi</p>
                    </div>

                    {{-- Step indicator --}}
                    <div class="flex items-center gap-2 px-6 py-4 border-b border-slate-100 bg-slate-50">
                        {{-- Step 1 --}}
                        <div class="flex items-center gap-2" id="step-1-indicator">
                            <div class="grid h-7 w-7 place-items-center rounded-full bg-blue-600 text-white text-xs font-bold transition" id="step-1-circle">1</div>
                            <span class="text-xs font-semibold text-blue-600 hidden sm:block" id="step-1-label">Pilih Dokter</span>
                        </div>
                        <div class="step-line" id="line-1-2"></div>
                        {{-- Step 2 --}}
                        <div class="flex items-center gap-2" id="step-2-indicator">
                            <div class="grid h-7 w-7 place-items-center rounded-full bg-slate-200 text-slate-400 text-xs font-bold transition" id="step-2-circle">2</div>
                            <span class="text-xs font-semibold text-slate-400 hidden sm:block" id="step-2-label">Pilih Tanggal</span>
                        </div>
                        <div class="step-line" id="line-2-3"></div>
                        {{-- Step 3 --}}
                        <div class="flex items-center gap-2" id="step-3-indicator">
                            <div class="grid h-7 w-7 place-items-center rounded-full bg-slate-200 text-slate-400 text-xs font-bold transition" id="step-3-circle">3</div>
                            <span class="text-xs font-semibold text-slate-400 hidden sm:block" id="step-3-label">Pilih Jam</span>
                        </div>
                    </div>

                    {{-- Form body --}}
                    <form method="POST" action="{{ route('reservations.store') }}" id="reservationForm">
                        @csrf
                        {{-- Hidden field — diisi otomatis oleh JS --}}
                        <input type="hidden" name="schedule_id" id="schedule_id" value="{{ old('schedule_id') }}">

                        <div class="p-6 space-y-5">

                            <div>
                                <label for="select_doctor" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                    <span class="inline-flex items-center gap-1.5">
                                        <span class="grid h-5 w-5 place-items-center rounded-full bg-blue-600 text-white text-[10px] font-bold">1</span>
                                        Nama Dokter
                                    </span>
                                </label>
                                <select id="select_doctor"
                                        class="custom-select w-full border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-blue-400 focus:ring-4 focus:ring-blue-100">
                                    <option value="">— Pilih dokter —</option>
                                </select>
                            </div>

                            <div>
                                <label for="select_date" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                    <span class="inline-flex items-center gap-1.5">
                                        <span class="grid h-5 w-5 place-items-center rounded-full bg-slate-300 text-white text-[10px] font-bold transition" id="badge-2">2</span>
                                        Hari &amp; Tanggal
                                    </span>
                                </label>
                                <select id="select_date"
                                        disabled
                                        class="custom-select w-full border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-blue-400 focus:ring-4 focus:ring-blue-100">
                                    <option value="">— Pilih tanggal —</option>
                                </select>
                                <p class="mt-1.5 text-xs text-slate-400" id="hint-date">Pilih dokter terlebih dahulu</p>
                            </div>

                           
                            <div>
                                <label for="select_time" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                    <span class="inline-flex items-center gap-1.5">
                                        <span class="grid h-5 w-5 place-items-center rounded-full bg-slate-300 text-white text-[10px] font-bold transition" id="badge-3">3</span>
                                        Jam Praktik
                                    </span>
                                </label>
                                <select id="select_time"
                                        disabled
                                        class="custom-select w-full border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-blue-400 focus:ring-4 focus:ring-blue-100">
                                    <option value="">— Pilih jam —</option>
                                </select>
                                <p class="mt-1.5 text-xs text-slate-400" id="hint-time">Pilih tanggal terlebih dahulu</p>
                            </div>

                         
                            <div id="preview-card" class="hidden preview-card rounded-xl border border-blue-200 bg-blue-50 px-4 py-4">
                                <p class="text-xs font-bold uppercase tracking-widest text-blue-500 mb-3">Ringkasan Reservasi</p>
                                <div class="grid grid-cols-2 gap-3 text-sm">
                                    <div>
                                        <p class="text-xs text-slate-400 mb-0.5">Dokter</p>
                                        <p class="font-semibold text-slate-800" id="preview-doctor">—</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-slate-400 mb-0.5">Spesialisasi</p>
                                        <p class="font-semibold text-slate-800" id="preview-spec">—</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-slate-400 mb-0.5">Tanggal</p>
                                        <p class="font-semibold text-slate-800" id="preview-date">—</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-slate-400 mb-0.5">Jam</p>
                                        <p class="font-semibold text-slate-800" id="preview-time">—</p>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="complaint" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                    Keluhan
                                    <span class="text-slate-400 font-normal">(opsional)</span>
                                </label>
                                <textarea name="complaint"
                                          id="complaint"
                                          rows="4"
                                          maxlength="1000"
                                          class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-blue-400 focus:ring-4 focus:ring-blue-100 resize-none"
                                          placeholder="Ceritakan keluhan Anda secara singkat...">{{ old('complaint') }}</textarea>
                                <div class="flex justify-between mt-1">
                                    @error('complaint')
                                        <p class="text-xs text-red-500">{{ $message }}</p>
                                    @else
                                    @enderror
                                    <p class="text-xs text-slate-300 ml-auto" id="char-count">0/1000</p>
                                </div>
                            </div>

                        </div>

                        {{-- Form footer --}}
                        <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-slate-100 bg-slate-50">
                            <a href="{{ route('pasien.dashboard') }}"
                               class="px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-200 transition">
                                Batal
                            </a>
                            <button type="submit"
                                    id="submit-btn"
                                    disabled
                                    class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-bold text-white transition
                                           bg-slate-300 cursor-not-allowed
                                           disabled:bg-slate-300 disabled:cursor-not-allowed">
                                <i class="fa-solid fa-calendar-check text-xs"></i>
                                Submit Reservasi
                            </button>
                        </div>

                    </form>
                </div>

                @endif
            </div>
        </main>
    </div>
</div>



@php
$schedulesJson = $schedules->map(fn ($s) => [
    'id'             => $s->id,
    'doctor_id'      => $s->doctor_id,
    'doctor_name'    => $s->doctor->user->name ?? '-',
    'specialization' => $s->doctor->specialization ?? '-',
    'date'           => $s->date,
    'start_time'     => substr($s->start_time, 0, 5),
    'end_time'       => substr($s->end_time, 0, 5),
    'quota'          => $s->quota,
])->values();
@endphp

<script>
// Data jadwal dari Laravel (sudah difilter: quota tersedia, tanggal >= hari ini)
const schedulesData = @json($schedulesJson);

const HARI = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
const BULAN = ['','Januari','Februari','Maret','April','Mei','Juni',
               'Juli','Agustus','September','Oktober','November','Desember'];

function formatTanggal(dateStr) {
    // dateStr = "2026-06-25"
    const d = new Date(dateStr + 'T00:00:00');
    const hari  = HARI[d.getDay()];
    const tgl   = d.getDate();
    const bulan = BULAN[d.getMonth() + 1];
    const tahun = d.getFullYear();
    return `${hari}, ${tgl} ${bulan} ${tahun}`;
}

const doctorMap = {};   // { doctorId: { name, spec, dates: { dateStr: [schedule] } } }

schedulesData.forEach(s => {
    if (!doctorMap[s.doctor_id]) {
        doctorMap[s.doctor_id] = {
            name:  s.doctor_name,
            spec:  s.specialization,
            dates: {},
        };
    }
    if (!doctorMap[s.doctor_id].dates[s.date]) {
        doctorMap[s.doctor_id].dates[s.date] = [];
    }
    doctorMap[s.doctor_id].dates[s.date].push(s);
});

const selDoctor    = document.getElementById('select_doctor');
const selDate      = document.getElementById('select_date');
const selTime      = document.getElementById('select_time');
const hiddenId     = document.getElementById('schedule_id');
const submitBtn    = document.getElementById('submit-btn');
const previewCard  = document.getElementById('preview-card');
const hintDate     = document.getElementById('hint-date');
const hintTime     = document.getElementById('hint-time');
const charCount    = document.getElementById('char-count');
const complaint    = document.getElementById('complaint');

// Step badges
const step2Circle  = document.getElementById('step-2-circle');
const step3Circle  = document.getElementById('step-3-circle');
const badge2       = document.getElementById('badge-2');
const badge3       = document.getElementById('badge-3');
const line12       = document.getElementById('line-1-2');
const line23       = document.getElementById('line-2-3');
const step2Label   = document.getElementById('step-2-label');
const step3Label   = document.getElementById('step-3-label');

Object.entries(doctorMap).forEach(([id, doc]) => {
    const opt = document.createElement('option');
    opt.value = id;
    opt.textContent = `${doc.name} — ${doc.spec}`;
    selDoctor.appendChild(opt);
});


function resetSelect(sel, placeholder) {
    sel.innerHTML = `<option value="">${placeholder}</option>`;
    sel.disabled  = true;
}

function resetPreview() {
    hiddenId.value = '';
    submitBtn.disabled = true;
    submitBtn.className = submitBtn.className
        .replace('bg-blue-600 hover:bg-blue-700 cursor-pointer', '')
        + ' bg-slate-300 cursor-not-allowed';
    previewCard.classList.add('hidden');
}

function activateStep(circle, label, line) {
    circle.className = circle.className
        .replace('bg-slate-200 text-slate-400', 'bg-blue-600 text-white');
    if (label) {
        label.className = label.className
            .replace('text-slate-400', 'text-blue-600');
    }
    if (line) line.classList.add('active');
}

function deactivateStep(circle, label, line) {
    circle.className = circle.className
        .replace('bg-blue-600 text-white', 'bg-slate-200 text-slate-400');
    if (label) {
        label.className = label.className
            .replace('text-blue-600', 'text-slate-400');
    }
    if (line) line.classList.remove('active');
}


selDoctor.addEventListener('change', function () {
    resetSelect(selDate, '— Pilih tanggal —');
    resetSelect(selTime, '— Pilih jam —');
    resetPreview();
    deactivateStep(step2Circle, step2Label, line12);
    deactivateStep(step3Circle, step3Label, line23);
    badge2.className = badge2.className.replace('bg-blue-600', 'bg-slate-300');
    badge3.className = badge3.className.replace('bg-blue-600', 'bg-slate-300');
    hintDate.textContent = 'Pilih dokter terlebih dahulu';
    hintTime.textContent = 'Pilih tanggal terlebih dahulu';

    const docId = this.value;
    if (!docId) return;

    // Populate dates for this doctor
    const dates = Object.keys(doctorMap[docId].dates).sort();
    selDate.disabled = false;
    hintDate.textContent = `${dates.length} tanggal tersedia`;
    activateStep(step2Circle, step2Label, line12);
    badge2.className = badge2.className.replace('bg-slate-300', 'bg-blue-600');

    dates.forEach(dateStr => {
        const opt = document.createElement('option');
        opt.value = dateStr;
        opt.textContent = formatTanggal(dateStr);
        selDate.appendChild(opt);
    });
});

selDate.addEventListener('change', function () {
    resetSelect(selTime, '— Pilih jam —');
    resetPreview();
    deactivateStep(step3Circle, step3Label, line23);
    badge3.className = badge3.className.replace('bg-blue-600', 'bg-slate-300');
    hintTime.textContent = 'Pilih tanggal terlebih dahulu';

    const docId   = selDoctor.value;
    const dateStr = this.value;
    if (!docId || !dateStr) return;

    // Populate time slots
    const slots = doctorMap[docId].dates[dateStr] || [];
    selTime.disabled = false;
    hintTime.textContent = `${slots.length} slot waktu tersedia`;
    activateStep(step3Circle, step3Label, line23);
    badge3.className = badge3.className.replace('bg-slate-300', 'bg-blue-600');

    slots.sort((a, b) => a.start_time.localeCompare(b.start_time));
    slots.forEach(s => {
        const opt = document.createElement('option');
        opt.value = s.id;
        opt.textContent = `${s.start_time} – ${s.end_time}`;
        opt.dataset.schedule = JSON.stringify(s);
        selTime.appendChild(opt);
    });
});

selTime.addEventListener('change', function () {
    resetPreview();

    const schedId = this.value;
    if (!schedId) return;

    // Get selected option data
    const selectedOpt = this.options[this.selectedIndex];
    const s   = JSON.parse(selectedOpt.dataset.schedule);
    const doc = doctorMap[s.doctor_id];

    // Set hidden field
    hiddenId.value = schedId;

    // Update preview card
    document.getElementById('preview-doctor').textContent = doc.name;
    document.getElementById('preview-spec').textContent   = doc.spec;
    document.getElementById('preview-date').textContent   = formatTanggal(s.date);
    document.getElementById('preview-time').textContent   = `${s.start_time} – ${s.end_time}`;
    previewCard.classList.remove('hidden');

    // Enable submit
    submitBtn.disabled = false;
    submitBtn.className = submitBtn.className
        .replace('bg-slate-300 cursor-not-allowed', 'bg-blue-600 hover:bg-blue-700 cursor-pointer');
});


complaint.addEventListener('input', function () {
    charCount.textContent = `${this.value.length}/1000`;
});


const sidebarToggle  = document.getElementById('sidebar-toggle');
const sidebarOverlay = document.getElementById('sidebar-overlay');
const sidebar        = document.getElementById('sidebar');

sidebarToggle && sidebarToggle.addEventListener('change', () => {
    sidebar.classList.toggle('-translate-x-full', !sidebarToggle.checked);
});
sidebarOverlay && sidebarOverlay.addEventListener('click', () => {
    sidebarToggle.checked = false;
    sidebar.classList.add('-translate-x-full');
});

const oldScheduleId = '{{ old("schedule_id") }}';
if (oldScheduleId && schedulesData.length > 0) {
    const found = schedulesData.find(s => String(s.id) === String(oldScheduleId));
    if (found) {
        // Trigger doctor select
        selDoctor.value = found.doctor_id;
        selDoctor.dispatchEvent(new Event('change'));

        // Trigger date select
        setTimeout(() => {
            selDate.value = found.date;
            selDate.dispatchEvent(new Event('change'));

            // Trigger time select
            setTimeout(() => {
                selTime.value = found.id;
                selTime.dispatchEvent(new Event('change'));
            }, 50);
        }, 50);
    }
}
</script>

</body>
</html>