<?php

declare(strict_types=1);

namespace Xylemical\Kernel;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Xylemical\Config\ConfigFactory;
use Xylemical\Config\ConfigFactoryInterface;
use Xylemical\Config\SourceInterface as ConfigSourceInterface;
use Xylemical\Container\ContainerBuilder;
use Xylemical\Container\Definition\Source\ChainedSource;
use Xylemical\Container\Definition\SourceInterface as ContainerSourceInterface;
use Xylemical\Controller\Authentication\AuthenticationFactoryInterface;
use Xylemical\Controller\Authorization\AuthorizationFactoryInterface;
use Xylemical\Controller\Controller;
use Xylemical\Controller\MiddlewareFactoryInterface;
use Xylemical\Controller\ProcessorFactoryInterface;
use Xylemical\Controller\Request\RequestReaderInterface;
use Xylemical\Controller\RequesterFactoryInterface;
use Xylemical\Controller\ResponderFactoryInterface;
use Xylemical\Controller\Response\ResponseWriterInterface;
use Xylemical\Controller\RouteFactoryInterface;
use Xylemical\Controller\RouteInterface;
use Xylemical\Kernel\Container\ModuleContainerSource;
use Xylemical\Kernel\Module\ModuleManagerInterface;
use Xylemical\Kernel\Route\RouteListenerInterface;

/**
 * Provide the kernel of an application.
 */
final class Kernel {

  /**
   * The config source.
   *
   * @var \Xylemical\Config\SourceInterface
   */
  protected ConfigSourceInterface $configSource;

  /**
   * The config factory.
   *
   * @var \Xylemical\Config\ConfigFactoryInterface
   */
  protected ConfigFactoryInterface $config;

  /**
   * The container source.
   *
   * @var \Xylemical\Container\Definition\SourceInterface
   */
  protected ContainerSourceInterface $containerSource;

  /**
   * The container path.
   *
   * @var string
   */
  protected string $containerPath;

  /**
   * The application information.
   *
   * @var \Xylemical\Kernel\InformationInterface
   */
  protected InformationInterface $info;

  /**
   * The container.
   *
   * @var \Psr\Container\ContainerInterface
   */
  protected ContainerInterface $container;

  /**
   * The module manager.
   *
   * @var \Xylemical\Kernel\Module\ModuleManagerInterface
   */
  protected ModuleManagerInterface $manager;

  /**
   * The request.
   *
   * @var \Psr\Http\Message\ServerRequestInterface
   */
  protected ServerRequestInterface $request;

  /**
   * The route.
   *
   * @var \Xylemical\Controller\RouteInterface
   */
  protected RouteInterface $route;

  /**
   * Kernel constructor.
   *
   * @param \Xylemical\Kernel\InformationInterface $info
   *   The application information.
   * @param \Xylemical\Config\SourceInterface $configSource
   *   The configuration source.
   * @param \Xylemical\Container\Definition\SourceInterface $containerSource
   *   The container source.
   * @param string $containerPath
   *   The container path.
   */
  public function __construct(InformationInterface $info, ConfigSourceInterface $configSource, ContainerSourceInterface $containerSource, string $containerPath) {
    $this->info = $info;
    $this->configSource = $configSource;
    $this->containerSource = $containerSource;
    $this->containerPath = $containerPath;
  }

  /**
   * Run the application.
   *
   * @return int
   *   The exit code.
   *
   * @throws \Throwable
   */
  public function run(): int {
    $container = $this->getContainer();

    /** @var \Xylemical\Controller\Response\ResponseWriterInterface $writer */
    $writer = $container->get(ResponseWriterInterface::class);
    return $writer->putResponse($this->doRequest());
  }

  /**
   * Get the kernel.
   *
   * @return \Xylemical\Kernel\Kernel
   *   The kernel.
   */
  public function getSelf(): Kernel {
    return $this;
  }

  /**
   * Get the application information.
   *
   * @return \Xylemical\Kernel\InformationInterface
   *   The info.
   */
  public function getInfo(): InformationInterface {
    return $this->info;
  }

