<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartProduct;
use App\Entity\Product;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart", methods={"GET"})
     */
    public function cart(SessionInterface $session)
    {
        $cartId = $session->get('cart');

        $repoCart = $this->getDoctrine()->getRepository(Cart::class);


        return $this->render('cart/index.html.twig', [
            'cart' => $repoCart->find($cartId),
        ]);
    }


    /**
     * @Route("/cart/add.json", name="add_cart_json", methods={"POST"})
     */
    public function addToCartJson(Request $request, SessionInterface $session)
    {
        $repositoryP = $this->getDoctrine()->getRepository(Product::class);

        $product     = $repositoryP->find($request->request->get('product_id'));

        $objectManager = $this->getDoctrine()->getManager();

        if (!$product instanceof Product) {
            $status  = 'ko';
            $message = 'Product not found';
        } else {
            if ($product->getStock() < $request->request->get('quantity')) {
                $status  = 'ko';
                $message = 'Missing quantity for product';
            } else {
                $cartId = $session->get('cart');

                if (!$cartId) {
                    $cart = new Cart();
                    $cart->setUser($this->getUser());
                    $objectManager->persist($cart);
                    $objectManager->flush();

                    $session->set('cart', $cartId = $cart->getId());
                } else {
                    $repositoryCart = $this->getDoctrine()->getRepository(Cart::class);
                    /** @var Cart $cart */
                    $cart = $repositoryCart->find($cartId);
                }

                $cartProduct = new CartProduct();
                $cartProduct->setCart($cart);
                $cartProduct->setProduct($product);
                $cartProduct->setQuantity((int)$request->request->get('quantity'));

                $objectManager->persist($cartProduct);
                $objectManager->flush();

                $status  = 'ok';
                $message = 'Added to cart';
            }
        }

        return new JsonResponse([
            'result'  => $status,
            'message' => $message,
        ]);
    }

    public function partial(SessionInterface $session)
    {
        $cartId = $session->get('cart');

        $repositoryCart = $this->getDoctrine()->getRepository(Cart::class);

        /** @var Cart $cart */
        $cart = $cartId ? $repositoryCart->find($cartId) : new Cart();

        return $this->render('partials/cart.html.twig', [
            'cart' => $cart
        ]);
    }
}
