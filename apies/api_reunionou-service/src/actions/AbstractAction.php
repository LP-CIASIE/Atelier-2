<?php

namespace atelier\actions;

use Psr\Container\ContainerInterface;

abstract class AbstractAction
{
  protected ContainerInterface $container;

  public function __construct(ContainerInterface $container)
  {
    $this->container = $container;
  }
}
