<?php

declare(strict_types=1);

namespace Xylemical\Kernel\Account;

use Xylemical\Account\AccountInterface;

/**
 * Provides the current account.
 */
class AccountProvider implements AccountProviderInterface {

  /**
   * The current account.
   *
   * @var \Xylemical\Account\AccountInterface
   */
  protected AccountInterface $account;

  /**
   * AccountProvider constructor.
   */
  public function __construct() {
    $this->account = new Account();
  }

  /**
   * {@inheritdoc}
   */
  public function getAccount(): AccountInterface {
    return $this->account;
  }

  /**
   * {@inheritdoc}
   */
  public function setAccount(AccountInterface $account): static {
    $this->account = $account;
    return $this;
  }

}
