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
   * Check the request matches a route.
   *
   * @param \Psr\Http\Message\ServerRequestInterface $request
   *   The request.
   *
   * @return bool
   *   The result.
   */
  public function applies(ServerRequestInterface $request): bool;

  /**
   * Get the route matching the request.
   *
   * @param \Psr\Http\Message\ServerRequestInterface $request
   *   The request.
   * @param \Xylemical\Controller\ContextInterface $context
   *   The context.
   *
   * @return \Xylemical\Controller\RouteInterface
   *   The route.
   */
  public function getRoute(ServerRequestInterface $request, ContextInterface $context): RouteInterface;

}
