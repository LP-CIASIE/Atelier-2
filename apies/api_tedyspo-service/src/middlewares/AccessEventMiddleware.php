<?php

namespace atelier\tedyspo\middlewares;

use atelier\tedyspo\models\Event;
use Psr\Http\Message\ServerRequestInterface as Request;
use Throwable;

class AccessEventMiddleware extends AbstractMiddleware
{
  /**
   * Récupère l'id de l'utilisateur dans le token JWT reçu par la requête
   * Récupère l'événement par l'id de route
   * 
   * Vérifie si l'utilisateur est invité à l'événement
   *
   * @param Request $request
   * @return boolean
   */
  public function validateMiddleware(Request $request): bool
  {
    $params = $request->getQueryParams();
    $event = $this->getEvent($request);
    if (isset($params['code'])) {
      if ($event->code_share == $params['code']) {
        return true;
      } else {
        return false;
      }
    }

    $JWTService = $this->container->get('service.jwt');
    $user = $JWTService->decodeDataOfJWT($request->getHeader('Authorization'));

    $participant = $event->users()->where('event_user.id_user', $user['uid'])->withPivot('state')->first();

    if ($participant) {
      return $participant->pivot->state == "accepted" || $participant->pivot->state == "pending";
    } else {
      return false;
    }
  }


  /**
   * Message d'erreur en cas de refus du middleware
   *
   * @return Trhrowable
   */
  public function ErrorMiddleware(): \Throwable
  {
    throw new \Exception('Tu n\'es pas invité, tu n\'as pas encore accepté l\'invitation ou le code n\'est pas bon.', 403);
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
