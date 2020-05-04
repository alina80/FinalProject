<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OrderControllerTest extends WebTestCase
{
    public function testAddclient()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/addClient/{client_id}');
    }

    public function testPrevieworder()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/previewOrder');
    }

    public function testCreateorder()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/createOrder');
    }

}
