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
        <p class="text-sm text-gray-500 mb-8">Silakan masukkan Username dan Password Anda.</p>

        <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
          @if($errors->has('login_error'))
          <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-lg shadow-sm text-sm" role="alert">
              <p class="font-bold">Gagal Login!</p>
              <p>{{ $errors->first('login_error') }}</p>
          </div>
          @endif

          @csrf

          <div>
            <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Username</label>
            <input type="text" name="username" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none text-sm" placeholder="Masukkan ID Pengguna" required />
          </div>

          <div>
            <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Password</label>
            <div class="relative">
              <input type="password" id="password" name="password" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none text-sm pr-10" placeholder="••••••••" required />

              <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-blue-600 focus:outline-none">
                <i class="fa-regular fa-eye-slash" id="eyeIcon"></i>
              </button>
            </div>
          </div>

          <button type="submit" class="w-full bg-blue-900 hover:bg-blue-800 text-white font-bold py-3 rounded-lg transition shadow-lg mt-4 text-sm uppercase">Login Masuk</button>
        </form>
      </div>
    </div>

    <script>
      const togglePassword = document.querySelector('#togglePassword');
      const password = document.querySelector('#password');
      const eyeIcon = document.querySelector('#eyeIcon');

      togglePassword.addEventListener('click', function (e) {
          // Toggle tipe input antara 'password' dan 'text'
          const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
          password.setAttribute('type', type);

          // Toggle icon mata antara 'mata tertutup' dan 'mata terbuka'
          if (type === 'password') {
              eyeIcon.classList.remove('fa-eye');
              eyeIcon.classList.add('fa-eye-slash');
          } else {
              eyeIcon.classList.remove('fa-eye-slash');
              eyeIcon.classList.add('fa-eye');
          }
      });
    </script>
  </body>
</html>
