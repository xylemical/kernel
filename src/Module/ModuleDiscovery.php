<?php

declare(strict_types=1);

namespace Xylemical\Kernel\Module;

/**
 * Provides a service collector class for ModuleDiscoveryInterface.
 */
class ModuleDiscovery implements ModuleDiscoveryInterface {

  /**
   * The module discoveries.
   *
   * @var \Xylemical\Kernel\Module\ModuleDiscoveryInterface[]
   */
  protected array $discoveries = [];

  /**
   * {@inheritdoc}
   */
  public function getPaths(string $root): array {
    $paths = [];
    foreach ($this->discoveries as $discovery) {
      $paths = array_merge($paths, $discovery->getPaths($root));
    }
    return array_unique($paths);
  }

  /**
   * Add a discovery.
   *
   * @param \Xylemical\Kernel\Module\ModuleDiscoveryInterface $discovery
   *   The discovery.
   *
   * @return $this
   */
  public function addDiscovery(ModuleDiscoveryInterface $discovery): static {
    $this->discoveries[] = $discovery;
    return $this;
  }

}
