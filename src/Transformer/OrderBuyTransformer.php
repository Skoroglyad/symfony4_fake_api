<?php


namespace App\Transformer;


use App\Entity\Order;
use League\Fractal\TransformerAbstract;

class OrderBuyTransformer extends TransformerAbstract
{
    /**
     * @param Order $order
     * @return array
     */
    public function transform(Order $order)
    {
        return [
            'order_id' => $order->getId(),
            'last_name' => $order->getLastName(),
            'order_email' => $order->getEmail()
        ];
    }

}