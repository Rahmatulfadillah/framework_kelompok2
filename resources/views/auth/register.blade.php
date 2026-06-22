@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="w-full flex flex-wrap min-h-screen">
    <!-- Register Section -->
    <div class="w-full md:w-1/2 flex flex-col">
        <div class="flex justify-center md:justify-start pt-12 md:pl-12 md:-mb-12">
            <a href="/" class="bg-black text-white font-bold text-xl p-4 rounded-lg">MyApp</a>
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

            <form class="flex flex-col pt-3 md:pt-8" method="POST" action="{{ route('register') }}">
                @csrf
                <div class="flex flex-col pt-4">
                    <label for="name" class="text-lg font-medium text-gray-700">Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" 
                           placeholder="John Smith" 
                           class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 mt-1 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                </div>

                <div class="flex flex-col pt-4">
                    <label for="email" class="text-lg font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" 
                           placeholder="your@email.com" 
                           class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 mt-1 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                </div>

                <div class="flex flex-col pt-4">
                    <label for="password" class="text-lg font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password" placeholder="Password (min. 6 characters)" 
                           class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 mt-1 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror">
                    <p class="text-xs text-gray-500 mt-1">Minimal 6 karakter</p>
                </div>

                <div class="flex flex-col pt-4">
                    <label for="password_confirmation" class="text-lg font-medium text-gray-700">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" 
                           placeholder="Confirm Password" 
                           class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 mt-1 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <button type="submit" class="bg-black text-white font-bold text-lg hover:bg-gray-800 p-3 mt-8 rounded-lg transition duration-200">
                    Register
                </button>
            </form>
            
           
            
            <div class="text-center pt-12 pb-12">
                <p class="text-gray-600">Already have an account? <a href="{{ route('login') }}" class="underline font-semibold text-blue-600 hover:text-blue-800">Log in here.</a></p>
            </div>
        </div>
    </div>

    <div class="w-1/2 shadow-2xl">
        <img class="object-cover w-full h-screen hidden md:block" 
             src="https://source.unsplash.com/IXUM4cJynP0" alt="Background">
    </div>
</div>
@endsection