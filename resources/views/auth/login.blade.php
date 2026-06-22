@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="w-full flex flex-wrap min-h-screen">
    <!-- Login Section -->
    <div class="w-full md:w-1/2 flex flex-col">
        <div class="flex justify-center md:justify-start pt-12 md:pl-12 md:-mb-24">
            <a href="/" class="bg-black text-white font-bold text-xl p-4 rounded-lg">MyApp</a>
        </div>

        <div class="flex flex-col justify-center md:justify-start my-auto pt-8 md:pt-0 px-8 md:px-24 lg:px-32">
            <p class="text-center text-3xl font-bold text-gray-800">Welcome.</p>
            
            @if(session('success'))
                <div class="mt-4 p-3 bg-green-100 text-green-700 rounded-lg text-sm border border-green-300">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mt-4 p-3 bg-red-100 text-red-700 rounded-lg text-sm border border-red-300">
                    {{ $errors->first() }}
                </div>
            @endif

            <form class="flex flex-col pt-3 md:pt-8" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="flex flex-col pt-4">
                    <label for="email" class="text-lg font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" 
                           placeholder="your@email.com" 
                           class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 mt-1 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                </div>

                <div class="flex flex-col pt-4">
                    <label for="password" class="text-lg font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password" placeholder="Password" 
                           class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 mt-1 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <button type="submit" class="bg-black text-white font-bold text-lg hover:bg-gray-800 p-3 mt-8 rounded-lg transition duration-200">
                    Log In
                </button>
            </form>
        
            
            <div class="text-center pt-12 pb-12">
                <p class="text-gray-600">Don't have an account? <a href="{{ route('register') }}" class="underline font-semibold text-blue-600 hover:text-blue-800">Register here.</a></p>
            </div>
        </div>
    </div>

    <!-- Image Section -->
    <div class="w-1/2 shadow-2xl">
        <img class="object-cover w-full h-screen hidden md:block" 
             src="https://source.unsplash.com/IXUM4cJynP0" alt="Login background">
    </div>
</div>
@endsection