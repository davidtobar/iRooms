<?php
error_reporting(0);
require_once 'version.php'; 
include("language/en.php");

$defaultzone = date_default_timezone_get();
$baseurl = 'http://'. $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
$baseurl = str_replace("/install", "", $baseurl);

if(isset($_POST['submit-form'])) {
  if ($_POST) {
  foreach ($_POST as $param_name => $param_val) {
      $$param_name = $param_val;
    }
  }
  //check if DB works
  $connection = mysqli_connect($DATABASE_HOST,$DATABASE_USER,$DATABASE_PASS,$DATABASE_NAME);  
  // if (mysqli_select_db($DATABASE_NAME)) {
    //add initial data

  if (!$connection) {

      $msg = '<div class="margin-t-10">
               <div class="kode-alert kode-alert-icon alert5">
                 <i class="fa fa-warning"></i>
                 <a href="#" class="closed">×</a>
                 Error with the <strong>database</strong> connection information. Try Again.
               </div>
             </div>';  
  }else{

    $templine = '';
    $lines = file("installation.txt");
    foreach ($lines as $line){
      if (substr($line, 0, 2) == '--' || $line == '')
          continue;
      $templine .= $line;
      if (substr(trim($line), -1, 1) == ';')
      {
          mysqli_query($connection,$templine) or print('Error performing query \'<strong>' . $templine . '\': ' . mysqli_error() . '<br /><br />');
          $templine = '';
      }
    }

    $curr_timestamp = date('Y-m-d H:i:s');

    $sql1 = "INSERT INTO login (id, app_name, logo, favicon, description,created_at,updated_at) VALUES ('', '$APPNAME', '$LOGO', '$FAVICON', '$DESCRIPTION','$curr_timestamp','$curr_timestamp');";

    $sql2 = "INSERT INTO mail (id, mail_host, mail_port, smtp_email, password, from_name,created_at,updated_at) VALUES ('', '$MAIL_HOST', '$MAIL_PORT', '$SMTP_EMAIL','$MAIL_PASS','$FROM_NAME','$curr_timestamp','$curr_timestamp');";

    $final_pass = password_hash($USER_PASS, PASSWORD_DEFAULT);
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHILMNOPQRSTUVWXYZ0123456789";
    $random = str_shuffle($chars);

    $sql3 = "INSERT INTO users (id, name, username, email, phone, password, status,role,remember_token,created_at,updated_at) VALUES ('', '$USER_NAME', '$USERNAME', '$USER_EMAIL','$USER_PHONE','$final_pass',1,2,'$random','$curr_timestamp','$curr_timestamp');";

    mysqli_query($connection,$sql1);
    mysqli_query($connection,$sql2);
    mysqli_query($connection,$sql3);

     
    //create the files
        //create config file
        $fp = fopen('../config.php','w');
        $data = "";
          fwrite($fp, $data);
          fclose($fp);


        //ENV
        $fp = fopen('../../.env','w');
        $values = "APP_NAME=iRooms
APP_ENV=local
APP_KEY=base64:w2DLTOZaiKLl0vaBelZlFBftVxw3WRwu8XsswOELHko=
APP_DEBUG=true
APP_LOG_LEVEL=debug
APP_URL=http:$BASE_URL

DB_CONNECTION=mysql
DB_HOST=$DATABASE_HOST
DB_PORT=3306
DB_DATABASE=$DATABASE_NAME
DB_USERNAME=$DATABASE_USER
DB_PASSWORD=$DATABASE_PASS

BROADCAST_DRIVER=log
CACHE_DRIVER=file
SESSION_DRIVER=file
SESSION_LIFETIME=120
QUEUE_DRIVER=sync

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1";
        fwrite($fp, $values);
        fclose($fp);

        //index
        $fp = fopen('../config.php','w');
        $data = "";
          fwrite($fp, $data);
          fclose($fp);

        // Rename
        rename('../index.php','../index3.php');
        rename('../index2.php','../index.php');

        //create backups directory
        if (!file_exists('backups')) {
            mkdir('backups', 0777, true);
        }
        //create custom directory
        if (!file_exists('custom')) {
            mkdir('custom', 0777, true);
        }
        //create custom css
        $fp = fopen('custom/custom.css','w');
        fwrite($fp, "");
        fclose($fp);
        //create custom jss
        $fp = fopen('custom/custom.js','w');
        fwrite($fp, "");
        fclose($fp);

        //create custom php functions
        $fp = fopen('custom/custom.class.php','w');
        $customphp = "<?php
/* 

  Custom Functions Class 

*/
class CustomFunctions
{



}";
        fwrite($fp, $customphp);
        fclose($fp);

        //remove files
        unlink("installation.php");
        // unlink("installation.txt");
        //redirect to login
        header("Location: ../", true, 302); 
    }



}

