<?php

declare(strict_types=1);

namespace Xylemical\Kernel\Request;

use function str_starts_with;

/**
 * Provides the processed command-line.
 */
class Cli {

  /**
   * The script.
   *
   * @var string
   */
  protected string $script;

  /**
   * The options.
   *
   * @var \Xylemical\Kernel\Request\CliOption[][]
   */
  protected array $options = [];

  /**
   * The argument.
   *
   * @var \Xylemical\Kernel\Request\CliArgument[]
   */
  protected array $arguments = [];

  /**
   * Cli constructor.
   *
   * @param array $args
   *   The command-line arguments.
   */
  public function __construct(array $args) {
    $this->script = array_shift($args);
    $this->parse($args);
  }

  /**
   * Parse the command line into arguments and options.
   *
   * @param array $args
   *   The command-line arguments.
   */
  protected function parse(array $args): void {
    $in_option = FALSE;
    foreach ($args as $arg) {
      if (str_starts_with($arg, '-')) {
        $in_option = $arg;
        continue;
      }
      if ($in_option) {
        $this->options[$in_option][] = new CliOption($in_option, $arg);
        $in_option = FALSE;
      }
      else {
        $this->arguments[] = new CliArgument($arg);
      }
    }
  }

  /**
   * Get the script name.
   *
   * @return string
   *   The script.
   */
  public function getScript(): string {
    return $this->script;
  }

  /**
   * Get the options.
   *
   * @return \Xylemical\Kernel\Request\CliOption[]
   *   The options.
   */
  public function getOptions(): array {
    return array_reduce($this->options, function ($carry, $item) {
      return array_merge($carry, $item);
    }, []);
  }

  /**
   * Get the option as a value.
   *
   * @param string $option
   *   The option.
   *
   * @return string
   *   The value, each value separated by unix end-of-line.
   */
  public function getOption(string $option): string {
    return implode("\n",
      array_map(
        function ($option) {
          return $option->getValue();
        },
        $this->options[$option] ?? []
      )
    );
  }

  /**
   * Get the arguments.
   *
   * @return \Xylemical\Kernel\Request\CliArgument[]
   *   The arguments.
   */
  public function getArguments(): array {
    return $this->arguments;
  }

}
