<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    // Build the query parameter string to pass auth information to our request
 /*   $query = http_build_query([
        'client_id' => 3,
        'redirect_uri' => 'http://consumer.dev/callback',
        'response_type' => 'code',
        'scope' => 'conference'
    ]);

    // Redirect the user to the OAuth authorization page
    return redirect('http://passport.dev/oauth/authorize?' . $query);*/
    return view('welcome');
});
Route::get('/auth', function () {
    return view('auth/auth');
});

Route::get('/redirect', function () {

    $query = http_build_query([
        'client_id' => '3',
        'redirect_uri' => 'http://client.local/callback',
        'response_type' => 'code',
        'scope' => ''
    ]);

    return redirect('http://server.local/oauth/authorize?'.$query);
});

// Route that user is forwarded back to after approving on server
Route::get('callback', function (Request $request) {
    $http = new GuzzleHttp\Client;

    $response = $http->post('http://passport.dev/oauth/token', [
        'form_params' => [
            'grant_type' => 'authorization_code',
            'client_id' => 3, // from admin panel above
            'client_secret' => 'yxOJrP0L9gqbXxoxoFl5I22IytFOpeCnUXD3aE0d', // from admin panel above
            'redirect_uri' => 'http://consumer.dev/callback',
            'code' => $request->code // Get code from the callback
        ]
    ]);

    // echo the access token; normally we would save this in the DB
    return json_decode((string) $response->getBody(), true)['access_token'];
});

// routes/api.php
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');


Auth::routes();

Route::get('/home', 'HomeController@index');
