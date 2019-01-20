<?php

namespace App\Controller;

use App\Entity\Charge;
use App\Form\CardType;
use App\Model\Card;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class ChargeController extends AbstractController
{
    /**
     * @Route("/charge", name="charge")
     */
    public function index(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $card = new Card();
        $form = $this->createForm(CardType::class, $card);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $newCharge = new Charge();
            $newCharge->setAddress($form->getData()->getAddress());
            $newCharge->setYear($form->getData()->getYear());
            $newCharge->setMonth($form->getData()->getMonth());
            $newCharge->setCvv($form->getData()->getCvv());
            $newCharge->setNumber($form->getData()->getNumber());
            $em->persist($newCharge);
            $em->flush();

            return new JsonResponse("todo redirext vers dashboard user");
        }


        return $this->render('charge/index.html.twig', [
            'controller_name' => 'ChargeController',
            'form' => $form->createView(),
        ]);
    }
}
