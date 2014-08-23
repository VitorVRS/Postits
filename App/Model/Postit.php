<?php

namespace App\Model;

require_once("App/Dao/Postit.php");
require_once("App/Model/Model.php");

use \App\Dao\Postit as Dao;

class Postit extends Model{

  /**
   * @column_name("id")
   */

  private $id;

  /**
   * @column_name(title)
   */
  private $title;

  /**
   * @column_name(content)
   */
  private $content;

  /**
   * @column_name(color)
   */
  private $color;

  /**
   * @column_name(user_id)
   */
  private $userId;

  /**
   * @transient
   */
  private $Dao;

  public function __construct($id = null) {
    
    $this->Dao = new Dao($this);

    if (!empty($id)) {
      $this->id = $id;
      $this->Dao->load($this);
    }

  }

  public function setId($id) {
    $this->id = $id;
  }

  public function setTitle($title) {
    $this->title = $title;
  }

  public function setContent($content) {
    $this->content = $content;
  }

  public function setColor($color) {
    $this->color = $color;
  }

  public function setUserId($userId) {
    $this->userId = $userId;
  }

  public function getId() {
    return $this->id;
  }

  public function getTitle() {
    return $this->title;
  }

  public function getContent() {
    return $this->content;
  }

  public function getColor() {
    return $this->color;
  }

  public function getUserId() {
    return $this->userId;
  }

  public function save() {
    $this->Dao->save();
  }

  public function delete() {
    $this->Dao->delete();
  }

}