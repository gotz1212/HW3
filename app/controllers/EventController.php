<?php
use Phalcon\Mvc\View;
class EventController extends ControllerBase{

  public function beforeExecuteRoute(){ // function ????????????????????????????????????????
	  if(!$this->session->has('memberAuthen')) // ???????????? session ??????????? ???????
         $this->response->redirect('authen');  
   }
 
  public function indexAction(){
    $events=Activities::find();
    $this->view->data=$events;
  }
  public function editAction(){
    $id=$this->session->get("actid");
    $event=Activities::findFirst("$id");
    if($this->request->isPost()){
      $name = trim($this->request->getPost('name')); // ????????? form
      $date = trim($this->request->getPost('day')); // ????????? form
      $detail = trim($this->request->getPost('detail')); // ????????? form

      $photoUpdate='';
      if($this->request->hasFiles() == true){
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
        $uploads = $this->request->getUploadedFiles();
        $isUploaded = false;			
        foreach($uploads as $upload){
          if(in_array($upload->gettype(), $allowed)){					
            $photoName=md5(uniqid(rand(), true)).strtolower($upload->getname());
            $path = '../public/img/event/'.$photoName;
            ($upload->moveTo($path)) ? $isUploaded = true : $isUploaded = false;
          }
        }
        if($isUploaded)  $photoUpdate=$photoName ;
        }else
        die('You must choose at least one file to send. Please try again.');
    $event->name=$name;
    $event->date=$date;
    $event->detail=$detail;
    $event->picture=$photoUpdate;
    $event->save();
    
    }
  }

  public function addAction(){
    if($this->request->isPost()){
      $name = trim($this->request->getPost('name')); // ????????? form
      $date = trim($this->request->getPost('day')); // ????????? form
      $detail = trim($this->request->getPost('detail')); // ????????? form

      $photoUpdate='';
      if($this->request->hasFiles() == true){
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
        $uploads = $this->request->getUploadedFiles();
        $isUploaded = false;			
        foreach($uploads as $upload){
          if(in_array($upload->gettype(), $allowed)){					
            $photoName=md5(uniqid(rand(), true)).strtolower($upload->getname());
            $path = '../public/img/event/'.$photoName;
            ($upload->moveTo($path)) ? $isUploaded = true : $isUploaded = false;
          }
        }
        if($isUploaded)  $photoUpdate=$photoName ;
        }else
        die('You must choose at least one file to send. Please try again.');
    $event=new Activities();
    $event->name=$name;
    $event->date=$date;
    $event->detail=$detail;
    $event->picture=$photoUpdate;
    $event->save();
    
    
    }
  }
  public function setIdAction($temp){
    $this->session->set('actid',$temp);
	  $this->response->redirect('event/edit');    
  }
  public function deleteIdAction($temp){
    $event = Activities::findFirst($temp);
    $event->delete();
	  $this->response->redirect('event');    
  }
}  