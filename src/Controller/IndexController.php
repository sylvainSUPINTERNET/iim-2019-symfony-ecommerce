<?php

namespace App\Controller;

use App\Entity\Collection;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(SessionInterface $session)
    {
        $repository  = $this->getDoctrine()->getRepository(Collection::class);
        $collections = $repository->findAll();

        $repositoryP = $this->getDoctrine()->getRepository(Product::class);
        $products = $repositoryP->findBy([], [
            'dateAdd' => 'DESC'
        ], 8);

        return $this->render('index/index.html.twig', [
            'collections' => $collections,
            'products'    => $products
        ]);
    }

    /**
     * @Route("/empty", name="empty")
     */
    public function empty()
    {
        return $this->render('empty.html.twig');
    }

    public function menu_collections()
    {

        $repository  = $this->getDoctrine()->getRepository(Collection::class);
        $collections = $repository->findAll();

        return $this->render('partials/menu_collections.html.twig', [
            'collections' => $collections
        ]);
    }
}
