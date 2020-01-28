<?php


namespace App\Controller;


use App\Entity\Order;
use App\Entity\Tariff;
use App\Form\OrderType;
use App\Form\TariffFilterType;
use App\Transformer\OrderBuyTransformer;
use App\Transformer\TariffTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use FOS\RestBundle\View\View;
use League\Fractal\Manager;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdaterInterface;
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

    /**
     * ClientRestController constructor.
     * @param EntityManagerInterface $em
     * @param FilterBuilderUpdaterInterface $filterBuilderUpdater
     */
    public function __construct(EntityManagerInterface $em, FilterBuilderUpdaterInterface $filterBuilderUpdater)
    {
        $this->em = $em;
        $this->filterBuilderUpdater = $filterBuilderUpdater;
    }

    /**
     * @param Request $request
     * @return mixed
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
     * @return array|View
     *
     * @RestView()
     */
    public function postBuyAction(Request $request)
    {
        $order = new Order();
        $form = $this->createForm(OrderType::class, $order, ['method' => 'POST']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->persist($order);
            $this->em->flush();

//            return View::createRouteRedirect('api_get_offers');

        }
        return View::create($form, 400);
    }
}