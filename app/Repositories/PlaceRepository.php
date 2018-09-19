<?php

namespace App\Repositories;

use App\Model\ExternalData\Place;

class PlaceRepository {
    private $place;

    public function __construct(Place $place) {
        $this->place = $place;
    }

    public function inDistance($title, $coordinates, $km) {
        $result = $this->place
                    ->where('properties.title', $title)
                    ->where('geometry', 'nearSphere', [
                        '$geometry' => [
                            'type' => 'Point',
                            'coordinates' => $coordinates
                        ],
                        '$maxDistance' => $km * 1000
                    ])
                    ->get();
        return $result;
    }
}