?>
<!DOCTYPE html>
<html lang="<?=LANGUAGE?>">
  <head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>iRooms Installer</title>

  <!-- ========== Css Files ========== -->
  <link href="assets/css/root.css" rel="stylesheet">
  <style type="text/css">
    body{background: #F5F5F5;}
  </style>
  </head>
<body>

<!-- START CONTENT -->
<div class="container">

  <div class="row presentation">

      <div class="col-lg-8 col-md-6 titles">
        <span class="icon color2-bg"><i class="fa fa-cog"></i></span>
        <h1><?=$lan["installation"]?></h1>
        <h4><?=$lan["conf_details"]?></h4>
          <?=$msg?>
      </div>

      <div class="col-lg-4 col-md-6">

      </div>

  </div>  


<!-- START CONTAINER -->
<div class="container-padding  margin-b-50">
    <div class="row">
<div class="col-md-12 padding-0">
      <div class="panel panel-transparent" style="margin-top:-87px;">
            <div class="panel-body">
              
                <div role="tabpanel">

                  <!-- Nav tabs -->
                  <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#general" aria-controls="general" role="tab" data-toggle="tab" aria-expanded="true" class="active">General</a></li>
                    <li role="presentation" class=""><a href="#database" aria-controls="database" role="tab" data-toggle="tab" class="" aria-expanded="false"><?=$lan["database"]?></a></li>
                    <li role="presentation" class=""><a href="#users" aria-controls="users" role="tab" data-toggle="tab" class="" aria-expanded="false">User</a></li>
                    <li role="presentation" class=""><a href="#mail" aria-controls="mail" role="tab" data-toggle="tab" class="" aria-expanded="false"><?=$lan["mail"]?></a></li>
                  </ul>

                  <!-- Tab panes -->
                  <form action="installation" method="POST">
                  <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="general">
                      <div class="row">
                        <div class="col-md-5">
                          <div class="form-group">
                            <label for="input1" class="form-label">Base Url</label>
                            <input type="text" class="form-control" name="BASE_URL" placeholder="http://domain.com/" required>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="input1" class="form-label">App Name</label>
                            <input type="text" class="form-control" name="APPNAME" required>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="input1" class="form-label">Logo Url</label>
                            <input type="text" class="form-control" name="LOGO" placeholder="http://domain.com/img/logo.png" required>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-5">
                          <div class="form-group">
                            <label for="input1" class="form-label">Description</label>
                            <textarea class="form-control" rows="3" name="DESCRIPTION" required></textarea>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="input1" class="form-label">Favicon Url</label>
                            <input type="text" class="form-control" name="FAVICON" required>
                          </div>
                        </div>
                        <!-- <div class="col-md-4">
                          <div class="form-group">
                            <label for="input1" class="form-label">Timezone</label>
                            <input type="text" class="form-control" name="TIMEZONE" value="America/Los_Angeles" required>
                          </div>
                        </div> -->
                        
                        <!-- <div class="col-md-4">
                          <div class="form-group">
                            <label for="input1" class="form-label">Favicon (PNG URL)</label>
                            <input type="text" class="form-control" name="FAVICON" >
                          </div>
                        </div> -->
                      </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="database">
                      <div class="row">
                        <div class="col-md-3">
                          <div class="form-group">
                            <label for="input1" class="form-label">Database Name</label>
                            <input type="text" class="form-control" name="DATABASE_NAME" required>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label for="input1" class="form-label">Database User</label>
                            <input type="text" class="form-control" name="DATABASE_USER" required>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label for="input1" class="form-label">Database Password</label>
                            <input type="password" class="form-control" name="DATABASE_PASS">
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label for="input1" class="form-label">Database Host</label>
                            <input type="text" class="form-control" name="DATABASE_HOST" value="localhost" required>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="users">
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="input1" class="form-label">Name</label>
                            <input type="text" class="form-control" name="USER_NAME" required>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="input1" class="form-label">Username</label>
                            <input type="text" class="form-control" name="USERNAME" required>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="input1" class="form-label">Email</label>
                            <input type="email" class="form-control" name="USER_EMAIL" required>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="input1" class="form-label">Password</label>
                            <input type="password" class="form-control" name="USER_PASS" pattern=".{6,}" required title="6 characters minimum"/>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="input1" class="form-label">Phone</label>
                            <input type="text" class="form-control" name="USER_PHONE" required>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="mail">
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="input1" class="form-label">Mail host</label>
                            <input type="text" class="form-control" name="MAIL_HOST" required>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="input1" class="form-label">Mail Port</label>
                            <input type="text" class="form-control" name="MAIL_PORT" required>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="input1" class="form-label">Smtp Email</label>
                            <input type="email" class="form-control" name="SMTP_EMAIL" required>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="input1" class="form-label">Password</label>
                            <input type="password" class="form-control" name="MAIL_PASS" required/>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="input1" class="form-label">From Name</label>
                            <input type="text" class="form-control" name="FROM_NAME" required>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div>

                </div>              

            </div>
            <div class="panel-footer">
              <button type="submit" name="submit-form" class="btn btn-default"><?=$lan["save"]?></button>
              </form>
            </div>

      </div>
    </div>
    </div>

</div>
<!-- END CONTAINER -->

<!-- ================================================
jQuery Library
================================================ -->
<script type="text/javascript" src="assets/js/jquery.min.js"></script>

<!-- ================================================
Bootstrap Core JavaScript File
================================================ -->
<script src="assets/js/bootstrap/bootstrap.min.js"></script>

<!-- ================================================
Plugin.js - Some Specific JS codes for Plugin Settings
================================================ -->
<script type="text/javascript" src="assets/js/plugins.js"></script>
<!-- ================================================
Bootstrap Select
================================================ -->
<script type="text/javascript" src="assets/js/bootstrap-select/bootstrap-select.js"></script>
</body>
</html>