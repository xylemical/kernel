<?php

declare(strict_types=1);

namespace Xylemical\Kernel\Module;

/**
 * Provides a module factory.
 */
interface ModuleFactoryInterface {

  /**
   * Get the module definition from the path.
   *
   * @param string $path
   *   The path.
   *
   * @return \Xylemical\Kernel\Module\ModuleInterface|null
   *   The module or NULL.
   */
  public function getModule(string $path): ?ModuleInterface;

}
