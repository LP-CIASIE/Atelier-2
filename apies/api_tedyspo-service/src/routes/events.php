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
