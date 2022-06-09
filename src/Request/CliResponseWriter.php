<?php

declare(strict_types=1);

namespace Xylemical\Kernel\Request;

use Psr\Http\Message\ResponseInterface;
use Xylemical\Controller\Response\ResponseWriterInterface;

/**
 * Provides a response writer for command-line contents.
 */
class CliResponseWriter implements ResponseWriterInterface {

  use CliTrait;

  /**
   * {@inheritdoc}
   */
  public function applies(ResponseInterface $response): bool {
    return $this->isCli();
  }

  /**
   * {@inheritdoc}
   */
  public function putResponse(ResponseInterface $response): int {
    $cli = $this->parse();

    if ($output = $cli->getOption('--output')) {
      $response = $this->putFile($response, $output);
    }
    else {
      $response = $this->putConsole($response);
    }

    $status = $response->getStatusCode();
    return $status !== 200 ? $status : 0;
  }

  /**
   * Put the response into a file.
   *
   * @param \Psr\Http\Message\ResponseInterface $response
   *   The response.
   * @param string $output
   *   The output filename.
   */
  protected function putFile(ResponseInterface $response, string $output): ResponseInterface {
    $handle = @fopen($output, 'wb');
    if (!$handle) {
      return $response->withStatus(404, "Unable to write to file.");
    }
    $stream = $response->getBody();
    while (!$stream->eof()) {
      fwrite($handle, $stream->read(4096));
    }
    fclose($handle);
    return $response;
  }

  /**
   * Put the response directly to console.
   *
   * @param \Psr\Http\Message\ResponseInterface $response
   *   The response.
   *
   * @return \Psr\Http\Message\ResponseInterface
   *   The response.
   */
  protected function putConsole(ResponseInterface $response): ResponseInterface {
    $stream = $response->getBody();
    while (!$stream->eof()) {
      echo $stream->read(4096);
    }
    return $response;
  }

}
