<?php

namespace atelier\tedyspo\services;

use atelier\tedyspo\models\Comment;
use atelier\tedyspo\models\Event;
use atelier\tedyspo\models\User;
use Illuminate\Database\Eloquent\Collection;
use Respect\Validation\Validator as v;

class CommentService extends AbstractService
{
  public function getCount($id_event)
  {
    try {
      $event = Event::findOrFail($id_event);
    } catch (\Exception $e) {
      throw new \Exception('Cette événement n\'existe pas', 404);
    }

    return $event->comments()->count();
  }

  public function createComment($data, $id_user, $id_event): Comment
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

  public function getComments($id_event, $data): Collection
  {
    try {
      $event = Event::findOrFail($id_event);
    } catch (\Exception $e) {
      throw new \Exception('Cette événement n\'existe pas ou plus', 404);
    }

    if (isset($data['page'])) {
      try {
        v::key('page', v::intVal()->positive())
          ->assert($data);
      } catch (\Exception $e) {
        throw new \Exception('Données pour la pagination invalides', 400);
      }
    } else {
      $data['page'] = 1;
    }

    if (isset($data['size'])) {
      try {
        v::key('size', v::intVal()->positive())
          ->assert($data);
      } catch (\Exception $e) {
        throw new \Exception('Données pour la pagination invalides', 400);
      }
    } else {
      $data['size'] = 10;
    }

    try {
      $comments = $event->comments()->skip($data['size'] * ($data['page'] - 1))->take($data['size'])->get();
    } catch (\Exception $e) {
      throw new \Exception('Erreur lors de la récupération des commentaires', 500);
    }

    return $comments;
  }

  public function getComment($id_comment)
  {
    try {
      $comment = Comment::findOrFail($id_comment);
    } catch (\Exception $e) {
      throw new \Exception('Ce commentaire n\'existe pas ou plus', 404);
    }

    return $comment;
  }

  public function updateComment($id_comment, $id_user,  $body): Comment
  {
    $this->isOwner($id_comment, $id_user);

    try {
      $comment = Comment::findOrFail($id_comment);
    } catch (\Exception $e) {
      throw new \Exception('Ce commentaire n\'existe pas ou plus', 404);
    }

    try {
      v::key('comment', v::stringType()->notEmpty())
        ->assert($body);
    } catch (\Exception $e) {
      throw new \Exception('Données invalides', 400);
    }

    $comment->comment = $body['comment'];

    try {
      $comment->save();
    } catch (\Exception $e) {
      throw new \Exception('Erreur lors de la mise à jour du commentaire', 500);
    }

    return $comment;
  }

  public function deleteComment($id_comment, $id_user): void
  {
    $comment = $this->isOwner($id_comment, $id_user);

    try {
      $comment->delete();
    } catch (\Exception $e) {
      throw new \Exception('Erreur lors de la suppression du commentaire', 500);
    }
  }

  public function isOwner($id_comment, $id_user): Comment
  {
    try {
      $comment = Comment::findOrFail($id_comment);
    } catch (\Exception $e) {
      throw new \Exception('Ce commentaire n\'existe pas ou plus', 404);
    }

    if ($comment->id_user != $id_user) {
      throw new \Exception('Vous n\'êtes pas le propriétaire de ce commentaire', 403);
    }

    return $comment;
  }
}
