<?php

namespace Model;

use repository\MoviesRepository;
use Predis;

class MoviesModel
{
    private $moviesRepository;

    public function __construct(MoviesRepository $moviesRepository)
    {
        $this->moviesRepository = $moviesRepository;
    }

    public function getAMovies($id)
    {
        # redis client
        $redisClient = new Predis\Client([
            'host' => '172.25.0.4',
            'port' => '6379',
        ]);

        # get value from redis
        $moviesDataFromRedis = $redisClient->get('movies_' . $id);

        if (empty($moviesDataFromRedis)) {
            $moviesData = [];
            $result = $this->moviesRepository->getAMovies($id);

            if ($result) {
                $moviesData = $result->fetch_assoc();
                $result->free();

                if (!empty($moviesData['casts'])) {
                    $moviesData['casts'] = explode(',', $moviesData['casts']);
                }

                if (!empty($moviesData['imdb']) && !empty($moviesData['rotten_tomatto'])) {
                    $moviesData['ratings'] = [
                        'imdb' => $moviesData['imdb'],
                        'rotten_tomatto' => $moviesData['rotten_tomatto']
                    ];
                    unset($moviesData['imdb']);
                    unset($moviesData['rotten_tomatto']);
                }
            }

            # get value from redis
            $redisClient->set('movies_' . $id, json_encode($moviesData), 'EX', 60);

            return [
                'data' => $moviesData
            ];
        }

        return [
          'data' => json_decode($moviesDataFromRedis)
        ];
    }

    public function getMovies()
    {
        # redis client
        $redisClient = new Predis\Client([
            'host' => '172.25.0.4',
            'port' => '6379',
        ]);

        # get redis value
        $moviesDataFromRedis = $redisClient->get('all_movies');

        if (empty($moviesDataFromRedis)) {
            $movies = [];
            $result = $this->moviesRepository->getMovies();

            if ($result) {
                while ($moviesData = $result->fetch_assoc()) {
                    if (!empty($moviesData['casts'])) {
                        $moviesData['casts'] = explode(',', $moviesData['casts']);
                    }
                    if (!empty($moviesData['imdb']) && !empty($moviesData['rotten_tomatto'])) {
                        $moviesData['ratings'] = [
                            'imdb' => $moviesData['imdb'],
                            'rotten_tomatto' => $moviesData['rotten_tomatto']
                        ];
                        unset($moviesData['imdb']);
                        unset($moviesData['rotten_tomatto']);
                    }
                    $movies[] = $moviesData;
                }
            }

            $redisClient->set('all_movies', json_encode($movies), 'EX', 60);

            return [
                'data' => $movies
            ];
        }

        return [
            'data' => $moviesDataFromRedis
        ];
    }

    public function validateParams($requestBody)
    {
        if (!isset($requestBody['name'])
            || !isset($requestBody['casts'])
            || !isset($requestBody['release_date'])
            || !isset($requestBody['director'])
            || !isset($requestBody['imdb'])
            || !isset($requestBody['rotten_tomatto'])
        ) {
            return false;
        }

        return true;
    }

    public function createMovies()
    {
        $requestBody = file_get_contents("php://input");
        $requestBody = json_decode($requestBody, true);

        if (!$this->validateParams($requestBody)) {
            http_response_code(400);

            // Return JSON response with error message
            $response = [
                'error' => 'Bad Request',
                'message' => 'Missing required parameters. Please provide all data values'
            ];

            // Set Content-Type header to indicate JSON response
            header('Content-Type: application/json');

            // Encode the response data into JSON format and echo it
            return $response;
        }

        # resolve values
        $this->resolveValuesTypes($requestBody);

        $result = $this->moviesRepository->createMovies($requestBody);

        if ($result) {
            # store in redis
            $redisClient = new Predis\Client([
                'host' => '172.25.0.4',
                'port' => '6379',
            ]);

            # Set value in redis
            $redisClient->set('movies_' . $result, json_encode($requestBody), 'EX', 60);
        }

        return [
            'data' => $result ? 'Movies Created Successfully' : "There was an error while creating Post"
        ];
    }

    public function resolveValuesTypes(&$requestBody)
    {
        $requestBody['casts'] = implode(',', $requestBody['casts']);
        $releaseDate = new \DateTime($requestBody['release_date']);
        $requestBody['release_date'] = $releaseDate->format('Y-m-d');
    }
}