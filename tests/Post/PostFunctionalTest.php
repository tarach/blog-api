<?php

declare(strict_types=1);

namespace App\Tests\Post;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostFunctionalTest extends WebTestCase
{
    public function testShouldGetPosts(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/posts');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testShouldCreatePost(KernelBrowser $client = null): string
    {
        if (!$client) {
            $client = $this->getAuthenticatedClient();
        }

        $content = json_encode([
            'title' => 'Some title',
            'body' => '<strong>Some Body</strong>'
        ]);

        $client->request(
            'POST',
            '/api/posts',
            [],
            [],
            [],
            $content
        );

        $this->assertEquals(201, $client->getResponse()->getStatusCode());

        $body = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('id', $body);
        return $body['id'];
    }

    public function testShouldGetOnlyIsPublishedPosts(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/posts?isPublished=true');

        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $records = json_decode($response->getContent(), true);
        $onlyPublished = true;
        foreach($records as $record) {
            $published = $record['published'] ?? null;
            if (false === $published) {
                $onlyPublished = false;
                break;
            }
        }

        $this->assertTrue($onlyPublished);
    }

    public function testShouldUpdatePost(): void
    {
        $client = $this->getAuthenticatedClient();
        $id = $this->testShouldCreatePost($client);

        $newTitle = 'New Title';
        $content = json_encode([
            'title' => $newTitle,
        ]);

        $client->request(
            'PATCH',
            '/api/posts/' . $id,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/merge-patch+json',
            ],
            $content,
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('title', $response);
        $this->assertEquals($newTitle, $response['title']);
    }

    public function testShouldDeletePost(): void
    {
        $client = $this->getAuthenticatedClient();
        $id = $this->testShouldCreatePost($client);

        $client->request('DELETE', '/api/posts/' . $id);

        $this->assertEquals(204, $client->getResponse()->getStatusCode());
    }

    private function getAuthenticatedClient(): KernelBrowser
    {
        $client = static::createClient(
        [],
        [
            'PHP_AUTH_USER' => 'test',
            'PHP_AUTH_PW'   => 'qwe123',
            'CONTENT_TYPE' => 'application/json',
        ]);

        return $client;
    }
}