<?php

declare(strict_types=1);

namespace Xylemical\Kernel;

use Xylemical\Controller\RequesterFactoryInterface;
use Xylemical\Controller\RequesterInterface;
use Xylemical\Controller\RouteInterface;

/**
 * Provides the kernel requester factory.
 */
class RequesterFactory implements RequesterFactoryInterface {

  /**
   * The requester.
   *
   * @var \Xylemical\Controller\RequesterInterface[]
   */
  protected array $requesters = [];

  /**
   * {@inheritdoc}
   */
  public function getRequester(RouteInterface $route): RequesterInterface {
    foreach ($this->requesters as $requester) {
      if (is_null($requester->getBody($route))) {
        return $requester;
      }
    }
    throw new \InvalidArgumentException("No requesters applied.");
  }

  /**
   * Add a requester.
   *
   * @param \Xylemical\Controller\RequesterInterface $requester
   *   The requester.
   *
   * @return $this
   */
  public function addRequester(RequesterInterface $requester): static {
    $this->requesters[] = $requester;
    return $this;
  }

}
