<?php

declare(strict_types=1);

namespace Xylemical\Kernel\Account;

use Xylemical\Account\AccountInterface;
use Xylemical\Account\AccountProviderInterface as AccountProvider;

/**
 * Provides the account provider.
 */
interface AccountProviderInterface extends AccountProvider {

  /**
   * Set the current account.
   *
   * @param \Xylemical\Account\AccountInterface $account
   *   The account.
   *
   * @return $this
   */
  public function setAccount(AccountInterface $account): static;

}
