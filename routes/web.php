<?php

use Illuminate\Support\Facades\Route;


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

Route::resource('posts', 'App\Http\Controllers\PostController');

Route::get('contact/{name}', 'App\Http\Controllers\PostController@contactPage');

Route::get('item/{item}/{color}/{size}', 'App\Http\Controllers\PostController@itemPage');

Route::get('bladeTest', 'App\Http\Controllers\PostController@bladeExample');
