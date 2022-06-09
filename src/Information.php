<?php

declare(strict_types=1);

namespace Xylemical\Kernel;

/**
 * Provides the application information.
 */
class Information implements InformationInterface {

  /**
   * The application name.
   *
   * @var string
   */
  protected string $name;

  /**
   * The application version.
   *
   * @var string
   */
  protected string $version;

  /**
   * The application root path.
   *
   * @var string
   */
  protected string $root;

  /**
   * AppInfo constructor.
   *
   * @param string $name
   *   The name.
   * @param string $version
   *   The version.
   * @param string $root
   *   The root.
   */
  public function __construct(string $name, string $version, string $root = '') {
    $this->name = $name;
    $this->version = $version;
    $this->root = $root ?: getcwd();
  }

  /**
   * {@inheritdoc}
   */
  public function getName(): string {
    return $this->name;
  }

  /**
   * {@inheritdoc}
   */
  public function getVersion(): string {
    return $this->version;
  }

  /**
   * {@inheritdoc}
   */
  public function getRoot(): string {
    return $this->root;
  }

}
