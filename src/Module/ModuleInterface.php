<?php

declare(strict_types=1);

namespace Xylemical\Kernel\Module;

use Xylemical\Container\Definition\SourceInterface;

/**
 * Provides the base module behaviour.
 */
interface ModuleInterface {

  /**
   * Get the name of the module.
   *
   * @return string
   *   The name.
   */
  public function getName(): string;

  /**
   * Get the label of the module.
   *
   * @return string
   *   The label.
   */
  public function getLabel(): string;

  /**
   * Get the description.
   *
   * @return string
   *   The description.
   */
  public function getDescription(): string;

  /**
   * Get the module dependencies.
   *
   * @return string[]
   *   The dependencies.
   */
  public function getDependencies(): array;

  /**
   * Get the container source for the module.
   *
   * @return \Xylemical\Container\Definition\SourceInterface
   *   The source.
   */
  public function getContainer(): SourceInterface;

  /**
   * Get the root path for the module.
   *
   * @return string
   *   The root.
   */
  public function getRoot(): string;

}
