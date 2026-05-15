<x-layout-app title="Dashboard & Analisis" :role="$role">

    <div class="space-y-4 fade-in pb-8">

        {{-- Kartu Statistik --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <div class="bg-white p-4 rounded-xl border border-slate-100 flex justify-between items-center shadow-sm">
                <div>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Total Siswa</p>
                    <h3 class="text-xl font-bold text-slate-800 mt-0.5">{{ $statistik['total_siswa'] ?? 0 }}</h3>
                </div>
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-100 flex justify-between items-center shadow-sm">
                <div>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Hadir Hari Ini</p>
                    <h3 class="text-xl font-bold text-green-500 mt-0.5">{{ $statistik['hadir_hari_ini'] ?? 0 }}</h3>
                </div>
                <div class="w-8 h-8 rounded-lg bg-green-50 text-green-500 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-user-check"></i>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-100 flex justify-between items-center shadow-sm">
                <div>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Rata-rata Datang</p>
                    <h3 class="text-lg font-bold text-indigo-500 mt-0.5">{{ $statistik['rata_rata_kedatangan'] ?? '00:00' }}</h3>
                </div>
                <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-500 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-clock"></i>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-100 flex justify-between items-center shadow-sm">
                <div>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Siswa Terlambat</p>
                    <h3 class="text-xl font-bold text-red-500 mt-0.5">{{ $statistik['terlambat_hari_ini'] ?? 0 }}</h3>
                </div>
                <div class="w-8 h-8 rounded-lg bg-red-50 text-red-500 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-person-running"></i>
                </div>
            </div>
        </div>

        {{-- Grafik Kehadiran Per Tingkat + Tab --}}
        <div class="bg-white rounded-xl border border-slate-100 p-5 shadow-sm">
            <div class="flex justify-between items-start mb-3">
                <div>
                    <h3 class="font-bold text-sm text-slate-800">Statistik Kehadiran Per Tingkat</h3>
                    <p class="text-[10px] text-slate-400 mt-0.5">Perbandingan kehadiran kelas 7, 8, 9.</p>
                </div>
                <div class="flex gap-1 bg-slate-50 p-1 rounded-md border border-slate-100">
                    <button onclick="switchTab('harian')" id="tab-harian"
                        class="px-2 py-0.5 text-[10px] font-bold rounded bg-white text-slate-800 shadow-sm transition">Harian</button>
                    <button onclick="switchTab('mingguan')" id="tab-mingguan"
                        class="px-2 py-0.5 text-[10px] font-medium text-slate-500 hover:text-slate-800 transition">Mingguan</button>
                    <button onclick="switchTab('bulanan')" id="tab-bulanan"
                        class="px-2 py-0.5 text-[10px] font-medium text-slate-500 hover:text-slate-800 transition">Bulanan</button>
                </div>
            </div>
            <div id="chart-attendance" class="w-full h-[240px]"></div>
        </div>{{-- Siswa Terlambat & Siswa Rajin --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    
    {{-- 1. Proporsi Ketepatan Waktu (Donut Chart) --}}
    <div class="bg-white rounded-xl border border-slate-100 p-5 shadow-sm">
        <h3 class="font-bold text-sm text-slate-800 mb-3">Proporsi Ketepatan Waktu</h3>
        <div id="chart-pie" class="w-full h-[200px]"></div>
    </div>

    {{-- 2. Siswa Terlambat Terkini (Monitor Negatif) --}}
    <div class="bg-white rounded-xl border border-slate-100 p-5 shadow-sm flex flex-col">
        <div class="flex justify-between items-center mb-3">
            <h3 class="font-bold text-sm text-slate-800">Siswa Terlambat Terkini</h3>
            <span class="text-[9px] bg-red-100 text-red-500 px-2 py-0.5 rounded font-bold animate-pulse">Live Update</span>
        </div>
        <div class="flex-1 overflow-y-auto custom-scroll space-y-2 max-h-[200px]">
            @forelse($statistik['siswaTerlambatTerkini'] ?? [] as $telat)
                <div class="flex items-center justify-between p-2.5 bg-red-50 rounded-lg border border-red-100">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-md bg-red-200 text-red-700 flex items-center justify-center font-bold text-[11px]">
                            {{ $telat['kelas'] ?? '-' }}
                        </div>
                        <div>
                            <p class="font-bold text-slate-800 text-xs">{{ $telat['nama'] ?? 'Siswa' }}</p>
                            <p class="text-[9px] text-red-500 font-bold">Telat {{ $telat['menit_terlambat'] ?? 0 }} Menit</p>
                        </div>
                    </div>
                    <span class="text-[10px] font-mono font-bold text-slate-500">{{ $telat['waktu_datang'] ?? '00:00' }}</span>
                </div>
            @empty
                <div class="flex items-center justify-center h-full py-6 text-xs text-slate-400">Belum ada catatan hari ini.</div>
            @endforelse
        </div>
    </div>

    {{-- 3. KARTU BARU: 3 SISWA PALING RAJIN (Achievement) --}}
    <div class="bg-white rounded-xl border border-slate-100 p-5 shadow-sm flex flex-col">
        <div class="flex justify-between items-center mb-3">
            <h3 class="font-bold text-sm text-slate-800">Top 3 Siswa Terdisiplin</h3>
            <span class="text-[9px] bg-emerald-100 text-emerald-600 px-2 py-0.5 rounded font-bold uppercase tracking-wider">Bulan Ini</span>
        </div>
        <div class="flex-1 space-y-2">
            @php
                // Mapping icon medal berdasarkan index
                $medals = [
                    0 => ['bg' => 'bg-amber-100', 'text' => 'text-amber-600', 'icon' => 'fa-medal'], // Emas
                    1 => ['bg' => 'bg-slate-100', 'text' => 'text-slate-500', 'icon' => 'fa-medal'], // Perak
                    2 => ['bg' => 'bg-orange-100', 'text' => 'text-orange-600', 'icon' => 'fa-medal'], // Perunggu
                ];
            @endphp

            @forelse($statistik['siswaRajin'] ?? [] as $index => $rajin)
                <div class="flex items-center justify-between p-2.5 bg-emerald-50/50 rounded-lg border border-emerald-100 transition hover:scale-[1.02]">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full {{ $medals[$index]['bg'] ?? 'bg-blue-100' }} {{ $medals[$index]['text'] ?? 'text-blue-600' }} flex items-center justify-center text-xs shadow-sm">
                            <i class="fa-solid {{ $medals[$index]['icon'] ?? 'fa-award' }}"></i>
                        </div>
                        <div>
                            <p class="font-extrabold text-slate-800 text-xs uppercase">{{ $rajin['nama'] ?? 'Siswa' }}</p>
                            <p class="text-[9px] text-slate-500 font-bold tracking-tight">Kelas {{ $rajin['kelas'] ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-[11px] font-black text-emerald-600">{{ $rajin['total_tepat_waktu'] ?? 0 }}x</p>
                        <p class="text-[7px] font-bold text-slate-400 uppercase tracking-tighter">Hadir Tepat</p>
                    </div>
                </div>
            @empty
                <div class="flex items-center justify-center h-full py-6 text-xs text-slate-400">
                    Data kedisiplinan belum tersedia.
                </div>
            @endforelse
        </div>
    </div>

</div>

        {{-- Tren Pelanggaran --}}
        <div class="bg-white rounded-xl border border-slate-100 p-5 shadow-sm">
            <div class="mb-3">
                <h3 class="font-bold text-sm text-slate-800">Peta Tren Pelanggaran</h3>
                <p class="text-[10px] text-slate-400 mt-0.5">Tren pelanggaran siswa semester ini.</p>
            </div>
            <div id="chart-trend" class="w-full h-[240px]"></div>
        </div>

    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const statData = @json($statistik);
            const trenData = @json($tren);

            const dataHarian   = @json($rekapHarian);
            const dataMingguan = @json($rekapMingguan);
            const dataBulanan  = @json($rekapBulanan);

            function toSeries(data) {
                return [
                    { name: 'Hadir', data: data.map(d => d.hadir ?? 0) },
                    { name: 'Izin',  data: data.map(d => d.izin  ?? 0) },
                    { name: 'Sakit', data: data.map(d => d.sakit ?? 0) },
                    { name: 'Alfa',  data: data.map(d => d.alpha ?? 0) },
                ];
            }

            let attendanceChart = new ApexCharts(
                document.querySelector('#chart-attendance'), {
                    series: toSeries(dataHarian),
                    chart: {
                        type: 'bar',
                        height: 240, // Telah di-scale down dari 280
                        toolbar: { show: false },
                        fontFamily: 'Plus Jakarta Sans, sans-serif',
                    },
                    colors: ['#22c55e', '#3b82f6', '#eab308', '#ef4444'],
                    plotOptions: { bar: { columnWidth: '50%', borderRadius: 2 } },
                    xaxis: { categories: ['Kelas 7', 'Kelas 8', 'Kelas 9'] },
                    grid: { borderColor: '#f1f5f9' },
                    legend: { position: 'bottom', fontSize: '11px' },
                    dataLabels: { enabled: false },
                    tooltip: { y: { formatter: val => val + ' siswa' } },
                }
            );
            attendanceChart.render();

            window.switchTab = function (tab) {
                ['harian','mingguan','bulanan'].forEach(t => {
                    const el = document.getElementById('tab-' + t);
                    if (t === tab) {
                        el.classList.add('bg-white','text-slate-800','shadow-sm');
                        el.classList.remove('text-slate-500');
                    } else {
                        el.classList.remove('bg-white','text-slate-800','shadow-sm');
                        el.classList.add('text-slate-500');
                    }
                });
                const map = { harian: dataHarian, mingguan: dataMingguan, bulanan: dataBulanan };
                attendanceChart.updateSeries(toSeries(map[tab]));
            };

            const tepatWaktu = Math.round((statData.proporsiKetepatanWaktu ?? 0) * 100);
            new ApexCharts(document.querySelector('#chart-pie'), {
                series: [tepatWaktu, 100 - tepatWaktu],
                labels: ['Tepat Waktu', 'Terlambat'],
                chart: {
                    type: 'donut',
                    height: 200, // Telah di-scale down dari 220
                    fontFamily: 'Plus Jakarta Sans, sans-serif'
                },
                colors: ['#3b82f6', '#ef4444'],
                legend: { position: 'bottom', fontSize: '11px' },
                dataLabels: { enabled: false },
                plotOptions: { pie: { donut: { size: '70%' } } },
            }).render();

            const bulanLabel = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];
            let trendValues  = new Array(12).fill(0);
            if (Array.isArray(trenData)) {
                trenData.forEach(item => {
                    const idx = (item.month ?? 0) - 1;
                    if (idx >= 0 && idx < 12) trendValues[idx] = item.total_pelanggaran ?? 0;
                });
            }
            new ApexCharts(document.querySelector('#chart-trend'), {
                series: [{ name: 'Pelanggaran', data: trendValues }],
                chart: {
                    type: 'area',
                    height: 240, // Telah di-scale down dari 280
                    toolbar: { show: false },
                    fontFamily: 'Plus Jakarta Sans, sans-serif'
                },
                colors: ['#ef4444'],
                stroke: { curve: 'smooth', width: 2 },
                fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05 } },
                xaxis: { categories: bulanLabel },
                grid: { borderColor: '#f3f4f6' },
                dataLabels: { enabled: false },
                markers: { size: 4, colors: ['#ef4444'], strokeWidth: 0 },
            }).render();
        });
    </script>
    @endpush

</x-layout-app>
