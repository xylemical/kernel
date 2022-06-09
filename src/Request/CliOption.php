<?php

declare(strict_types=1);

namespace Xylemical\Kernel\Request;

/**
 * Provides a command-line option.
 */
class CliOption {

  /**
   * The option.
   *
   * @var string
   */
  protected string $option;

  /**
   * The value.
   *
   * @var string
   */
  protected string $value;

  /**
   * CliOption construct.
   *
   * @param string $option
   *   The option.
   * @param string $value
   *   The value.
   */
  public function __construct(string $option, string $value) {
    $this->option = $option;
    $this->value = $value;
  }

  /**
   * Get the option.
   *
   * @return string
   *   The option.
   */
  public function getOption(): string {
    return $this->option;
  }

  /**
   * Get the value.
   *
   * @return string
   *   The value.
   */
  public function getValue(): string {
    return $this->value;
  }

}
