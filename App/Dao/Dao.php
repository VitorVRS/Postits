<?php

namespace App\Dao;

require_once("App/Database/Database.php");

abstract class Dao {
  
  private $model;

  private $fields;

  public function __construct(\App\Model\Model $model) {
    $this->model = $model;
    $this->fields();
  }

  private function fields() {
    //@TODO get attriubtes by annotations to persist
  }

  protected function fetchAll($resultSet) {
  
    $result = \App\Database\Database::fetchAll($resultSet);
    return $result;
  }

  protected function getModel() {
    return $this->model;
  }


}