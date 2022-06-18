<?php

declare(strict_types=1);

namespace Xylemical\Kernel;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Provide a means to modify the request.
 */
interface RequestListenerInterface {

  /**
   * Listen to the request.
   *
   * @param \Psr\Http\Message\ServerRequestInterface $request
   *   The request.
   *
   * @return \Psr\Http\Message\ServerRequestInterface
   *   The updated request.
   */
  public function listen(ServerRequestInterface $request): ServerRequestInterface;

}
