<?php

declare(strict_types=1);

namespace Xylemical\Kernel\Container;

use Xylemical\Config\ConfigFactoryInterface;
use Xylemical\Config\ConfigInterface;
use Xylemical\Container\Definition\AbstractSource;
use Xylemical\Container\Definition\Modifier\ServiceCollectorModifier;
use Xylemical\Controller\Authentication\AuthenticationFactoryInterface;
use Xylemical\Controller\Authorization\AuthorizationFactoryInterface;
use Xylemical\Controller\ContextFactory;
use Xylemical\Controller\ContextFactoryInterface;
use Xylemical\Controller\MiddlewareFactoryInterface;
use Xylemical\Controller\ProcessorFactoryInterface;
use Xylemical\Controller\Request\RequestReaderInterface;
use Xylemical\Controller\RequesterFactoryInterface;
use Xylemical\Controller\ResponderFactoryInterface;
use Xylemical\Controller\Response\ResponseWriterInterface;
use Xylemical\Controller\RouteFactoryInterface;
use Xylemical\Kernel\Account\AccountProvider;
use Xylemical\Kernel\Account\AccountProviderInterface;
use Xylemical\Kernel\Account\AuthenticationFactory;
use Xylemical\Kernel\Account\AuthorizationFactory;
use Xylemical\Kernel\InformationInterface;
use Xylemical\Kernel\MiddlewareFactory;
use Xylemical\Kernel\Module\ModuleDiscovery;
use Xylemical\Kernel\Module\ModuleDiscoveryInterface;
use Xylemical\Kernel\Module\ModuleFactory;
use Xylemical\Kernel\Module\ModuleFactoryInterface;
use Xylemical\Kernel\Module\ModuleInstaller;
use Xylemical\Kernel\Module\ModuleInstallerInterface;
use Xylemical\Kernel\Module\ModuleManager;
use Xylemical\Kernel\Module\ModuleManagerInterface;
use Xylemical\Kernel\Module\ModuleStorageInterface;
use Xylemical\Kernel\Module\Storage\ConfigModuleStorage;
use Xylemical\Kernel\ProcessorFactory;
use Xylemical\Kernel\Request\WebRequestReader;
use Xylemical\Kernel\Request\WebResponseWriter;
use Xylemical\Kernel\RequesterFactory;
use Xylemical\Kernel\ResponderFactory;
use Xylemical\Kernel\Route\RouteFactory;
use Xylemical\Kernel\Route\RouteMatcher;
use Xylemical\Kernel\Route\RouteMatcherInterface;
use function is_array;

/**
 * Provides container definition via configuration.
 */
class ConfigContainerSource extends AbstractSource {

  /**
   * The configuration.
   *
   * @var \Xylemical\Config\ConfigInterface
   */
  protected ConfigInterface $config;

  /**
   * ConfigContainerSource constructor.
   *
   * @param \Xylemical\Config\ConfigInterface $config
   *   The configuration.
   */
  public function __construct(ConfigInterface $config) {
    $this->config = $config;
    $this->timestamp = $config->get('timestamp') ?? time();
  }

  /**
   * {@inheritdoc}
   */
  protected function doLoad(): array {
    $definition = $this->config->get('definition');
    $definition = is_array($definition) ? $definition : [];
    $defaults = $this->doDefaults();
    if (!isset($definition['modifiers'])) {
      $definition['modifiers'] = $defaults['modifiers'];
    }
    $definition['services'] = ($definition['services'] ?? []) + $defaults['services'];
    return $definition;
  }

  /**
   * Provide defaults for the config container.
   *
   * @return array
   *   The defaults.
   */
  protected function doDefaults(): array {
    return [
      'modifiers' => [
        ServiceCollectorModifier::class,
      ],
      'services' => [
        AccountProviderInterface::class => [
          'class' => AccountProvider::class,
        ],
        AuthenticationFactoryInterface::class => [
          'class' => AuthenticationFactory::class,
          'arguments' => [
            '@' . AccountProviderInterface::class,
          ],
          'tags' => [
            [
              'name' => 'service.collector',
              'tag' => 'authentication_factory',
              'method' => 'addFactory',
            ],
          ],
        ],
        AuthorizationFactoryInterface::class => [
          'class' => AuthorizationFactory::class,
          'tags' => [
            [
              'name' => 'service.collector',
              'tag' => 'authorization_factory',
              'method' => 'addFactory',
            ],
          ],
        ],
        ContextFactoryInterface::class => [
          'class' => ContextFactory::class,
        ],
        MiddlewareFactoryInterface::class => [
          'class' => MiddlewareFactory::class,
          'tags' => [
            [
              'name' => 'service.collector',
              'tag' => 'middleware_factory',
              'method' => 'addFactory',
            ],
          ],
        ],
        ModuleDiscoveryInterface::class => [
          'class' => ModuleDiscovery::class,
          'tags' => [
            [
              'name' => 'service.collector',
              'tag' => 'module_discovery',
              'method' => 'addDiscovery',
            ],
          ],
        ],
        ModuleFactoryInterface::class => [
          'class' => ModuleFactory::class,
          'tags' => [
            [
              'name' => 'service.collector',
              'tag' => 'module_factory',
              'method' => 'addFactory',
            ],
          ],
        ],
        ModuleManagerInterface::class => [
          'class' => ModuleManager::class,
          'arguments' => [
            '@' . InformationInterface::class,
            '@' . ModuleStorageInterface::class,
            '@' . ModuleDiscoveryInterface::class,
            '@' . ModuleInstallerInterface::class,
            '@' . ModuleFactoryInterface::class,
          ],
        ],
        ModuleStorageInterface::class => [
          'class' => ConfigModuleStorage::class,
          'arguments' => [
            '@' . ConfigFactoryInterface::class,
            'modules',
          ],
        ],
        ModuleInstallerInterface::class => [
          'class' => ModuleInstaller::class,
          'tags' => [
            [
              'name' => 'service.collector',
              'tag' => 'module_installer',
              'method' => 'addInstaller',
            ],
          ],
        ],
        ProcessorFactoryInterface::class => [
          'class' => ProcessorFactory::class,
          'tags' => [
            [
              'name' => 'service.collector',
              'tag' => 'processor',
              'method' => 'addProcessor',
            ],
          ],
        ],
        RequesterFactoryInterface::class => [
          'class' => RequesterFactory::class,
          'tags' => [
            [
              'name' => 'service.collector',
              'tag' => 'requester',
              'method' => 'addRequester',
            ],
          ],
        ],
        RequestReaderInterface::class => [
          'class' => WebRequestReader::class,
        ],
        ResponderFactoryInterface::class => [
          'class' => ResponderFactory::class,
          'tags' => [
            [
              'name' => 'service.collector',
              'tag' => 'responder',
              'method' => 'addResponder',
            ],
          ],
        ],
        ResponseWriterInterface::class => [
          'class' => WebResponseWriter::class,
        ],
        RouteFactoryInterface::class => [
          'class' => RouteFactory::class,
          'arguments' => [
            '@' . ContextFactoryInterface::class,
            '@' . RouteMatcherInterface::class,
          ],
        ],
        RouteMatcherInterface::class => [
          'class' => RouteMatcher::class,
          'tags' => [
            [
              'name' => 'service.collector',
              'tag' => 'route_matcher',
              'method' => 'addMatcher',
            ],
          ],
        ],
      ],
    ];
  }

}
