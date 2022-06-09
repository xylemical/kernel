<?php

declare(strict_types=1);

namespace Xylemical\Kernel\Module\Storage;

use Xylemical\Config\ConfigFactoryInterface;
use Xylemical\Config\ConfigInterface;
use Xylemical\Config\EditableConfigInterface;
use Xylemical\Kernel\Module\ModuleStorageInterface;

/**
 * Provides module storage using the configuration.
 */
class ConfigModuleStorage implements ModuleStorageInterface {

  /**
   * The configuration factory.
   *
   * @var \Xylemical\Config\ConfigFactoryInterface
   */
  protected ConfigFactoryInterface $configFactory;

  /**
   * The configuration key.
   *
   * @var string
   */
  protected string $key;

  /**
   * The modules.
   *
   * @var array
   */
  protected array $modules;

  /**
   * ConfigModuleStorage constructor.
   *
   * @param \Xylemical\Config\ConfigFactoryInterface $configFactory
   *   The config factory.
   * @param string $key
   *   The config key.
   */
  public function __construct(ConfigFactoryInterface $configFactory, string $key) {
    $this->configFactory = $configFactory;
    $this->key = $key;
  }

  /**
   * Get the configuration.
   *
   * @return \Xylemical\Config\ConfigInterface
   *   The configuration.
   */
  protected function getConfig(): ConfigInterface {
    return $this->configFactory->get($this->key);
  }

  /**
   * Get editable configuration.
   *
   * @return \Xylemical\Config\EditableConfigInterface
   *   The config.
   */
  protected function getEditable(): EditableConfigInterface {
    return $this->configFactory->getEditable($this->key);
  }

  /**
   * {@inheritdoc}
   */
  public function isInstalled(string $module): bool {
    return $this->getConfig()->has($module);
  }

  /**
   * {@inheritdoc}
   */
  public function setInstalled(string $module, bool $flag): static {
    $config = $this->getEditable();
    if ($flag) {
      $updates = $config->get($module) ?: [];
      $config->set($module, $updates);
    }
    else {
      $config->remove($module);
    }
    $config->save();
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getUpdates(string $module): array {
    $config = $this->getConfig();
    if ($config->has($module)) {
      return $config->get($module);
    }
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function hasUpdate(string $module, string $update): bool {
    $config = $this->getConfig();
    if ($config->has($module)) {
      return in_array($update, $config->get($module));
    }
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function setUpdate(string $module, string $update, bool $flag): static {
    $config = $this->getEditable();
    $updates = $config->get($module) ?: [];
    if ($flag) {
      if (!in_array($update, $updates)) {
        $updates[] = $update;
        $config->set($module, $updates)->save();
      }
    }
    elseif (in_array($update, $updates)) {
      $updates = array_filter($updates, function ($item) use ($update) {
        return $item !== $update;
      });
      $config->set($module, $updates)->save();
    }
    return $this;
  }

}
