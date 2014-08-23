<?php
/**
 * requires
 */
require_once("App/Database/Database.php");
require_once("App/Model/User.php");
require_once("App/Model/Postit.php");
require_once("App/Services/Postit.php");

use App\Database\Database as Database;

/**
 * uses
 */
use App\Model\User;
use App\Model\Postit as Postit;
use App\Services\Postit as PostitService;


Database::execute("BEGIN");

try {
  
  if (empty($_POST["method"])) {
    throw new Exception("Invalid request.");    
  }

  switch ($_POST["method"]) {
    case 'loadAll':
      
      if (empty($_POST["userData"]["id"])) {
        throw new Exception("Invalid parameters.");        
      }
      $user = new User($_POST["userData"]["id"]);

      $postitService = new PostitService();

      $data = $postitService->getByUser($user);

      $return = array("message" => "Loaded All", "postits" => $data);
      break;
    
    case 'savePostit':

      $oDado = $_POST["oPostit"];

      $oPostit = new Postit();
      $oPostit->setId($oDado["id"]);
      $oPostit->setTitle($oDado["title"]);
      $oPostit->setContent($oDado["content"]);
      $oPostit->setColor($oDado["color"]);
      $oPostit->setUserId($_POST["userData"]["id"]);

      $oPostit->save();

      $return = array("status" => true);

    break;

    case 'deletePostit':

      $oPostit = new Postit($_POST['id']);

      $oPostit->delete();

      $return = array("status" => true);

    break;

    default:
      throw new Exception("Method not found or not allowed");    
      break;
  }

  Database::execute("COMMIT");

} catch (Exception $e) {
  $return = array("message" => $e->getMessage()); 
  Database::execute("ROLLBACK");
}


echo json_encode($return);