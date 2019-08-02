<?php

namespace App\Repositories;

use Illuminate\Http\Request;

class CartRepository
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var array
     */
    private $items = [];

    public function __construct (Request $request)
    {
        $this->request = $request;
    }
}
