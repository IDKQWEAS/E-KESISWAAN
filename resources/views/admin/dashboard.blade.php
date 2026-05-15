<x-layout-app title="Dashboard & Analisis" :role="$role">

    <div class="space-y-4 fade-in pb-8">

        {{-- Banner Pengaturan Tahun Pelajaran --}}
        <div class="bg-gradient-to-r from-indigo-600 to-blue-500 rounded-xl p-4 flex items-center justify-between shadow-md">
            <div>
                <h2 class="text-white font-bold text-sm">Pengaturan Tahun Pelajaran</h2>
                <p class="text-indigo-100 text-[11px] mt-0.5">Tahun yang diaktifkan akan berlaku untuk seluruh sistem.</p>
            </div>
            <button onclick="openModalTahunAjaran()"
                class="flex items-center gap-2 bg-white text-indigo-600 font-bold text-xs px-4 py-2 rounded-lg shadow hover:bg-indigo-50 transition">
                <i class="fa-solid fa-calendar-days"></i>
                Kelola Tahun
            </button>
        </div>

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
        </div>

        {{-- Proporsi & Siswa Terlambat --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="bg-white rounded-xl border border-slate-100 p-5 shadow-sm">
                <h3 class="font-bold text-sm text-slate-800 mb-3">Proporsi Ketepatan Waktu</h3>
                <div id="chart-pie" class="w-full h-[200px]"></div>
            </div>
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
                        <div class="flex items-center justify-center h-full py-6 text-xs text-slate-400">
                            Belum ada catatan siswa terlambat hari ini.
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

    {{-- ===== MODAL KELOLA TAHUN AJARAN ===== --}}
    <div id="modalTahunAjaran"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm hidden"
        onclick="handleOverlayClick(event)">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden" onclick="event.stopPropagation()">

            {{-- Header Modal --}}
            <div class="px-6 pt-6 pb-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-base">Atur Tahun Pelajaran</h3>
            </div>

            {{-- Body Modal --}}
            <div class="px-6 py-5 space-y-5">

                {{-- Form Tambah Tahun Baru --}}
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-2">Tambah Tahun Baru</label>
                    <div class="flex gap-2">
                        <input id="inputTahunAjaran" type="text" placeholder="Cth: 2026/2027"
                            class="flex-1 border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 transition" />
                        <select id="inputSemester"
                            class="border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 transition">
                            <option value="Ganjil">Ganjil</option>
                            <option value="Genap">Genap</option>
                        </select>
                        <button onclick="tambahTahunAjaran()"
                            class="w-9 h-9 flex items-center justify-center rounded-lg bg-green-500 hover:bg-green-600 text-white transition shadow-sm flex-shrink-0">
                            <i class="fa-solid fa-plus text-sm"></i>
                        </button>
                    </div>
                    <p id="errorTambah" class="text-[10px] text-red-500 mt-1 hidden"></p>
                </div>

                {{-- List Tahun Ajaran --}}
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-2">Aktifkan Tahun</label>
                    <div id="listTahunAjaran" class="space-y-2 max-h-52 overflow-y-auto custom-scroll pr-1">
                        {{-- Diisi JS --}}
                        <div class="text-xs text-slate-400 text-center py-4">Memuat data...</div>
                    </div>
                </div>

            </div>

            {{-- Footer Modal --}}
            <div class="px-6 pb-5">
                <button onclick="closeModalTahunAjaran()"
                    class="w-full py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>
    {{-- ===== END MODAL ===== --}}

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
            // ===== KELOLA TAHUN AJARAN =====

            // Data awal dari server (PHP)
            let tahunAjaranList = @json($taList ?? []);

            function renderListTahunAjaran() {
                const container = document.getElementById('listTahunAjaran');
                if (!tahunAjaranList.length) {
                    container.innerHTML = '<div class="text-xs text-slate-400 text-center py-4">Belum ada data tahun ajaran.</div>';
                    return;
                }
                container.innerHTML = tahunAjaranList.map(item => {
                    const isAktif = item.status === 'aktif';
                    return `
                        <div class="flex items-center justify-between px-3 py-2.5 rounded-lg border ${isAktif ? 'bg-indigo-50 border-indigo-200' : 'bg-white border-slate-100'} transition">
                            <span class="text-sm font-semibold ${isAktif ? 'text-indigo-700' : 'text-slate-700'}">
                                ${item.tahun_ajaran} ${item.semester}
                            </span>
                            ${isAktif
                                ? `<span class="text-[10px] font-bold px-2 py-0.5 rounded bg-indigo-500 text-white">AKTIF</span>`
                                : `<button onclick="aktifkanTahunAjaran(${item.id})"
                                       class="text-[10px] font-bold px-2 py-0.5 rounded border border-slate-300 text-slate-500 hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-600 transition">
                                       Aktifkan
                                   </button>`
                            }
                        </div>
                    `;
                }).join('');
            }

            function openModalTahunAjaran() {
                fetchTahunAjaran();
                document.getElementById('modalTahunAjaran').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeModalTahunAjaran() {
                document.getElementById('modalTahunAjaran').classList.add('hidden');
                document.body.style.overflow = '';
                document.getElementById('inputTahunAjaran').value = '';
                document.getElementById('errorTambah').classList.add('hidden');
            }

            function handleOverlayClick(e) {
                if (e.target === document.getElementById('modalTahunAjaran')) {
                    closeModalTahunAjaran();
                }
            }

            async function fetchTahunAjaran() {
                try {
                    const res = await fetch('{{ route("admin.tahun_ajaran.list") }}', {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    if (res.ok) {
                        tahunAjaranList = await res.json();
                        renderListTahunAjaran();
                    }
                } catch (e) {
                    console.error('Gagal fetch tahun ajaran', e);
                }
            }

            async function tambahTahunAjaran() {
                const tahun   = document.getElementById('inputTahunAjaran').value.trim();
                const semester = document.getElementById('inputSemester').value;
                const errEl   = document.getElementById('errorTambah');

                if (!tahun) {
                    errEl.textContent = 'Tahun ajaran tidak boleh kosong.';
                    errEl.classList.remove('hidden');
                    return;
                }
                if (!/^\d{4}\/\d{4}$/.test(tahun)) {
                    errEl.textContent = 'Format harus: 2025/2026';
                    errEl.classList.remove('hidden');
                    return;
                }
                errEl.classList.add('hidden');

                try {
                    const res = await fetch('{{ route("admin.tahun_ajaran.store") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({ tahun_ajaran: tahun, semester }),
                    });
                    const data = await res.json();
                    if (res.ok) {
                        document.getElementById('inputTahunAjaran').value = '';
                        await fetchTahunAjaran();
                    } else {
                        errEl.textContent = data.message ?? 'Gagal menambah tahun ajaran.';
                        errEl.classList.remove('hidden');
                    }
                } catch (e) {
                    errEl.textContent = 'Terjadi kesalahan jaringan.';
                    errEl.classList.remove('hidden');
                }
            }

            async function aktifkanTahunAjaran(id) {
                try {
                    const res = await fetch(`{{ url('admin/tahun_ajaran/aktifkan') }}/${id}`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });
                    if (res.ok) {
                        await fetchTahunAjaran();
                        // Reload halaman supaya statistik ikut update
                        setTimeout(() => window.location.reload(), 600);
                    }
                } catch (e) {
                    console.error('Gagal aktifkan tahun ajaran', e);
                }
            }

            // Expose ke global scope (dipanggil dari onclick HTML)
            window.openModalTahunAjaran  = openModalTahunAjaran;
            window.closeModalTahunAjaran = closeModalTahunAjaran;
            window.handleOverlayClick    = handleOverlayClick;
            window.tambahTahunAjaran     = tambahTahunAjaran;
            window.aktifkanTahunAjaran   = aktifkanTahunAjaran;

            // Render list awal dari data server (tanpa fetch ulang)
            renderListTahunAjaran();

        });
    </script>
    @endpush

</x-layout-app>
