<?php

declare(strict_types=1);

namespace Xylemical\Kernel;

use Psr\Http\Message\ResponseInterface;

/**
 * Provide a means to modify the response.
 */
interface ResponseListenerInterface {

  /**
   * Listen to the response.
   *
   * @param \Psr\Http\Message\ResponseInterface $response
   *   The response.
   *
   * @return \Psr\Http\Message\ResponseInterface
   *   The updated response.
   */
  public function listen(ResponseInterface $response): ResponseInterface;

}
