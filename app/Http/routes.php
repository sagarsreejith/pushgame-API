<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::post('pushgame_login', [
    'as' => 'pushgame_login', 'uses' => 'PushGameApi@PushGameLogin'
]);
Route::post('global_timer', [
    'as' => 'global_timer', 'uses' => 'PushGameApi@GlobalTimer'
]);
Route::post('pushgame_leader_board', [
    'as' => 'pushgame_leader_board', 'uses' => 'PushGameApi@PushgameLeaderBoard'
]);
Route::post('pushgame_leader_board_post', [
    'as' => 'pushgame_leader_board_post', 'uses' => 'PushGameApi@PushgameLeaderBoardMessagePosting'
]);
Route::post('global_timer_product', [
    'as' => 'global_timer_product', 'uses' => 'PushGameApi@ProductModeGlobalTimer'
]);
Route::post('pushgame_leader_board_individual', [
    'as' => 'pushgame_leader_board_individual', 'uses' => 'PushGameApi@PushGameLeaderBoardIndividual'
]);
Route::post('pushgame_leader_board_product_post', [
    'as' => 'pushgame_leader_board_product_post', 'uses' => 'PushGameApi@ProductModeMessagePosting'
]);
Route::post('pushgame_profile', [
    'as' => 'pushgame_profile', 'uses' => 'PushGameApi@PushGameProfile'
]);
Route::post('delete_message', [
    'as' => 'delete_message', 'uses' => 'PushGameApi@PushGameDeleteMessage'
]);
Route::post('push_get_all_message', [
    'as' => 'push_get_all_message', 'uses' => 'PushGameApi@PushGameGetAllMessage'
]);
Route::post('latest_dash_message', [
    'as' => 'latest_dash_message', 'uses' => 'PushGameApi@PushGameLatestMessage'
]);
Route::post('game_mission', [
    'as' => 'game_mission', 'uses' => 'PushGameApi@PushGameMission'
]);
