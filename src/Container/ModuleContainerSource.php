<?php

declare(strict_types=1);

namespace Xylemical\Kernel\Container;

use Xylemical\Container\Definition\Source\ChainedSource;
use Xylemical\Kernel\Module\ModuleManagerInterface;

/**
 * Create the container source using modules.
 */
class ModuleContainerSource extends ChainedSource {

  /**
   * The module manager.
   *
   * @var \Xylemical\Kernel\Module\ModuleManagerInterface
   */
  protected ModuleManagerInterface $manager;

  /**
   * ModuleContainerSource constructor.
   *
   * @param \Xylemical\Kernel\Module\ModuleManagerInterface $manager
   *   The module manager.
   */
  public function __construct(ModuleManagerInterface $manager) {
    parent::__construct([]);
    $this->manager = $manager;
  }

  /**
   * {@inheritdoc}
   */
  public function load(): static {
    $this->sources = [];
    foreach ($this->manager->getInstalled() as $module) {
      $this->sources[] = $module->getContainer();
    }
    return parent::load();
  }

}
