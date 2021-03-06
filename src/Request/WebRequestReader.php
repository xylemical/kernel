<?php

declare(strict_types=1);

namespace Xylemical\Kernel\Request;

use Psr\Http\Message\ServerRequestInterface;
use Xylemical\Controller\Request\RequestReaderInterface;
use Xylemical\Controller\ServerRequest;

/**
 * Provides a web server request reader.
 */
class WebRequestReader implements RequestReaderInterface {

  use WebTrait;

  /**
   * {@inheritdoc}
   */
  public function getRequest(): ?ServerRequestInterface {
    if ($this->isWeb()) {
      return ServerRequest::createFromGlobals();
    }
    return NULL;
  }

}
