<?php

namespace App\Listeners;

use App\Events\OrderSubmitted;
use App\Mail\OrderShipped;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Barryvdh\DomPDF\ServiceProvider;
use Illuminate\Support\Facades\Mail;

class SendAdminNotification /*implements ShouldQueue*/
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * @param OrderSubmitted $event
     *
     */
    public function handle(OrderSubmitted $event): void
    {

        $order = $event->order;

        $total = 0;

        foreach ($order->orderItems as $item){
            $total += $item->price * $item->quantity;
        }

        $data = [
            'name' => $order->user->name,
            'email' => $order->user->email,
            'date' => $order->created_at,
            'address' => $order->address,
            'items' => $order->orderItems,
            'total' => $total
        ];


        $pdf = PDF\Pdf::loadView('billCheck' , $data);

        $pdf->save($order->id.'.pdf' , 'public');

        Mail::to('aliafshar9898@gmail.com')->send(new OrderShipped($order));
    }
}
