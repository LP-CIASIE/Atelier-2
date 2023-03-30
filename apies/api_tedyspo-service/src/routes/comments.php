<?php

// Collection Commentaires
// GET
$app->get('/events/{id_event}/comments[/]', atelier\tedyspo\actions\comments\GetCommentsAction::class)->setName('get_comments');
$app->get('/events/{id_event}/comments/{id_comment}[/]', atelier\tedyspo\actions\comments\GetCommentAction::class)->setName('get_comment');

// POST
$app->post('/events/{id_event}/comments[/]', atelier\tedyspo\actions\comments\CreateCommentAction::class)->setName('create_comment');

// PUT
$app->put('/events/{id_event}/comments/{id_comment}[/]', atelier\tedyspo\actions\comments\UpdateCommentAction::class)->setName('update_comment');

// DELETE
$app->delete('/events/{id_event}/comments/{id_comment}[/]', atelier\tedyspo\actions\comments\DeleteCommentAction::class)->setName('delete_comment');
