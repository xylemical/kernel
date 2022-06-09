<?php

declare(strict_types=1);

namespace Xylemical\Kernel\Request;

use Psr\Http\Message\ServerRequestInterface;
use Xylemical\Controller\Request\RequestReaderInterface;
use Xylemical\Controller\ServerRequest;

/**
 * Provides a request based on the command-line.
 */
class CliRequestReader implements RequestReaderInterface {

  use CliTrait;

  /**
   * {@inheritdoc}
   */
  public function applies(): bool {
    return $this->isCli();
  }

  /**
   * {@inheritdoc}
   */
  public function getRequest(): ServerRequestInterface {
    $cli = $this->parse();

    $server = [];
    foreach (explode("\n", $cli->getOption('--header')) as $value) {
      $parts = explode('=', $value, 2);
      if (count($parts) !== 2) {
        continue;
      }
      $header = strtoupper($parts[0]);
      $header = 'HTTP_' . str_replace('-', '_', $header);
      $server[$header][] = $parts[1];
    }

    if ($contentType = $cli->getOption('--content-type')) {
      $server['HTTP_CONTENT_TYPE'][] = $contentType;
    }

    return new ServerRequest($server, [], [], '');
  }

}
