<?php

namespace src\services\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Twig extension class
 */
class Extension extends AbstractExtension
{
    private Functions $functions;

    public function __construct()
    {
        $this->functions = new Functions();
    }

    public function getFunctions(): array
    {
        $allFunctions = [];
        $functions = get_class_methods($this->functions);

        foreach ($functions as $method) {
            $allFunctions[] = new TwigFunction($method, [$this->functions, $method]);
        }

        return $allFunctions;
    }
}
