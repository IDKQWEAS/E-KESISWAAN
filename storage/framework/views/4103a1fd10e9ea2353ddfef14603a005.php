<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Monitoring Absensi - E-KESISWAAN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        tailwind.config = { theme: { extend: { fontFamily: { sans: ["Plus Jakarta Sans", "sans-serif"] }, colors: { primary: "#2563eb" } } } };
    </script>
</head>
<body class="bg-[#f8fafc] font-sans text-slate-900 antialiased">
    <div class="min-h-screen flex flex-col">
        
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 md:px-8 sticky top-0 z-30 shadow-sm">
            <div class="flex items-center gap-2 md:gap-3 flex-shrink min-w-0">
                <div class="w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center overflow-hidden flex-shrink-0 border border-slate-100">
                    <img src="<?php echo e(asset('smp.png')); ?>" alt="Logo" class="w-full h-full object-cover">
                </div>
                <div class="min-w-0">
                    <h1 class="text-xs md:text-sm font-extrabold text-slate-800 uppercase truncate">Monitoring <?php echo e($tingkat ? 'Kelas ' . $tingkat : 'Siswa'); ?></h1>
                    <p class="hidden sm:block text-[8px] font-black text-slate-400 uppercase tracking-widest mt-0.5">SMPN 1 Polanharjo</p>
                </div>
            </div>
            
            <div class="flex items-center gap-2 md:gap-3 flex-shrink-0">
                <select onchange="window.location.href='?tingkat='+this.value" class="bg-slate-50 border border-slate-200 rounded-lg py-1.5 px-2 text-[9px] font-black text-slate-700 uppercase outline-none cursor-pointer">
                    <option value="">SEMUA</option>
                    <option value="7" <?php echo e($tingkat == '7' ? 'selected' : ''); ?>>KELAS 7</option>
                    <option value="8" <?php echo e($tingkat == '8' ? 'selected' : ''); ?>>KELAS 8</option>
                    <option value="9" <?php echo e($tingkat == '9' ? 'selected' : ''); ?>>KELAS 9</option>
                </select>

                <button onclick="handleTandaiAlpha()" class="h-8 md:h-9 px-3 bg-red-600 text-white font-black rounded-lg hover:bg-red-700 flex items-center gap-2 uppercase text-[9px] tracking-widest transition-all shadow-sm">
                    <i class="fa-solid fa-lock text-[10px]"></i>
                    <span class="hidden md:inline">Tutup Absensi</span>
                </button>

                <form action="<?php echo e(route('logout')); ?>" method="POST" class="inline"><?php echo csrf_field(); ?>
                    <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all shadow-sm">
                        <i class="fa-solid fa-power-off text-xs"></i>
                    </button>
                </form>
            </div>
        </header>

        <main class="flex-1 p-4 md:p-6 flex flex-col gap-5">
            
            <div class="bg-white rounded-[1.5rem] p-4 md:p-6 border border-slate-200 shadow-sm">
                <div class="flex flex-col md:flex-row gap-3">
                    <div class="flex-1 flex h-[52px] border-2 border-slate-100 rounded-xl bg-slate-50 focus-within:border-primary transition-all overflow-hidden">
                        <div class="px-4 flex items-center text-slate-400"><i class="fa-solid fa-qrcode text-xl"></i></div>
                        <input type="text" 
                            id="nipdInput" 
                            maxlength="5" 
                            oninput="if(this.value.length === 5) handleManualAbsen()"
                            class="flex-1 bg-transparent border-none outline-none text-sm md:text-base font-bold text-slate-700 uppercase" 
                            placeholder="Masukan NIPD Untuk Absen" 
                            autofocus 
                            onkeypress="if(event.key==='Enter') handleManualAbsen()">
                        <button onclick="handleManualAbsen()" class="bg-primary text-white px-4 md:px-8 font-black text-[10px] md:text-xs uppercase tracking-widest">Absen</button>
                    </div>
                    <button onclick="cancelAttendance()" class="h-[52px] px-6 border-2 border-red-50 text-red-500 font-extrabold rounded-xl hover:bg-red-50 flex items-center justify-center gap-2 uppercase text-[10px] tracking-wider">
                        <i class="fa-solid fa-user-xmark text-sm"></i> Batal Absen
                    </button>
                </div>
            </div>

            
            <div class="flex-1 bg-white rounded-[1.5rem] border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left border-collapse min-w-[750px]">
                        <thead class="bg-slate-50/50 border-b border-slate-100 sticky top-0 z-10 backdrop-blur-md">
                            <tr class="text-[9px] font-black text-slate-400 uppercase tracking-widest">
                                <th class="px-5 py-4 text-center w-24">Waktu</th>
                                <th class="px-5 py-4">Data Siswa</th> 
                                <th class="px-5 py-4 text-center w-20">Kelas</th>
                                <th class="px-5 py-4 text-center w-32">Status</th>
                                <th class="px-5 py-4 text-center w-32">Tipe</th> 
                                <th class="px-5 py-4 text-center w-24 text-orange-500">Terlambat</th>
                                <th class="px-5 py-4 text-center w-20 text-red-500">Poin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php $__empty_1 = true; $__currentLoopData = $riwayatAbsen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php
                                    $statusRaw = strtolower($row['status'] ?? '');
                                    $tipeRaw = strtolower($row['tipe_absensi'] ?? '');

                                    // Warna untuk kolom Tipe (Tepat Waktu/Terlambat/Alpha)
                                    if ($statusRaw === 'terlambat') {
                                        $tipeLabel = 'TERLAMBAT';
                                        $tipeColor = 'bg-orange-500';
                                    } elseif ($statusRaw === 'tepat waktu') {
                                        $tipeLabel = 'TEPAT WAKTU';
                                        $tipeColor = 'bg-emerald-500';
                                    } else {
                                        $tipeLabel = 'ALPHA';
                                        $tipeColor = 'bg-red-600';
                                    }

                                    // Warna untuk kolom Status (Datang/Pulang)
                                    $statusColor = ($tipeRaw === 'pulang') ? 'bg-purple-500' : 'bg-blue-500';
                                ?>
                                <tr class="hover:bg-slate-50/50 transition-colors h-14">
                                    <td class="px-5 text-center font-mono font-bold text-slate-500 text-[10px]">
                                        <?php echo e(\Carbon\Carbon::parse($row['created_at'])->timezone('Asia/Jakarta')->format('H:i')); ?>

                                    </td>
                                    <td class="px-5">
                                        <div class="flex flex-col">
                                            <span class="text-[11px] font-extrabold text-slate-800 uppercase leading-tight"><?php echo e($row['nama']); ?></span>
                                            <span class="text-[8px] font-bold text-blue-500 uppercase tracking-wider">NIPD: <?php echo e($row['nipd']); ?></span>
                                        </div>
                                    </td>
                                    <td class="px-5 text-center font-black text-slate-600 text-[10px]">
                                        <?php echo e($row['kelas']); ?>

                                    </td>
                                    
                                    <td class="px-5 text-center">
                                        <span class="<?php echo e($statusColor); ?> text-white px-2 py-1 rounded-full text-[8px] font-black uppercase shadow-sm">
                                            <?php echo e(strtoupper($tipeRaw)); ?>

                                        </span>
                                    </td>
                                    
                                    <td class="px-5 text-center">
                                        <span class="<?php echo e($tipeColor); ?> text-white px-2 py-1 rounded-full text-[8px] font-black uppercase shadow-sm">
                                            <?php echo e($tipeLabel); ?>

                                        </span>
                                    </td>
                                    <td class="px-5 text-center">
                                        <span class="px-2 py-1 bg-orange-50 text-orange-600 rounded text-[10px] font-black"><?php echo e($row['total_terlambat'] ?? 0); ?>x</span>
                                    </td>
                                    <td class="px-5 text-center font-black text-red-600 text-[10px]"><?php echo e($row['total_poin'] ?? 0); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr><td colspan="7" class="py-20 text-center text-slate-300 font-bold uppercase text-[9px] italic">Tidak ada aktivitas absensi hari ini</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        const Toast = Swal.mixin({ toast: true, position: "top", showConfirmButton: false, timer: 2500 });

        // Absen Manual NIPD - Otomatis Scan 5 Angka
        async function handleManualAbsen() {
            const input = document.getElementById("nipdInput");
            const nipd = input.value.trim(); 
            
            if (nipd.length !== 5) return;

            try {
                const res = await fetch("<?php echo e(route('absensi.scan')); ?>", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' },
                    body: JSON.stringify({ nipd })
                });

                if(res.ok) { 
                    Toast.fire({ icon: "success", title: "Berhasil Absen!" }); 
                    setTimeout(() => location.reload(), 600); 
                } else { 
                    const data = await res.json(); 
                    Toast.fire({ icon: "error", title: data.message || "Gagal" }); 
                }
            } catch (e) { 
                Toast.fire({ icon: "error", title: "Masalah koneksi" }); 
            }

            input.value = ""; 
            input.focus();
        }

        // Batal Absen Dinamis menggunakan NIPD
        async function cancelAttendance() {
            const { value: nipd } = await Swal.fire({ 
                title: "Batal Absen", 
                text: "Masukkan NIPD siswa", 
                input: "text", 
                showCancelButton: true,
                confirmButtonText: 'Lanjut',
                cancelButtonText: 'Kembali'
            });

            if (nipd) {
                const dataRiwayat = <?php echo json_encode($riwayatAbsen, 15, 512) ?>;
                const siswaFound = dataRiwayat.find(item => item.nipd == nipd);
                const displayInfo = siswaFound ? siswaFound.nama : nipd;

                const { value: tipe } = await Swal.fire({
                    title: 'Pilih Tipe Pembatalan',
                    text: `Siswa: ${displayInfo}`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#2563eb', 
                    cancelButtonColor: '#9333ea',  
                    confirmButtonText: 'Batal Datang',
                    cancelButtonText: 'Batal Pulang',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) return { value: 'datang' };
                    if (result.dismiss === Swal.DismissReason.cancel) return { value: 'pulang' };
                    return { value: null };
                });

                if (tipe) {
                    Swal.fire({ title: 'Memproses...', didOpen: () => Swal.showLoading(), allowOutsideClick: false });

                    try {
                        const res = await fetch(`/absensi/batal/${nipd}/${tipe}`, { 
                            method: 'DELETE', 
                            headers: { 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' } 
                        });
                        
                        const data = await res.json();

                        if(res.ok) {
                            Swal.fire('Berhasil!', `Data absen ${tipe} untuk ${displayInfo} telah dihapus.`, 'success')
                                .then(() => location.reload());
                        } else {
                            Swal.fire('Gagal', data.message || 'Data tidak ditemukan', 'error');
                        }
                    } catch (e) {
                        Swal.fire('Error', 'Gagal menghubungi server.', 'error');
                    }
                }
            }
        }

        // Tutup Absensi Alpha
        async function handleTandaiAlpha() {
            const result = await Swal.fire({
                title: 'Tutup Absensi?',
                text: "Siswa yang belum hadir otomatis ditandai ALPHA.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'Ya, Tutup'
            });

            if (result.isConfirmed) {
                Swal.fire({ title: 'Memproses...', didOpen: () => Swal.showLoading(), allowOutsideClick: false });
                try {
                    const res = await fetch("<?php echo e(route('absensi.tandaiAlpha')); ?>", {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' }
                    });
                    const data = await res.json();

                    if (res.ok) {
                        Swal.fire('Sukses!', data.message, 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Perhatian', data.message || 'Gagal memproses', 'info');
                    }
                } catch (e) {
                    Swal.fire('Error', 'Gagal hubungi server', 'error');
                }
            }
        }
    </script>
</body>
</html><?php /**PATH C:\laragon\www\New foldersssss\ekesiswaan\resources\views/absensi/index.blade.php ENDPATH**/ ?>