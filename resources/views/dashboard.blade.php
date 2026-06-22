@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <div class="w-64 bg-gray-800 text-white flex flex-col flex-shrink-0">
        <div class="flex items-center justify-center h-16 bg-gray-900">
            <span class="text-2xl font-bold">MyApp</span>
        </div>
        
        <nav class="flex-1 overflow-y-auto py-4">
            <div class="px-4 py-2 text-xs text-gray-400 uppercase tracking-wider">Main</div>
            <a href="{{ route('dashboard') }}" class="sidebar-link flex items-center px-4 py-3 bg-gray-700 text-white rounded-lg mx-2">
                <i class="fas fa-home w-5"></i>
                <span class="ml-3">Dashboard</span>
            </a>
            <a href="#" class="sidebar-link flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg mx-2">
                <i class="fas fa-chart-line w-5"></i>
                <span class="ml-3">Analytics</span>
            </a>
            <a href="#" class="sidebar-link flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg mx-2">
                <i class="fas fa-users w-5"></i>
                <span class="ml-3">Users</span>
            </a>
            
            <div class="px-4 py-2 mt-4 text-xs text-gray-400 uppercase tracking-wider">Management</div>
            <a href="#" class="sidebar-link flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg mx-2">
                <i class="fas fa-shopping-cart w-5"></i>
                <span class="ml-3">Orders</span>
            </a>
            <a href="#" class="sidebar-link flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg mx-2">
                <i class="fas fa-box w-5"></i>
                <span class="ml-3">Products</span>
            </a>
            <a href="#" class="sidebar-link flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg mx-2">
                <i class="fas fa-cog w-5"></i>
                <span class="ml-3">Settings</span>
            </a>
            
            <div class="px-4 py-2 mt-4 text-xs text-gray-400 uppercase tracking-wider">Account</div>
            <a href="#" class="sidebar-link flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg mx-2">
                <i class="fas fa-user w-5"></i>
                <span class="ml-3">Profile</span>
            </a>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
               class="sidebar-link flex items-center px-4 py-3 text-red-400 hover:bg-gray-700 hover:text-red-300 rounded-lg mx-2">
                <i class="fas fa-sign-out-alt w-5"></i>
                <span class="ml-3">Logout</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 overflow-y-auto">
        <!-- Header -->
        <header class="bg-white shadow-sm sticky top-0 z-10">
            <div class="flex items-center justify-between px-6 py-4">
                <h1 class="text-2xl font-semibold text-gray-800">Dashboard</h1>
                <div class="flex items-center space-x-4">
                    <button class="text-gray-500 hover:text-gray-700 relative">
                        <i class="fas fa-bell text-xl"></i>
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
                    </button>
                    <button class="text-gray-500 hover:text-gray-700 relative">
                        <i class="fas fa-envelope text-xl"></i>
                        <span class="absolute -top-1 -right-1 bg-blue-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">5</span>
                    </button>
                    <div class="flex items-center space-x-2 border-l pl-4">
                        <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=1F2937&color=fff&size=40&bold=true" 
                             alt="Avatar" class="w-10 h-10 rounded-full border-2 border-gray-300">
                        <div>
                            <span class="text-sm font-medium text-gray-700 block">{{ Auth::user()->name }}</span>
                            <span class="text-xs text-gray-500">{{ Auth::user()->email }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="p-6">
            <!-- Welcome Message -->
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg shadow-lg p-6 mb-6 text-white">
                <h2 class="text-2xl font-bold">Welcome back, {{ Auth::user()->name }}! 👋</h2>
                <p class="mt-1 opacity-90">Here's what's happening with your business today.</p>
            </div>

            <!-- Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="stat-card bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Total Revenue</p>
                            <p class="text-2xl font-bold text-gray-800">${{ number_format($totalRevenue) }}</p>
                            <p class="text-xs text-green-500 mt-1">↑ 12.5% from last month</p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-full">
                            <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Total Users</p>
                            <p class="text-2xl font-bold text-gray-800">{{ number_format($totalUsers) }}</p>
                            <p class="text-xs text-green-500 mt-1">↑ 8.2% from last month</p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-full">
                            <i class="fas fa-users text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Active Orders</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $activeOrders }}</p>
                            <p class="text-xs text-yellow-500 mt-1">↔ 0.5% from last month</p>
                        </div>
                        <div class="bg-yellow-100 p-3 rounded-full">
                            <i class="fas fa-shopping-cart text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Conversion Rate</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $conversionRate }}%</p>
                            <p class="text-xs text-red-500 mt-1">↓ 2.1% from last month</p>
                        </div>
                        <div class="bg-purple-100 p-3 rounded-full">
                            <i class="fas fa-percentage text-purple-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity and Quick Actions -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Recent Activity -->
                <div class="lg:col-span-2 bg-white rounded-lg shadow">
                    <div class="p-6 border-b">
                        <h3 class="text-lg font-semibold text-gray-800">Recent Activity</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($recentActivities as $activity)
                            <div class="flex items-center hover:bg-gray-50 p-2 rounded-lg transition duration-200">
                                <div class="bg-{{ $activity->color }}-100 p-2 rounded-full">
                                    <i class="fas fa-{{ $activity->icon }} text-{{ $activity->color }}-600"></i>
                                </div>
                                <div class="ml-4 flex-1">
                                    <p class="text-sm font-medium text-gray-800">{{ $activity->title }}</p>
                                    <p class="text-xs text-gray-500">{{ $activity->description }}</p>
                                </div>
                                <span class="text-xs text-gray-400">{{ $activity->time }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b">
                        <h3 class="text-lg font-semibold text-gray-800">Quick Actions</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <button class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
                            <i class="fas fa-plus mr-2"></i> Add New Product
                        </button>
                        <button class="w-full bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
                            <i class="fas fa-users mr-2"></i> Invite Users
                        </button>
                        <button class="w-full bg-purple-500 hover:bg-purple-600 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
                            <i class="fas fa-file-alt mr-2"></i> Generate Report
                        </button>
                        <button class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
                            <i class="fas fa-cog mr-2"></i> System Settings
                        </button>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="mt-6 bg-white rounded-lg shadow">
                <div class="p-6 border-b flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800">Recent Orders</h3>
                    <button class="text-sm text-blue-500 hover:text-blue-700 font-medium">View All <i class="fas fa-arrow-right ml-1"></i></button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($recentOrders as $order)
                            <tr class="hover:bg-gray-50 transition duration-200">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $order->id }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $order->customer }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $order->product }}</td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColors = [
                                            'Completed' => 'green',
                                            'Pending' => 'yellow',
                                            'Processing' => 'blue',
                                            'Cancelled' => 'red'
                                        ];
                                        $color = $statusColors[$order->status] ?? 'gray';
                                    @endphp
                                    <span class="px-3 py-1 text-xs font-semibold bg-{{ $color }}-100 text-{{ $color }}-800 rounded-full">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $order->total }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-8 text-center text-sm text-gray-500 border-t pt-6">
                <p>&copy; {{ date('Y') }} MyApp. All rights reserved. | Built with ❤️ using Laravel</p>
            </div>
        </div>
    </div>
</div>

<style>
    .sidebar-link {
        transition: all 0.3s ease;
    }
    .sidebar-link:hover {
        background-color: #374151;
        padding-left: 1.5rem;
    }
    .stat-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
    }
    .gradient-bg {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
</style>
@endsection