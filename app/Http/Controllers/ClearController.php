<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ClearController extends Controller
{
    public function index()
    {

        DB::table('items')->truncate();
        DB::table('item_user')->truncate();


    }
}
