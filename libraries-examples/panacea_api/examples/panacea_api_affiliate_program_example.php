<?php 
require_once('panacea_api.php');
/*
* Panacea Affiliate program example
* 1.) Get dialing code for an ipadress
* 2.) Create a new user
* 3.) Set dialling code
*/
//setting user data
$PanaceaApi = new PanaceaApi();

//specify your username and password
$PanaceaApi->setUsername("username");
$PanaceaApi->setPassword("password");
$userdata =array(
"email"=>"testuser@test.com",
"cellphone_number"=>"0799939999",
"default_dialling_code"=>"27");
//setting a promo code
//you will be provided with this code by panacea
$promotion_code = "abcdefg";

//Get user ip address
$ip = $_SERVER['REMOTE_ADDR'];

//attempt to get country information
$result         = $PanaceaApi->get_affiliate_country_info($ip);
if($PanaceaApi->ok($result)){
  if(count($result['details']) < 12){
      //unable to get country information
      //se we are going to use the default prefix we set above
      $dialling_code = $userdata["default_dialling_code"];
  } else{
       $dialling_code = $result['details']['dialling_code'];
  }
}

$msg = $PanaceaApi->affiliate_register_user($userdata['email'], $dialling_code, $userdata['cellphone_number'],$ip );
if($PanaceaApi->ok($msg)) {
  //new user has been created
  if( $promotion_code !== ""){
      $result = $PanaceaApi->affiliate_set_promotion_code($msg['details']['user_id'], $promotion_code);
      if($PanaceaApi->ok($result)) {
      //Promotion code saved
      } else {
          echo $result['details']['Error'];
      }
  }
  //handle these values in any manner you see fit
  echo "New user created: " . "<br />"
  echo $msg['details']['message']."<br />";
  echo $msg['details']['username']."<br />";
  echo $msg['details']['password']."<br />";
} else{
//Displaying error messages
  foreach($msg['details']['ErrorMessages'] as $key=>$value){
      echo $value."\r\n";
  }
}
?>