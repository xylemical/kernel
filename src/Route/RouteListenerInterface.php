<?php

declare(strict_types=1);

namespace Xylemical\Kernel\Route;

use Xylemical\Controller\RouteInterface;

/**
 * Provide a means to modify the route.
 */
interface RouteListenerInterface {

  /**
   * Listen to the route.
   *
   * @param \Xylemical\Controller\RouteInterface $route
   *   The route.
   *
   * @return \Xylemical\Controller\RouteInterface
   *   The updated route.
   */
  public function listen(RouteInterface $route): RouteInterface;

}
