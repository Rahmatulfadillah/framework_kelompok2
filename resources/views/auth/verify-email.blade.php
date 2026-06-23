@extends('layouts.app')

@section('title', 'Verifikasi Email')

@section('content')
<div class="w-full flex flex-wrap min-h-screen">
    <!-- Verification Section -->
    <div class="w-full md:w-1/2 flex flex-col">
        <div class="flex justify-center md:justify-start pt-12 md:pl-12 md:-mb-24">
            <a href="/" class="bg-black text-white font-bold text-xl p-4 rounded-lg">MyApp</a>
        </div>

        <div class="flex flex-col justify-center md:justify-start my-auto pt-8 md:pt-0 px-8 md:px-24 lg:px-32">
            <p class="text-center text-3xl font-bold text-gray-800">Verifikasi Email</p>

            <div class="mt-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-blue-800 text-base">
                    Terima kasih telah mendaftar! Sebelum melanjutkan, silakan periksa email Anda untuk link verifikasi.
                </p>
                <p class="text-blue-700 text-sm mt-3">
                    Jika Anda tidak menerima email, kami akan mengirimkan yang lain.
                </p>
            </div>

            @if(session('message'))
                <div class="mt-4 p-3 bg-green-100 text-green-700 rounded-lg text-sm border border-green-300">
                    {{ session('message') }}
                </div>
            @endif

            <form class="flex flex-col pt-8" method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="bg-blue-600 text-white font-bold text-lg hover:bg-blue-700 p-3 rounded-lg transition duration-200">
                    Kirim Ulang Email Verifikasi
                </button>
            </form>

            <form class="flex flex-col pt-4" method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-gray-400 text-white font-bold text-lg hover:bg-gray-500 p-3 rounded-lg transition duration-200">
                    Logout
                </button>
            </form>

            <div class="mt-6 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                <p class="text-yellow-800 text-sm">
                    <strong>💡 Tips:</strong> Cek folder spam jika email tidak ditemukan di inbox.
                </p>
            </div>
        </div>
    </div>

    <!-- Image Section -->
    <div class="w-1/2 shadow-2xl">
        <img class="object-cover w-full h-screen hidden md:block"
             src="https://source.unsplash.com/IXUM4cJynP0" alt="Verification background">
    </div>
</div>
@endsection
