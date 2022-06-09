<?php

declare(strict_types=1);

namespace Xylemical\Kernel\Module;

/**
 * Provides for different module states.
 */
interface ModuleInstallerInterface {

  /**
   * Install a module.
   *
   * @param \Xylemical\Kernel\Module\ModuleInterface $module
   *   The module.
   *
   * @return $this
   */
  public function install(ModuleInterface $module): static;

  /**
   * Uninstall a module.
   *
   * @param \Xylemical\Kernel\Module\ModuleInterface $module
   *   The module.
   *
   * @return $this
   */
  public function uninstall(ModuleInterface $module): static;

}
