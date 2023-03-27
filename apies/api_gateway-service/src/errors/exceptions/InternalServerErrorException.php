<?php

namespace atelier\gateway\errors\exceptions;

use Slim\Exception\HttpException;
use Slim\Exception\HttpInternalServerErrorException;

class InternalServerErrorException extends HttpException
{
  protected $code = 500;
  protected $message = 'Une erreur interne du serveur est survenue. Veuillez réessayer ultérieurement.';
  protected string $title = '500 - Erreur interne du serveur';
}
