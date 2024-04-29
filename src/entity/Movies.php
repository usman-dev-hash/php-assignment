<?php

class Movies {
    private $name;
    private $casts;
    private $release_date;
    private $director;
    private $ratings;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function getCasts()
    {
        return $this->casts;
    }

    /**
     * @param array $casts
     */
    public function setCasts(array $casts)
    {
        $this->casts = $casts;
    }

    /**
     * @return DateTime
     * @throws Exception
     */
    public function getReleaseDate()
    {
        // Assuming you want to convert the stored string to a DateTime object
        return new DateTime($this->release_date);
    }

    /**
     * @param string $release_date
     * @throws Exception
     */
    public function setReleaseDate(string $release_date)
    {
        // Validate the format of the release date before assigning
        if (!preg_match('/^\d{2}-\d{2}-\d{4}$/', $release_date)) {
            throw new Exception('Invalid release date format. Use DD-MM-YYYY');
        }
        $this->release_date = $release_date;
    }

    /**
     * @return string
     */
    public function getDirector()
    {
        return $this->director;
    }

    /**
     * @param string $director
     */
    public function setDirector(string $director)
    {
        $this->director = $director;
    }

    /**
     * @return array
     */
    public function getRatings()
    {
        // Assuming you want to decode the JSON string to an associative array
        return json_decode($this->ratings, true);
    }

    /**
     * @param array $ratings
     * @throws Exception
     */
    public function setRatings(array $ratings)
    {
        // Validate the structure of the ratings array (optional)
        if (!array_key_exists('imdb', $ratings) || !array_key_exists('rotten_tomatto', $ratings)) {
            throw new Exception('Ratings array must contain keys "imdb" and "rotten_tomatto"');
        }
        $this->ratings = json_encode($ratings);
    }
}