<?php

namespace App\Dao;

require_once("App/Dao/Dao.php");
require_once("App/Model/User.php");

class User extends Dao{

  public function load(\App\Model\User $user) {
    $this->read($this->getModel()->getId());
  }

  public function read($id) {

    $sql = "select * from users where id = '{$id}'";

    $resultSet = \App\Database\Database::execute($sql);

    $user = $this->fetchAll($resultSet);

    if ($user) {
      $this->getModel()->setId($id);
      $this->getModel()->setNome($user[0]["nome"]);
    }
  }

}