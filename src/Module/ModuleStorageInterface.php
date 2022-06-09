<?php

declare(strict_types=1);

namespace Xylemical\Kernel\Module;

/**
 * Provides the module installation storage mechanism.
 */
interface ModuleStorageInterface {

  /**
   * Check the module has been installed.
   *
   * @param string $module
   *   The module.
   *
   * @return bool
   *   The result.
   */
  public function isInstalled(string $module): bool;

  /**
   * Set the module to be installation status.
   *
   * @param string $module
   *   The module.
   * @param bool $flag
   *   The installed flag.
   *
   * @return $this
   */
  public function setInstalled(string $module, bool $flag): static;

  /**
   * Get the module updates.
   *
   * @param string $module
   *   The module.
   *
   * @return string[]
   *   The updates.
   */
  public function getUpdates(string $module): array;

  /**
   * Check the module has an update.
   *
   * @param string $module
   *   The module.
   * @param string $update
   *   The update.
   *
   * @return bool
   *   The result.
   */
  public function hasUpdate(string $module, string $update): bool;

  /**
   * Set the module applied update.
   *
   * @param string $module
   *   The module.
   * @param string $update
   *   The update.
   * @param bool $flag
   *   The flag.
   *
   * @return $this
   */
  public function setUpdate(string $module, string $update, bool $flag): static;

}
