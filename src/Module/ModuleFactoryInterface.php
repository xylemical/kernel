<?php

declare(strict_types=1);

namespace Xylemical\Kernel\Module;

/**
 * Provides a module factory.
 */
interface ModuleFactoryInterface {

  /**
   * Check that the path represents a module.
   *
   * @param string $path
   *   The path.
   *
   * @return bool
   *   The result.
   */
  public function applies(string $path): bool;

  /**
   * Get the module definition from the path.
   *
   * @param string $path
   *   The path.
   *
   * @return \Xylemical\Kernel\Module\ModuleInterface
   *   The module.
   */
  public function getModule(string $path): ModuleInterface;

}
