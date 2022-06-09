<?php

declare(strict_types=1);

namespace Xylemical\Kernel;

/**
 * Provides details about the application.
 */
interface InformationInterface {

  /**
   * Get the application name.
   *
   * @return string
   *   The name.
   */
  public function getName(): string;

  /**
   * Get the application version string.
   *
   * This is expected to be in semver format.
   *
   * @return string
   *   The version.
   */
  public function getVersion(): string;

  /**
   * The root path.
   *
   * @return string
   *   The path.
   */
  public function getRoot(): string;

}
