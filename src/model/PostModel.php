<?php

namespace Model;

use repository\PostRepository;
use Predis;

class PostModel
{
    private $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function getPost($id)
    {
        # redis client
        $redisClient = new Predis\Client([
            'host' => '172.25.0.4',
            'port' => '6379',
        ]);

        # Get value from redis
        $dataFromRedis = $redisClient->get('posts_' . $id);

        if (empty($dataFromRedis)) {
            $post = [];
            $result = $this->postRepository->getPost($id);

            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $post[] = $row;
                }
            }

            # Set value in redis
            $redisClient->set('posts_' . $id, json_encode($post), "EX", 60);

            return [
                'data' => $post
            ];
        }

        return [
            'data' => $dataFromRedis
        ];

    }

    public function validateParams($requestBody)
    {
        if (!isset($requestBody['title']) || !isset($requestBody['content']) || !isset($requestBody['author'])) {
            return false;
        }

        return true;
    }

    public function createPost()
    {
        $requestBody = file_get_contents("php://input");
        $requestBody = json_decode($requestBody, true);

        if (!$this->validateParams($requestBody)) {
            http_response_code(400);

            // Return JSON response with error message
            $response = [
                'error' => 'Bad Request',
                'message' => 'Missing required parameters. Please provide title, content and author value'
            ];

            // Set Content-Type header to indicate JSON response
            header('Content-Type: application/json');

            // Encode the response data into JSON format and echo it
            return $response;
        }

        $result = $this->postRepository->createPost($requestBody);

        if ($result) {
            # store in redis
            $redisClient = new Predis\Client([
                'host' => '172.25.0.4',
                'port' => '6379',
            ]);

            # Set value in redis
            $redisClient->set('posts_' . $result, json_encode($requestBody), 'EX', 60);
        }

        return [
            'data' => $result ? 'Post Created Successfully' : "There was an error while creating Post"
        ];
    }
}