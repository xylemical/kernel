<?php

declare(strict_types=1);

namespace Xylemical\Kernel\Account;

use Xylemical\Controller\Authorization\AuthorizationFactoryInterface;
use Xylemical\Controller\Authorization\AuthorizationInterface;
use Xylemical\Controller\RouteInterface;

/**
 * Provides a generic authorization factory.
 */
class AuthorizationFactory implements AuthorizationFactoryInterface {

  /**
   * The authorization factories.
   *
   * @var \Xylemical\Controller\Authorization\AuthorizationFactoryInterface[]
   */
  protected array $factories = [];

  /**
   * {@inheritdoc}
   */
  public function getAuthorization(RouteInterface $route): ?AuthorizationInterface {
    foreach ($this->factories as $factory) {
      if ($authorization = $factory->getAuthorization($route)) {
        return $authorization;
      }
    }
    return NULL;
  }

  /**
   * Adds an authorization factory.
   *
   * @param \Xylemical\Controller\Authorization\AuthorizationFactoryInterface $factory
   *   The factory.
   *
   * @return $this
   */
  public function addFactory(AuthorizationFactoryInterface $factory): static {
    $this->factories[] = $factory;
    return $this;
  }

}
