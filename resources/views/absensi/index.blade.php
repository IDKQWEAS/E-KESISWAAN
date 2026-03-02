<!doctype html>
<html role="absem">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Monitoring Absensi - E-KESISWAAN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
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
                    boxShadow: { soft: "0 4px 20px -2px rgba(0, 0, 0, 0.05)" },
                },
            },
        };
    </script>
    <style>
        .custom-scroll::-webkit-scrollbar { width: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 20px; }
        
        /* Animasi Masuk */
        .fade-in { animation: fadeIn 0.4s ease-out forwards; opacity: 0; transform: translateY(10px); }
        @keyframes fadeIn { to { opacity: 1; transform: translateY(0); } }

        /* Posisi dan Style Pop-up Notifikasi (Toast) agar di tengah atas */
        div:where(.swal2-container).swal2-top {
            top: 10px !important;
            width: auto !important;
            min-width: 320px;
            max-width: 600px;
        }
        div:where(.swal2-popup).swal2-toast {
            padding: 0.5rem 1rem !important;
            border-radius: 1rem !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
            display: flex !important;
            align-items: center !important;
            gap: 0.75rem !important;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        .swal2-icon { border-width: 2px !important; transform: scale(0.8) !important; margin: 0 !important; }
        .swal2-input { margin: 1em auto !important; width: 80% !important; }
    </style>
</head>
<body class="bg-surface text-slate-600 font-sans min-h-screen flex flex-col">
    
    <header class="h-20 bg-white border-b border-slate-200 px-8 flex items-center justify-between z-50 sticky top-0 relative">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center shadow-lg shadow-blue-200">
                <i class="fa-solid fa-qrcode text-white text-lg"></i>
            </div>
            <div>
                <h1 class="font-bold text-xl text-slate-800 tracking-tight leading-none">MONITORING ABSENSI</h1>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">SMP NEGERI 1 POLANHARJO</p>
            </div>
        </div>

        <div class="flex items-center gap-8">
            <div class="text-right hidden md:block">
                <p class="text-xs font-bold text-slate-500 mb-0.5" id="currentDate">Senin, 12 Januari 2026</p>
                <p class="text-2xl font-bold text-primary font-mono leading-none tracking-tight" id="clock">07:00:00</p>
            </div>
            <div class="h-10 w-px bg-slate-200"></div>
            <a href="/" class="flex items-center gap-2 text-red-500 hover:text-red-600 font-bold text-sm transition group">
                <div class="w-9 h-9 rounded-xl bg-red-50 border border-red-100 flex items-center justify-center group-hover:bg-red-500 group-hover:text-white transition">
                    <i class="fa-solid fa-right-from-bracket text-sm"></i>
                </div>
            </a>
        </div>
    </header>

    <main class="flex-1 p-6 md:px-12 md:py-8 overflow-hidden flex flex-col">
        <div class="flex-1 bg-white rounded-[2rem] shadow-soft border border-slate-200 flex flex-col overflow-hidden fade-in">
            
            <div class="p-8 border-b border-slate-100 bg-white">
                <div class="flex flex-col md:flex-row gap-4 items-center">
                    
                    <div class="flex-1 relative shadow-sm rounded-xl group focus-within:shadow-md transition-all duration-300 border border-slate-300 flex w-full h-[60px]">
                        <div class="pl-5 pr-4 flex items-center justify-center bg-slate-50 rounded-l-xl border-r border-slate-200">
                            <i class="fa-solid fa-barcode text-2xl text-slate-400"></i>
                        </div>
                        <input type="text" id="nisnInput" class="flex-1 px-4 bg-white text-lg font-bold text-slate-700 placeholder:text-slate-300 placeholder:font-medium focus:outline-none" placeholder="Scan Barcode / Ketik NISN Siswa..." autofocus autocomplete="off" onkeypress="if (event.key === 'Enter') handleManualAbsen();" />
                        <button onclick="handleManualAbsen()" class="hidden md:flex px-8 bg-primary hover:bg-blue-700 text-white font-bold rounded-r-xl text-sm transition items-center gap-2">
                            ABSEN <i class="fa-solid fa-check"></i>
                        </button>
                    </div>

                    <button onclick="cancelAttendance()" class="h-[60px] px-6 bg-white text-red-600 border-2 border-red-100 hover:border-red-200 hover:bg-red-50 rounded-xl text-sm font-bold transition flex items-center justify-center gap-2 shadow-sm whitespace-nowrap w-full md:w-auto">
                        <i class="fa-solid fa-user-xmark text-lg"></i>
                        <span>Batalkan Absensi</span>
                    </button>
                </div>
                <p class="text-[10px] text-slate-400 mt-2 ml-1 italic">*Pastikan kursor aktif di kolom input saat melakukan scanning.</p>
            </div>

            <div class="flex-1 overflow-y-auto custom-scroll bg-white">
                <table class="w-full text-left border-collapse" id="absensiTable">
                    <thead class="bg-slate-50 text-slate-500 text-[11px] uppercase font-bold tracking-wider sticky top-0 z-10 border-b border-slate-100">
                        <tr>
                            <th onclick="sortTable(0)" class="px-8 py-4 cursor-pointer hover:bg-slate-100 transition select-none group whitespace-nowrap">
                                <div class="flex items-center justify-between gap-4">
                                    <span>WAKTU DATANG</span>
                                    <div class="flex items-center gap-2">
                                        <span id="sortLabel0" class="text-[9px] text-primary font-bold hidden normal-case"></span>
                                        <div class="w-6 h-6 rounded flex items-center justify-center transition-colors group-hover:bg-white group-hover:border group-hover:border-slate-200">
                                            <i id="sortIcon0" class="fa-solid fa-sort text-slate-400"></i>
                                        </div>
                                    </div>
                                </div>
                            </th>
                            <th class="px-8 py-4 whitespace-nowrap">NISN</th>
                            <th class="px-8 py-4 whitespace-nowrap">NAMA SISWA</th>
                            <th onclick="sortTable(3)" class="px-8 py-4 cursor-pointer hover:bg-slate-100 transition select-none group whitespace-nowrap">
                                <div class="flex items-center justify-between gap-4">
                                    <span>KELAS</span>
                                    <div class="flex items-center gap-2">
                                        <span id="sortLabel3" class="text-[9px] text-primary font-bold hidden normal-case"></span>
                                        <div class="w-6 h-6 rounded flex items-center justify-center transition-colors group-hover:bg-white group-hover:border group-hover:border-slate-200">
                                            <i id="sortIcon3" class="fa-solid fa-sort text-slate-400"></i>
                                        </div>
                                    </div>
                                </div>
                            </th>
                            <th class="px-8 py-4 text-right whitespace-nowrap">STATUS</th>
                        </tr>
                    </thead>
                    <tbody id="attendanceTableBody" class="divide-y divide-slate-50 text-sm">
                        <tr class="hover:bg-blue-50/30 transition group">
                            <td class="px-8 py-5 font-mono text-slate-500 font-bold whitespace-nowrap">06:30:15</td>
                            <td class="px-8 py-5 font-bold text-blue-600 font-mono">12345670</td>
                            <td class="px-8 py-5 font-bold text-slate-700 whitespace-nowrap">Siti Aminah</td>
                            <td class="px-8 py-5"><span class="bg-slate-100 text-slate-600 border border-slate-200 px-2.5 py-1 rounded-lg text-xs font-bold">7B</span></td>
                            <td class="px-8 py-5 text-right whitespace-nowrap">
                                <span class="bg-green-100 text-green-700 border border-green-200 px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wide shadow-sm">Tepat Waktu</span>
                            </td>
                        </tr>
                        <tr class="hover:bg-red-50/30 transition group bg-red-50/10">
                            <td class="px-8 py-5 font-mono text-red-500 font-bold whitespace-nowrap">07:15:22</td>
                            <td class="px-8 py-5 font-bold text-slate-600 font-mono">12349999</td>
                            <td class="px-8 py-5 font-bold text-slate-700 whitespace-nowrap">Doni Tata</td>
                            <td class="px-8 py-5"><span class="bg-white text-red-500 border border-red-100 px-2.5 py-1 rounded-lg text-xs font-bold">9A</span></td>
                            <td class="px-8 py-5 text-right whitespace-nowrap">
                                <span class="bg-red-500 text-white px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wide shadow-md shadow-red-200">Terlambat</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="px-8 py-4 border-t border-slate-100 flex justify-between items-center text-xs text-slate-400 bg-white">
                <span>Menampilkan data hari ini</span>
                <div class="flex gap-1">
                    <button class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 text-slate-600 transition disabled:opacity-50" disabled>Prev</button>
                    <button class="px-3 py-1.5 bg-primary text-white rounded-lg shadow-md font-bold">1</button>
                    <button class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 text-slate-600 transition">Next</button>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Mengatur Jam Digital di Header
        setInterval(() => {
            const now = new Date();
            document.getElementById("clock").innerText = now.toLocaleTimeString("id-ID", { hour12: false });
            const options = { weekday: "long", year: "numeric", month: "long", day: "numeric" };
            document.getElementById("currentDate").innerText = now.toLocaleDateString("id-ID", options);
        }, 1000);

        // Pengaturan Pop-up Toast SweetAlert (Notifikasi Scan)
        const Toast = Swal.mixin({
            toast: true,
            position: "top",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: false,
            didOpen: (toast) => {
                toast.addEventListener("mouseenter", Swal.stopTimer);
                toast.addEventListener("mouseleave", Swal.resumeTimer);
            },
        });

        // Logika Input Manual / Scan Barcode
        function handleManualAbsen() {
            const input = document.getElementById("nisnInput");
            const nisn = input.value.trim();

            if (!nisn) {
                Toast.fire({
                    icon: "error",
                    html: `<div class="flex items-center gap-2"><span class="font-bold text-slate-800">Gagal:</span><span class="text-slate-600 text-sm">Input kosong. Silakan scan ulang.</span></div>`,
                    background: "#fef2f2", color: "#991b1b", iconColor: "#ef4444",
                });
                return;
            }

            // Dummy Logic (Ubah ke Ajax Call di Laravel)
            if (nisn === "123") {
                Toast.fire({
                    icon: "success",
                    html: `
                        <div class="flex flex-col items-start gap-1">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-slate-800">Berhasil:</span>
                                <span class="text-primary font-bold">Andi Saputra (7A)</span>
                                <span class="text-[10px] bg-green-100 text-green-700 px-2 py-0.5 rounded-full font-bold ml-1 uppercase">Tepat Waktu</span>
                            </div>
                        </div>
                    `,
                    background: "#ffffff", iconColor: "#22c55e", timer: 3000,
                });
                input.value = "";
            } else if (nisn === "456") {
                Toast.fire({
                    icon: "warning",
                    html: `
                        <div class="flex flex-col items-start gap-1">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-slate-800">Sudah Absen:</span>
                                <span class="text-slate-600 font-bold">Budi Santoso (8C)</span>
                            </div>
                        </div>
                    `,
                    background: "#ffffff", iconColor: "#f97316",
                });
                input.value = "";
            } else {
                Toast.fire({
                    icon: "error",
                    html: `<div class="flex items-center gap-2"><span class="font-bold text-slate-800">Gagal:</span><span class="text-slate-600 text-sm">NISN <b>${nisn}</b> tidak terdaftar.</span></div>`,
                    background: "#ffffff", iconColor: "#ef4444",
                });
                input.select();
            }
        }

        // Logika Batalkan Absensi
        async function cancelAttendance() {
            const { value: nisn } = await Swal.fire({
                title: "",
                icon: undefined,
                html: `
                    <div class="flex flex-col items-center gap-3 mb-2">
                        <div class="w-24 h-24 rounded-full bg-red-50 flex items-center justify-center border-4 border-white shadow-sm mb-2">
                            <i class="fa-solid fa-triangle-exclamation text-5xl text-red-500"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-slate-800">Batalkan Absensi</h2>
                        <p class="text-sm text-slate-500">Masukkan NISN siswa untuk menghapus data absensi hari ini.</p>
                    </div>
                `,
                input: "text",
                inputPlaceholder: "Ketik NISN Siswa...",
                showCancelButton: true,
                confirmButtonText: "Hapus Data",
                confirmButtonColor: "#ef4444",
                cancelButtonText: "Batal",
                cancelButtonColor: "#64748b",
                inputValidator: (value) => {
                    if (!value) return "NISN tidak boleh kosong!";
                },
                customClass: {
                    confirmButton: "font-bold rounded-lg px-6 py-2.5 shadow-lg shadow-red-200",
                    cancelButton: "font-bold rounded-lg px-6 py-2.5",
                    popup: "rounded-[2rem] py-8",
                    input: "text-lg font-bold text-center",
                },
            });

            if (nisn) {
                Toast.fire({
                    icon: "success",
                    html: `<span class="font-bold text-slate-700">Data absensi ${nisn} berhasil dihapus.</span>`,
                });
            }
        }

        // Logika Sorting Tabel
        let sortDirection = {};
        function sortTable(columnIndex) {
            const table = document.getElementById("absensiTable");
            const tbody = table.querySelector("tbody");
            const rows = Array.from(tbody.querySelectorAll("tr"));

            const isAscending = sortDirection[columnIndex] === "asc";
            sortDirection[columnIndex] = isAscending ? "desc" : "asc";

            [0, 3].forEach((idx) => {
                document.getElementById(`sortLabel${idx}`).classList.add("hidden");
                const icon = document.getElementById(`sortIcon${idx}`);
                icon.className = "fa-solid fa-sort text-slate-400";
                icon.parentElement.className = "w-6 h-6 rounded flex items-center justify-center transition-colors group-hover:bg-white group-hover:border group-hover:border-slate-200";
            });

            const activeIcon = document.getElementById(`sortIcon${columnIndex}`);
            if (activeIcon) {
                activeIcon.className = isAscending ? "fa-solid fa-arrow-down-a-z text-white text-[10px]" : "fa-solid fa-arrow-down-z-a text-white text-[10px]";
                activeIcon.parentElement.className = "w-6 h-6 rounded flex items-center justify-center bg-primary shadow-sm border border-primary";
            }
            const activeLabel = document.getElementById(`sortLabel${columnIndex}`);
            if (activeLabel) {
                activeLabel.classList.remove("hidden");
                activeLabel.innerText = isAscending ? "A-Z" : "Z-A";
            }

            rows.sort((a, b) => {
                const cellA = a.querySelectorAll("td")[columnIndex].innerText.trim().toLowerCase();
                const cellB = b.querySelectorAll("td")[columnIndex].innerText.trim().toLowerCase();
                if (cellA < cellB) return isAscending ? -1 : 1;
                if (cellA > cellB) return isAscending ? 1 : -1;
                return 0;
            });

            tbody.style.opacity = "0.5";
            setTimeout(() => {
                tbody.innerHTML = "";
                rows.forEach((row) => tbody.appendChild(row));
                tbody.style.opacity = "1";
            }, 150);
        }
    </script>
</body>
</html>