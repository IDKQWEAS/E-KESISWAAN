<x-layout-app title="Admin Dashboard" role="admin">
    
    <header class="h-20 flex-none px-8 flex items-center justify-between bg-white border-b border-slate-200 z-20">
        <div>
            <h2 class="text-xl font-bold text-slate-800 tracking-tight">Dashboard Overview</h2>
            <p class="text-xs text-slate-500">Monitoring data sekolah real-time.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="px-4 py-2 bg-slate-50 border border-slate-200 text-slate-600 rounded-xl text-xs font-bold flex items-center gap-2">
                <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                Online System
            </div>
        </div>
    </header>

    <div class="flex-1 overflow-y-auto p-8 custom-scroll">
        
        <div class="space-y-8 pb-10"> <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-6 rounded-3xl shadow-lg text-white flex flex-col md:flex-row justify-between items-center gap-4 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full -mr-16 -mt-16 pointer-events-none"></div>
                <div class="relative z-10">
                    <h3 class="text-lg font-bold flex items-center gap-2">
                        <i class="fa-solid fa-calendar-days"></i> Pengaturan Tahun Pelajaran
                    </h3>
                    <p class="text-blue-100 text-sm mt-1 opacity-90 max-w-xl">
                        Tahun ajaran aktif: <span class="font-bold text-white bg-white/20 px-2 py-0.5 rounded">2025/2026 Ganjil</span>.
                    </p>
                </div>
                <button onclick="toggleModal('modalTahun')" class="relative z-10 bg-white text-blue-700 px-6 py-3 rounded-xl text-sm font-bold hover:bg-blue-50 transition shadow-sm flex items-center gap-2 whitespace-nowrap">
                    <i class="fa-solid fa-gear"></i> Kelola Tahun
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex justify-between items-center hover:shadow-md transition">
                    <div><p class="text-xs font-bold text-slate-400 uppercase">Total Siswa</p><h3 class="text-3xl font-bold text-slate-800 mt-1">320</h3></div>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl"><i class="fa-solid fa-users"></i></div>
                </div>
                <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex justify-between items-center hover:shadow-md transition">
                    <div><p class="text-xs font-bold text-slate-400 uppercase">Hadir Hari Ini</p><h3 class="text-3xl font-bold text-green-600 mt-1">305</h3></div>
                    <div class="w-12 h-12 rounded-xl bg-green-50 text-green-600 flex items-center justify-center text-xl"><i class="fa-solid fa-user-check"></i></div>
                </div>
                <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex justify-between items-center hover:shadow-md transition">
                    <div><p class="text-xs font-bold text-slate-400 uppercase">Rata-rata Datang</p><h3 class="text-3xl font-bold text-indigo-600 mt-1">06:40</h3></div>
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl"><i class="fa-solid fa-clock"></i></div>
                </div>
                <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex justify-between items-center hover:shadow-md transition">
                    <div><p class="text-xs font-bold text-slate-400 uppercase">Terlambat</p><h3 class="text-3xl font-bold text-red-600 mt-1">15</h3></div>
                    <div class="w-12 h-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center text-xl"><i class="fa-solid fa-person-running"></i></div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold text-lg text-slate-800">Statistik Kehadiran Mingguan</h3>
                    <select class="text-xs border border-slate-200 rounded-lg p-2 bg-slate-50 font-bold text-slate-600 outline-none"><option>Minggu Ini</option></select>
                </div>
                <div id="chart-attendance" class="w-full h-[320px]"></div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm flex flex-col justify-between">
                    <div><h3 class="font-bold text-lg text-slate-800 mb-2">Proporsi Ketepatan Waktu</h3></div>
                    <div id="chart-pie" class="w-full h-[250px] flex justify-center"></div>
                </div>

                <div class="lg:col-span-2 bg-white p-8 rounded-3xl border border-slate-200 shadow-sm flex flex-col">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="font-bold text-lg text-slate-800">Siswa Terlambat Terkini</h3>
                        <span class="text-[10px] bg-red-50 text-red-600 px-3 py-1 rounded-full font-bold animate-pulse border border-red-100">Live Update</span>
                    </div>
                    <div class="flex-1 space-y-3">
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-700 flex items-center justify-center font-bold text-sm">9A</div>
                                <div><p class="font-bold text-slate-800 text-sm">Doni Tata</p><p class="text-[10px] text-red-500 font-bold">Telat 15 Menit</p></div>
                            </div>
                            <span class="text-xs font-mono font-bold text-slate-600">07:15</span>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-700 flex items-center justify-center font-bold text-sm">8C</div>
                                <div><p class="font-bold text-slate-800 text-sm">Siti Aminah</p><p class="text-[10px] text-red-500 font-bold">Telat 5 Menit</p></div>
                            </div>
                            <span class="text-xs font-mono font-bold text-slate-600">07:05</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm">
                <h3 class="font-bold text-lg text-slate-800 mb-6">Peta Tren Pelanggaran</h3>
                <div id="chart-trend" class="w-full h-[300px]"></div>
            </div>
        
        </div> </div> <div id="modalTahun" class="fixed inset-0 bg-navy-900/50 hidden items-center justify-center z-50 backdrop-blur-sm">
        <div class="bg-white rounded-3xl w-full max-w-md shadow-2xl p-6">
            <h3 class="font-bold text-lg text-slate-800 mb-4">Atur Tahun Pelajaran</h3>
            <div class="space-y-4">
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200">
                    <label class="text-[10px] font-bold text-slate-500 uppercase mb-2 block">Tambah Baru</label>
                    <div class="flex gap-2">
                        <input type="text" placeholder="2026/2027" class="flex-1 border border-slate-300 rounded-xl px-3 py-2 text-sm font-bold">
                        <button class="bg-primary text-white px-4 rounded-xl font-bold"><i class="fa-solid fa-plus"></i></button>
                    </div>
                </div>
                <button onclick="toggleModal('modalTahun')" class="w-full py-3 text-slate-500 font-bold text-sm hover:bg-slate-50 rounded-xl">Tutup</button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleModal(id) {
            const el = document.getElementById(id);
            el.classList.toggle('hidden');
            el.classList.toggle('flex');
        }

        // 1. Bar Chart
        new ApexCharts(document.querySelector("#chart-attendance"), {
            series: [{ name: "Hadir", data: [310, 305, 315, 300, 320] }, { name: "Terlambat", data: [10, 15, 5, 20, 0] }],
            chart: { type: "bar", height: 320, toolbar: { show: false }, fontFamily: "Plus Jakarta Sans, sans-serif" },
            colors: ["#22c55e", "#ef4444"],
            plotOptions: { bar: { borderRadius: 6, columnWidth: '50%' } },
            stroke: { show: true, width: 2, colors: ['transparent'] },
            xaxis: { categories: ["Senin", "Selasa", "Rabu", "Kamis", "Jumat"] },
            grid: { borderColor: '#f1f5f9' }
        }).render();

        // 2. Donut Chart
        new ApexCharts(document.querySelector("#chart-pie"), {
            series: [95, 5], labels: ["Tepat Waktu", "Terlambat"],
            chart: { type: "donut", height: 260, fontFamily: "Plus Jakarta Sans, sans-serif" },
            colors: ["#3b82f6", "#ef4444"],
            legend: { position: "bottom" },
            dataLabels: { enabled: false }
        }).render();

        // 3. Area Chart
        new ApexCharts(document.querySelector("#chart-trend"), {
            series: [{ name: "Poin Pelanggaran", data: [20, 45, 30, 60, 40, 75, 50] }],
            chart: { type: "area", height: 300, toolbar: { show: false }, fontFamily: "Plus Jakarta Sans, sans-serif" },
            colors: ["#ef4444"],
            stroke: { curve: 'smooth', width: 3 },
            fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [0, 90, 100] } },
            xaxis: { categories: ["Mg 1", "Mg 2", "Mg 3", "Mg 4", "Mg 5", "Mg 6", "Mg 7"] },
            grid: { borderColor: '#f1f5f9' }
        }).render();
    </script>
    @endpush
</x-layout-app>