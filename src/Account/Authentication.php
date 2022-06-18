<?php

declare(strict_types=1);

namespace Xylemical\Kernel\Account;

use Xylemical\Account\AccountInterface;
use Xylemical\Controller\Authentication\AuthenticationInterface;
use Xylemical\Controller\RouteInterface;

/**
 * Provides authentication that sets the account provider.
 */
class Authentication implements AuthenticationInterface {

  /**
   * The account provider.
   *
   * @var \Xylemical\Kernel\Account\AccountProviderInterface
   */
  protected AccountProviderInterface $provider;

  /**
   * The chosen authentication.
   *
   * @var \Xylemical\Controller\Authentication\AuthenticationInterface
   */
  protected AuthenticationInterface $authentication;

  /**
   * Authentication constructor.
   *
   * @param \Xylemical\Kernel\Account\AccountProviderInterface $provider
   *   The account provider.
   * @param \Xylemical\Controller\Authentication\AuthenticationInterface $authentication
   *   The authentication.
   */
  public function __construct(AccountProviderInterface $provider, AuthenticationInterface $authentication) {
    $this->provider = $provider;
    $this->authentication = $authentication;
  }

  /**
   * {@inheritdoc}
   */
  public function authenticate(RouteInterface $route): ?AccountInterface {
    if ($account = $this->authentication->authenticate($route)) {
      $this->provider->setAccount($account);
    }
    return $account;
  }

}
