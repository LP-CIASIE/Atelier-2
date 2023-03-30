<?php

use atelier\tedyspo\actions\comments;
use atelier\tedyspo\middlewares\AccessEventMiddleware;
use atelier\tedyspo\middlewares\OwnerEventMiddleware;


// Collection Commentaires
// GET
$app->get('/events/{id_event}/comments[/]', comments\GetCommentsAction::class)->setName('get_comments')->add(new AccessEventMiddleware($container));
$app->get('/events/{id_event}/comments/{id_comment}[/]', comments\GetCommentAction::class)->setName('get_comment')->add(new AccessEventMiddleware($container));

// POST
$app->post('/events/{id_event}/comments[/]', comments\CreateCommentAction::class)->setName('create_comment')->add(new AccessEventMiddleware($container));

// PUT
$app->put('/events/{id_event}/comments/{id_comment}[/]', comments\UpdateCommentAction::class)->setName('update_comment')->add(new AccessEventMiddleware($container));

// DELETE
$app->delete('/events/{id_event}/comments/{id_comment}[/]', comments\DeleteCommentAction::class)->setName('delete_comment')->add(new AccessEventMiddleware($container));
