<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RabbitMQPublisher;

class OrderController extends Controller
{
    public function store(Request $request, RabbitMQPublisher $publisher)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'total' => 'required|numeric',
        ]);

        $orderData = [
            'order_id' => uniqid(),
            'user_email' => $data['email'],
            'total' => $data['total'],
        ];

        $publisher->publishOrderCreated($orderData);

        return response()->json(['status' => 'Order created and event sent.']);
    }
}
