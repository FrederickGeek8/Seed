<?php
die();
include('../core/init.inc.php');

$users = get_members($_SESSION['tid']);
$uc = count($users);
$errors = array();

if(isset($_GET['claimed'])){
  $errors[] = "Download has already been claimed.";
}

$fin = false;
if(is_finished($_SESSION['tid'])){
  $fin = true;
}

$dl = false;
if(is_finished($_SESSION['tid']) && has_download($_SESSION['tid'])){
  $dl = true;
}

if(isset($_GET['finish']) && !$fin){
    finish_account($_SESSION['tid']);
    header("Location: builder");
}

if(isset($_POST['numu'])){
	$num = (int)$_POST['numu'];
	if(($uc+$num) > 8){
		$errors[] = "Too many users!";
	}

	if(empty($errors)){
		add_users(0, $_SESSION['tid'], $_POST['numu'], $uc);
		header("Location: builder");
		die();
	}
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $siteinfo['title']; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $siteinfo['meta']; ?>">

    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <style>

    body {
      background: url("/assets/img/new-york-1.jpg");
      background-repeat:no-repeat;
        background-size:cover;
        background-position:center;
        background-attachment:fixed;
        padding-top:1.5%;
        padding-bottom: 1.5%;
        text-shadow:none;
    }

    .jumbotron {
    	padding: 10px;
    }

    .form-signin {
      color:black;
        max-width:500px;
        /*padding:19px 29px 29px;*/
        margin:20px auto 20px;
        background:rgba(255,255,255,0.8);
        -webkit-box-shadow:0 1px 2px #aaa;
        -moz-box-shadow:0 1px 2px #aaa;
        box-shadow:0 1px 2px #aaa;
        border-radius:  1px;
        min-height:500px;
    }
    .form-signin .form-signin-heading, .form-signin .checkbox {
        margin-bottom:10px
    }
    .form-signin input[type="email"], .form-signin input[type="password"], .form-signin input[type="text"] {
        font-size:16px;
        height:auto;
        margin-bottom:15px;
        padding:7px 9px
    }
    .form-signin>img {
        max-width:100%
    }
    .user-item {
    	background:#eee;
    	width:100%;
    	padding-top:20px;
    	padding-bottom:20px;
    	margin:0;
    	text-align: center;
    	border-bottom:1px solid #d5d5d5;
    	cursor: pointer;
        text-shadow:none;
    }
    </style>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="/assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="/assets/ico/apple-touch-icon-57-precomposed.png">
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-43675987-1', 'auto');
  ga('send', 'pageview');

</script>
  </head>

  <body>

    <div class="container">
      <noscript><div style="padding-top:15%;width:100%;height:100%;top:0;left:0;right:0;bottom:0;z-index:1000;position:fixed;background:black;text-align:center;color:white;"><h1>Abort, Abort!</h1><h4>Your browser does not have Javascript enabled or does not support it.</h4><h6>Please check if there is a native application available for your system.</h6></div></noscript>
      <!-- Main component for a primary marketing message or call to action -->
      <div class="body">
        <?php
        if (empty($errors) === false){
          foreach ($errors as $error){
            echo "<div class='alert alert-danger'>{$error}</div>";
          }
        }

        ?>
        <ul class="pager">
          <li class="previous"><a href="/logout">&larr; Logout</a></li>
        </ul>
        <div class="jumbotron">
          <h1><?=$siteinfo['title']?> Team Builder</h1>
        </div>
        <div class="form-signin">
        <?php if($uc < 8 && !$fin){ ?>
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h4 class="modal-title" id="myModalLabel">Adding Users</h4>
		      </div>
		      <div class="modal-body">
		        <form style="text-align:center" action='builder' method='post' id="adding">
		          <p style="font-size:16px;">Add <select name="numu"><?php for ($i=1;$i<=(8-$uc);$i++) { echo "<option value=\"{$i}\">{$i}</option>"; } ?></select> Users</p>
		        </form>
		      </div>
		      <div class="modal-footer">
		        <button type="submit" form="adding" class="btn btn-primary">Add</button>
		      </div>
		    </div>
		  </div>
		</div>
		<?php }
        if($uc > 0 && !$fin){ ?>
        <div class="modal fade" id="finnish" tabindex="-1" role="dialog" aria-labelledby="finnishlabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title" id="finnishlabel">Please Confirm</h4>
              </div>
              <div class="modal-body">
                Are you sure you want to finish account creation? No more accounts can be created.
              </div>
              <div class="modal-footer">
                <a class="btn btn-warning" href="?finish">Yes</a>
                <button class="btn btn-danger" data-dismiss="modal">No</button>
              </div>
            </div>
          </div>
        </div>
        <?php }
		if($uc != 0){
			foreach ($users as $user) {
				echo "<div class=\"user-item\">{$user}</div>";
			}
		}else{
			echo "<div class=\"user-item\">No Users to Show</div>";
		}
    if($dl){
		?>
    <div class="modal fade" id="passcon" tabindex="-1" role="dialog" aria-labelledby="passcon" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="passcon">Warning</h4>
          </div>
          <div class="modal-body">
            The password file contains all the usernames and passwords for the student accounts. You can only download this file <u>once</u>, so make sure you have a stable internet connection. The passwords cannot be regenerated so be cautious with this file. Are you sure you want to download the password file now?
          </div>
          <div class="modal-footer">
            <a class="btn btn-warning" href="password">Yes</a>
            <button class="btn btn-danger" data-dismiss="modal">No</button>
          </div>
        </div>
      </div>
    </div>
    <div style="text-align:center;"><button type="button" class="btn btn-success btn-lng" style="margin:10px;" data-toggle="modal" data-target="#passcon">Download Password File</button></div><?php } ?><div style="text-align:right;"><?php if($uc < 8 && !$fin){ ?><button type="button" class="btn btn-primary" style="margin:5px;" data-toggle="modal" data-target="#myModal">Add</button><?php } if($uc > 0 && !$fin){ ?><button type="button" class="btn btn-success" style="margin:5px;" data-toggle="modal" data-target="#finnish">Finish</button><?php } ?></div>
        </div>
	  	<div class="footer">
          <hr>
          <p>&copy; <?php echo $siteinfo['title']; ?> 2014&emsp;<a href="/privacy" id='privacy'>Privacy Policy</a>&emsp;<a href="/report" id='privacy'>Report a Bug</a></p>
        </div>
      </div>
    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
    <script src="//marketdream.org/assets/js/prefixfree.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script> 
    <?php if($uc == 0  && !$fin){ ?><script type="text/javascript">$('#myModal').modal('show');</script><?php } ?>
  </body>
</html>