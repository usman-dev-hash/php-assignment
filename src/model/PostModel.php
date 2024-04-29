<?php

namespace Model;

use bootstrap\RedisClient;
use repository\PostRepository;

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
        $redisClient = RedisClient::getClient();

        # Get value from redis
        $dataFromRedis = $redisClient->get('posts_' . $id);

        if (empty($dataFromRedis)) {
            $post = null;
            $result = $this->postRepository->getPost($id);

            if ($result) {
                $post = $result->fetch_assoc();
            }

            # Set value in redis
            $redisClient->set('posts_' . $id, json_encode($post), "EX", 60);

            # Set cache miss indicator in response headers
            header('X-Cache-Status: miss');

            return [
                'data' => $post
            ];
        }

        # Data found in Redis, set cache hit indicator in response headers
        header('X-Cache-Status: hit');

        return [
            'data' => json_decode($dataFromRedis)
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
            # redis client
            $redisClient = RedisClient::getClient();

            # Set value in redis
            $redisClient->set('posts_' . $result, json_encode($requestBody), 'EX', 60);
        }

        return [
            'data' => $result ? 'Post Created Successfully' : "There was an error while creating Post"
        ];
    }
}