<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


BroadCast::channel('new-join.{id}', function ($user, $id) {
    return $user;
});


BroadCast::channel('time.{id}', function ($user, $id) {
    return true;
});

BroadCast::channel('time.newbooking.{id}', function ($user, $id) {
    return true;
});

BroadCast::channel('customer.join.{id}', function ($user, $id) {
    return true;
});
