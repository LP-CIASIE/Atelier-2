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
