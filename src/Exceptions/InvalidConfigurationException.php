<?php

namespace Ensue\GA4\Exceptions;

use Ensue\NicoSystem\Exceptions\NicoException;

class InvalidConfigurationException extends NicoException
{

    protected $code = 422;

    protected string $respCode = 'err_invalid_configuration';
}
