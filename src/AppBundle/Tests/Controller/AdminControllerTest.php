<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{
    public function testListproducts()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/admin/listProducts');
    }

    public function testAddproduct()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/admin/addProduct');
    }

    public function testEditproduct()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/admin/editProduct/{id}');
    }

    public function testSaveproduct()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/admin/saveProduct/{id}');
    }

    public function testDeleteproduct()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/admin/deleteProduct/{id}');
    }

}
