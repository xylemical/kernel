<?php

declare(strict_types=1);

namespace Xylemical\Kernel\Request;

/**
 * Provides support for command-line objects.
 */
trait CliTrait {

  /**
   * Check to see if we are running the command-line.
   *
   * @return bool
   *   The result.
   */
  protected function isCli(): bool {
    return empty($_SERVER['REMOTE_ADDR']) &&
      !isset($_SERVER['HTTP_USER_AGENT']) &&
      count($_SERVER['argv']) > 0;
  }

  /**
   * Parses the command-line.
   *
   * @return \Xylemical\Kernel\Request\Cli
   *   The command-line.
   */
  protected function parse(): Cli {
    return new Cli($_SERVER['argv']);
  }

}
