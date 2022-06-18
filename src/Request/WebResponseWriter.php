<?php

declare(strict_types=1);

namespace Xylemical\Kernel\Request;

use Psr\Http\Message\ResponseInterface;
use Xylemical\Controller\Response\ResponseWriterInterface;
use function headers_sent;

/**
 * Provides the web server response.
 */
class WebResponseWriter implements ResponseWriterInterface {

  use WebTrait;

  /**
   * {@inheritdoc}
   */
  public function putResponse(ResponseInterface $response): ?int {
    if (!$this->isWeb()) {
      return NULL;
    }

    if (!headers_sent()) {
      header(sprintf('HTTP/%s %d %s',
        $response->getProtocolVersion(),
        $response->getStatusCode(),
        $response->getReasonPhrase(),
      ));
      foreach ($response->getHeaders() as $name => $values) {
        foreach ($values as $value) {
          header(sprintf('%s: %s', $name, $value), FALSE);
        }
      }
    }
    $stream = $response->getBody();
    if ($stream->isSeekable()) {
      $stream->rewind();
    }
    while (!$stream->eof()) {
      $buffer = $stream->read(4096);
      echo $buffer;
    }
    return 0;
  }

}
