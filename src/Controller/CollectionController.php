<?php

namespace App\Controller;

use App\Entity\Collection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CollectionController extends AbstractController
{
    /**
     * @Route("/collections", name="collections")
     */
    public function index()
    {
        $repositoryCollection = $this->getDoctrine()->getRepository(Collection::class);
        $collections = $repositoryCollection->findAll();

        return $this->render('collection/index.html.twig', [
            "collections" => $collections
        ]);
    }

    /**
     * @Route("/collection/{slug_collection}/{collection_id}/products", name="collection_products")
     */
    public function showProductsFromCollection($slug_collection, $collection_id, SessionInterface $session)
    {
        $collections = $this->getDoctrine()->getRepository(Collection::class)->findAll();

        $repositoryCollection = $this->getDoctrine()->getRepository(Collection::class);
        $collection = $repositoryCollection->find($collection_id);
        return $this->render('collection/collections_list_products.html.twig', [
            'collection' => $collection,
            'collections' => $collections
        ]);
    }


}
