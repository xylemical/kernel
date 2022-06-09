<?php

declare(strict_types=1);

namespace Xylemical\Kernel\Module;

/**
 * Provides module discovery.
 */
interface ModuleDiscoveryInterface {

  /**
   * Get the paths that contain modules.
   *
   * @param string $root
   *   The root of the application.
   *
   * @return string[]
   *   The module paths.
   */
  public function getPaths(string $root): array;

}
