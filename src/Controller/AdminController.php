<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Tariff;
use App\Form\TariffType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;


class AdminController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('admin/index.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getListTariffsAction()
    {
        $tariffs = $this->em->getRepository(Tariff::class)->findAll();

        return $this->render('admin/tariff.html.twig', [
            'tariffs' => $tariffs
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getListOrdersAction()
    {
        $orders = $this->em->getRepository(Order::class)->findAll();
        return $this->render('admin/order.html.twig', [
            'orders' => $orders
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $tariff = new Tariff();
        $form = $this->createForm(TariffType::class, $tariff, [
            'action' => $this->generateUrl('admin_create_tariff'),
            'method' => 'POST'
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($tariff);
            $this->em->flush();
            return $this->redirectToRoute('admin_list_tariff');
        }

        return $this->render('admin/create.html.twig', [
            'form' => $form->createView(),
            'create' => true
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws EntityNotFoundException
     */
    public function editAction(Request $request, $id)
    {
        $tariff = $this->em->getRepository(Tariff::class)->findOneBy(['id'=>$id]);
        if (!$tariff) {
            throw new EntityNotFoundException('Tariff with id '. $id . ' does not exist');
        }
        $form = $this->createForm(TariffType::class, $tariff, [
            'action' => $this->generateUrl('admin_edit_tariff', ['id' => $id]),
            'method' => 'POST'
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            return $this->redirectToRoute('admin_list_tariff');
        }

        return $this->render('admin/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
