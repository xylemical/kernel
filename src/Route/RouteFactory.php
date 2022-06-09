<?php

declare(strict_types=1);

namespace Xylemical\Kernel\Route;

use Psr\Http\Message\ServerRequestInterface;
use Xylemical\Controller\ContextFactoryInterface;
use Xylemical\Controller\RouteFactoryInterface;
use Xylemical\Controller\RouteInterface;

/**
 * Provides the kernel route factory.
 */
class RouteFactory implements RouteFactoryInterface {

  /**
   * The context factory.
   *
   * @var \Xylemical\Controller\ContextFactoryInterface
   */
  protected ContextFactoryInterface $contextFactory;

  /**
   * The route matcher.
   *
   * @var \Xylemical\Kernel\Route\RouteMatcherInterface
   */
  protected RouteMatcherInterface $matcher;

  /**
   * Context factory.
   *
   * @param \Xylemical\Controller\ContextFactoryInterface $contextFactory
   *   The context factory.
   * @param \Xylemical\Kernel\Route\RouteMatcherInterface $matcher
   *   The route matcher.
   */
  public function __construct(ContextFactoryInterface $contextFactory, RouteMatcherInterface $matcher) {
    $this->contextFactory = $contextFactory;
    $this->matcher = $matcher;
  }

  /**
   * {@inheritdoc}
   */
  public function getRoute(ServerRequestInterface $request): RouteInterface {
    if ($this->matcher->applies($request)) {
      return $this->matcher->getRoute($request, $this->contextFactory->getContext($request));
    }
    throw new \InvalidArgumentException("Unable to match route for request.");
  }

}
