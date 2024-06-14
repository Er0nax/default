<?php

namespace src\components;

use Exception;
use src\helpers\FileHelper;
use PDO;

/**
 * Database component.
 */
class Database
{
    public PDO $con;
    private FileHelper $FileHelper;
    private array|bool $envVariables;

    /**
     * Constructor.
     */
    public function __construct()
    {
        // create new file helper
        $this->FileHelper = new FileHelper();

        // env file given?
        if (!$this->FileHelper->exist('.env')) {
            exit('Could not find the .env file!');
        }

        // parse env file
        $this->envVariables = parse_ini_file($this->FileHelper->get('.env'));

        $host = $this->getEnvVariable('DBHOST');
        $name = $this->getEnvVariable('DBNAME');
        $user = $this->getEnvVariable('DBUSER');
        $pass = $this->getEnvVariable('DBPASS');

        try {
            $this->con = new PDO('mysql:host=' . $host . ';dbname=' . $name, $user, $pass);
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->con->exec('set names utf8mb4');

            // unset unsafe variables
            $this->envVariables = false;
        } catch (Exception $e) {
            exit('Could not connect to database: ' . $e->getMessage());
        }
    }

    /**
     * Returns the value or exist with error message.
     * @param string $key
     * @return mixed|void
     */
    private function getEnvVariable(string $key)
    {
        if (isset($this->envVariables[$key])) {
            return $this->envVariables[$key];
        }

        exit('Could not find <b>' . $key . '</b> inside .env file!');
    }
}