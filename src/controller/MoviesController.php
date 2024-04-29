<?php

namespace controller;

use DI\Container;
use Model\MoviesModel;

class MoviesController
{
    /** @var MoviesModel */
    private $moviesModel;

    public function __construct(Container $container)
    {
        $this->moviesModel = $container->get('model.movies');
    }

    public function checkRedisAction()
    {
        return $this->moviesModel->checkRedis();
    }

    public function createMoviesAction()
    {
        return $this->moviesModel->createMovies();
    }

    public function getAMoviesAction($id)
    {
        return $this->moviesModel->getAMovies($id);
    }

    public function getMoviesAction()
    {
        return $this->moviesModel->getMovies();
    }
}