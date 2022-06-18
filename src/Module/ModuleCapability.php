<?php

declare(strict_types=1);

namespace Xylemical\Kernel\Module;

/**
 * Provides a service collector for module capabilities.
 */
class ModuleCapability implements ModuleCapabilityInterface {

  /**
   * The capability providers.
   *
   * @var \Xylemical\Kernel\Module\ModuleCapabilityInterface[]
   */
  protected array $capabilities = [];

  /**
   * {@inheritdoc}
   */
  public function getHandler(ModuleInterface $module, string $service): mixed {
    foreach ($this->capabilities as $capability) {
      if ($handler = $capability->getHandler($module, $service)) {
        return $handler;
      }
    }
    return NULL;
  }

  /**
   * Add a capability.
   *
   * @param \Xylemical\Kernel\Module\ModuleCapabilityInterface $capability
   *   The capability.
   *
   * @return $this
   */
  public function addCapability(ModuleCapabilityInterface $capability): static {
    $this->capabilities[] = $capability;
    return $this;
  }

}
