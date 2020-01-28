<?php


namespace App\Transformer;


use App\Entity\Tariff;
use League\Fractal\TransformerAbstract;

class TariffTransformer extends TransformerAbstract
{
    /**
     * @param Tariff $tariff
     * @return array
     */
    public function transform(Tariff $tariff)
    {
        return [
            'tariff_id' => $tariff->getId(),
            'tariff_title' => $tariff->getTitle(),
            'tariff_cost' => $tariff->getCoefficient() * 100
        ];
    }

}