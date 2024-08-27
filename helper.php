<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;   
require './mailer/src/Exception.php';
require './mailer/src/PHPMailer.php';
require './mailer/src/SMTP.php';
date_default_timezone_set("Asia/Calcutta");
//DB Connector
function db_connect(){
    try{
        $pdo=new PDO("mysql:host=localhost;dbname=baablog","root","");
        return $pdo;
    }
    catch(Exception $e){
        return false;
    }
}
//DB Connector
//Registration Function
function register_user($name,$email,$mobile,$password){
    if(check_userdata('mobile',$mobile)==2 && check_userdata('email',$email)==2){

    
    if($dbo=db_connect()){
        $sql="insert into users(name,email,mobile,usertype,userstatus,password) values(?,?,?,?,?,?)";
        $stmt=$dbo->prepare($sql);
        $encypted_password=password_hash($password,PASSWORD_BCRYPT);
        $s=$stmt->execute([$name,$email,$mobile,'user',1,$encypted_password]);
        if($s){
            $subject="AccountRegistration";
            $message="<h3>Welcome $name to Login Module</h3><p>Your account has been successfully registered with us. </p>";
            send_mail($email,$name,$subject,$message);
            return 1;
        }else{
            return false;
        }
        
    }
    else{
        return false;
    }
    }
    else{
        return 2;
    }

}
//Registration Function
//Function for checking email or mobile number exists in our DB
function check_userdata($fieldname,$value){
    if($dbo=db_connect()){
        $stmt=$dbo->prepare("select *from users where $fieldname=?");
        $stmt->execute([$value]);
        $users=$stmt->fetch();
        if($users){
            return 1;
        }
        else{
            return 2;
        }
    }
    else{
        return false;
    }
}
//Function for checking email or mobile number exists in our DB
//Function for login
function login_check($email,$password){
    if($dbo=db_connect()){
        $stmt=$dbo->prepare("select *from users where email=?");
        $stmt->execute([$email]);
        $users=$stmt->fetch();
        if(password_verify($password,$users['password'])){
            echo "Login Success";
        }
        else{
            echo "Login Failed";
        }
    }
    else{
        return false;
    }
}
//Function for login
//Function for sending mail
function send_mail($tomail,$name,$subject,$message){
    //pasted
      $mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'cybersquarebaabtra@gmail.com';                     //SMTP username
    $mail->Password   = 'ugzs gysu ermg easp';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('cybersquarebaabtra@gmail.com', 'Admin Login Module');
    $mail->addAddress($tomail, $name);     //Add a recipient
    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $message;
    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
    //pasted
} 
//Function for sending 
//Password Reset
function password_reset_init($email){
    if($dbo=db_connect()){
        $stmt=$dbo->prepare("select *from users where email=?");
        $stmt->execute([$email]);
        $users=$stmt->fetch();  
        if($users){
            $token=password_token_generator($users['userid']);
            if($token){
                if($token==2 || $token==-1){
                    echo "There is an error occured please contact system admin";
                }
                else{
                    $url="http://localhost/router.php?opt=4&token=".$token."&uid=".$users['userid'];
                    $title="Password Reset Request";
                    $message="<h3>Your password reset link from this host attached below</h3><p>".$url."</p>";
                    send_mail($email,'User',$title,$message);
                    echo "<p>There is an email forwarded to your email account for resetting password</p>";
                }
            }

        }
        else{
            return -1;
        }
    }
    else{
        return false;
    }
}

//Password Reset
//Password token gen_function
function password_token_generator($uid){
    $token=substr(str_shuffle('abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRTUVWXYZ2346789'),0,5);
    if($dbo=db_connect()){
        $stmt=$dbo->prepare("select *from users where userid=?");
        $stmt->execute([$uid]);
        $users=$stmt->fetch();
        if($users){
            $sql="update users set password_token=? where userid=?";
            $stmt1=$dbo->prepare($sql);
            $s=$stmt1->execute([$token,$uid]);
            if($s){
                return $token;
            }
            else{
                return 2;
            }
        }
        else{
            return -1;
        }
    }
    else{
        return false;
    }
}
//Password token gen_function
//Password token expiry function
function password_token_expiry($uid,$token){
    if($dbo=db_connect()){
        $stmt=$dbo->prepare("select password_token_gen_datetime from users where userid=? and password_token=?");
        $stmt->execute([$uid,$token]);
        $users=$stmt->fetch();
        if($users){
            $timeFirst  = strtotime($users['password_token_gen_datetime']);
            $timeSecond = strtotime(date('Y-m-d h:i:s'));
            $differenceInSeconds = $timeSecond - $timeFirst;
            if($differenceInSeconds/3600<1){
                   return 1; 
            }
            else{
                return -2;
            }

        }
        else{
            return -1;
        }
    }
    else{
        return false;
    }
}
//Password token expiry function
//password_reset_init('cybersquarebaabtra@gmail.com');
//password_token_expiry(4,'vmf6A');
//password_token_generator(4);
//register_user('baabtra','cybersquarebaabtra@gmail.com',9400770900,'asd123#');
//print_r(get_declared_classes());
?>