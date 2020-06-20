<?php

class Connection
{
    public $pdo;
    public function __construct()
    {
        try {
            $pdo = new PDO('pgsql:host=34.75.209.144;dbname=recaudosoft', 'api', '6U0icL%S!!$8b-z');
            $this->pdo = $pdo;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function runQuery($query)
    {
        try {
            $result = $this->pdo->query($query);
            return $result;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function genericQuery($query, $multiple = false)
    {
        try {
            $result = $this->runQuery($query);
            return $multiple ? $result->fetchAll() : $result->fetch();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
