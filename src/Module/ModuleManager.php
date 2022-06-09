<?php

declare(strict_types=1);

namespace Xylemical\Kernel\Module;

use Xylemical\Kernel\InformationInterface;
use function array_filter;
use function array_merge;
use function array_unique;
use function in_array;

/**
 * Provides the module manager.
 */
class ModuleManager implements ModuleManagerInterface {

  /**
   * The storage for the module installation.
   *
   * @var \Xylemical\Kernel\Module\ModuleStorageInterface
   */
  protected ModuleStorageInterface $storage;

  /**
   * The module installer.
   *
   * @var \Xylemical\Kernel\Module\ModuleInstallerInterface
   */
  protected ModuleInstallerInterface $installer;

  /**
   * The modules.
   *
   * @var \Xylemical\Kernel\Module\ModuleInterface[]
   */
  protected array $modules = [];

  /**
   * ModuleManager constructor.
   *
   * @param \Xylemical\Kernel\InformationInterface $info
   *   The application information.
   * @param \Xylemical\Kernel\Module\ModuleStorageInterface $storage
   *   The storage.
   * @param \Xylemical\Kernel\Module\ModuleDiscoveryInterface $discovery
   *   The discovery.
   * @param \Xylemical\Kernel\Module\ModuleInstallerInterface $installer
   *   The installer.
   * @param \Xylemical\Kernel\Module\ModuleFactoryInterface $processor
   *   The processor.
   */
  public function __construct(InformationInterface $info, ModuleStorageInterface $storage, ModuleDiscoveryInterface $discovery, ModuleInstallerInterface $installer, ModuleFactoryInterface $processor) {
    $this->storage = $storage;
    $this->installer = $installer;
    foreach ($discovery->getPaths($info->getRoot()) as $path) {
      if ($processor->applies($path)) {
        $module = $processor->getModule($path);
        $this->modules[$module->getName()] = $module;
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getModules(): array {
    return $this->modules;
  }

  /**
   * {@inheritdoc}
   */
  public function getDependentModules(string $module): array {
    $processed = [];
    foreach ($this->modules as $dependent) {
      if (in_array($dependent->getName(), $processed)) {
        continue;
      }
      elseif (!in_array($module, $dependent->getDependencies())) {
        continue;
      }
      $processed = array_unique(
        array_merge(
          [$dependent->getName()],
          $processed,
          $this->getDependentModules($dependent->getName())
        )
      );
    }
    return $processed;
  }

  /**
   * {@inheritdoc}
   */
  public function getInstalled(): array {
    $storage = $this->storage;
    return array_filter($this->modules, function ($module) use ($storage) {
      return $storage->isInstalled($module->getName());
    });
  }

  /**
   * {@inheritdoc}
   */
  public function hasModule(string $module): bool {
    return isset($this->modules[$module]);
  }

  /**
   * {@inheritdoc}
   */
  public function getModule(string $module): ?ModuleInterface {
    return $this->modules[$module] ?? NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function isInstalled(string $module): bool {
    return $this->hasModule($module) && $this->storage->isInstalled($module);
  }

  /**
   * {@inheritdoc}
   */
  public function install(array $modules): static {
    foreach ($modules as $module) {
      $this->doInstall($module);
    }
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function uninstall(array $modules): static {
    foreach ($modules as $module) {
      $this->doUninstall($module);
    }
    return $this;
  }

  /**
   * Perform the installation of a module.
   *
   * @param string $module
   *   The module.
   */
  protected function doInstall(string $module): void {
    if ($this->isInstalled($module)) {
      return;
    }
    if ($module = $this->getModule($module)) {
      $this->storage->setInstalled($module->getName(), TRUE);

      foreach ($module->getDependencies() as $dependency) {
        $this->doInstall($dependency);
      }

      $this->installer->install($module);
    }
  }

  /**
   * Perform the uninstallation of a module.
   *
   * @param string $module
   *   The module.
   */
  protected function doUninstall(string $module): void {
    if (!$this->isInstalled($module)) {
      return;
    }
    if ($module = $this->getModule($module)) {
      $this->storage->setInstalled($module->getName(), FALSE);

      foreach ($this->getDependentModules($module->getName()) as $dependent) {
        $this->doUninstall($dependent);
      }
      $this->installer->uninstall($module);
    }
  }

}