  /**
   * Get the configuration factory.
   *
   * @return \Xylemical\Config\ConfigFactoryInterface
   *   The factory.
   */
  public function getConfig(): ConfigFactoryInterface {
    if (isset($this->config)) {
      return $this->config;
    }

    $config = $this->configSource->get('kernel');
    $class = $config[ConfigFactoryInterface::class] ?? ConfigFactory::class;
    $this->config = new $class($this->configSource);
    return $this->config;
  }

  /**
   * Get the kernel container.
   *
   * @return \Psr\Container\ContainerInterface
   *   The container.
   *
   * @throws \Throwable
   */
  public function getContainer(): ContainerInterface {
    if (isset($this->container)) {
      return $this->container;
    }

    $path = $this->containerPath;
    $class = 'Xylemical\\Container\\BootstrapContainer';

    $services = [
      InformationInterface::class => [$this, 'getInfo'],
      ConfigFactoryInterface::class => [$this, 'getConfig'],
      ContainerInterface::class => [$this, 'getContainer'],
      ServerRequestInterface::class => [$this, 'getRequest'],
      RouteInterface::class => [$this, 'getRoute'],
      Kernel::class => [$this, 'getSelf'],
    ];

    $builder = new ContainerBuilder($this->containerSource, "{$path}/bootstrap.php", $class);
    $bootstrap = $builder->getContainer()->add($services);

    $moduleSource = new ModuleContainerSource($bootstrap->get(ModuleManagerInterface::class));
    $source = new ChainedSource([$this->containerSource, $moduleSource]);

    $class = 'Xylemical\\Container\\CompiledContainer';
    $builder = new ContainerBuilder($source, "{$path}/container.php", $class);

    $this->container = $builder->getContainer()->add($services);

    return $this->container;
  }

  /**
   * Get the request.
   *
   * @return \Psr\Http\Message\ServerRequestInterface
   *   The request.
   *
   * @throws \Throwable
   */
  public function getRequest(): ServerRequestInterface {
    if (isset($this->request)) {
      return $this->request;
    }

    $container = $this->getContainer();

    /** @var \Xylemical\Controller\Request\RequestReaderInterface $factory */
    $factory = $container->get(RequestReaderInterface::class);
    $request = $factory->getRequest();

    if ($container->has(RequestListenerInterface::class)) {
      /** @var \Xylemical\Kernel\RequestListenerInterface $listener */
      $listener = $container->get(RequestListenerInterface::class);
      if ($listener->applies($request)) {
        $request = $listener->listen($request);
      }
    }

    $this->request = $request;
    return $this->request;
  }

  /**
   * Get the route.
   *
   * @return \Xylemical\Controller\RouteInterface
   *   The route.
   *
   * @throws \Throwable
   */
  public function getRoute(): RouteInterface {
    if (isset($this->route)) {
      return $this->route;
    }

    $container = $this->getContainer();

    /** @var \Xylemical\Controller\RouteFactoryInterface $factory */
    $factory = $container->get(RouteFactoryInterface::class);
    $route = $factory->getRoute($this->getRequest());

    if ($container->has(RouteListenerInterface::class)) {
      $listener = $container->get(RouteListenerInterface::class);
      if ($listener->applies($route)) {
        $route = $listener->listen($route);
      }
    }

    $this->route = $route;
    return $this->route;
  }

  /**
   * Reset the kernel for another request-response iteration.
   *
   * @return $this
   */
  public function reset(): Kernel {
    unset($this->request);
    unset($this->route);
    return $this;
  }

  /**
   * Perform the request.
   *
   * @return \Psr\Http\Message\ResponseInterface
   *   The response.
   *
   * @throws \Throwable
   */
  protected function doRequest(): ResponseInterface {
    $container = $this->getContainer();

    $controller = new Controller(
      $container->get(RequesterFactoryInterface::class),
      $container->get(ProcessorFactoryInterface::class),
      $container->get(ResponderFactoryInterface::class),
      $container->get(MiddlewareFactoryInterface::class),
      $container->get(AuthenticationFactoryInterface::class),
      $container->get(AuthorizationFactoryInterface::class)
    );

    return $controller->handle($this->getRoute());
  }

}
