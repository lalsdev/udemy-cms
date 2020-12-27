<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Models\Product;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});
//
//Route::get('/hello', function(){
//    return view('hello');
//});
//
////pass parameter to the route
//Route::get('/post/{id}/{name}', function($id, $name){
//    return "this is post number " . $id . " from " . $name;
//});
//
//// give a name to a route
//Route::get('admin/posts/example', array('as' => 'admin.home' ,function(){
//    // this is the same as 'admin/posts/example'
//    $url = route('admin.home');
//    echo "<a href=". $url .">Click here</href><br/>";
//    return 'this url is ' . $url;
//}));

//Route::get('/post/{id}', 'App\Http\Controllers\PostsController@index');

//Route::resource('posts', 'App\Http\Controllers\PostController');
//
//Route::get('contact/{name}', 'App\Http\Controllers\PostController@contactPage');
//
//Route::get('item/{item}/{color}/{size}', 'App\Http\Controllers\PostController@itemPage');
//
//Route::get('bladeTest', 'App\Http\Controllers\PostController@bladeExample');


Route::get('/insert', function(){
   DB::insert('insert into product(price, stock, size, created_at) values(?,?,?,?)', [10, 18, 10, Carbon::now()]);
});

Route::get('/readPriceGreaterThan1400', function(){
   $result = DB::select('select * from product where price > 1400');
   return $result;
});

Route::get('/updateOnePrice', function(){
    $update = DB::table('product')
        ->where('stock', '>', 5000)
        ->update(['updated_at' => Carbon::now()]);
    return $update;
});

Route::get('/deleteOneUnit', function(){
   $delete = DB::table('product')
       ->where('stock', '<', 50)
       ->delete();
   return $delete;
});


Route::get('/readSizeAbove25', function(){
   $result = DB::select('select * from product where size > 25');
   return $result;
});

Route::get('/readStock', function(){
   $results = DB::select('select * from product');
   foreach($results as $result){
        dump('the stock left is ' . $result->stock);
   }
   return $results;
});

// ELOQUENT

Route::get('/read', function(){
   return Product::all();
});

// ca trouve le produit d'id 3
// find c'est une méthode inhéritée de la classe Model de Eloquent du coup peut l'utiliser sur le Model product pcq classe enfant
Route::get('/find', function(){
//   $product = Product::find(10);
    $product = Product::findOrFail(10);
    return $product;
});

Route::get('/orderByStockAmount', function(){
   $products = Product::orderBy('stock', 'desc')->get();
   return $products;
});

// controller renvoie la réponse construite
Route::get('/read/{id}','App\Http\Controllers\ProductController@show');

Route::get('/insert', function(){
   $product = new Product;
   $product->price = 100;
   $product->stock = 10;
   $product->size = 74;
   $product->save();
});

Route::get('creation', function(){
   Product::create(['price'=>542, 'stock'=>10, 'size'=>42]);
});

Route::get('/updateIt', function(){
   return Product::where('stock', '<', 1000)->where('size', '>', 10)->where('price', '<', 600)->update(['price' => 1]);
});

Route::get('/deleteMany', function(){
   return Product::where('price', '=', 1)->delete();
});
