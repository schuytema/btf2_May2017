<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="robots" content="noindex" />
    <meta name="robots" content="nofollow" />
    <link rel="icon" href="../../favicon.ico">

    <title>Breakthrough Foundry 2</title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url(); ?>css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo base_url(); ?>css/starter-template.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>css/bootstrap-datetimepicker.min.css" rel="stylesheet">

    <style type="text/css">

    	p.footer {
    		text-align: right;
    		font-size: 11px;
    		border-top: 1px solid #D0D0D0;
    		line-height: 32px;
    		padding: 0 10px 0 10px;
    		margin: 20px 0 0 0;
    	}

      .channel-btn {
        color:#999;
        background-color:transparent;
        padding-top:14px;
        padding-bottom:14px;
        line-height:20px;
      }

      .channel-btn.focus,.channel-btn:focus,.channel-btn:hover,.channel-btn.active,.channel-btn:active,.open>.dropdown-toggle.channel-btn {
        color:#fff;
        background-color:transparent;
      }
      .channel-btn.active.focus,.channel-btn.active:focus,.channel-btn.active:hover,.channel-btn:active.focus,.channel-btn:active:focus,.channel-btn:active:hover,.open>.dropdown-toggle.channel-btn.focus,.open>.dropdown-toggle.channel-btn:focus,.open>.dropdown-toggle.channel-btn:hover {
        color:#fff;
        background-color:transparent;
      }
      .channel-btn.active,.channel-btn:active,.open>.dropdown-toggle.channel-btn {
        background-image:none;
      }
      .channel-btn.disabled.focus,.channel-btn.disabled:focus,.channel-btn.disabled:hover,.channel-btn[disabled].focus,.channel-btn[disabled]:focus,.channel-btn[disabled]:hover,fieldset[disabled] .channel-btn.focus,fieldset[disabled] .channel-btn:focus,fieldset[disabled] .channel-btn:hover {
        background-color:transparent;
        border-color:#ccc;
      }
      .channel-btn .badge {
        color:#fff;
        background-color:transparent;
      }

      .nav-tabs > li, .nav-pills > li {
        float:none;
        display:inline-block;
        *display:inline; /* ie7 fix */
        zoom:1; /* hasLayout ie7 trigger */
       }

      .nav-pills .nav-tabs{
        text-align:center;
      }
    </style>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo base_url();?>main/home">Breakthrough Foundry 2</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
        <ul class="nav navbar-nav">
        	<?php
        		if ($page == 'project_list') {
        			echo '<li class="active"><a href="'.base_url().'main/project_list">Projects</a></li>';
        		} else {
        			echo '<li><a href="'.base_url().'main/project_list">Projects</a></li>';
        		}
            if ($in_project)
            {
              $user_info = $this->ion_auth->user()->row();
              $project_info = $this->m_btf2_projects->get_project_info($project_id);?>
              <li>
              <div class="dropdown">
                <button class="btn channel-btn dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                  <?php echo $project_info['Name'];?>
                  <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1" id="ProjectPanel">
                  <?php echo '<li><a href="'.base_url().'main/chat_list/'.$project_info['PK_Project_Id'].'/-general">Project Chat</a></li>';
                        echo '<li><a href="'.base_url().'main/project_schedule/'.$project_info['PK_Project_Id'].'">Project Schedule</a></li>';
                        echo '<li><a href="'.base_url().'main/tasks/'.$project_info['PK_Project_Id'].'">Task Management</a></li>';
                        echo '<li><a href="'.base_url().'main/team_list/'.$project_info['PK_Project_Id'].'">View Project Team</a></li>';
                        echo '<li><a href="'.base_url().'main/work_list/'.$project_info['PK_Project_Id'].'">Resource Tracker</a></li>';
                        if($this->m_btf2_projects->is_admin($user_info->id, $project_info['PK_Project_Id']))
                        {
                          echo '<li><a href="'.base_url().'main/project_invite_email/'.$project_info['PK_Project_Id'].'">Invite Team Member</a></li>';
                        }?>
                </ul>
              </div>
            </li>
            <?php
            }
            if($this->ion_auth->logged_in())
            {
              echo '<li><a href="'.base_url().'auth/logout">Logout</a></li>';
            } else {
              echo '<li><a href="'.base_url().'main/index">Login</a></li>';
            }
        	  ?>
          </ul>

        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container">
