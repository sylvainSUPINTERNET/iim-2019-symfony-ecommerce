<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartProduct;
use App\Entity\Charge;
use App\Entity\Command;
use App\Entity\Product;
use App\Entity\User;
use App\Form\CardType;
use App\Model\Card;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;


class ChargeController extends AbstractController
{
    /**
     * @Route("/charge", name="charge")
     */
    public function index(Request $request, SessionInterface $session)
    {

        $em = $this->getDoctrine()->getManager();

        $cartId = $session->get('cart');

        $repoCart = $this->getDoctrine()->getRepository(Cart::class)->find($cartId);
        $repoProduct = $this->getDoctrine()->getRepository(Product::class);
        $repoUser = $this->getDoctrine()->getRepository(User::class);

        $newCommand = new Command();
        $amount = 0;

        foreach ($repoCart->getCartProducts() as $item) {
            ($repoProduct->find($item->getProduct()->getId()));
            $newCommand->addProduct($repoProduct->find($item->getProduct()->getId()));
            $amount += $repoProduct->find($item->getProduct()->getId())->getPrice();
        }

        $newCommand->setUser($repoUser->find($this->getUser()->getId()));

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
            $newCharge->setAmount($amount);
            $em->persist($newCharge);


            $newCommand->setCharge($newCharge);
            $newCommand->setAmount($newCharge->getAmount());
            $em->persist($newCommand);
            $em->flush();

            $session->remove('cart');
            return $this->redirectToRoute('command_show', [
                "id" => $newCommand->getId()
            ]);
        }


        return $this->render('charge/index.html.twig', [
            'controller_name' => 'ChargeController',
            'form' => $form->createView(),
        ]);

    }
}
