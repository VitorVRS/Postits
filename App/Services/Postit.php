<?php

namespace App\Services;

require_once("App/Dao/Postit.php");

use \App\Dao\Postit as Dao;

class Postit {

  private $Dao;

  public function __construct() {
    $this->Dao = new Dao(new \App\Model\Model());
  }

  public function getByUser(\App\Model\User $user) {
    $data = $this->Dao->find($user->getId(), false);
    return $data;
  }

}