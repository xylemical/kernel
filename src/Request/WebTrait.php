<?php

declare(strict_types=1);

namespace Xylemical\Kernel\Request;

/**
 * Provides support for web-server requests.
 */
trait WebTrait {

  /**
   * Check that PHP is running as a web-server.
   *
   * @return bool
   *   The result.
   */
  protected function isWeb(): bool {
    return TRUE;
  }

}
