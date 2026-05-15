<x-layout-app title="Database Siswa" role="admin">

    <div class="flex-1 overflow-y-auto p-6 custom-scroll bg-[#f8fafc]">

        {{-- HEADER --}}
        <div class="bg-white rounded-[16px] border border-slate-200 shadow-sm p-5 mb-5">

            <form action="{{ route('admin.siswa') }}"
                  method="GET"
                  id="filterForm"
                  class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-4">

                <div>

                    <h1 class="text-lg font-bold text-slate-800">
                        Database Siswa
                    </h1>

                    <p class="text-slate-500 text-xs mt-0.5">
                        Total :
                        {{ $dataSiswa['total'] ?? 0 }}
                        Siswa
                    </p>

                </div>

                <div class="flex flex-wrap items-center gap-3 w-full xl:w-auto">

                    {{-- SEARCH --}}
                    <div class="relative flex-1 xl:w-[240px]">

                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>

                        <input type="text"
                               id="liveSearchInput"
                               placeholder="Ketik Nama / NISN..."
                               class="w-full bg-slate-50 border border-transparent hover:border-slate-200 rounded-lg py-2 pl-9 pr-3 text-xs font-bold text-slate-700 placeholder:text-slate-400 outline-none focus:ring-2 focus:ring-blue-500/20 transition-all shadow-sm focus:shadow-md">

                    </div>

                    {{-- FILTER KELAS --}}
                    <div class="relative group">

                        <select name="kelas"
                                onchange="document.getElementById('filterForm').submit()"
                                class="appearance-none bg-slate-50 border border-transparent hover:border-slate-200 rounded-lg py-2 pl-3 pr-8 text-xs font-bold text-slate-700 outline-none focus:ring-2 focus:ring-blue-500/20 cursor-pointer min-w-[120px] transition-all">

                            <option value="">Semua Kelas</option>

                            @foreach([7,8,9] as $tingkat)

                                @foreach(range('A','G') as $huruf)

                                    @php
                                        $kls = $tingkat . $huruf;
                                    @endphp

                                    <option value="{{ $kls }}"
                                        {{ request('kelas') == $kls ? 'selected' : '' }}>

                                        Kelas {{ $kls }}

                                    </option>

                                @endforeach

                            @endforeach

                        </select>

                        <i class="fa-solid fa-chevron-down absolute right-3 top-3 text-[10px] text-slate-400 pointer-events-none"></i>

                    </div>

                </div>

            </form>

        </div>

        {{-- TABLE CONTAINER --}}
        {{-- TABLE CONTAINER --}}
