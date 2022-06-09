<?php

declare(strict_types=1);

namespace Xylemical\Kernel\Request;

/**
 * Provide a command-line argument.
 */
class CliArgument {

  /**
   * The argument.
   *
   * @var string
   */
  protected string $argument;

  /**
   * CliArgument constructor.
   *
   * @param string $argument
   *   The argument.
   */
  public function __construct(string $argument) {
    $this->argument = $argument;
  }

  /**
   * Get the argument.
   *
   * @return string
   *   The argument.
   */
  public function getArgument(): string {
    return $this->argument;
  }

}
