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
      #sidebar { transition: all 0.3s ease-in-out; }
    </style>
</head>
<body class="bg-surface flex h-screen overflow-hidden text-slate-600 font-sans">

    <x-sidebar :role="$role" />

    <main class="flex-1 flex flex-col relative h-full w-full transition-all">
        {{ $slot }}
    </main>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById("sidebar");
            if (window.innerWidth < 1024) {
                sidebar.classList.toggle("hidden");
            } else {
                sidebar.classList.toggle("sidebar-closed");
            }
        }
        if (window.innerWidth < 1024) document.getElementById("sidebar").classList.add("hidden");
    </script>

    @stack('scripts')
</body>
</html>