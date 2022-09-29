<?php

namespace App\Tags;

use Statamic\Tags\Tags;

class OrdersCount extends Tags
{
    /**
     * The {{ orders_count }} tag.
     *
     * @return string|array
     */
    public function index()
    {
      //
    }

    public function wildcard($tag)
    {
        if ($tag == "Child") $tag = "Otroški teki";
        if ($tag == "Nordic") $tag = "Nordijska hoja";
        return tag("registrations")->sum(fn($registration) => $registration["length"] == $tag ? 1 : 0);
    }

    /**
     * The {{ orders_count:example }} tag.
     *
     * @return string|array
     */
    public function example()
    {
        //
    }
}
