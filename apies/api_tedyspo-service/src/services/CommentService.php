<?php

namespace atelier\tedyspo\services;

use atelier\tedyspo\models\Comment;
use atelier\tedyspo\models\Event;
use atelier\tedyspo\models\User;
use Respect\Validation\Validator as v;

class CommentService extends AbstractService
{
  public function createComment($data, $id_user, $id_event)
  {
    try {
      v::key('comment', v::stringType()->notEmpty())
        ->assert($data);
    } catch (\Exception $e) {
      throw new \Exception('Données invalides', 400);
    }

    try {
      User::findOrFail($id_user);
    } catch (\Exception $e) {
      throw new \Exception('Identifiant utilisateur invalide', 400);
    }

    try {
      Event::findOrFail($id_event);
    } catch (\Exception $e) {
      throw new \Exception('Identifiant événement invalide', 400);
    }

    $comment = new Comment();
    $comment->id_comment = \Ramsey\Uuid\Uuid::uuid4();
    $comment->comment = $data['comment'];
    $comment->id_user = $id_user;
    $comment->id_event = $id_event;

    try {
      $comment->save();
    } catch (\Exception $e) {
      throw new \Exception('Erreur lors de la création du commentaire', 500);
    }

    return $comment;
  }
}
