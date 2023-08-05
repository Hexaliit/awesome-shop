<?php

namespace App\Http\Controllers\API;

use App\Events\OrderSubmitted;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Product;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;
use Shetabit\Multipay\Invoice;
use Shetabit\Payment\Facade\Payment;
use SoapClient;
use function Brick\Math\sum;

class OrdersController extends Controller
{
    public function store(Request $request){
/*        try {

            DB::beginTransaction();*/

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

            return Payment::purchase($invoice , function ($drive , $transactionId) use ($order) {
                $order->update([
                    'transaction_id' => $transactionId
                ]);
            })->pay()->getAction();

            //DB::commit();

           /* $receipt = Payment::amount($total_amount)->transactionId($order->transaction_id)->verify();*/

            /*echo $receipt->getReferenceId();*/

/*        } catch (InvalidPaymentException $exception) {
            DB::rollBack();
            echo $exception->getMessage();
        }*/




/*        $order->transaction_id  = Str::random();
        $order->save();

        return response()->json(['message' => 'success'] , 201);*/

        }

        public function callback(Request $request){
            if ($request->input('success') == 1) {

                $order = Order::query()->where('transaction_id' , $request->input('trackId'))->first();

                $order->update([
                    'order_status' => 1
                ]);

                $order->save();




                /*$pdf = PDF\Pdf::loadView('billCheck' , $data);*/

/*                $pdf = PDF::loadView('billCheck' , $data);

                $pdf->save($order->id.'.pdf' , 'public');*/


                OrderSubmitted::dispatch($order);

                return response()->json([
                    'message' => 'Order Successfully Submitted.Check Your Email',
                    'success' => true,
                ]);
            } else {
                return response()->json([
                    'message' => 'Error',
                    'success' => false,
                ] , 400);
            }
/*            dump($request->input('status'));
            dump($request->input('success'));
            dump($request->input('orderId'));
            dump($request->input('trackId'));*/
        }


}
