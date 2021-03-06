<?php


namespace App\Controller;


use App\Entity\Order;
use App\Entity\Tariff;
use App\Form\OrderType;
use App\Form\TariffFilterType;
use App\Transformer\OrderLiqPayTransformer;
use App\Transformer\OrderRequestTransformer;
use App\Transformer\PolicyTransformer;
use App\Transformer\TariffTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use FOS\RestBundle\View\View;
use League\Fractal\Manager;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdaterInterface;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

use League\Fractal\Resource\Collection as FractalCollection;
use League\Fractal\Resource\Item as FractalItem;

use FOS\RestBundle\Controller\Annotations\View as RestView;

class ClientRestController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var FilterBuilderUpdaterInterface
     */
    private $filterBuilderUpdater;

    /** @var ProducerInterface */
    private $producer;

    /**
     * ClientRestController constructor.
     * @param EntityManagerInterface $em
     * @param FilterBuilderUpdaterInterface $filterBuilderUpdater
     * @param ProducerInterface $producer
     */
    public function __construct( EntityManagerInterface $em,
                                 FilterBuilderUpdaterInterface $filterBuilderUpdater,
                                 ProducerInterface $producer
                                )
    {
        $this->em = $em;
        $this->filterBuilderUpdater = $filterBuilderUpdater;
        $this->producer = $producer;
    }

    /**
     * @param Request $request
     * @return mixed
     *
     * @RestView()
     */
    public function getOffersAction(Request $request)
    {
        /** @var QueryBuilder $qb */
        $qb = $this->em->getRepository(Tariff::class)->getTariffsQB();

        $filterForm = $this->get('form.factory')->create(TariffFilterType::class);
        $filterForm->handleRequest($request);

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $this->filterBuilderUpdater->addFilterConditions($filterForm, $qb);
        }

        $fractal = new Manager();
        $resource = new FractalCollection($qb->getQuery()->getResult(), new TariffTransformer());

        return $fractal->createData($resource)->toArray();
    }

    /**
     * @param Request $request
     * @return View
     *
     * @RestView()
     */
    public function postBuyAction(Request $request)
    {
        $order = new Order();
        $form = $this->createForm(OrderType::class, $order, ['method' => 'POST']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $amount = $order->getTariff()->getCoefficient() * 100;
            $order->setAmount($amount);

            $this->em->persist($order);
            $this->em->flush();

            $this->sendDataToSk($order);
            $this->sendDataToLiqPay($order);
            $this->getPolicyFrommSk($order->getSkId());

            //відправка в чергу
//            $this->producer->publish($order->getId());

            sleep(10);
            $order->setStatus(Order::STATUS_READY);
            $this->em->flush();

            $this->sendPolicyToClient($order);

            return View::create('Insurance policy has been sent ', 200);
        }
        return View::create($form, 400);
    }

    /**
     * @param Request $request
     * @return View
     * @throws \Exception
     *
     * @RestView()
     */
    public function postPaymentReceiveAction(Request $request)
    {
        $parameters = $request->attributes->all()['data'];
        $data = [
            'order_id' => $parameters['order_id'],
            'amount' => $parameters['amount'],
            'payment_id' => random_int(1, 100)
        ];

        return View::create($data, 200);
    }

    /**
     * @param Request $request
     * @return array
     *
     * @RestView()
     */
    public function getPolicyAction(Request $request)
    {
        $orders = $this->em->getRepository(Order::class)->findAll();
        $fractal = new Manager();
        $resource = new FractalCollection($orders, new PolicyTransformer());
        return $fractal->createData($resource)->toArray();
    }

    /**
     * @param Request $request
     * @param $id
     * @return array|View
     *
     * @RestView()
     */
    public function getPoliciesAction(Request $request, $id)
    {
        $order = $this->em->getRepository(Order::class)->findOneBy(['id'=>$id]);
        if (!$order) {
            return  View::create('Policy with id '. $id . ' does not exist', 400);
        }
        $fractal = new Manager();
        $resource = new FractalItem($order, new PolicyTransformer());
        return $fractal->createData($resource)->toArray();
    }

    /**
     * @param $order
     */
    private function sendPolicyToClient(&$order)
    {
        //1. підключаємо окрмий сервіс для відправки листів
        //2. в сервісі реалізований SwiftMailer
        //3. прикріплюємо pdf, генеруємо лист
        //4. кидаємо в чергу на відправку
    }

    /**
     * @param $sk_id
     */
    private function getPolicyFrommSk($sk_id)
    {
        $this->forward('App\Controller\SkRestController::getDownloadAction', ['sk_id'=>$sk_id]);
    }

    /**
     * @param $order
     */
    private function sendDataToSk(&$order)
    {
        $fractal = new Manager();
        $resource = new FractalItem($order, new OrderRequestTransformer());
        $parameters = $fractal->createData($resource)->toArray();

        $response = $this->forward('App\Controller\SkRestController::postContractCreateAction', $parameters);
        $content = json_decode($response->getContent(), true);

        $order->setSkId($content['sk_id']);
        $this->em->flush();
    }

    /**
     * @param $order
     */
    private function sendDataToLiqPay(&$order)
    {
        $fractal = new Manager();
        $resource = new FractalItem($order, new OrderLiqPayTransformer());
        $liqPayParameters = $fractal->createData($resource)->toArray();

        $liqPayResponse = $this->forward('App\Controller\LiqPayRestController::postFormAction', $liqPayParameters);
        $liqPayContent = json_decode($liqPayResponse->getContent(), true);

        if ($liqPayContent['order_id'] == $order->getId() && $liqPayContent['amount'] == $order->getAmount()){
            $order->setIdPayment($liqPayContent['payment_id']);
            $order->setStatus(Order::STATUS_PAID);
            $this->em->flush();
        }
    }
}