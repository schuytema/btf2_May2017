<?php
	$user_info = $this->ion_auth->user()->row();
?>

<h1> You are logged in! </h1>

      <div class="starter-template">
        <h1>Your Projects</h1>
        <ul class="nav nav-list">
        <?php
        	$projects = $this->m_btf2_projects->get_project_menu($user_info->id);

        	foreach($projects as $project)
        	{
        ?>
        	<li>
            	<a href="<?php echo base_url(); ?>main/project_home/<?php echo $project['project_id']; ?>">

                	<?php
                		if ($this->m_btf2_projects->is_admin($user_info->id, $project['project_id']))
                		{
                			echo '<b>'.$project['Name'].' (admin)</b>';
                		} else {
        					echo '<b>'.$project['Name'].'</b>';
        				}
        			?>
                </a>

            </li>
        <?php
        	}
        ?>
        </ul>
      </div>
<a class="btn btn-primary" href="<?php echo base_url(); ?>auth/logout" role="button">logout</a>
