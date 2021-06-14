<?php

namespace App\Controller;

use App\Entity\Products;
use App\Form\ProductsType;
use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
#[Route('/products')]
class ProductsController extends AbstractController
{


    #[Route('/', name: 'products_index', methods: ['GET'])]
public function index(ProductsRepository $productsRepository): Response
{
    $products = $productsRepository->findAll();
    $images = array();
    foreach ($products as $key => $product) {
        $images[$key] = base64_encode(stream_get_contents($product->getImage()));
    }

    return $this->render('products/index.html.twig', [
        'images' => $images,
        'products' => $products
    ]);
}






    #[Route('/new', name: 'products_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $product = new Products();
        $form = $this->createForm(ProductsType::class, $product);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $file = $product->getImage();
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('upload_directory'), $fileName);
            $product->setImage($fileName);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('products_index');
        }

        return $this->render('products/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'products_show', methods: ['GET'])]
    public function show(Products $product): Response
    {


        $image = base64_encode(stream_get_contents($product->getImage()));


        return $this->render('products/show.html.twig', [
            'image' => $image,
            'product' => $product,
        ]);
    }

#[Route('/{id}/edit', name: 'products_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Products $product): Response
    {
        $form = $this->createForm(ProductsType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('products_index');
        }

        return $this->render('products/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/delete/{id}', name: 'products_delete', methods: ['POST'])]
    public function delete(Request $request, Products $product): Response
    {
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('products_index');
    }


    #[Route('/afficherProduits', name: 'afficherProduits', methods: ['GET'])]
    public function afficherProduits(ProductsRepository $productsRepository): Response
    {
        $products = $productsRepository->findAll();
        $images = array();
        foreach ($products as $key => $product) {
            $images[$key] = base64_encode(stream_get_contents($product->getImage()));
        }

        return $this->render('products/affichageProduits.html.twig', [
            'images' => $images,
            'products' => $products
        ]);
    }

    #[Route('/{id}', name: 'products_show', methods: ['GET'])]
    public function afficherDetails(Products $product): Response
    {
        $image = base64_encode(stream_get_contents($product->getImage()));

        return $this->render('products/details_produits.html.twig', [
            'image' => $image,
            'product' => $product,
        ]);
    }

    /**
     * @Route("/showcategorie/{categorie}" ,name="showcategorie")
     */
    public function affichercategorie(ProductsRepository $productsRepository ,$categorie): Response{
        $products=$productsRepository->findBy(['categorie' => $categorie]);
        $images = array();
        foreach ($products as $key => $product) {
            $images[$key] = base64_encode(stream_get_contents($product->getImage()));
        }

        return $this->render('products/affichageProduits.html.twig', [
            'images' => $images,
            'products' => $products
        ]);
    }

}
