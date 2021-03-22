<?php

declare(strict_types=1);

namespace App\Tests\Post;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostFunctionalTest extends WebTestCase
{
    public function testShouldGetPosts(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/posts');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testShouldCreatePost(): void
    {
        $client = static::createClient();

        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . base64_encode('test:qwe123'),
        ];

        dump($headers);

        $client->request('POST', '/api/posts', [
            'headers' => $headers,
            'json' => [],
        ]);

        dump([
            'response' => [
                'status' => $client->getResponse()->getStatusCode(),
                'body' => $client->getResponse()->getContent(),
            ],
        ]);
    }

    public function testShouldGetOnlyIsPublishedPosts(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/posts?isPublished=true');

        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $records = json_decode($response->getContent(), true);
        $published = false;
        foreach($records as $record) {
            $published = $record['published'] ?? null;
            if (!$published) {
                break;
            }
        }

        $this->assertTrue($published);
    }

    public function testShouldUpdatePost(): void
    {

    }

    public function testShouldDeletePost(): void
    {

    }
}