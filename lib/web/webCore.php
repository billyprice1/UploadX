<?php

/*

@author: Pips

@title: Web Core.
@desc: Class that handles all web-based requests, such as administrator settings and file viewing.

*/
  

class webCore{
  
  protected $settingsHandler;
  protected $userHandler;
  protected $errorHandler;
  protected $fileHandler;
  
  function __construct(){
    
    $this->errorHandler = new errorHandler();
    $this->settingsHandler = new settingsHandler();
    $this->userHandler = new userHandler();
    $this->fileHandler = new fileHandler();
    
    
  }
  
    // process whatever request has come in from a form with POST
  function process(){
    
    $action = $_POST['action'];
    $message;
    
    // create user
    if( ($action == 'createuser') ){
      
      if(!$this->userHandler->isUser($_POST['username'])){

        $this->userHandler->createUser($_POST['username']);

        $message = "Created user <b>".$_POST['username']."</b>";

        include $GLOBALS['templates']['notification'];
      }else{
        
        $this->errorHandler->throwError('action:badusername');
        
      }
      
      
    }
    
    // delete user
    if ($action == 'deleteuser'){
      
      if($this->userHandler->isUser($_POST['username'])){
        
        $this->userHandler->deleteUser($_POST['username']);
        
      }else{
        
        $this->errorHandler->throwError('action:nouser');
        
      }
      
    }
    
      // login
    if ($action == 'login'){
      
      
      
    }
    
  }
  
    // should do this
  function buildPage(){
    
    
    
  }
  
    // this build's the file viewer/preview based on GET headers.
  function buildPreview(){
    
    $id = $_GET['id'];
    
    $file_data = $this->fileHandler->getFileData($id);
    
    $src = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'/view';
    $type = $file_data['type'];
    $uploader = $file_data['uploader'];
    $uploader_ip = $file_data['uploader_ip'];
    $is_admin = $_SESSION['loggedin'];
    $show_views = $this->settingsHandler->getSettings()['security']['show_views'];
    
    
    $show = true;
        
      // stupid way of showing the top half and bottom half of the frame.
      include __DIR__.'/../templates/frame/frame.php';
    
    if(strpos($type,'image') !== false){
      
      include __DIR__.'/../templates/viewer/image.php';
      
    } else if(strpos($type,'text') !== false){
      
      include __DIR__.'/../templates/viewer/text.php';
      
    }else if(strpos($type,'video') !== false){
      
      include __DIR__.'/../templates/viewer/video.php';
      
    }
    
   $show = false;
        // stupid way of showing the top half and bottom half of the frame.
        include __DIR__.'/../templates/frame/frame.php';
    
    
  }
    
  
}

?>