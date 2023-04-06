<?php

use atelier\tedyspo\actions\events;
use atelier\tedyspo\middlewares\AccessEventMiddleware;
use atelier\tedyspo\middlewares\OwnerEventMiddleware;

// Collection Share Evenements - Events
// GET
$app->get('/events/{id_event}/users[/]', events\GetUsersEventAction::class)->setName('get_event_users')->add(new AccessEventMiddleware($container));
$app->get('/events/{id_event}/users/{id_user}[/]', events\GetEventUserAction::class)->setName('get_event_user')->add(new AccessEventMiddleware($container));

// POST
$app->post('/events/{id_event}/users/{id_user}[/]', events\CreateEventUserAction::class)->setName('create_event_user')->add(new OwnerEventMiddleware($container));

// PUT
$app->put('/events/{id_event}/users/{id_user}[/]', events\UpdateEventUserAction::class)->setName('update_event_user');

// DELETE
$app->delete('/events/{id_event}/users/{id_user}[/]', events\DeleteEventUserAction::class)->setName('delete_event_user')->add(new OwnerEventMiddleware($container));

// Collection Share Evenements - Users
// GET
$app->get('/users/{id_user}/events[/]', events\GetEventsUserAction::class)->setName('get_users_events');

// POST
$app->post('/users/{id_user}/events/{id_event}[/]', events\CreateEventUserAction::class)->setName('create_user_event')->add(new OwnerEventMiddleware($container));

// PUT
$app->put('/users/{id_user}/events/{id_event}[/]', events\UpdateEventUserAction::class)->setName('update_user_event');

// DELETE
$app->delete('/users/{id_user}/events/{id_event}[/]', events\DeleteEventUserAction::class)->setName('delete_user_event')->add(new OwnerEventMiddleware($container));
