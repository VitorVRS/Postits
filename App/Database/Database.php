<?php

namespace App\Database;

class Database {
  
  private $host = "<HOST>";

  private $port = "<PORT>";

  private $dbname = "<DBNAME>";

  private $user = "<USERNAME>";

  private $password = "<PASSWORD>";

  private $stringConnection;

  private $connection;

  private static $instance;

  public function __construct() {
    $this->stringConnection = "host={$this->host} port={$this->port} dbname={$this->dbname} user={$this->user} ";

    if (!empty($this->password)) {
      $this->stringConnection .= "password={$this->password}";
    }

    $this->connect();
  }

  private function connect() {
    $this->connection = @pg_connect($this->stringConnection);
    if (!$this->connection) {
      throw new Exception("Can't connect to database.");      
    }
  }

  private function disconnect() {
    @pg_close($this->connection);
    $this->connection = null;
  }

  public function getConnection() {
    return $this->connection;
  }

  public static function getInstance() {

    if (self::$instance == null) {
      self::$instance = new Database();
    }

    return self::$instance;
  }

  public static function execute($query) {

    $resultSet = @pg_query(self::getInstance()->getConnection(), $query);

    if (!$resultSet) {
      throw new \Exception(@pg_last_error());      
    }

    return $resultSet;
  }

  public static function fetchAll($rs) {
    $fetch = pg_fetch_all($rs);
    return $fetch;
  }
    
}