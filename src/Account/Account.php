<?php

declare(strict_types=1);

namespace Xylemical\Kernel\Account;

use Xylemical\Account\AccountInterface;

/**
 * Provides a generic account.
 */
class Account implements AccountInterface {

  /**
   * The account identifier.
   *
   * @var string
   */
  protected string $id;

  /**
   * Account constructor.
   *
   * @param string $id
   *   The account identifier.
   */
  public function __construct(string $id = '') {
    $this->id = $id;
  }

  /**
   * {@inheritdoc}
   */
  public function getId(): string {
    return $this->id;
  }

}
