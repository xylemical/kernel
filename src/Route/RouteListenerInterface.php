<?php

declare(strict_types=1);

namespace Xylemical\Kernel\Route;

use Xylemical\Controller\RouteInterface;

/**
 * Provide a means to modify the route.
 */
interface RouteListenerInterface {

  /**
   * Check the listener applies to the route.
   *
   * @param \Xylemical\Controller\RouteInterface $route
   *   The route.
   *
   * @return bool
   *   The result.
   */
  public function applies(RouteInterface $route): bool;

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
