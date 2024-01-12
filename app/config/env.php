<?php

/**
 * env handler
 */
class env
{
    public array  $variables       = [];
    private array $unsafeVariables = [
        'DBHOST',
        'DBUSER',
        'DBPASS',
        'DBNAME'
    ];

    /**
     * constructor
     */
    public function __construct()
    {
        $this->loadVariables();
    }

    /**
     * load all env variables from the .env file
     * @return void
     */
    private function loadVariables(): void
    {
        // try to parse env file
        try {
            $variables = parse_ini_file('../.env');

            // filter unsafe variables
            foreach ($variables as $name => $value) {
                // is unsafe variable
                if (!in_array($name, $this->unsafeVariables)) {
                    // add to array
                    $this->variables[$name] = $value;
                }
            }
        } catch (Exception $e) {
            die('error while loading the .env file!');
        }
    }
}