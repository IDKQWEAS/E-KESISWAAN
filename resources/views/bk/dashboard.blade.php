<x-layout-app title="Dashboard & Analisis BK" role="bk">
    
    <header class="h-20 flex-none px-8 flex items-center justify-between bg-white/90 backdrop-blur-sm z-40 sticky top-0 border-b border-slate-200/50">
        <div>
            <h2 class="text-xl font-bold text-slate-800 tracking-tight" id="pageTitle">Dashboard & Analisis</h2>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tahun Pelajaran:</span>
            <select class="border border-slate-200 p-2 rounded-xl text-sm font-bold text-primary bg-white outline-none cursor-pointer focus:ring-2 focus:ring-primary/20 transition shadow-sm">
                <option>2025/2026 Ganjil (Aktif)</option>
                <option>2024/2025 Genap</option>
            </select>
        </div>
    </header>

    <div class="flex-1 overflow-y-auto px-8 py-8 custom-scroll bg-surface">
        <div class="space-y-8 fade-in pb-10">
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-3xl shadow-card border border-slate-200 flex justify-between items-center hover:shadow-lg transition">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Siswa</p>
                        <h3 class="text-3xl font-bold text-slate-800 mt-1">320</h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl"><i class="fa-solid fa-users"></i></div>
                </div>
                <div class="bg-white p-6 rounded-3xl shadow-card border border-slate-200 flex justify-between items-center hover:shadow-lg transition">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Hadir Hari Ini</p>
                        <h3 class="text-3xl font-bold text-green-600 mt-1">305</h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-green-50 text-green-600 flex items-center justify-center text-xl"><i class="fa-solid fa-user-check"></i></div>
                </div>
                <div class="bg-white p-6 rounded-3xl shadow-card border border-slate-200 flex justify-between items-center hover:shadow-lg transition">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Rata-rata Datang</p>
                        <h3 class="text-3xl font-bold text-indigo-600 mt-1">06:40</h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl"><i class="fa-solid fa-clock"></i></div>
                </div>
                <div class="bg-white p-6 rounded-3xl shadow-card border border-slate-200 flex justify-between items-center hover:shadow-lg transition">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Siswa Terlambat</p>
                        <h3 class="text-3xl font-bold text-red-600 mt-1">15</h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center text-xl"><i class="fa-solid fa-person-running"></i></div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-card border border-slate-200">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="font-bold text-lg text-slate-800">Statistik Kehadiran Per Tingkat</h3>
                        <p class="text-xs text-slate-500">Perbandingan kehadiran kelas 7, 8, 9.</p>
                    </div>
                    <div class="flex gap-2 bg-slate-100 p-1 rounded-xl">
                        <button class="px-4 py-1.5 bg-white text-slate-800 text-xs font-bold rounded-lg shadow-sm">Harian</button>
                        <button class="px-4 py-1.5 text-slate-500 text-xs font-medium hover:text-slate-800">Mingguan</button>
                        <button class="px-4 py-1.5 text-slate-500 text-xs font-medium hover:text-slate-800">Bulanan</button>
                    </div>
                </div>
                <div id="chart-attendance" class="w-full h-[350px]"></div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-white p-8 rounded-3xl shadow-card border border-slate-200">
                    <h3 class="font-bold text-slate-800 mb-6">Proporsi Ketepatan Waktu</h3>
                    <div id="chart-pie" class="w-full h-[250px] flex justify-center"></div>
                </div>
                
                <div class="bg-white p-8 rounded-3xl shadow-card border border-slate-200 flex flex-col">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="font-bold text-slate-800">Siswa Terlambat Terkini</h3>
                        <span class="text-[10px] bg-red-100 text-red-600 px-2 py-1 rounded-lg font-bold animate-pulse">Live Update</span>
                    </div>
                    <div class="flex-1 overflow-y-auto custom-scroll pr-2 space-y-3">
                        <div class="flex items-center justify-between p-4 bg-red-50 rounded-2xl border border-red-100">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-red-200 text-red-700 flex items-center justify-center font-bold text-sm">7A</div>
                                <div>
                                    <p class="font-bold text-slate-800 text-sm">Andi Saputra</p>
                                    <p class="text-[10px] text-red-500 font-bold">Telat 15 Menit</p>
                                </div>
                            </div>
                            <span class="text-xs font-mono font-bold text-slate-600">07:15</span>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-red-50 rounded-2xl border border-red-100">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-red-200 text-red-700 flex items-center justify-center font-bold text-sm">8C</div>
                                <div>
                                    <p class="font-bold text-slate-800 text-sm">Budi Santoso</p>
                                    <p class="text-[10px] text-red-500 font-bold">Telat 20 Menit</p>
                                </div>
                            </div>
                            <span class="text-xs font-mono font-bold text-slate-600">07:20</span>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-red-50 rounded-2xl border border-red-100">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-red-200 text-red-700 flex items-center justify-center font-bold text-sm">9A</div>
                                <div>
                                    <p class="font-bold text-slate-800 text-sm">Doni Tata</p>
                                    <p class="text-[10px] text-red-500 font-bold">Telat 5 Menit</p>
                                </div>
                            </div>
                            <span class="text-xs font-mono font-bold text-slate-600">07:05</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-card mt-8">
                <div class="flex justify-between items-end mb-6">
                    <div>
                        <h3 class="font-bold text-slate-800 text-lg">Peta Tren Pelanggaran</h3>
                        <p class="text-xs text-slate-500">Tren pelanggaran siswa semester ini.</p>
                    </div>
                    <select class="border p-2 rounded-xl text-xs bg-white focus:outline-none cursor-pointer">
                        <option>Semester Genap 2026</option>
                    </select>
                </div>
                <div id="chart-trend" class="w-full h-[350px]"></div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        // Chart Kehadiran Per Tingkat (Bar)
        new ApexCharts(document.querySelector("#chart-attendance"), {
            series: [
                { name: "Hadir", data: [120, 115, 118] },
                { name: "Izin", data: [5, 3, 4] },
                { name: "Sakit", data: [2, 1, 3] },
                { name: "Alfa", data: [1, 2, 0] },
            ],
            chart: { type: "bar", height: 350, toolbar: { show: false }, fontFamily: "Plus Jakarta Sans, sans-serif" },
            colors: ["#22c55e", "#3b82f6", "#eab308", "#ef4444"],
            plotOptions: { bar: { horizontal: false, columnWidth: "55%", borderRadius: 4 } },
            xaxis: { categories: ["Kelas 7", "Kelas 8", "Kelas 9"] },
            grid: { borderColor: "#f1f5f9" },
        }).render();

        // Chart Proporsi (Donut)
        new ApexCharts(document.querySelector("#chart-pie"), {
            series: [95, 5],
            labels: ["Tepat Waktu", "Terlambat"],
            chart: { type: "donut", height: 250, fontFamily: "Plus Jakarta Sans, sans-serif" },
            colors: ["#3b82f6", "#ef4444"],
            legend: { position: "bottom" },
            dataLabels: { enabled: false },
        }).render();

        // Chart Tren Pelanggaran (Area)
        new ApexCharts(document.querySelector("#chart-trend"), {
            series: [{ name: "Pelanggaran", data: [30, 40, 35, 50, 49, 60] }],
            chart: { type: "area", height: 350, toolbar: { show: false }, fontFamily: "Plus Jakarta Sans, sans-serif" },
            colors: ["#ef4444"],
            stroke: { curve: "smooth", width: 3 },
            fill: { type: "gradient", gradient: { shadeIntensity: 1, opacityFrom: 0.7, opacityTo: 0.3 } },
            xaxis: { categories: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun"] },
            grid: { borderColor: "#f3f4f6" },
        }).render();
    </script>
    @endpush

</x-layout-app>