<?php

namespace atelier\tedyspo\middlewares;

use atelier\tedyspo\models\Event;
use Exception;
use Psr\Http\Message\ServerRequestInterface as Request;

class OwnerEventMiddleware extends AbstractMiddleware
{
  /**
   * Récupère l'id de l'utilisateur dans le token JWT reçu par la requête
   * Récupère l'événement par l'id de route
   * 
   * Vérifie si l'utilisateur est le propriétaire de l'événement
   *
   * @param Request $request
   * @return boolean
   */
  public function validateMiddleware(Request $request): bool
  {
    $JWTService = $this->container->get('service.jwt');
    $user = $JWTService->decodeDataOfJWT($request->getHeader('Authorization'));

    $event = $this->getEvent($request);

    $owner = $event->getOwner();

    if ($owner->id_user == $user['uid']) {
      return true;
    } else {
      return false;
    }
  }


  /**
   * Message d'erreur en cas de refus du middleware
   *
   * @return array
   */
  public function ErrorMiddleware(): Exception
  {
    throw new \Exception('Vous n\'êtes pas le propriétaire de cet événement', 403);
  }


  /**
   * Récupère l'événement par l'id de route
   *
   * @param Request $request
   * @return Event
   */
  public function getEvent(Request $request): Event
  {
    // Get id of the event
    $routeArguments = \Slim\Routing\RouteContext::fromRequest($request)->getRoute()->getArguments();
    $id_event = $routeArguments['id_event'];

    // Get event
    $eventService = $this->container->get('service.event');
    $event = $eventService->getEventById($id_event);

    return $event;
  }
}
