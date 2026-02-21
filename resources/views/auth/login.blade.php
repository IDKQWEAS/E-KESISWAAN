<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - E-KESISWAAN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style> body { font-family: "Inter", sans-serif; } </style>
  </head>
  <body class="bg-slate-900 h-screen flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl flex overflow-hidden w-full max-w-4xl h-[550px]">
      <div class="hidden md:flex flex-col justify-center w-5/12 p-10 bg-blue-900 text-white relative">
        <h2 class="text-3xl font-bold mb-4">E-KESISWAAN</h2>
        <p class="text-blue-200 text-sm">Sistem Informasi Manajemen Sekolah Terintegrasi.</p>
        <div class="absolute bottom-0 right-0 w-40 h-40 bg-white opacity-10 rounded-tl-full"></div>
      </div>

      <div class="w-full md:w-7/12 p-10 flex flex-col justify-center bg-gray-50">
        <h3 class="text-2xl font-bold text-gray-800 mb-1">Selamat Datang</h3>
        <p class="text-sm text-gray-500 mb-8">Silakan login sesuai hak akses Anda.</p>

        <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
          @csrf
          <div>
            <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Username</label>
            <input type="text" name="username" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none text-sm" placeholder="Masukkan ID Pengguna" required />
          </div>
          <div class="relative">
            <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Password</label>
            <input type="password" name="password" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none text-sm" placeholder="••••••••" required />
          </div>
          <div>
            <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Pilih Role (Simulasi)</label>
            <select name="role" class="w-full bg-white border border-gray-300 text-gray-700 text-sm rounded-lg p-3 focus:ring-blue-500">
              <option value="admin">Admin / Operator</option>
              <option value="bk">Guru BK</option>
              <option value="kepsek">Kepala Sekolah</option>
              <option value="absen">absen</option>

            </select>
          </div>
          <button type="submit" class="w-full bg-blue-900 hover:bg-blue-800 text-white font-bold py-3 rounded-lg transition shadow-lg mt-2 text-sm uppercase">Login Masuk</button>
        </form>
      </div>
    </div>
  </body>
</html>