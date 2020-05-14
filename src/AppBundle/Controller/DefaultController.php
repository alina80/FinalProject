<?php

namespace AppBundle\Controller;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller



{
    /**
     * @Route("/", name="homepage")
     */
    public function homeAction(Request $request)
    {
        $catRepo = $this->getDoctrine()->getManager()->getRepository('AppBundle:Category');
        $prodRepo = $this->getDoctrine()->getManager()->getRepository('AppBundle:Product');
        $categories = $catRepo->findAll();
        $products = $prodRepo->findAll();
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
        */
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $products,
            $request->query->getInt('page', 1), /*page number*/
            6 /*limit per page*/
        );

        return $this->render('AppBundle:Default:home.html.twig', [
            'categories'=>$categories,
            'products'=>$pagination
        ]);
    }

    /**
     * @Route("/category/{id}", name="category")
     */
    public function categoryAction($id)
    {
        $catRepo = $this->getDoctrine()->getManager()->getRepository('AppBundle:Category');
        $category = $catRepo->findOneById($id);
        $categories = $catRepo->findAll();
        return $this->render('AppBundle:Default:category.html.twig', [
            'products'=>$category->getProducts(),
            'categories'=>$categories
            ]);
    }

    /**
     * @Route("/product/{id}",name="product")
     */
    public function productAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $cookie = $request->cookies->get('Uid');
        $cartRepo = $this->getDoctrine()->getManager()->getRepository('AppBundle:Cart');
        $cartItemRepo = $this->getDoctrine()->getManager()->getRepository('AppBundle:CartItem');
        $cartItem = null;
        if($cookie){
            $cart = $cartRepo->findOneByCookie($cookie);
            $cartItem = $cartItemRepo->findOneBy([
               'cartId'=>$cart->getId(),
                'productId'=>$id
            ]);
        }
        $catRepo = $this->getDoctrine()->getManager()->getRepository('AppBundle:Category');
        $prodRepo = $this->getDoctrine()->getManager()->getRepository('AppBundle:Product');
        $categories = $catRepo->findAll();
        $products = $prodRepo->findAll();

        $product = $prodRepo->findOneById($id);

        $photos = $prodRepo->find($id)->getPhotos();
        $cartItems = $cartItemRepo->findAll();
        return $this->render('AppBundle:Default:product.html.twig', [
            'product'=>$product,
            'categories'=>$categories,
            'products'=>$products,
            'photos'=>$photos,
            'cartItems'=>$cartItems,
            'cartItem'=>$cartItem
        ]);

    }

    /**
     * @Route("/search/{id}",name="search")
     */
    public function searchAction($id)
    {
        $catRepo = $this->getDoctrine()->getManager()->getRepository('AppBundle:Category');
        $prodRepo = $this->getDoctrine()->getManager()->getRepository('AppBundle:Product');
        $categories = $catRepo->findAll();
        $products = $prodRepo->findAll();

        $prodRepo = $this->getDoctrine()->getManager()->getRepository('AppBundle:Product');
        $product = $prodRepo->findOneById($id);
        return $this->render('AppBundle:Default:search.html.twig', [
            'product'=>$product,
            'categories'=>$categories,
            'products'=>$products
        ]);
    }

    /**
     * @Route("/about", name="about")
     */
    public function aboutAction()
    {
        $catRepo = $this->getDoctrine()->getManager()->getRepository('AppBundle:Category');
        $prodRepo = $this->getDoctrine()->getManager()->getRepository('AppBundle:Product');
        $categories = $catRepo->findAll();
        $products = $prodRepo->findAll();
        return $this->render('AppBundle:Default:about.html.twig',[
            'categories'=>$categories,
            'products'=>$products
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contactAction()
    {
        $catRepo = $this->getDoctrine()->getManager()->getRepository('AppBundle:Category');
        $prodRepo = $this->getDoctrine()->getManager()->getRepository('AppBundle:Product');
        $categories = $catRepo->findAll();
        $products = $prodRepo->findAll();
        return $this->render('AppBundle:Default:contact.html.twig',[
            'categories'=>$categories,
            'products'=>$products
        ]);
    }
}
