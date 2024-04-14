<?php

namespace App\Http\Controllers;

use \Illuminate\Routing\Controller as topController;

abstract class Controller extends topController
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;
}
