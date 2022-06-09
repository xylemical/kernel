<?php

declare(strict_types=1);

namespace Xylemical\Kernel\Account;

use Xylemical\Controller\Authentication\AuthenticationFactoryInterface;
use Xylemical\Controller\Authentication\AuthenticationInterface;
use Xylemical\Controller\RouteInterface;

/**
 * Provides the kernel authentication factory.
 */
class AuthenticationFactory implements AuthenticationFactoryInterface {

  /**
   * The account provider.
   *
   * @var \Xylemical\Kernel\Account\AccountProviderInterface
   */
  protected AccountProviderInterface $provider;

  /**
   * The factories.
   *
   * @var \Xylemical\Controller\Authentication\AuthenticationFactoryInterface[]
   */
  protected array $factories = [];

  /**
   * AuthenticationFactory constructor.
   *
   * @param \Xylemical\Kernel\Account\AccountProviderInterface $provider
   *   The provider.
   */
  public function __construct(AccountProviderInterface $provider) {
    $this->provider = $provider;
  }

  /**
   * {@inheritdoc}
   */
  public function getAuthentication(RouteInterface $route): ?AuthenticationInterface {
    foreach ($this->factories as $factory) {
      if ($authentication = $factory->getAuthentication($route)) {
        return new Authentication($this->provider, $authentication);
      }
    }
    return NULL;
  }

  /**
   * Add an authentication factory.
   *
   * @param \Xylemical\Controller\Authentication\AuthenticationFactoryInterface $factory
   *   The factory.
   *
   * @return $this
   */
  public function addFactory(AuthenticationFactoryInterface $factory): static {
    $this->factories[] = $factory;
    return $this;
  }

}
