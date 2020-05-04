<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Cart;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\CartItem;


class CartController extends Controller
{
    private function createCartAction(Request $req)
    {
        $em = $this->getDoctrine()->getManager();
        $cookie = $req->cookies->get('Uid');

        $value = uniqid();
        $cookie = new Cookie( 'Uid', $value, time() + (3600));
        $resp = new Response('');

        $resp->headers->setCookie($cookie);
        $resp->sendHeaders();
        $newCart = new Cart();
        $newCart->setCookie($value);
        $newCart->setCartTime(time());
        $em->persist($newCart);
        $em->flush();
        return $value;
    }

    /**
     * @Route("/showCart")
     */
    public function showCartAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $cookie = $request->cookies->get('Uid');
        $catRepo = $this->getDoctrine()->getManager()->getRepository('AppBundle:Category');
        $categories = $catRepo->findAll();

        $cartItems = [];
        if ($cookie) {
            $repoCart = $em->getRepository('AppBundle:Cart');
            $cart = $repoCart->findOneByCookie($cookie);
            $cartItems = $cart->getCartItems();
        }
        return $this->render('AppBundle:Cart:show_cart.html.twig', [
            'cartItems'=>$cartItems,
            'categories'=>$categories
        ]);
    }

    /**
     * @Route("/addToCart/{id}")
     */
    public function addToCart(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $cookie = $request->cookies->get('Uid');
        if (!$cookie) {
            $cookie = $this->createCartAction($request);
        }

        $repoCart = $em->getRepository('AppBundle:Cart');
        $cart = $repoCart->findOneByCookie($cookie);
        $productRepo = $em->getRepository('AppBundle:Product');
        $product = $productRepo->findOneById($id);

        $repoCartItem = $em->getRepository('AppBundle:CartItem');
        $cartItem = $repoCartItem->findOneBy([
            'cartId'=>$cart->getId(),
            'productId'=>$product->getId()
        ]);

        if (!$cartItem) {
            $cartItem = new CartItem();
            $cartItem->setCart($cart);
            $cartItem->setProduct($product);
            $cartItem->setQuantity('1');
        }else{
            $qty = $cartItem->getQuantity()+$request->request->get('selectQuantity');
            $cartItem->setQuantity($qty);
        }
        $em->persist($cartItem);
        $em->flush();
        return $this->redirectToRoute('app_cart_showcart',[]);
    }

    /**
     * @Route("/addOne/{id}")
     */
    public function addOne(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $cookie = $request->cookies->get('Uid');
       
        if (!$cookie) {
            $cookie = $this->createCartAction($request);
        }

            $repoCart = $em->getRepository('AppBundle:Cart');
            $cart = $repoCart->findOneByCookie($cookie);
            $productRepo = $em->getRepository('AppBundle:Product');
            $product = $productRepo->findOneById($id);

            $repoCartItem = $em->getRepository('AppBundle:CartItem');

            $cartItem = $repoCartItem->findOneBy([
                'cartId'=>$cart->getId(),
                'productId'=>$product->getId()
            ]);

            if (!$cartItem) {
                $cartItem = new CartItem();
                $cartItem->setCart($cart);
                $cartItem->setProduct($product);
                $cartItem->setQuantity(1);

            }else{
                $qty = $cartItem->getQuantity()+1;
                $cartItem->setQuantity($qty);
            }
            $em->persist($cartItem);
            $em->flush();
        return $this->redirectToRoute('app_cart_showcart',[

        ]);
    }

    /**
     * @Route("/deleteOne/{id}")
     */
    public function deleteOne(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $cookie = $request->cookies->get('Uid');
        //print_r($req->cookies->all());
        if (!$cookie) {
            $cookie = $this->createCartAction($request);
        }

        $repoCart = $em->getRepository('AppBundle:Cart');
        $cart = $repoCart->findOneByCookie($cookie);
        $productRepo = $em->getRepository('AppBundle:Product');
        $product = $productRepo->findOneById($id);

        $repoCartItem = $em->getRepository('AppBundle:CartItem');

        $cartItem = $repoCartItem->findOneBy([
            'cartId'=>$cart->getId(),
            'productId'=>$product->getId()
        ]);

        if (!$cartItem) {
            $cartItem = new CartItem();
            $cartItem->setCart($cart);
            $cartItem->setProduct($product);
            $cartItem->setQuantity(1);

        }else{
            $qty = $cartItem->getQuantity();
            $qty = $qty - 1;
            if($qty > 0){

                $cartItem->setQuantity($qty);
                $em->persist($cartItem);
                $em->flush();
            }
            if($qty == 0){
                $em->remove($cartItem);
                $em->flush();
            }
        }

        return $this->redirectToRoute('app_cart_showcart',[]);
    }

    /**
     * @Route("/removeFromCart/{id}")
     */
    public function removeFromCartAction(Request $request, $id=null)
    {
        $em = $this->getDoctrine()->getManager();
        $cookie = $request->cookies->get('Uid');
        //print_r($req->cookies->all());
        if ($cookie) {
            $repoCart = $em->getRepository('AppBundle:Cart');
            $cart = $repoCart->findOneByCookie($cookie);
            $productRepo = $em->getRepository('AppBundle:Product');
            $product = $productRepo->findOneById($id);

            $repoCartItem = $em->getRepository('AppBundle:CartItem');

            $cartItem = $repoCartItem->findOneById($id);
            $em->remove($cartItem);
            $em->flush();
        }

        return $this->redirectToRoute('app_cart_showcart');

    }


    /**
     * @Route("/changeCart/{cart_id}")
     */
    public function changeCartAction($cart_id)
    {
        return $this->render('AppBundle:Cart:change_cart.html.twig', array(
            // ...
        ));
    }

}