<div id="tableContainer">

    <div class="bg-white rounded-[16px] border border-slate-200 shadow-sm overflow-hidden">

        {{-- TABLE --}}
        <div class="overflow-x-auto">

            <table class="w-full border-collapse table-fixed">

                <thead class="border-b border-slate-100 bg-white">

                    <tr>

                        {{-- IDENTITAS --}}
                        <th class="w-[60%] py-4 pl-6 pr-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-left">
                            IDENTITAS SISWA
                        </th>

                        {{-- KELAS --}}
                        <th class="w-[20%] py-4 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">
                            KELAS
                        </th>

                        {{-- GENDER --}}
                        <th class="w-[20%] py-4 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">
                            GENDER
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-50">

                    @forelse($dataSiswa['data'] ?? [] as $siswa)

                        <tr class="hover:bg-slate-50 transition">

                            {{-- IDENTITAS --}}
                            <td class="w-[60%] py-3 pl-6 pr-4">

                                <div class="flex items-center gap-3 overflow-hidden">

                                    @php

                                        $genderColor =
                                            ($siswa['jenis_kelamin'] ?? '') == 'P'
                                            ? 'bg-pink-100 text-pink-600'
                                            : 'bg-[#dbeafe] text-[#2563eb]';

                                        $nama = $siswa['nama'] ?? 'X';

                                        $words = explode(' ', $nama);

                                        $initials =
                                            substr($words[0],0,1) .
                                            (isset($words[1]) ? substr($words[1],0,1) : '');

                                    @endphp

                                    <div class="w-9 h-9 min-w-[36px] rounded-full {{ $genderColor }} flex items-center justify-center font-bold text-[10px] uppercase">

                                        {{ $initials }}

                                    </div>

                                    <div class="overflow-hidden">

                                        <h4 class="text-sm font-bold text-slate-800 uppercase truncate">

                                            {{ $siswa['nama'] ?? '-' }}

                                        </h4>

                                        <p class="text-[10px] text-slate-400 truncate">

                                            NISN :
                                            {{ $siswa['nisn'] ?? '-' }}

                                        </p>

                                    </div>

                                </div>

                            </td>

                            {{-- KELAS --}}
                            <td class="w-[20%] py-3 px-4 text-center">

                                <span class="bg-slate-100 text-slate-600 border border-slate-200 px-2.5 py-1 rounded-md text-xs font-bold inline-block min-w-[50px]">

                                    {{ $siswa['kelas'] ?? '-' }}

                                </span>

                            </td>

                            {{-- GENDER --}}
                            <td class="w-[20%] py-3 px-4 text-center">

                                @if(($siswa['jenis_kelamin'] ?? '') == 'L')

                                    <span class="bg-[#eff6ff] text-[#2563eb] border border-blue-100 px-3 py-1 rounded-md text-[10px] font-bold inline-flex items-center justify-center gap-1 min-w-[100px]">

                                        <i class="fa-solid fa-mars text-[9px]"></i>

                                        Laki-laki

                                    </span>

                                @else

                                    <span class="bg-pink-50 text-pink-600 border border-pink-100 px-3 py-1 rounded-md text-[10px] font-bold inline-flex items-center justify-center gap-1 min-w-[100px]">

                                        <i class="fa-solid fa-venus text-[9px]"></i>

                                        Perempuan

                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="3"
                                class="py-10 text-center text-slate-400 text-xs italic">

                                Tidak ada data siswa

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- PAGINATION --}}
        <div class="flex flex-col md:flex-row justify-between items-center gap-3 p-4 border-t border-slate-100 bg-white">

            <p class="text-slate-500 text-[10px] font-bold uppercase">

                Halaman

                {{ $dataSiswa['current_page'] ?? 1 }}

                dari

                {{ $dataSiswa['last_page'] ?? 1 }}

            </p>

            <div class="flex items-center gap-2">

                {{-- PREV --}}
                @if(($dataSiswa['current_page'] ?? 1) > 1)

                    <a href="{{ request()->fullUrlWithQuery([
                        'page' => ($dataSiswa['current_page'] ?? 1) - 1
                    ]) }}"
                    class="pagination-link px-3 py-1.5 border border-slate-200 rounded-xl text-slate-600 text-xs font-bold hover:bg-slate-50 transition">

                        Prev

                    </a>

                @else

                    <span class="px-3 py-1.5 border border-slate-200 rounded-xl text-slate-300 text-xs font-bold">

                        Prev

                    </span>

                @endif

                {{-- CURRENT PAGE --}}
                <div class="w-9 h-9 bg-[#2563eb] text-white rounded-xl flex items-center justify-center text-xs font-bold shadow-md">

                    {{ $dataSiswa['current_page'] ?? 1 }}

                </div>

                {{-- NEXT --}}
                @if(($dataSiswa['current_page'] ?? 1) < ($dataSiswa['last_page'] ?? 1))

                    <a href="{{ request()->fullUrlWithQuery([
                        'page' => ($dataSiswa['current_page'] ?? 1) + 1
                    ]) }}"
                    class="pagination-link px-3 py-1.5 border border-slate-200 rounded-xl text-slate-600 text-xs font-bold hover:bg-slate-50 transition">

                        Next

                    </a>

                @else

                    <span class="px-3 py-1.5 border border-slate-200 rounded-xl text-slate-300 text-xs font-bold">

                        Next

                    </span>

                @endif

            </div>

        </div>

    </div>

</div>

    </div>

    {{-- AJAX PAGINATION --}}
    <script>

    function initSearch() {

        const searchInput = document.getElementById('liveSearchInput');

        if (!searchInput) return;

        searchInput.addEventListener('keyup', function() {

            const keyword = this.value.toLowerCase();

            const rows = document.querySelectorAll('tbody tr');

            rows.forEach(row => {

                const text = row.innerText.toLowerCase();

                if (text.includes(keyword)) {

                    row.style.display = '';

                } else {

                    row.style.display = 'none';

                }

            });

        });

    }

    async function loadPagination(url) {

        try {

            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            const parser = new DOMParser();

            const doc = parser.parseFromString(data.table, 'text/html');

            const newTable =
                doc.querySelector('#tableContainer').innerHTML;

            document.querySelector('#tableContainer').innerHTML = newTable;

            window.history.pushState({}, '', url);

            initSearch();

        } catch(err) {

            console.error(err);

        }

    }

    document.addEventListener('click', function(e) {

        const link = e.target.closest('.pagination-link');

        if (!link) return;

        e.preventDefault();

        loadPagination(link.href);

    });

    document.addEventListener('DOMContentLoaded', function() {

        initSearch();

    });

</script>

</x-layout-app>