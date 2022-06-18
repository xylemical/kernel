<?php

declare(strict_types=1);

namespace Xylemical\Kernel;

use Xylemical\Controller\ResponderFactoryInterface;
use Xylemical\Controller\ResponderInterface;
use Xylemical\Controller\ResultInterface;
use Xylemical\Controller\RouteInterface;

/**
 * Provides the kernel responder factory.
 */
class ResponderFactory implements ResponderFactoryInterface {

  /**
   * The responder.
   *
   * @var \Xylemical\Controller\ResponderInterface[]
   */
  protected array $responders = [];

  /**
   * {@inheritdoc}
   */
  public function getResponder(RouteInterface $route, ResultInterface $result): ResponderInterface {
    foreach ($this->responders as $responder) {
      if (!is_null($responder->getResponse($route, $result))) {
        return $responder;
      }
    }
    throw new \InvalidArgumentException("No responders applied.");
  }

  /**
   * Add a responder.
   *
   * @param \Xylemical\Controller\ResponderInterface $responder
   *   The responder.
   *
   * @return $this
   */
  public function addResponder(ResponderInterface $responder): static {
    $this->responders[] = $responder;
    return $this;
  }

}
