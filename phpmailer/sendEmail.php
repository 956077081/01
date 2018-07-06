<?php
//header("content-type: text/html;charset= utf-8");
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require './vendor/autoload.php';

$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
try {
    //Server settings
    $mail->SMTPDebug = 2;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.163.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'privateEmail';                 // SMTP username
    $mail->Password = 'xxxxx';                          // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 25;    //163邮箱 端口见   http://help.163.com/09/1223/14/5R7P3QI100753VB8.html                           // TCP port to connect to
    //Recipients 收件人
    $mail->setFrom('privateEmail@163.com', 'fromsecretperson');  //发件人
    $mail->addAddress('receiverEamil@126.com', '王子泉');     // Add a recipient
   // $mail->addAddress('ellen@example.com'); //多人时可再添加               // Name is optional
    $mail->addReplyTo('privateEmail@163.com', '发送成功!');
   // $mail->addCC('cc@example.com');//添加抄送 指给多人发送时可以看到除了自己外其他接件人的信息地址
    //$mail->addBCC('bcc@example.com');  //给多人发送 接件人 看不到其他接件的信息
    //Attachments
    $mail->addAttachment('./girl.zip',"girljpg");         // Add attachments
   // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
    //Content
    $mail->isHTML(true);   //是否html格式                               // Set email format to HTML
    $mail->Subject = 'Here is the subject';  //邮件主题
    $mail->Body    = 'give you a beautiful girl picture body!</b>'; //邮件内容
    $mail->AltBody = 'give you a beautiful girl picture !'; //当不支持html代码时的内容.
    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
}