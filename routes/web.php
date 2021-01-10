<?php

use App\Models\Country;
use App\Models\Human;
use App\Models\Photo;
use App\Models\Project;
use App\Models\Role;
use App\Models\Task;
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
// id de user -> route va aller chercher tous les roles d'un certain user
Route::get('/user/{id}/role', function($id){
    $user = User::find($id)->roles()->orderBy('id', 'desc')->get();
    return $user;
//    foreach($user->roles as $role){
//        echo $role->name . '<br/>';
//    }
});

// table users (id, name, email)
// table roles (id, name -> admin, subscriber)
// table role_user (id, role_id, user_id) -> grace a la table pivot il trouve le role associé en allant regarder
// dans cette table le role_id correspondant au user_id et va dans la table roles pour afficher les rows correspondantes

// id de role -> route va chercher tous les users lié a un role
Route::get('/role/{id}/user', function($id){
   $user = Role::find($id)->users()->get();
   return $user;
});


// access pivot table
Route::get('user/pivot', function(){
   $user = User::find(1);
   // ici tu peux utiliser $user->roles pcq roles est une méthode présente dans le modèle user qui est un objet créé de la classe User (modèle)
   foreach($user->roles as $role){
       echo $role->pivot->created_at . '<br/>';
   }
});
// access pivot row for user
Route::get('user/{id}', function($id){
   $users = Role::find($id)->users()->get();
   foreach($users as $user){
       echo $user->name;
       echo $user->pivot . '<br/>';
   }
});

/* HAS MANY THROUGH */

// user table
// product table
// country table

// user belongs to country Belgium
// want to find out through a product where a user is from

// User has many Product
// Product has one Country
Route::get('/users/country', function(){
    $country = Country::find(2);
    // jusque la ca va
    // mtn tu passe par la table user pour trouver les produits lié a ce putain de pays
    return $country;
});

// Project has many Humans
// Human has many tasks
Route::get('/projects', function(){
    // récupère le projet id = 11
    $project = Project::find(11);

    return $project->tasks;
    // retrouve les taches pour l'humain indice 0 (pcq relation has many depuis l'humain)
    //return $project->humans[0]->tasks;
//   $project = Project::create([
//      'name' => 'shop clothes'
//   ]);
//
//   $human3 = Human::create([
//       'name' => 'Human 3',
//       'project_id' => $project->id
//   ]);
//    $human4 = Human::create([
//        'name' => 'Human 4',
//        'project_id' => $project->id
//    ]);
//
//    $human5 = Human::create([
//        'name' => 'Human 5',
//        'project_id' => $project->id
//    ]);
//
//    $task4 = Task::create([
//        'title' => 'Tache 4 Apprendre OS de l\'humain 3',
//        'human_id' => $human3->id
//    ]);
//
//    $task5 = Task::create([
//        'title' => 'Tache 5 Apprendre Linux de l\'humain 4',
//        'human_id' => $human4->id
//    ]);
//
//    $task6 = Task::create([
//        'title' => 'Tache 6 Apprendre SQL de l\'humain 5',
//        'human_id' => $human5->id
//    ]);
});

















//// un user peut avoir plusieurs roles
//Route::get('/rolesForUser/{id}', function($id){
//    // find c'est une méthode de éloquent qui est statique ?
//   $user = User::find($id);
//   // roles c'est une méthode que moi j'ai défini qui est dans le modele user et qui s'apply sur un objet qui vient de la classe User
//   return $user->roles;
//   // renvoie la row correspondante de role + pivot table avec user_id et role_id
//});
//
//// ICI CA FOIRE
//// un role peut avoir plusieurs users
//Route::get('/usersForRole/{id}', function($id){
//   $role = Role::find($id);
//    return $role->users;
//   // renvoie row correspondante de users + pivot table avec role_id et user_id
//});

/* POLYMORPHIC RELATIONSHIP */
// un modele est utilisé pour deux autres modeles
// table photo contient 2 colonnes : imageable_id (id de de l'element) et imageable_type (modele pour element imageable_id)
Route::get('/polymorphic/product/pictures', function(){
    $product = Product::find(1);
    $pictures = [];
    foreach($product->photos as $photo){
        $pictures[] = $photo->path;
    }
    return $pictures;
});

Route::get('/polymorphic/user/pictures', function(){
    $user = User::find(1);
    $pictures = [];
    foreach($user->photos as $photo){

        $pictures[] = $photo->path;
    }
    return $pictures;
});

Route::get('/addData', function(){
//    Photo::create([
//       "picturable_id" => 1,
//       "picturable_type" => 'App\Models\Product',
//       "path" => "dellBlackComputer.jpeg"
//    ]);
//
//    Photo::create([
//        "picturable_id" => 1,
//        "picturable_type" => 'App\Models\User',
//        "path" => "CharliePic.jpeg"
//    ]);

//    Product::create([
//        "brand" => "Dell",
//    ]);
//
//    User::create([
//        "name" => "Charlie",
//    ]);

});
