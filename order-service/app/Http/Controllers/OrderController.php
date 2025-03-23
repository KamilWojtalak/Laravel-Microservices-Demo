<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RabbitMQPublisher;

class OrderController extends Controller
{
    public function store(Request $request, RabbitMQPublisher $publisher)
    {
        $orderData = [
            'order_id' => uniqid(),
            'user_email' => $request->input('email'),
            'total' => $request->input('total'),
        ];

        $publisher->publishOrderCreated($orderData);

        return response()->json(['status' => 'Order created and event sent.']);
    }
}
