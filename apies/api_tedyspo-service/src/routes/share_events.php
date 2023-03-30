<?php

// Collection Share Evenements - Events
// GET
$app->get('/events/{id_event}/users[/]', atelier\tedyspo\actions\events\GetEventUsersAction::class)->setName('get_event_users')->add(new atelier\tedyspo\middlewares\AccessEventMiddleware($container));
$app->get('/events/{id_event}/users/{id_user}[/]', atelier\tedyspo\actions\events\GetEventUserAction::class)->setName('get_event_user')->add(new atelier\tedyspo\middlewares\AccessEventMiddleware($container));

// POST
$app->post('/events/{id_event}/users/{id_user}[/]', atelier\tedyspo\actions\events\CreateUserEventAction::class)->setName('create_event_user')->add(new atelier\tedyspo\middlewares\OwnerEventMiddleware($container));

// PUT
$app->put('/events/{id_event}/users/{id_user}[/]', atelier\tedyspo\actions\events\UpdateUserEventAction::class)->setName('update_event_user');

// DELETE
$app->delete('/events/{id_event}/users/{id_user}[/]', atelier\tedyspo\actions\events\DeleteUserEventAction::class)->setName('delete_event_user')->add(new atelier\tedyspo\middlewares\OwnerEventMiddleware($container));




// Collection Share Evenements - Users
// GET
$app->get('/users/{id_user}/events/{id_event}[/]', atelier\tedyspo\actions\events\GetEventUserAction::class)->setName('get_event_user')->add(new atelier\tedyspo\middlewares\AccessEventMiddleware($container));

// POST
$app->post('/users/{id_user}/events/{id_event}[/]', atelier\tedyspo\actions\events\CreateUserEventAction::class)->setName('create_user_event')->add(new atelier\tedyspo\middlewares\OwnerEventMiddleware($container));

// PUT
$app->put('/users/{id_user}/events/{id_event}[/]', atelier\tedyspo\actions\events\UpdateUserEventAction::class)->setName('update_user_event');

// DELETE
$app->delete('/users/{id_user}/events/{id_event}[/]', atelier\tedyspo\actions\events\DeleteUserEventAction::class)->setName('delete_user_event')->add(new atelier\tedyspo\middlewares\OwnerEventMiddleware($container));
