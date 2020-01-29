<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class SkRestController extends AbstractController
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function postContractCreateAction(Request $request)
    {
        $data = [
            'status' => 'success',
            'sk_id' => random_int(1, 100)
        ];
        return new JsonResponse($data, 200);
    }

    /**
     * @param $sk_id
     * @return JsonResponse
     * @throws \Exception
     */
    public function getDownloadAction($sk_id)
    {
        $data = [
            'status' => 'success',
            'sk_id' => random_int(1, 100)
        ];
        return new JsonResponse($data, 200);
    }
}