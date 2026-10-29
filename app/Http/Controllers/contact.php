<?php
// echo 1;
// die;
$form_name = $_POST['form_name'];
$last_name = $_POST['last_name'];
$form_email = $_POST['form_email'];
$form_phone = $_POST['form_phone'];
$form_subject = $_POST['form_subject'];
$form_message = $_POST['form_message'];
// $form_name = "sourabh";
// $last_name = "bhardwaj";
// $form_email = "ss@gmail.com";
// $form_subject = "test";
// $form_message = "test";
$subject = $form_subject;

$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Smart Cloud Dimensions</title>

    <style type="text/css">
              /* Template styling */
              body {
                  font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
                  width: 100%;
                  max-width: 100%;
                  font-size: 17px;
                  line-height: 24px;
                  color: #373737;
                  background: #F9F9F9;
              }
              table {
                  width: 100%;
                  margin: 0 auto;
              }
              h1, h2, h3, h4 {
                  color: #2ab27b;
                  margin-bottom: 12px;
                  line-height: 26px;
              }
              p, ul, ul li {
                  font-size: 17px;
                  margin: 0 0 16px;
                  line-height: 24px;
              }
              ul {
                  margin-bottom: 24px;
              }
              ul li {
                  margin-bottom: 8px;
              }
              p.mini {
                  font-size: 12px;
                  line-height: 18px;
                  color: #ABAFB4;
              }
              p.message {
                  font-size: 16px;
                  line-height: 20px;
                  margin-bottom: 4px;
              }
              hr {
                  margin: 2rem 0;
                  width: 100%;
                  border: none;
                  border-bottom: 1px solid #ECECEC;
              }
              a, a:link, a:visited, a:active, a:hover {
                  font-weight: bold;
                  color: #439fe0;
                  text-decoration: none;
                  word-break: break-word;
              }
              a:active, a:hover {
                  text-decoration: underline;
              }
              .time {
                  font-size: 11px;
                  color: #ABAFB4;
                  padding-right: 6px;
              }
              .emoji {
                  vertical-align: bottom;
              }
              .avatar {
                  border-radius: 2px;
              }
              #footer p {
                  margin-top: 16px;
                  font-size: 12px;
              }

              /* Client-specific Styles */
              #outlook a {padding:0;}
              body{width:100% !important; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; margin:0 auto; padding:0;}
              .ExternalClass {width:100%;}
              .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;}
              #backgroundTable {margin:0; padding:0; width:100%; line-height: 100% !important;}

              /* Some sensible defaults for images
              Bring inline: Yes. */
              img {outline:none; text-decoration:none; -ms-interpolation-mode: bicubic;}
              a img {border:none;}
              .image_fix {display:block;}

              /* Outlook 07, 10 Padding issue fix
              Bring inline: No.*/
              table td {border-collapse: collapse;}

              /* Fix spacing around Outlook 07, 10 tables
              Bring inline: Yes */
              table { border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; }

              /* Mobile */
              @media only screen and (max-device-width: 480px) {
                  /* Part one of controlling phone number linking for mobile. */
                  a[href^="tel"], a[href^="sms"] {
                      text-decoration: none;
                      color: blue; /* or whatever your want */
                      pointer-events: none;
                      cursor: default;
                  }

                  .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
                      text-decoration: default;
                      color: orange !important;
                      pointer-events: auto;
                      cursor: default;
                  }

              }

              /* Not all email clients will obey these, but the important ones will */
              @media only screen and (max-width: 480px) {
                  .card {
                      padding: 1rem 0.75rem !important;
                  }
                  .link_option {
                      font-size: 14px;
                  }
                  hr {
                      margin: 2rem -0.75rem !important;
                      padding-right: 1.5rem !important;
                  }
              }

              /* More Specific Targeting */
              @media only screen and (min-device-width: 768px) and (max-device-width: 1024px) {
                  /* You guessed it, ipad (tablets, smaller screens, etc) */
                  /* repeating for the ipad */
                  a[href^="tel"], a[href^="sms"] {
                      text-decoration: none;
                      color: blue; /* or whatever your want */
                      pointer-events: none;
                      cursor: default;
                  }

                  .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
                      text-decoration: default;
                      color: orange !important;
                      pointer-events: auto;
                      cursor: default;
                  }
              }

              /* iPhone Retina */
              @media only screen and (-webkit-min-device-pixel-ratio: 2) and (max-device-width: 640px)  {
                  /* Must include !important on each style to override inline styles */
                  #footer p {
                      font-size: 9px;
                  }
              }

              /* Android targeting */
              @media only screen and (-webkit-device-pixel-ratio:.75){
                  /* Put CSS for low density (ldpi) Android layouts in here */
                  img {
                      max-width: 100%;
                      height: auto;
                  }
              }
              @media only screen and (-webkit-device-pixel-ratio:1){
                  /* Put CSS for medium density (mdpi) Android layouts in here */
                  img {
                      max-width: 100%;
                      height: auto;
                  }
              }
              @media only screen and (-webkit-device-pixel-ratio:1.5){
                  /* Put CSS for high density (hdpi) Android layouts in here */
                  img {
                      max-width: 100%;
                      height: auto;
                  }
              }
              /* Galaxy Nexus */
              @media only screen and (min-device-width : 720px) and (max-device-width : 1280px) {
                  img {
                      max-width: 100%;
                      height: auto;
                  }
                  body {
                      font-size: 16px;
                  }
              }
              /* end Android targeting */


          </style>

