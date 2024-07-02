<?php
function handle_registration(){
  $messages = array();
  if(empty($_POST["submit"])) return $messages;
  
  $exit = false;
  // ensure user has provided name
  if (empty($_POST["name"])){
    $messages[] = array(
      text => "Missing name.",
      type => "danger"
    );
    $exit = true;
  }
    
  // ensure user has provided an email
  if (empty($_POST["email"])){
    $messages[] = array(
      text => "Missing email",
      type => "danger"
    );      
    $exit = true;
  }elseif(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
    // ensure user has provided a valid(!) email
    $messages[] = array(
      text => "Invalid email",
      type => "danger"
    );    
    $exit = true;
  }
  
  if(!$exit){
    $file = 'register.txt';
    $linecount = 0;
    $handle = fopen($file, "r");
    while(!feof($handle)){
      $line = fgets($handle);
      $linecount++;
    }
    fclose($handle);

    $people = $_POST["name"]."\t".$_POST["email"]."\t".$_POST["diet"]."\t".$_POST["school"]."\t".$_POST["contestant"]."\n";
    $ret = file_put_contents($file, $people, FILE_APPEND);
    if ($ret === FALSE){
      print_r(error_get_last());
      $messages[] = array(
        text => "Server error, please try again.",
        type => "danger"
      );    
    } else {
      if ($linecount < 50){
        $messages[] = array(
          text => "Thank you for registering!",
          type => "success"
        );
      }
      else{
        $messages[] = array(
          text => "Event is full, you have been added to the waiting list.",
          type => "info"
        );
      }
    }
  }
  return $messages;
}
?>