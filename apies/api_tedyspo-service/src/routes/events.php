<?php

/** =========================
 * Collection Evenements
 * ======================= */

// GET
$app->get('/events[/]', atelier\tedyspo\actions\events\GetEventsAction::class)->setName('get_events');
$app->get('/events/{id_event}[/]', atelier\tedyspo\actions\events\GetEventAction::class)->setName('get_event');

// POST
$app->post('/events[/]', atelier\tedyspo\actions\events\CreateEventAction::class)->setName('create_event');

// PUT
$app->put('/events/{id_event}[/]', atelier\tedyspo\actions\events\UpdateEventAction::class)->setName('update_event')->add(new atelier\tedyspo\middlewares\OwnerEventMiddleware($container));

// DELETE
$app->delete('/events/{id_event}[/]', atelier\tedyspo\actions\events\DeleteEventAction::class)->setName('delete_event')->add(new atelier\tedyspo\middlewares\OwnerEventMiddleware($container));



// Collection Share Evenements
// GET
$app->get('/events/{id_event}/users[/]', atelier\tedyspo\actions\events\GetEventUsersAction::class)->setName('get_event_users')->add(new atelier\tedyspo\middlewares\AccessEventMiddleware($container));
$app->get('/events/{id_event}/users/{id_user}[/]', atelier\tedyspo\actions\events\GetEventUserAction::class)->setName('get_event_user')->add(new atelier\tedyspo\middlewares\AccessEventMiddleware($container));

// POST
$app->post('/events/{id_event}/users/{id_user}[/]', atelier\tedyspo\actions\events\CreateUserEventAction::class)->setName('create_event_user')->add(new atelier\tedyspo\middlewares\OwnerEventMiddleware($container));

// PUT
$app->put('/events/{id_event}/users/{id_user}[/]', atelier\tedyspo\actions\events\UpdateUserEventAction::class)->setName('update_event_user');

// DELETE
$app->delete('/events/{id_event}/users/{id_user}[/]', atelier\tedyspo\actions\events\DeleteUserEventAction::class)->setName('delete_event_user')->add(new atelier\tedyspo\middlewares\OwnerEventMiddleware($container));
