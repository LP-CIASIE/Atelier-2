<?php

use atelier\tedyspo\actions\users;
use atelier\tedyspo\middlewares\AccessEventMiddleware;
use atelier\tedyspo\middlewares\OwnerEventMiddleware;

/** ======================
 * Collection Users
 * ===================== */
// GET

$app->get('/users[/]', users\GetUsersAction::class)->setName('get_users');
$app->get('/users/{id_user}[/]', users\GetUserAction::class)->setName('get_user');

// POST
$app->post('/signup[/]', users\CreateUserAction::class)->setName('create_user');

// PUT
$app->put('/users[/]', users\UpdateUserAction::class)->setName('update_user');

// DELETE
$app->delete('/users/{id_user}[/]', users\DeleteUserAction::class)->setName('delete_user');
