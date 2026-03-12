@props(['title', 'role', 'showAcademic' => true])
<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $title ?? 'E-KESISWAAN' }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ["Plus Jakarta Sans", "sans-serif"] },
                    colors: {
                        navy: { 900: "#0f172a", 800: "#1e293b", 700: "#334155" },
                        primary: { DEFAULT: "#2563eb", hover: "#1d4ed8", light: "#eff6ff" },
                        surface: "#f8fafc",
                    },
                    boxShadow: { card: "0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.02)" },
                },
            },
        };
    </script>
    <style>
        .custom-scroll::-webkit-scrollbar { width: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 20px; }
        .fade-in { animation: fadeIn 0.4s ease-out forwards; opacity: 0; transform: translateY(10px); }
        @keyframes fadeIn { to { opacity: 1; transform: translateY(0); } }
        .sidebar-closed { margin-left: -18rem; }
        #sidebar { transition: all 0.3s ease-in-out; z-index: 50; }

        /* ── FIX UTAMA: Cegah SweetAlert2 menggeser layout ── */
        /* SweetAlert2 menambahkan padding-right & overflow:hidden ke body/html
           yang menyebabkan sidebar fixed naik/bergeser */
        html, body {
            /* Scrollbar selalu tampil agar tidak ada layout shift saat popup muncul */
            overflow-y: scroll !important;
            scrollbar-gutter: stable;
        }
        body.swal2-shown,
        html.swal2-shown {
            /* Override semua manipulasi SweetAlert2 */
            padding-right: 0 !important;
            margin-right: 0 !important;
            overflow: hidden !important;
        }
        /* Hilangkan padding yang ditambah SweetAlert ke body */
        body[style*="padding-right"] {
            padding-right: 0 !important;
        }
        /* Pastikan sidebar tidak terpengaruh overflow body */
        #sidebar {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            height: 100% !important;
        }
        /* Saat sidebar ditutup (lg) gunakan transform alih-alih margin */
        #sidebar.sidebar-closed {
            transform: translateX(-100%) !important;
            margin-left: 0 !important;
        }
    </style>
</head>
{{-- Hapus overflow-hidden dari body agar sidebar tidak terpengaruh swal --}}
<body class="bg-surface flex h-screen text-slate-600 font-sans">

    <x-sidebar :role="$role ?? 'admin'" />

    {{-- FIX: tambah padding-left untuk kompensasi sidebar fixed --}}
    <main id="main-content" class="flex-1 flex flex-col relative h-screen min-w-0 transition-all duration-300 pl-72 lg:pl-72">

        <header class="h-20 flex-none px-8 flex items-center justify-between bg-white/90 backdrop-blur-sm z-40 sticky top-0 border-b border-slate-200/50">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="p-2 rounded-lg hover:bg-slate-200 text-slate-600 focus:outline-none transition">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
                <h2 class="text-xl font-bold text-slate-800 tracking-tight">{{ $title ?? 'Dashboard' }}</h2>
            </div>

            @if($showAcademic)
                <div class="flex items-center gap-3">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider hidden md:block">
                        Tahun Pelajaran:
                    </span>

                    @if(!empty($listTahunAjaran))
                        <form method="GET" action="{{ url()->current() }}" id="formTahunAjaran">
                            @foreach(request()->except('id_tahun_ajaran') as $key => $val)
                                <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                            @endforeach

                            <select
                                id="selectTahunAjaran"
                                name="id_tahun_ajaran"
                                class="border border-slate-200 p-2 rounded-xl text-sm font-bold text-primary bg-white outline-none cursor-pointer focus:ring-2 focus:ring-primary/20 transition shadow-sm"
                            >
                                @foreach($listTahunAjaran as $ta)
                                    @php
                                        $isAktif  = ! empty($ta['is_aktif']) || ! empty($ta['aktif'])
                                                    || ($ta['status'] ?? '') === 'aktif'
                                                    || ($ta['is_active'] ?? false) === true;
                                        $label    = ($ta['tahun_ajaran'] ?? $ta['nama'] ?? 'Tahun ?')
                                                    . ' ' . ucfirst($ta['semester'] ?? '')
                                                    . ($isAktif ? ' (Aktif)' : '');
                                        $selected = request('id_tahun_ajaran')
                                            ? (string) request('id_tahun_ajaran') === (string) $ta['id']
                                            : $isAktif;
                                    @endphp
                                    <option value="{{ $ta['id'] }}" {{ $selected ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    @else
                        <span class="border border-slate-200 p-2 rounded-xl text-sm font-bold text-primary bg-white shadow-sm px-4">
                            {{ $tahunAjaranAktif['tahun_ajaran'] ?? '-' }}
                        </span>
                    @endif
                </div>
            @endif
        </header>

        <div class="flex-1 overflow-y-auto px-8 py-8 custom-scroll bg-surface">
            {{ $slot }}
        </div>

    </main>

    <script>
        function toggleSidebar() {
            const sidebar     = document.getElementById("sidebar");
            const mainContent = document.getElementById("main-content");

            if (window.innerWidth < 1024) {
                // Mobile: show/hide sidebar
                sidebar.classList.toggle("hidden");
            } else {
                // Desktop: slide sidebar in/out menggunakan transform (bukan margin)
                const isClosed = sidebar.classList.toggle("sidebar-closed");
                // Sesuaikan padding main content
                mainContent.style.paddingLeft = isClosed ? '0' : '';
                setTimeout(() => window.dispatchEvent(new Event('resize')), 300);
            }
        }

        // Mobile: sembunyikan sidebar secara default
        if (window.innerWidth < 1024) {
            document.getElementById("sidebar").classList.add("hidden");
            document.getElementById("main-content").style.paddingLeft = '0';
        }

        const selectTA = document.getElementById('selectTahunAjaran');
        if (selectTA) {
            selectTA.addEventListener('change', function () {
                document.getElementById('formTahunAjaran').submit();
            });
        }
    </script>

    @stack('scripts')
</body>
</html>
