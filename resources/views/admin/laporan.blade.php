<x-layout-app title="Cetak Laporan" role="admin">
    {{-- Padding dikurangi dari p-8 ke p-6 --}}
    <div class="flex-1 overflow-y-auto p-6 custom-scroll bg-[#f8fafc]">
        
        <div class="mx-auto space-y-6">

            {{-- Header Section (Text 2xl -> xl, sm -> xs, gap 4 -> 3) --}}
            <div class="flex flex-col md:flex-row justify-between items-end gap-3">
                <div>
                    <h1 class="text-xl font-bold text-slate-800 mb-1">Pusat Laporan</h1>
                    <p class="text-slate-500 text-xs">
                        Unduh rekapitulasi data berdasarkan kelas dan tahun ajaran.
                    </p>
                </div>
                
                {{-- Dropdowns --}}
                <div class="flex flex-wrap gap-2.5">
                    {{-- Dropdown Tahun Ajaran --}}
                    <div class="relative group">
                        {{-- Label 10px -> 8px --}}
                        <label class="block text-[8px] font-black text-slate-400 uppercase mb-1 ml-1">Tahun Ajaran</label>
                        <select id="filterTA" class="appearance-none bg-white border border-slate-200 rounded-xl py-2 pl-4 pr-9 text-xs font-bold text-slate-700 outline-none focus:ring-2 focus:ring-blue-500/20 cursor-pointer min-w-[170px] transition-all shadow-sm">
                            @foreach($taList as $ta)
                                <option value="{{ $ta['id'] }}" {{ $ta['status'] == 'aktif' ? 'selected' : '' }}>
                                    {{ $ta['tahun_ajaran'] }} - {{ ucfirst($ta['semester']) }}
                                </option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-3.5 top-7 text-[10px] text-slate-400 pointer-events-none"></i>
                    </div>

                    {{-- Dropdown Kelas --}}
                    <div class="relative group">
                        <label class="block text-[8px] font-black text-slate-400 uppercase mb-1 ml-1">Filter Kelas</label>
                        <select id="filterKelas" class="appearance-none bg-white border border-slate-200 rounded-xl py-2 pl-4 pr-9 text-xs font-bold text-slate-700 outline-none focus:ring-2 focus:ring-blue-500/20 cursor-pointer min-w-[130px] transition-all shadow-sm">
                            <option value="">Semua Kelas</option>
                            @foreach(['7A','7B','7C','7D','7E','7F','7G','8A','8B','8C','8D','8E','8F','8G','9A','9B','9C','9D','9E','9F','9G'] as $k)
                                <option value="{{ $k }}">Kelas {{ $k }}</option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-3.5 top-7 text-[10px] text-slate-400 pointer-events-none"></i>
                    </div>
                </div>
            </div>

            {{-- Grid Section (Gap 8 -> 6) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- KARTU LAPORAN --}}
                @php $reports = [
                    ['type' => 'absensi', 'title' => 'Laporan Absensi', 'icon' => 'fa-file-pdf', 'color' => 'red'],
                    ['type' => 'pelanggaran', 'title' => 'Laporan Pelanggaran', 'icon' => 'fa-file-invoice', 'color' => 'green'],
                    ['type' => 'prestasi', 'title' => 'Laporan Prestasi', 'icon' => 'fa-trophy', 'color' => 'yellow']
                ]; @endphp

                @foreach($reports as $rpt)
                {{-- Padding p-10 -> p-8, Rounded [2rem] -> [1.5rem] --}}
                <div class="bg-white p-8 rounded-[1.5rem] shadow-sm border border-slate-200 text-center hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1 bg-{{ $rpt['color'] }}-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-center"></div>
                    
                    {{-- Icon Container w-24/h-24 -> w-20/h-20, Icon 4xl -> 3xl --}}
                    <div class="w-20 h-20 bg-{{ $rpt['color'] }}-50 rounded-full flex items-center justify-center mx-auto mb-5 group-hover:bg-{{ $rpt['color'] }}-100 transition-colors duration-300">
                        <i class="fa-solid {{ $rpt['icon'] }} text-3xl text-{{ $rpt['color'] }}-500"></i>
                    </div>
                    
                    {{-- Title text-xl -> text-lg --}}
                    <h3 class="font-bold text-slate-800 text-lg mb-6 tracking-tight">{{ $rpt['title'] }}</h3>
                    
                    {{-- Buttons py-2.5 -> py-2, text-11px -> 9px --}}
                    <div class="flex gap-2.5 justify-center">
                        <button onclick="triggerDownload('{{ $rpt['type'] }}', 'pdf')" class="px-5 py-2 border border-red-100 text-red-600 rounded-full text-[9px] font-black uppercase tracking-widest hover:bg-red-50 transition block">
                            PDF
                        </button>
                        <button onclick="triggerDownload('{{ $rpt['type'] }}', 'excel')" class="px-5 py-2 border border-green-100 text-green-600 rounded-full text-[9px] font-black uppercase tracking-widest hover:bg-green-50 transition block">
                            EXCEL
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div> 
    </div>

    @push('scripts')
    <script>
        function triggerDownload(type, format) {
            const kelas = document.getElementById('filterKelas').value;
            const idTA = document.getElementById('filterTA').value;

            // Susun URL dengan query string
            const baseUrl = "{{ url('/admin/laporan/download') }}";
            const fullUrl = `${baseUrl}/${type}/${format}?kelas=${kelas}&id_tahun_ajaran=${idTA}`;

            // Tampilkan Loading Swal untuk UX
            Swal.fire({
                title: 'Menyiapkan File...',
                text: 'Laporan sedang diunduh',
                timer: 2000,
                showConfirmButton: false,
                didOpen: () => { Swal.showLoading() }
            });

            window.location.href = fullUrl;
        }

        const errorMessage = "{{ session('error') }}";
        if (errorMessage) {
            Swal.fire({
                title: 'Gagal Download',
                text: errorMessage,
                icon: 'error',
                confirmButtonColor: '#2563eb'
            });
        }
    </script>
    @endpush
</x-layout-app>