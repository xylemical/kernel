<?php

declare(strict_types=1);

namespace Xylemical\Kernel\Module;

/**
 * Provides support for modules to provide functionality.
 */
interface ModuleCapabilityInterface {

  /**
   * Check the module supports the service.
   *
   * @codingStandardsIgnoreStart
   *
   * @param \Xylemical\Kernel\Module\ModuleInterface $module
   *   The module.
   * @param class-string $service
   *   The service class name.
   *
   * @codingStandardsIgnoreEnd
   *
   * @return bool
   *   The result.
   */
  public function applies(ModuleInterface $module, string $service): bool;

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
   * @return T
   *   The service.
   */
  public function getHandler(ModuleInterface $module, string $service): mixed;

}