</head>
<body>
<table width="100%" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" style="font-size: 17px; line-height: 24px; color: #373737; background: #F9F9F9;">
    <tr>
        <td valign="top">
            <table id="header" width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td valign="bottom" style="padding: 20px 16px 20px;">
                        <div style="max-width: 600px; margin: 0 auto; text-align: center;">
                        <img src="https://smartclouddimensions.net/img/logo/logo.png" width="250px" />
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table id="body" width="100%" cellpadding="0" cellspacing="0" border="0" align="center">
                <tr>
                    <td valign="top">
                        <div style="max-width: 600px; margin: 0 auto; padding: 0 12px;">
                            <div class="card" style="background: white; border-radius: 0.5rem; padding: 2rem; margin-bottom: 1rem;">
                                <h2 style="color: #4b6e99; margin: 0 0 12px; line-height: 30px; text-align:center;">Contact us details</h2>
                                <p>Hello Admin,</p>
                                <p>You have a new query </p>
								<p><b>Name</b> - '.$form_name.' '.$last_name.'</p>
								<p><b>Email</b> - '.$form_email.'</p>
								<p><b>Phone</b> - '.$form_phone.'</p>
								<p><b>Subject</b> - '.$form_subject.'</p>
								<p><b>Message</b> - '.$form_message.'</p>
                            </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    </td>
    </tr>
    <tr>
        <td>
            <table id="footer" width="100%" cellpadding="0" cellspacing="0" border="0" align="center" style="color: #989EA6;">
                <tr>
                    <td valign="top" align="center">
                        <div style="max-width: 600px; margin: 0 auto;">
                            <p class="footer_address" style="margin-top: 16px; font-size: 12px; line-height: 20px;">
                                <a href="https://smartclouddimensions.net/" style="font-weight: bold; color: #439fe0;">Smart Cloud Dimensions</a> &nbsp;&bull;&nbsp;
                                <br />
                                <!-- Rubinsheina 11A &nbsp;&bull;&nbsp; Saint-Petersburg, Russia &nbsp;&bull;&nbsp; 191002 -->
                            </p>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html></b>';
//SMTP
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);
try {
    $mail->SMTPDebug = 2;
    $mail->isSMTP();
    $mail->Host       = 'smtp.ipage.com';
    $mail->SMTPAuth   = true;  // Enable SMTP authentication
    $mail->Username   = 'sales@smartclouddimensions.net';
    $mail->Password   = 'Zain@2025';
    $mail->SMTPSecure = 'ssl';
    $mail->Port       = 465;

    $mail->setFrom('sales@smartclouddimensions.net', 'smartclouddimensions');
    //$mail->addAddress('receiver1@gfg.com');dmb@ipremiumbrands.com
    $mail->addAddress('sales@smartclouddimensions.net', 'smartclouddimensions');
    //$mail->addAddress('mobappssolutions128@gmail.com', 'smartclouddimensions');
    //$mail->addAddress('hala@crosswords.sa', 'Crosswords');

    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    = $message;
    //$mail->AltBody = 'Body in plain text for non-HTML mail clients';
    $mail->send();
    echo "1";
} catch (Exception $e) {
    echo $e;
 }
//SMTP

// $header = "From:noreply@crosswords.sa \r\n";
// //$header .= "Cc:afgh@somedomain.com \r\n";
// $header = "MIME-Version: 1.0\r\n";
// $header .= "Content-type: text/html\r\n";

// $retval = mail ($to,$subject,$message,$header);

// if( $retval == true ) {
// echo "1";
// }else {
// echo "2";
//}

?>
