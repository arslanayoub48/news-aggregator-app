<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use App\Traits\PaginatorTrait;

abstract class Controller
{
    use ApiResponse;
    use PaginatorTrait;
}
