<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tailwind Register Template</title>
    <meta name="author" content="David Grzyb">
    <meta name="description" content="">

    <!-- Tailwind -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css?family=Karla:400,700&display=swap');

        .font-family-karla {
            font-family: karla;
        }
    </style>
</head>
<body class="bg-white font-family-karla h-screen">

    <div class="w-full flex flex-wrap">

        <!-- Register Section -->
        <div class="w-full md:w-1/2 flex flex-col">

            <div class="flex justify-center md:justify-start pt-12 md:pl-12 md:-mb-12">
                <a href="#" class="bg-black text-white font-bold text-xl p-4" alt="Logo">Logo</a>
            </div>

            <div class="flex flex-col justify-center md:justify-start my-auto pt-8 md:pt-0 px-8 md:px-24 lg:px-32">
                <p class="text-center text-3xl">Join Us.</p>
                
                <!-- Message Container -->
                <div id="message" class="mt-4 text-center text-sm hidden"></div>
                
                <form class="flex flex-col pt-3 md:pt-8" onsubmit="handleRegister(event)">
                    <div class="flex flex-col pt-4">
                        <label for="name" class="text-lg">Name</label>
                        <input type="text" id="name" placeholder="John Smith" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mt-1 leading-tight focus:outline-none focus:shadow-outline" required />
                    </div>

                    <div class="flex flex-col pt-4">
                        <label for="email" class="text-lg">Email</label>
                        <input type="email" id="email" placeholder="your@email.com" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mt-1 leading-tight focus:outline-none focus:shadow-outline" required />
                    </div>

                    <div class="flex flex-col pt-4">
                        <label for="password" class="text-lg">Password</label>
                        <input type="password" id="password" placeholder="Password (min. 6 characters)" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mt-1 leading-tight focus:outline-none focus:shadow-outline" required />
                        <p class="text-xs text-gray-500 mt-1">Minimal 6 karakter</p>
                    </div>

                    <div class="flex flex-col pt-4">
                        <label for="confirm-password" class="text-lg">Confirm Password</label>
                        <input type="password" id="confirm-password" placeholder="Confirm Password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mt-1 leading-tight focus:outline-none focus:shadow-outline" required />
                    </div>

                    <input type="submit" value="Register" class="bg-black text-white font-bold text-lg hover:bg-gray-700 p-2 mt-8 cursor-pointer" />
                </form>
                
             
                
                <div class="text-center pt-12 pb-12">
                    <p>Already have an account? <a href="login.html" class="underline font-semibold">Log in here.</a></p>
                </div>
            </div>

        </div>

        <!-- Image Section -->
        <div class="w-1/2 shadow-2xl">
            <img class="object-cover w-full h-screen hidden md:block" src="https://source.unsplash.com/IXUM4cJynP0" alt="Background" />
        </div>
    </div>

    <script>
        function handleRegister(event) {
            event.preventDefault();
            
            const nameInput = document.getElementById('name');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('confirm-password');
            const messageDiv = document.getElementById('message');
            
            const name = nameInput.value.trim();
            const email = emailInput.value.trim();
            const password = passwordInput.value.trim();
            const confirmPassword = confirmPasswordInput.value.trim();
            
            // Reset message
            messageDiv.className = 'mt-4 text-center text-sm hidden';
            messageDiv.textContent = '';
            
            // Validasi Nama
            if (!name) {
                messageDiv.className = 'mt-4 text-center text-sm text-red-600';
                messageDiv.textContent = '⚠️ Harap isi nama lengkap!';
                nameInput.focus();
                return;
            }
            
            // Validasi Email
            if (!email) {
                messageDiv.className = 'mt-4 text-center text-sm text-red-600';
                messageDiv.textContent = '⚠️ Harap isi email!';
                emailInput.focus();
                return;
            }
            
            // Validasi format email
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                messageDiv.className = 'mt-4 text-center text-sm text-red-600';
                messageDiv.textContent = '⚠️ Format email tidak valid!';
                emailInput.focus();
                return;
            }
            
            // Validasi Password
            if (!password) {
                messageDiv.className = 'mt-4 text-center text-sm text-red-600';
                messageDiv.textContent = '⚠️ Harap isi password!';
                passwordInput.focus();
                return;
            }
            
            if (password.length < 6) {
                messageDiv.className = 'mt-4 text-center text-sm text-red-600';
                messageDiv.textContent = '⚠️ Password minimal 6 karakter!';
                passwordInput.focus();
                return;
            }
            
            // Validasi Konfirmasi Password
            if (!confirmPassword) {
                messageDiv.className = 'mt-4 text-center text-sm text-red-600';
                messageDiv.textContent = '⚠️ Harap konfirmasi password!';
                confirmPasswordInput.focus();
                return;
            }
            
            if (password !== confirmPassword) {
                messageDiv.className = 'mt-4 text-center text-sm text-red-600';
                messageDiv.textContent = '⚠️ Password dan konfirmasi password tidak sama!';
                confirmPasswordInput.focus();
                return;
            }
            
            // Jika semua validasi berhasil
            messageDiv.className = 'mt-4 text-center text-sm text-green-600';
            messageDiv.textContent = '✅ Pendaftaran berhasil! Selamat datang, ' + name + '!';
            
            // Simpan data user ke localStorage (simulasi)
            const userData = {
                name: name,
                email: email,
                password: password,
                registeredAt: new Date().toISOString()
            };
            
            // Simpan ke localStorage
            localStorage.setItem('user_' + email, JSON.stringify(userData));
            
            // Reset form
            nameInput.value = '';
            emailInput.value = '';
            passwordInput.value = '';
            confirmPasswordInput.value = '';
            
            // Simulasi redirect setelah 2 detik
            setTimeout(() => {
                alert('Pendaftaran berhasil! Silakan login.');
                // window.location.href = 'login.html'; // Uncomment untuk redirect ke login
            }, 2000);
        }
    </script>

</body>
</html>