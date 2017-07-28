<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
  <head>
    <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script type="text/javascript">
      function scrollBottom()
      {
        $('#chat-box').animate({ scrollTop: 999999999999 }, 'fast');
        return false;
      }

        $(document).ready(function() {
        if(<?php echo !isset($_SESSION['time']); ?>){
            var visitortime = new Date();
            var visitortimezone = "GMT " + -visitortime.getTimezoneOffset()/60;
            $.ajax({
                type: "GET",
                url: "<?php echo base_url();?>main/timezone",
                data: 'time='+ visitortimezone,
                success: function(){
                    location.reload();
                }
            });
        }
    });
    </script>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="robots" content="noindex" />
    <meta name="robots" content="nofollow" />
    <link rel="icon" type="image/png" href="<?php echo base_url();?>img/anvil.png">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <title>Breakthrough Foundry 2</title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url(); ?>css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo base_url(); ?>css/starter-template.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>css/bootstrap-datetimepicker.min.css" rel="stylesheet">

    <style type="text/css">

    	p.footer {
    		text-align: center;
    		font-size: 11px;
    		border-top: 1px solid #D0D0D0;
    		line-height: 20px;
    		padding: 0 10px 0 10px;
    		margin: 20px 0 0 0;
    	}

      .tooltiptext {
        visibility: hidden;
        background-color: #F5F5F5;
        color: #333;
        width:auto;
        text-align: center;
        padding: 5px 3px 5px 3px;
        margin: -20px 0px 0px -50px;
        border-radius: 6px;
        position: absolute;
        z-index: 1;
      }

      a:hover .tooltiptext {
        visibility: visible;
      }

          </style>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <!-- onload="scrollBottom()" is for the chatbox to scrolls to the recent messages automatcally -->
  <body <?php if($page=='chat_list'){ echo 'onload="scrollBottom()"'; }?>>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo base_url();?>main/home">
          	<img alt="Breakthrough Foundry" src="<?php echo base_url();?>img/anvil.png">
          </a>
          <a class="navbar-brand" href="<?php echo base_url();?>main/home" id="title">
            <script>
              var b = "B".fontcolor("#6b9e3d");
              var str = b+"reakthrough";
              b = "F".fontcolor("#6b9e3d");
              str = str+b+"oundry";
              if(screen.width <= 500) {
                str = str.fontsize(2);
              }
              var a = document.getElementById("title");
              a.innerHTML = str;
            </script>
          </a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
        <?php
        $groups_name = "Interest Groups";
        if($this->ion_auth->logged_in())
        {
          $user_info = $this->ion_auth->user()->row();
          $query = $this->db->query("SELECT interest_group_name FROM users WHERE id = '$user_info->id'");
          $groups_name = $query->result_array()[0]['interest_group_name'];
        }?>
        <ul class="nav navbar-nav">
          <li <?php if($page == 'project_list'){echo 'class="active"';}?>>
            <a href="<?php echo base_url();?>main/project_list">Projects</a>
          </li>
          <li <?php if($page == 'interest_groups'){echo 'class="active"';}?>>
            <a href="<?php echo base_url();?>main/interest_groups"><?php echo $groups_name;?></a>
          </li>
          <?php
            if ($page == "chat_list" || $page == "add_channel")
            {
              include 'main_head_chat.php';
            }
            ?>
         </ul>

          <?php
          if($this->ion_auth->logged_in())
          {
          	$user_info = $this->ion_auth->user()->row();
            $global_read_check = 1;
            $global_pms = $this->m_btf2_chat->get_global_pms($user_info->id);
            if($global_pms!=NULL)
            {
              foreach ($global_pms as $message)
              {
                if ($message['Is_Read'] == 0)
                {
                  $global_read_check = 0;
                }
              }
            }
          	echo '<ul class="nav navbar-nav navbar-right">';
          	//echo '<ul class="nav navbar-nav pull-right">';
          	echo '<li class="dropdown">';
          	echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">'.$user_info->first_name.' '.$user_info->last_name.' <span class="caret"></span></a>';
          	echo '<ul class="dropdown-menu">';
          	echo '<li><a href="'.base_url().'main/user_profile/'.$user_info->id.'">My Profile</a></li>';
          	echo '<li><a href="'.base_url().'main/user_settings">My Settings</a></li>';
            if ($global_read_check == 0)
            {
              echo '<li><a href="'.base_url().'main/inbox/'.$user_info->id.'">Messages <font style="color:#CC3333">⬤</font></a></li>';
            }
            else {
              echo '<li><a href="'.base_url().'main/inbox/'.$user_info->id.'">Messages</a></li>';
            }
          	echo '<li role="separator" class="divider"></li>';
          	echo '<li><a href="'.base_url().'auth/logout">Logout</a></li>';
          	echo '</ul>';
          	echo '</li>';
          	echo '</ul>';
          }
          ?>

        </div><!--/.nav-collapse -->
      </div>
    </nav>
    <div class="container">
