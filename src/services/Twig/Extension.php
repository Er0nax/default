<?php

namespace src\services\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * Twig extension class
 */
class Extension extends AbstractExtension
{
    private Functions $functions;
    private Filters $filters;

    /**
     * @param array $entry
     */
    public function __construct(array $entry)
    {
        $this->functions = new Functions();
        $this->functions->entry = $entry;

        $this->filters = new Filters();
    }


    /**
     * @return array
     */
    public function getFunctions(): array
    {
        $allFunctions = [];
        $functions = get_class_methods($this->functions);

        foreach ($functions as $method) {
            $allFunctions[] = new TwigFunction($method, [$this->functions, $method]);
        }

        return $allFunctions;
    }


    /**
     * @return array
     */
    public function getFilters(): array
    {
        $allFilters = [];
        $filters = get_class_methods($this->filters);

        foreach ($filters as $method) {
            $allFilters[] = new TwigFilter($method, [$this->filters, $method]);
        }

        return $allFilters;
    }
}
