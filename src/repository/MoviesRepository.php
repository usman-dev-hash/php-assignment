<?php

namespace repository;

use mysqli;
use Predis;

class MoviesRepository
{
    private $mysqli;

    public function __construct(mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function getAMovies($id)
    {
        $query = "SELECT name, casts, release_date, director, imdb, rotten_tomatto FROM movies WHERE id = ?";
        $statement = $this->mysqli->prepare($query);
        $statement->bind_param("i", $id);

        $statement->execute();

        # Get the result object
        return $statement->get_result();
    }

    public function getMovies()
    {
        $query = "SELECT name, casts, release_date, director, imdb, rotten_tomatto FROM movies";

        return $this->mysqli->query($query);
    }

    public function createMovies($requestBody)
    {
        $query = "INSERT INTO movies (name, casts, release_date, director, imdb, rotten_tomatto) VALUES (?, ?, ?, ?, ?, ?)";
        $statement = $this->mysqli->prepare($query);

        // Bind parameters to the prepared statement
        $statement->bind_param(
            "ssssss",
            $requestBody['name'],
            $requestBody['casts'],
            $requestBody['release_date'],
            $requestBody['director'],
            $requestBody['imdb'],
            $requestBody['rotten_tomatto']
        );

        # Execute the statement
        $success = $statement->execute();

        # Check if the insertion was successful
        if ($success) {
            # Return the latest ID
            return $this->mysqli->insert_id;
        } else {
            # Handle the case where the insertion failed
            return false;
        }
    }
}