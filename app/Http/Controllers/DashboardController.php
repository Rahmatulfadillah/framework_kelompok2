<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'totalRevenue' => 45231,
            'totalUsers' => 2847,
            'activeOrders' => 156,
            'conversionRate' => 3.24,
            'recentActivities' => [
                (object) [
                    'icon' => 'user-plus',
                    'color' => 'green',
                    'title' => 'New user registered',
                    'description' => 'John Doe created an account',
                    'time' => '5 min ago'
                ],
                (object) [
                    'icon' => 'shopping-bag',
                    'color' => 'blue',
                    'title' => 'New order placed',
                    'description' => 'Order #12345 - $149.99',
                    'time' => '15 min ago'
                ],
                (object) [
                    'icon' => 'star',
                    'color' => 'yellow',
                    'title' => 'New review submitted',
                    'description' => '5-star rating for Product X',
                    'time' => '1 hour ago'
                ],
                (object) [
                    'icon' => 'exclamation-triangle',
                    'color' => 'red',
                    'title' => 'System alert',
                    'description' => 'Server CPU usage at 85%',
                    'time' => '2 hours ago'
                ]
            ],
            'recentOrders' => [
                (object) ['id' => '#12345', 'customer' => 'John Smith', 'product' => 'Premium Package', 'status' => 'Completed', 'total' => '$149.99'],
                (object) ['id' => '#12346', 'customer' => 'Sarah Johnson', 'product' => 'Starter Bundle', 'status' => 'Pending', 'total' => '$79.99'],
                (object) ['id' => '#12347', 'customer' => 'Mike Wilson', 'product' => 'Pro Subscription', 'status' => 'Processing', 'total' => '$299.99'],
                (object) ['id' => '#12348', 'customer' => 'Emily Brown', 'product' => 'Basic Plan', 'status' => 'Cancelled', 'total' => '$49.99'],
            ]
        ];

        return view('dashboard', $data);
    }
}