<?php

declare(strict_types=1);

namespace Xylemical\Kernel\Module;

/**
 * Provides support for modules to provide functionality.
 */
interface ModuleCapabilityInterface {

  /**
   * Get a module capability handler.
   *
   * @codingStandardsIgnoreStart
   *
   * @param \Xylemical\Kernel\Module\ModuleInterface $module
   *   The module.
   * @param class-string<T> $service
   *   The service class name.
   *
   * @template T
   *
   * @codingStandardsIgnoreEnd
   *
   * @return T|null
   *   The service or null.
   */
  public function getHandler(ModuleInterface $module, string $service): mixed;

}
