<?php

namespace App\Dao;

require_once("App/Database/Database.php");
require_once("App/Dao/Dao.php");
require_once("App/Model/Postit.php");

use \App\Database\Database as Database;

class Postit extends Dao {

  public function load(\App\Model\Postit $postit) {
    $this->read($this->getModel()->getId());
  }

  public function read($id) {

    $sql = "select * from postits where user_id = '{$id}'";

    $resultSet = Database::execute($sql);

    $postit =  $this->fetchAll($resultSet);

    if ($postit) {
      
      $this->getModel()->setTitle($postit[0]["title"]);
      $this->getModel()->setContent($postit[0]["content"]);
      $this->getModel()->setColor($postit[0]["color"]);
      $this->getModel()->setUserId($postit[0]["user_id"]);
      $this->getModel()->setId($id);

    }

  }

  public function find($userId, $model = true) {

    $sql = "select * from postits where user_id = '{$userId}'";

    $resultSet = Database::execute($sql);

    $postit =  $this->fetchAll($resultSet);

    if ($postit) {
      
      $return = array();

      foreach ($postit as $dado) {
        
        if ($model) {
          $oPostit = new \App\Model\Postit();
          $oPostit->setTitle($dado["title"]);
          $oPostit->setContent($dado["content"]);
          $oPostit->setColor($dado["color"]);
          $oPostit->setUserId($dado["user_id"]);
          $oPostit->setId($dado["id"]);
        } else {
          $oPostit = new \stdClass();
          $oPostit->title   = $dado["title"];
          $oPostit->content = $dado["content"];
          $oPostit->color   = $dado["color"];
          $oPostit->userId  = $dado["user_id"];
          $oPostit->id      = $dado["id"];
        }

        $return[] = $oPostit;

      }

      return $return;

    }

  }

  public function findById($id) {
    $sql = "select * from postits where id = '$id'";

    $resultSet = Database::execute($sql);

    $postit =  $this->fetchAll($resultSet);
    if ($postit) {
      $oPostit = new \App\Model\Postit();
      $oPostit->setTitle($postit[0]["title"]);
      $oPostit->setContent($postit[0]["content"]);
      $oPostit->setColor($postit[0]["color"]);
      $oPostit->setUserId($postit[0]["user_id"]);
      $oPostit->setId($postit[0]["id"]);

      return $oPostit;
    }
  }

  public function save() {

    if ($this->findById($this->getModel()->getId())) {
      $this->update();
    } else {
      $this->insert();
    }

  }

  protected function insert() {
    $sql = "insert into postits values(";
    $sql .= "'" . $this->getModel()->getId()      . "',";
    $sql .= "'" . $this->getModel()->getTitle()   . "',";
    $sql .= "'" . $this->getModel()->getContent() . "',";
    $sql .= "'" . $this->getModel()->getColor()   . "',";
    $sql .= "'" . $this->getModel()->getUserId()  . "'";

    $sql .= ")";

    $resultSet = Database::execute($sql);

    return !!$resultSet;   
  }

  protected function update() {
    $sql  = "update postits set ";
    $sql .= "title   = '" . $this->getModel()->getTitle()   . "',";
    $sql .= "content = '" . $this->getModel()->getContent() . "',";
    $sql .= "color   = '" . $this->getModel()->getColor()   . "', ";
    $sql .= "user_id = '" . $this->getModel()->getUserId()  . "' ";

    $sql .= "where id = '". $this->getModel()->getId() ."'";

    $resultSet = Database::execute($sql);

    return !!$resultSet;
  }

  public function delete() {

    $sql = "delete from postits where id = '" . $this->getModel()->getid() . "'";

    $resultSet = Database::execute($sql);

    return !!$resultSet;
  }

}