<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class ProductController extends Controller
{
    public function show(Request $request){
        $results = DB::table('product')->where('id', '=', $request->id)->get();
        return $results;
    }
}
