<?php


namespace App\Transformer;


use App\Entity\Order;
use League\Fractal\TransformerAbstract;

class OrderRequestTransformer extends TransformerAbstract
{
    /**
     * @param Order $order
     * @return array
     */
    public function transform(Order $order)
    {
        return [
            'tariff_id' => $order->getTariff()->getId(),
            'last_name' => $order->getLastName(),
            'email' => $order->getEmail()
        ];
    }

}