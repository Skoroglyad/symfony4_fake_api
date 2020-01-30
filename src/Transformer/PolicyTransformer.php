<?php


namespace App\Transformer;


use App\Entity\Order;
use League\Fractal\TransformerAbstract;

class PolicyTransformer extends TransformerAbstract
{
    /**
     * @param Order $order
     * @return array
     */
    public function transform(Order $order)
    {
        return [
            'id' => $order->getId(),
            'amount' => $order->getAmount(),
            'tariff_title' => $order->getTariff()->getTitle(),
            'sk_id' => $order->getSkId(),
            'payment_id' => $order->getIdPayment(),
            'status' => $order->getStatusString()
        ];
    }

}