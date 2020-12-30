<?php

use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Models\User;


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
   $product->user_id= 4;
   $product->save();
});

Route::get('creation', function(){
   Product::create(['price'=>100, 'stock'=>800, 'size'=>38]);
});

Route::get('/updateIt', function(){
   return Product::where('stock', '<', 1000)->where('size', '>', 10)->where('price', '<', 600)->update(['price' => 1]);
});

Route::get('/deleteMany', function(){
   return Product::where('price', '=', 1)->delete();
});

Route::get('/deleteOne', function(){
    return Product::destroy([1]);
});

// put in a trash space - soft delete
// du coup quand tu fais un get all mais que ya un timestamp pour un des item dans la table : celui la ne sera pas montré

Route::get('/softDelete', function(){
    return Product::where('id', '=', 11)->delete();
});

// montre elements supprimés donc deleted_at est pas nul
Route::get('/deletedItems', function(){
   return Product::onlyTrashed()->get();
});

// récupère éléments restore la
Route::get('/restore', function(){
    $products = Product::onlyTrashed()->restore();
    return $products;
});

// create user
Route::get('/createUser', function(){
   $user = new User;
   $user->name = 'Morgane';
   $user->email = 'morgane@gmail.fr';
   $user->password = 'hello';
   $user->is_admin = 0;
   $user->save();
});

// ELOQUENT RELATIONSHIPS

/* ONE TO ONE */

// get one product from one user (one-to-one relationship)
Route::get('/user/{id}/product', function($id){
//   return User::find($id)->product;
    return User::find($id)->product->price;
});

// avec id du produit on retrouve le user lié
// grace a méthode dans modele Product user défini la
Route::get('product/{id}/user', function($id){
   return Product::find($id)->user;
});

// change value of price for a product related to one user
Route::get('/postsUser/{id}/price', function($id){
   $user =  User::find($id);
   $user->product->price = 5050;
   return $user;
});

/* ONE TO MANY */
// id user appartient a plusieurs produits
Route::get('/products/{id}', function($id){
    $user = User::find($id);
    foreach($user->products as $product){
        echo $product->price . '€<br/>';
    }
});

/* MANY TO MANY */
// un user peut avoir plusieurs roles
Route::get('/rolesForUser/{id}', function($id){
    // find c'est une méthode de éloquent qui est statique ?
   $user = User::find($id);
   // roles c'est une méthode que moi j'ai défini qui est dans le modele user et qui s'apply sur un objet qui vient de la classe User
   return $user->roles;
   // renvoie la row correspondante de role + pivot table avec user_id et role_id
});

// ICI CA FOIRE
// un role peut avoir plusieurs users
Route::get('/usersForRole/{id}', function($id){
   $role = Role::find($id);
    return $role->users;
   // renvoie row correspondante de users + pivot table avec role_id et user_id
});

