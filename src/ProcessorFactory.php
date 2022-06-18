<?php

declare(strict_types=1);

namespace Xylemical\Kernel;

use Xylemical\Controller\ProcessorFactoryInterface;
use Xylemical\Controller\ProcessorInterface;
use Xylemical\Controller\RouteInterface;

/**
 * Provides the kernel processor factory.
 */
class ProcessorFactory implements ProcessorFactoryInterface {

  /**
   * The processors.
   *
   * @var \Xylemical\Controller\ProcessorInterface[]
   */
  protected array $processors = [];

  /**
   * {@inheritdoc}
   */
  public function getProcessor(RouteInterface $route, mixed $contents): ProcessorInterface {
    foreach ($this->processors as $processor) {
      if (!is_null($processor->getResult($route, $contents))) {
        return $processor;
      }
    }
    throw new \InvalidArgumentException("No processors available.");
  }

  /**
   * Add a processor.
   *
   * @param \Xylemical\Controller\ProcessorInterface $processor
   *   The processor.
   *
   * @return $this
   */
  public function addProcessor(ProcessorInterface $processor): static {
    $this->processors[] = $processor;
    return $this;
  }

}
