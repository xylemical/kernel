<?php

declare(strict_types=1);

namespace Xylemical\Kernel\Module;

/**
 * Provides a generic module factory.
 */
class ModuleFactory implements ModuleFactoryInterface {

  /**
   * The factories.
   *
   * @var \Xylemical\Kernel\Module\ModuleFactoryInterface[]
   */
  protected array $factories = [];

  /**
   * {@inheritdoc}
   */
  public function getModule(string $path): ?ModuleInterface {
    foreach ($this->factories as $factory) {
      if ($module = $factory->getModule($path)) {
        return $module;
      }
    }
    return NULL;
  }

  /**
   * Add a factory.
   *
   * @param \Xylemical\Kernel\Module\ModuleFactoryInterface $factory
   *   The processor.
   *
   * @return $this
   */
  public function addFactory(ModuleFactoryInterface $factory): static {
    $this->factories[] = $factory;
    return $this;
  }

}
