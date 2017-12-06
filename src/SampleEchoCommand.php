<?php

namespace Application;

use Framework\Base\Application\ApplicationAwareTrait;
use Framework\Terminal\Commands\CommandHandlerInterface;

/**
 * Class SampleEchoCommand
 * @package Application
 */
class SampleEchoCommand implements CommandHandlerInterface
{
    use ApplicationAwareTrait;

    public function run(array $parameterValues = [])
    {
        return "Sample echo command";
    }
}
