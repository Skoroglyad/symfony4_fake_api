<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class LiqPayRestController extends AbstractController
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function postFormAction(Request $request)
    {
        $parameters = $request->attributes->all()['data'];
        $data['data'] = [
            'status' => 'success',
            'amount' => $parameters['amount'],
            'order_id' => $parameters['order_id']
        ];

        $response = $this->forward('App\Controller\ClientRestController::postPaymentReceiveAction', $data);

        return new JsonResponse(json_decode($response->getContent(), true), 200);
    }
}