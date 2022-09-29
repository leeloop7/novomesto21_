<?php

namespace App\Tags;

use Statamic\Entries\Collection;
use Statamic\Tags\Tags;

class Registrations extends Tags
{
    /**
     * The {{ registrations }} tag.
     *
     * @return string|array
     */
    public function index()
    {

      $entries = Collection::find("orders")
      ->queryEntries()
      ->where("is_paid", true)
      ->get();

      // Filter only individual entries and map to normalize
      $competitors = $entries->filter(function($c) { return !$c->competitors; })->map(function ($c) {
        return [
            "first_name" => $c->customer?->first_name,
            "last_name" => $c->customer?->last_name,
            "length" => isset($c->items[0]) ? $c->items[0]?->variant["variant"] : null,
            "dog" => [
                "name" => $c->dog_name,
                "breed" => $c->dog_breed,
                "birthyear" => $c->dog_birthyear
            ],
            "school" => $c->primary_school
        ];
      });

      // Loop through group entries and add to normalized collection
      foreach($entries as $entry) {
        if ($entry->competitors) {
          foreach(collect($entry->competitors) as $c) {
            if(isset($c["cells"][8]) && $c["cells"][8] !== "Dolžina") {
              $competitors->push(["first_name" => $c["cells"][0], "last_name" => $c["cells"][1], "length" => $c["cells"][8]]);
            }
          }
        }
      }

      // Return sorted competitors
      return $competitors->sortBy("last_name", SORT_NATURAL|SORT_FLAG_CASE);
    }

    /**
     * The {{ registrations:example }} tag.
     *
     * @return string|array
     */
    public function example()
    {
        //
    }
}
