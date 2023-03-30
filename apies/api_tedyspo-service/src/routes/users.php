<?php

/** ======================
 * Collection Users
 * ===================== */
// GET

$app->get('/users[/]', atelier\tedyspo\actions\users\GetUsersAction::class)->setName('get_users');
$app->get('/users/{id_user}[/]', atelier\tedyspo\actions\users\GetUserAction::class)->setName('get_user');

// POST
$app->post('/signup[/]', atelier\tedyspo\actions\users\CreateUserAction::class)->setName('create_user');

// PUT
$app->put('/users[/]', atelier\tedyspo\actions\users\UpdateUserAction::class)->setName('update_user');

// DELETE
$app->delete('/users/{id_user}[/]', atelier\tedyspo\actions\users\DeleteUserAction::class)->setName('delete_user');



// Collection Share Evenements
// GET
$app->get('/users/{id_user}/events/{id_event}[/]', atelier\tedyspo\actions\events\GetEventUserAction::class)->setName('get_event_user')->add(new atelier\tedyspo\middlewares\AccessEventMiddleware($container));

// POST
$app->post('/users/{id_user}/events/{id_event}[/]', atelier\tedyspo\actions\events\CreateUserEventAction::class)->setName('create_user_event')->add(new atelier\tedyspo\middlewares\OwnerEventMiddleware($container));

// PUT
$app->put('/users/{id_user}/events/{id_event}[/]', atelier\tedyspo\actions\events\UpdateUserEventAction::class)->setName('update_user_event');

// DELETE
$app->delete('/users/{id_user}/events/{id_event}[/]', atelier\tedyspo\actions\events\DeleteUserEventAction::class)->setName('delete_user_event')->add(new atelier\tedyspo\middlewares\OwnerEventMiddleware($container));
