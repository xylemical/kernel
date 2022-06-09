<?php

declare(strict_types=1);

namespace Xylemical\Kernel\Module;

/**
 * Provides service collector module installer.
 */
class ModuleInstaller implements ModuleInstallerInterface {

  /**
   * The installers.
   *
   * @var \Xylemical\Kernel\Module\ModuleInstallerInterface[]
   */
  protected array $installers = [];

  /**
   * {@inheritdoc}
   */
  public function install(ModuleInterface $module): static {
    foreach ($this->installers as $installer) {
      $installer->install($module);
    }
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function uninstall(ModuleInterface $module): static {
    foreach ($this->installers as $installer) {
      $installer->uninstall($module);
    }
    return $this;
  }

  /**
   * Add installers.
   *
   * @param \Xylemical\Kernel\Module\ModuleInstallerInterface $installer
   *   The installer.
   *
   * @return $this
   */
  public function addInstaller(ModuleInstallerInterface $installer): static {
    $this->installers[] = $installer;
    return $this;
  }

}
