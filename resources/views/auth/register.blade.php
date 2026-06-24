@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="w-full flex flex-wrap min-h-screen">
    <!-- Register Section -->
    <div class="w-full md:w-1/2 flex flex-col">
        <div class="flex justify-center md:justify-start pt-12 md:pl-12 md:-mb-12">
            <a href="/" class="bg-black text-white font-bold text-xl p-4 rounded-lg"><i class="fas fa-book-reader text-blue-400 text-xl mr-2"></i>SIperpus</a>
        </div>

        <div class="flex flex-col justify-center md:justify-start my-auto pt-8 md:pt-0 px-8 md:px-24 lg:px-32">
            <p class="text-center text-3xl font-bold text-gray-800">Join Us.</p>

            @if($errors->any())
                <div class="mt-4 p-3 bg-red-100 text-red-700 rounded-lg text-sm border border-red-300">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form class="flex flex-col pt-3 md:pt-8" method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf
                <div class="flex flex-col pt-4">
                    <label for="name" class="text-lg font-medium text-gray-700">Nama</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                           placeholder="John Smith"
                           class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 mt-1 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="flex flex-col pt-4">
                    <label for="email" class="text-lg font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                           placeholder="your@email.com"
                           class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 mt-1 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                           autocomplete="email">
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror

                    <!-- Email suggestion -->
                    <div id="emailSuggestion" class="mt-2 p-2 bg-yellow-50 border border-yellow-200 rounded text-sm text-yellow-800 hidden">
                        <span>Apakah Anda maksud: </span><a href="#" id="suggestionLink" class="font-semibold text-yellow-700 underline"></a>?
                    </div>

                    <!-- Email validation status -->
                    <div id="emailStatus" class="mt-2 text-xs"></div>
                </div>

                <div class="flex flex-col pt-4">
                    <label for="password" class="text-lg font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password" placeholder="Password"
                           class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 mt-1 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror"
                           autocomplete="new-password">
                    @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror

                    <!-- Password strength indicator -->
                    <div id="passwordStrength" class="mt-3 space-y-2">
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 rounded-full bg-gray-300" id="strength1"></div>
                            <span class="text-xs text-gray-600">Minimal 8 karakter</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 rounded-full bg-gray-300" id="strength2"></div>
                            <span class="text-xs text-gray-600">Huruf besar (A-Z)</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 rounded-full bg-gray-300" id="strength3"></div>
                            <span class="text-xs text-gray-600">Huruf kecil (a-z)</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 rounded-full bg-gray-300" id="strength4"></div>
                            <span class="text-xs text-gray-600">Angka (0-9)</span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col pt-4">
                    <label for="password_confirmation" class="text-lg font-medium text-gray-700">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           placeholder="Konfirmasi Password"
                           class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 mt-1 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500"
                           autocomplete="new-password">
                    <div id="passwordMatch" class="mt-2 text-xs"></div>
                </div>

                <button type="submit" class="bg-gray-800 text-white font-bold text-lg hover:bg-gray-800 p-3 mt-8 rounded-lg transition duration-200">
                    Register
                </button>
            </form>

            <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                <p class="text-sm font-semibold text-blue-800">📝 Syarat Pendaftaran:</p>
                <ul class="text-xs text-blue-700 mt-2 space-y-1 list-disc list-inside">
                    <li>Nama hanya boleh mengandung huruf dan spasi</li>
                    <li><strong>Wajib menggunakan email Google (@gmail.com / @googlemail.com)</strong></li>
                    <li>Email harus valid dan aktif (akan dikirimkan link verifikasi)</li>
                    <li>Password minimal 8 karakter</li>
                    <li>Password harus mengandung: Huruf Besar, huruf kecil, dan Angka</li>
                    <li>Password dan konfirmasi harus sama</li>
                </ul>
            </div>

            
           
            
            <div class="text-center pt-12 pb-12">
                <p class="text-gray-600">Sudah punya akun? <a href="{{ route('login') }}" class="underline font-semibold text-blue-600 hover:text-blue-800">Login di sini.</a></p>
            </div>
        </div>
    </div>

    <div class="w-1/2 shadow-2xl">
        <img class="object-cover w-full h-screen hidden md:block"
             src="/login%26register.jpg" alt="Background">
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const passwordConfirm = document.getElementById('password_confirmation');

    let emailValidationTimeout;

    // Email validation dan suggestion
    emailInput.addEventListener('blur', function() {
        clearTimeout(emailValidationTimeout);
        validateEmail(this.value);
    });

    emailInput.addEventListener('input', function() {
        clearTimeout(emailValidationTimeout);
        emailValidationTimeout = setTimeout(() => {
            validateEmail(this.value);
        }, 500);
    });

    function validateEmail(email) {
        const statusDiv = document.getElementById('emailStatus');
        const suggestionDiv = document.getElementById('emailSuggestion');

        if (!email) {
            statusDiv.innerHTML = '';
            suggestionDiv.classList.add('hidden');
            return;
        }

        fetch('{{ route("api.email.validate") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
            },
            body: JSON.stringify({ email: email })
        })
        .then(r => r.json())
        .then(data => {
            if (data.valid) {
                statusDiv.innerHTML = '<span class="text-green-600">✓ Email valid</span>';
                suggestionDiv.classList.add('hidden');
            } else {
                statusDiv.innerHTML = '<span class="text-red-600">✗ ' + data.message + '</span>';

                if (data.suggestion) {
                    document.getElementById('suggestionLink').href = '#';
                    document.getElementById('suggestionLink').textContent = data.suggestion;
                    document.getElementById('suggestionLink').onclick = (e) => {
                        e.preventDefault();
                        emailInput.value = data.suggestion;
                        validateEmail(data.suggestion);
                    };
                    suggestionDiv.classList.remove('hidden');
                } else {
                    suggestionDiv.classList.add('hidden');
                }
            }
        })
        .catch(e => {
            console.error('Validation error:', e);
        });
    }

    // Password strength checker
    passwordInput.addEventListener('input', function() {
        const password = this.value;

        const checks = {
            length: password.length >= 8,
            uppercase: /[A-Z]/.test(password),
            lowercase: /[a-z]/.test(password),
            number: /\d/.test(password)
        };

        Object.entries(checks).forEach((entry, index) => {
            const dot = document.getElementById('strength' + (index + 1));
            if (entry[1]) {
                dot.className = 'w-2 h-2 rounded-full bg-green-500';
            } else {
                dot.className = 'w-2 h-2 rounded-full bg-gray-300';
            }
        });

        // Check password match
        checkPasswordMatch();
    });

    // Password confirmation checker
    passwordConfirm.addEventListener('input', checkPasswordMatch);

    function checkPasswordMatch() {
        const matchDiv = document.getElementById('passwordMatch');
        if (passwordInput.value && passwordConfirm.value) {
            if (passwordInput.value === passwordConfirm.value) {
                matchDiv.innerHTML = '<span class="text-green-600">✓ Password cocok</span>';
            } else {
                matchDiv.innerHTML = '<span class="text-red-600">✗ Password tidak cocok</span>';
            }
        } else {
            matchDiv.innerHTML = '';
        }
    }
});
</script>
@endsection
