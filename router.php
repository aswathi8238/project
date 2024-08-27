<?php
include('helper.php');
$opt=$_REQUEST['opt'];
switch($opt){
    case 1:
        //Registration Operation
        $name=$_REQUEST['txtName'];
        $email=$_REQUEST['txtEmail'];
        $mobile=$_REQUEST['txtMobile'];
        $password=$_REQUEST['txtPass'];
        //$confirm=$_REQUEST['txtConfirm'];
        if(register_user($name,$email,$mobile,$password,)){

            header("location:login.php");
        }
        else{
            header ("location:register,php");
        }
        //echo "Registration";
        break;
    case 2:
        //Login Operation
        $email=$_REQUEST['txtEmail'];
        $password=$_REQUEST['txtPass'];
          if(login_check($email,$password)){


            header("location:forgot.php");
          }
              
          break;

         default:
         echo "error in operation";
        }
        