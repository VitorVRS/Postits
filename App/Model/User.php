<?php

namespace App\Model;

require_once("App/Dao/User.php");
require_once("App/Model/Model.php");

use App\Dao\User as Dao;

class User extends Model{
  
  private $id;

  private $nome;

  private $Dao;

  public function __construct($id) {
    
    $this->Dao = new Dao($this);

    if (!empty($id)) {
      $this->id = $id;
      $this->Dao->load($this);
    }

  }

  public function setId($id) {
    $this->id = $id;
  }

  public function getId() {
    return $this->id;
  }

  public function setNome($nome) {
    $this->nome = $nome;
  }

  public function getNome() {
    return $this->nome;
  }

}
