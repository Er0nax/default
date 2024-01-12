<?php

namespace controllers;

use PDO;
use PDOException;
use PDOStatement;

global $db;

/**
 * entry class
 */
class Entry extends Controller
{
    private ?string $tables = null;
    private ?string $columns = null;
    private ?string $where = null;
    private ?int $limit = null;
    private ?int $offset = null;
    private ?string $order = null;
    private ?string $type = null;
    public ?string $queryDump = null;

    /**
     * find single entry
     * @return array
     */
    public function one(): array
    {
        // get the result
        try {
            $this->type = null;
            $result = $this->execute();
        } catch (PDOException $e) {
            // nothing ...
        }

        // check if the result is empty or false
        if (empty($result)) {
            return [];
        }

        // return the result
        $return = $result->fetch(PDO::FETCH_ASSOC);

        if (is_array($return)) {
            return $return;
        }

        return [];
    }

    /**
     * returns bool whether an entry exists or not
     * @return bool
     */
    public function exists(): bool
    {
        try {
            $this->type = null;
            $stmt = $this->execute();

            if ($stmt) {
                $exists = $stmt->fetchColumn();
            } else {
                $exists = false;
            }

            if ($exists) {
                return true;
            } else {
                return false;
            }

        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * returns int how many rows where found
     * @return int
     */
    public function count(): int
    {
        try {
            $this->type = 'COUNT';
            $stmt = $this->execute();
            $int = $stmt->fetchColumn();

            if (is_numeric($int)) {
                return $int;
            }

            return 0;

        } catch (PDOException $e) {
            return 0;
        }
    }

    /**
     * @return int
     */
    public function sum(): int
    {
        try {
            $this->type = 'SUM';
            $stmt = $this->execute();

            $int = $stmt->fetchColumn();

            if (is_numeric($int)) {
                return $int;
            }

            return 0;
        } catch (PDOException $e) {
            return 0;
        }
    }

    /**
     * @return int
     */
    public function avg(): int
    {
        try {
            $this->type = 'AVG';
            $stmt = $this->execute();
            $int = $stmt->fetchColumn();

            if (is_numeric($int)) {
                return $int;
            }

            return 0;
        } catch (PDOException $e) {
            return 0;
        }
    }

    /**
     * @return int
     */
    public function max(): int
    {
        try {
            $this->type = 'MAX';
            $stmt = $this->execute();
            $int = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (is_numeric($int)) {
                return $int;
            }

            return 0;
        } catch (PDOException $e) {
            return 0;
        }
    }

    /**
     * @return int
     */
    public function min(): int
    {
        try {
            $this->type = 'MIN';
            $stmt = $this->execute();
            $int = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (is_numeric($int)) {
                return $int;
            }

            return 0;
        } catch (PDOException $e) {
            return 0;
        }
    }

    /**
     * find multiple entries
     * @return array|false
     */
    public function all(): bool|array
    {
        // get the result
        try {
            $this->type = 'ALL';
            $result = $this->execute();
        } catch (PDOException $e) {
            // nothing ...
        }

        // check if the result is empty or false
        if (empty($result)) {
            return [];
        }

        // return the result
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * creates the tables query string
     * possible inputs: \n
     * ['users', ['roles', 'users.roleID', 'roles.id'], ['colors', 'roles.colorID', 'colors.id']]
     * 'users JOIN roles ON users.roleID = roles.id JOIN colors ON roles.colorID = colors.id'
     * @param $tables
     * @return Entry
     */
    public function tables($tables): static
    {
        $tablesString = '';

        // check if the table is defined
        if (!empty($tables)) {

            // check if tables are in array form
            if (is_array($tables)) {

                $tablesString = $tables[0];
                $counter = 0;

                // loop through tables
                foreach ($tables as $table) {

                    // Skip first loop
                    if ($counter++ == 0) {
                        continue;
                    }

                    $tablesString = $tablesString . ' JOIN ' . $table[0] . ' ON ' . $table[1] . ' = ' . $table[2];
                }

            } else {
                $tablesString = $tables;
            }
        }

        // add tables
        $this->tables = $tablesString;

        return $this;
    }

    /**
     * creates the columns query string
     * possible inputs:
     * ['users' => ['username', 'description'], 'roles' => ['name', 'description']]
     * 'users.username, users.description, roles.name, roles.description'
     * @return $this
     */
    public function columns($columns): static
    {
        // set query string
        $columnsString = '';

        // check if colmuns are defined
        if (!empty($columns)) {

            // check if is array
            if (is_array($columns)) {
                // is first column in query?
                $isFirstLine = false;

                // loop through array
                foreach ($columns as $table => $tableColumns) {

                    // set current table
                    $currentTable = $table;

                    // loop through columns
                    if (!empty($tableColumns) && is_array($tableColumns)) {

                        // loop through all columns in table
                        foreach ($tableColumns as $tableColumn) {

                            // check if its first column
                            if (!$isFirstLine) {
                                // without ,
                                $columnsString = $columnsString . $currentTable . '.' . $tableColumn;
                            } else {
                                // with ,
                                $columnsString = $columnsString . ', ' . $currentTable . '.' . $tableColumn;
                            }

                            // set first line to true as we got one loop
                            $isFirstLine = true;
                        }
                    }
                }

            } else {
                $columnsString = $columns;
            }
        }

        // add columns
        $this->columns = $columnsString;

        return $this;
    }

    /**
     * creates the where query string
     * possible inputs:
     * ['users' => [['username', 'Eronax'], ['deleted', 'false']], 'roles' => [['id', '1']]]
     * users.username = 'Eronax' AND users.deleted = 'false' AND roles.id = '1'
     * @return $this
     */
    public function where($where, $operator = 'AND'): static
    {
        // create query string
        $whereString = '';

        // check if colmuns are defined
        if (!empty($where)) {

            // check if is array
            if (is_array($where)) {

                $isFirstLine = false;

                // loop throug all wheres
                foreach ($where as $table => $conditions) {

                    // check if $conditions are set and array
                    if (!empty($conditions) && is_array($conditions)) {
                        foreach ($conditions as $condition) {
                            $column = $condition[0];
                            $is = $condition[1];
                            $customEqual = $condition[2] ?? '=';

                            $singleString = $table . '.' . $column . $customEqual . '"' . $is . '"';

                            if (!$isFirstLine) {
                                $whereString = $singleString;
                            } else {
                                $whereString = $whereString . ' ' . $operator . ' ' . $singleString;
                            }

                            $isFirstLine = true;
                        }
                    }
                }

            } else {
                $whereString = $where;
            }
        }

        $whereString = '(' . $whereString . ')';

        // add columns
        $this->where = $whereString;

        return $this;
    }

    /**
     * @param $table
     * @return bool
     */
    public function checkIfTablesContainsSpecificTable($table): bool
    {
        return str_contains($this->tables, $table);
    }

    /**
     * sets a limit for the query
     * @return $this
     */
    public function limit($limit): static
    {
        // check if limit is set and is a number
        if (!empty($limit) && is_numeric($limit)) {
            $this->limit = $limit;
        } else {
            $this->limit = 1;
        }

        return $this;
    }

    /**
     * sets a new offset
     * @param $offset
     * @return $this
     */
    public function offset($offset): static
    {
        // check if limit is set and is a number
        if (!empty($offset) && is_numeric($offset)) {
            $this->offset = $offset;
        } else {
            $this->offset = 0;
        }

        return $this;
    }

    public function order(string $order = null): static
    {
        // check if limit is set and is a number
        if (!empty($order)) {
            $this->order = $order;
        }

        return $this;
    }

    /**
     * builds the query string
     */
    private function buildQuery(): ?string
    {
        // set query strings
        $columns = $this->columns;
        $tables = $this->tables;
        $where = $this->where;
        $type = $this->type;
        $query = null;

        // check if all are given
        if (!empty($columns) && !empty($tables)) {

            // is specific type given?
            $type = match ($type) {
                'COUNT' => 'COUNT',
                'SUM' => 'SUM',
                'AVG' => 'AVG',
                'MAX' => 'MAX',
                'MIN' => 'MIN',
                default => null,
            };

            // type still given?
            if (!empty($type)) {
                $query = 'SELECT ' . $type . '(' . $columns . ') FROM ' . $tables;
            } else {
                $query = 'SELECT ' . $columns . ' FROM ' . $tables;
            }
        }

        // add where
        if (!empty($where)) {
            $query = $query . ' WHERE ' . $where;
        }

        // add order
        if (!empty($this->order)) {
            $query = $query . ' ORDER BY ' . $this->order;
        }

        // add limit
        if (!empty($this->limit) && is_numeric($this->limit)) {
            $query = $query . ' LIMIT ' . $this->limit;
        }

        // add offset
        if (!empty($this->offset) && is_numeric($this->offset)) {
            $query = $query . ' OFFSET ' . $this->offset;
        }

        $this->queryDump = $query;
        return $query;
    }

    /**
     * execute the PDO statement
     * @return bool|PDOStatement|null
     */
    private function execute(): bool|PDOStatement|null
    {
        try {
            // get open db connection
            global $db;

            // get the query
            $query = $this->buildQuery();

            if (!empty($query)) {

                // prepare statement
                $stmt = $db->con->prepare($query);

                // execute
                $stmt->execute();

                // return statement
                return $stmt;
            }

            return null;
        } catch (PDOException $e) {
            // nothing
            return null;
        }
    }

    /**
     * @param $die
     * @return void
     */
    public function dumpQuery($die = true): void
    {
        try {
            Controller::_connect();
            $query = $this->buildQuery();
            _dump($query, $die);
        } catch (\Exception) {
            // nothin
        }
    }
}