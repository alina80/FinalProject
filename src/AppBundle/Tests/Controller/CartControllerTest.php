<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CartControllerTest extends WebTestCase
{
    public function testShowcartitems()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/showCartItems/{cart_id}');
    }

    public function testAddtocart()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/addToCart/{product_id}');
    }

    public function testRemovefromcart()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/removeFromCart/{product_id}');
    }

    public function testChangecart()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/changeCart/{cart_id}');
    }

}
