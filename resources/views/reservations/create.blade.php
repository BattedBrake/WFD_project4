<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Buat Reservasi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800">
    <div class="mx-auto max-w-4xl px-4 py-8">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold">Buat Reservasi</h1>
            <p class="text-sm text-slate-600">Pilih jadwal dokter yang tersedia dan kirim keluhan Anda.</p>
        </div>

        <form method="POST" action="{{ route('reservations.store') }}" class="space-y-6 rounded-2xl border bg-white p-6 shadow-sm">
            @csrf

            <div>
                <label for="schedule_id" class="mb-2 block text-sm font-medium">Jadwal Dokter</label>
                <select name="schedule_id" id="schedule_id" class="w-full rounded-lg border px-3 py-2">
                    <option value="">Pilih jadwal</option>
                    @foreach($schedules as $schedule)
                        <option value="{{ $schedule->id }}" @selected(old('schedule_id') == $schedule->id)>
                            {{ $schedule->doctor->user->name ?? 'Dokter' }} — {{ $schedule->date }} {{ $schedule->start_time }} - {{ $schedule->end_time }}
                        </option>
                    @endforeach
                </select>
                @error('schedule_id')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="complaint" class="mb-2 block text-sm font-medium">Keluhan</label>
                <textarea name="complaint" id="complaint" rows="4" class="w-full rounded-lg border px-3 py-2" placeholder="Ceritakan keluhan Anda...">{{ old('complaint') }}</textarea>
                @error('complaint')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3">
                <button class="rounded-lg bg-blue-600 px-4 py-2 text-white">Submit Reservasi</button>
                <a href="{{ route('reservations.index') }}" class="rounded-lg border px-4 py-2">Batal</a>
            </div>
        </form>
    </div>
</body>
</html>
