<?php


namespace App\Transformer;


use App\Entity\Order;
use League\Fractal\TransformerAbstract;

class OrderLiqPayTransformer extends TransformerAbstract
{
    /**
     * @param Order $order
     * @return array
     */
    public function transform(Order $order)
    {
        return [
            'client_email' => $order->getEmail(),
            'order_id' => $order->getId(),
            'amount' => $order->getAmount()
        ];
    }

}