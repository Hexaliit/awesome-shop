<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;
use Shetabit\Multipay\Invoice;
use Shetabit\Payment\Facade\Payment;
use SoapClient;

class OrdersController extends Controller
{
    public function store(Request $request){
        try {
            $order = new Order();
            $order->user_id = auth()->user()->id;
            $order->address = $request->input('address');

            $order->save();

            $total_amount = 0;

            foreach ($request->input('products') as $item){
                $product = Product::query()->find($item['product_id']);
                $orderItem = new OrderItems();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $product->id;
                $orderItem->quantity = $item['quantity'];
                $orderItem->price = $product->price;

                $orderItem->save();

                $total_amount += $item['quantity'] * $product->price;

            }

            $invoice = new Invoice();
            $invoice->amount($total_amount);

            Payment::purchase($invoice , function ($drive , $transactionId) use ($order) {
                $order->update([
                    'transaction_id' => $transactionId
                ]);

            })->pay()->render();

            $reciept = Payment::amount($total_amount)->transactionId($order->transaction_id)->verify();

            echo $reciept->getRefrenceId();

        } catch (InvalidPaymentException $exception) {
            echo $exception->getMessage();
        }




/*        $order->transaction_id  = Str::random();
        $order->save();

        return response()->json(['message' => 'success'] , 201);*/

        }

        public function callback(Request $request){
            $order = Order::query()->where('transaction_id' , $request->input('transaction_id'))->first();

            $order->update([
                'order_status' => 1
            ]);

            return response()->json(['message' => 'success']);
        }


}
