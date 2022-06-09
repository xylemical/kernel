<?php

declare(strict_types=1);

namespace Xylemical\Kernel\Module;

/**
 * Provides the module manager.
 */
interface ModuleManagerInterface {

  /**
   * Get list of modules.
   *
   * @return \Xylemical\Kernel\Module\ModuleInterface[]
   *   The modules indexed by name.
   */
  public function getModules(): array;

  /**
   * Get the names of the dependent modules.
   *
   * @param string $module
   *   The module.
   *
   * @return string[]
   *   The dependent modules.
   */
  public function getDependentModules(string $module): array;

  /**
   * Get all the installed modules.
   *
   * @return \Xylemical\Kernel\Module\ModuleInterface[]
   *   The modules.
   */
  public function getInstalled(): array;

  /**
   * Check the module exists.
   *
   * @param string $module
   *   The module.
   *
   * @return bool
   *   The result.
   */
  public function hasModule(string $module): bool;

  /**
   * Get a module.
   *
   * @param string $module
   *   The module.
   *
   * @return \Xylemical\Kernel\Module\ModuleInterface|null
   *   The module or NULL.
   */
  public function getModule(string $module): ?ModuleInterface;

  /**
   * Check the module is installed.
   *
   * @param string $module
   *   The module.
   *
   * @return bool
   *   The result.
   */
  public function isInstalled(string $module): bool;

  /**
   * Install modules.
   *
   * @param array $modules
   *   The modules.
   *
   * @return $this
   */
  public function install(array $modules): static;

  /**
   * Uninstall modules.
   *
   * @param array $modules
   *   The modules.
   *
   * @return $this
   */
  public function uninstall(array $modules): static;

}
