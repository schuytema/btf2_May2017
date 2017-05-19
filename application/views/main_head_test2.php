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
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false">
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
        		  $project_info = $this->m_btf2_projects->get_project_info($project_id);
        		  echo '<li class="dropdown">';
        		  echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">'.$project_info['Name'].' <span class="caret"></span></a>';
        		  echo '<ul class="dropdown-menu">';
        		  echo '<li><a href="'.base_url().'main/chat_list/'.$project_info['PK_Project_Id'].'/-general">Project Chat</a></li>';
        		  echo '<li><a href="'.base_url().'main/project_schedule/'.$project_info['PK_Project_Id'].'">Project Schedule</a></li>';
        		  echo '<li><a href="'.base_url().'main/tasks/'.$project_info['PK_Project_Id'].'">Task Management</a></li>';
        		  echo '<li><a href="'.base_url().'main/team_list/'.$project_info['PK_Project_Id'].'">View Project Team</a></li>';
        		  echo '<li><a href="'.base_url().'main/work_list/'.$project_info['PK_Project_Id'].'">Resource Tracker</a></li>';
		            if($this->m_btf2_projects->is_admin($user_info->id, $project_info['PK_Project_Id']))
		            {
		              echo '<li><a href="'.base_url().'main/project_invite_email/'.$project_info['PK_Project_Id'].'">Invite Team Member</a></li>';
		            }
        		  echo '</ul>';
        		  echo '</li>';
        		  }
           ?>
         </ul>
          
          <?php
          if($this->ion_auth->logged_in())
          {
          	$user_info = $this->ion_auth->user()->row();
          	echo '<ul class="nav navbar-nav navbar-right">';
          	//echo '<ul class="nav navbar-nav pull-right">';
          	echo '<li class="dropdown">';
          	echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">'.$user_info->first_name.' '.$user_info->last_name.' <span class="caret"></span></a>';
          	echo '<ul class="dropdown-menu">';
          	echo '<li><a href="'.base_url().'sandbox/user_profile/'.$user_info->id.'">My Profile</a></li>';
          	echo '<li><a href="'.base_url().'sandbox/user_settings">My Settings</a></li>';
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
