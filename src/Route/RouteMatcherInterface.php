<?php

declare(strict_types=1);

namespace Xylemical\Kernel\Route;

use Psr\Http\Message\ServerRequestInterface;
use Xylemical\Controller\ContextInterface;
use Xylemical\Controller\RouteInterface;

/**
 * Provides a route matching.
 */
interface RouteMatcherInterface {

  /**
   * Get the route matching the request.
   *
   * @param \Psr\Http\Message\ServerRequestInterface $request
   *   The request.
   * @param \Xylemical\Controller\ContextInterface $context
   *   The context.
   *
   * @return \Xylemical\Controller\RouteInterface|null
   *   The route.
   */
  public function getRoute(ServerRequestInterface $request, ContextInterface $context): ?RouteInterface;

}
