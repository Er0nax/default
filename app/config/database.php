<?php

/**
 * creates a new connection to the database
 */
class database
{
    /**
     * @var PDO
     */
    public PDO $con;
    /**
     * @var array|false
     */
    private array|false $env = false;

    /**
     * opens a new database connection
     */
    public function __construct()
    {
        // try to parse env file
        try {
            $this->env = parse_ini_file('../.env');
        } catch (Exception $e) {
            die('app/config/database <br>' . $e);
        }

        // set variables
        $host = $this->getEnvVariable('DBHOST');
        $name = $this->getEnvVariable('DBNAME');
        $user = $this->getEnvVariable('DBUSER');
        $pass = $this->getEnvVariable('DBPASS');

        $connectionString = 'mysql:host=' . $host . ';dbname=' . $name;

        $this->con = new PDO($connectionString, $user, $pass);
        $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->con->exec('set names utf8mb4');

        // unset unsafe variables
        $this->env = false;
    }

    /**
     * returns env variable
     *
     * @param $variable
     *
     * @return mixed|void
     */
    private function getEnvVariable($variable)
    {
        if (isset($this->env[$variable])) {
            return $this->env[$variable];
        }
    }
}