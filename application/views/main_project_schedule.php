<?php
	$user_info = $this->ion_auth->user()->row();
	$user_name = $user_info->first_name.' '.$user_info->last_name;
	$project_info = $this->m_btf2_projects->get_project_info($project_id);
	$upcoming_events = $this->m_btf2_tasks->get_upcoming_events(date('Y-m-d H:i:s'), $user_name, $project_id);
	$past_events = $this->m_btf2_tasks->get_past_events(date('Y-m-d H:i:s'), $user_name, $project_id);
	$u_id = 1; // upcoming collapse id for each month
	$u_m = 1; // upcoming modal id for each event
	$index = '0000-00';
	$month = "";
	$upcoming_months = array();
	$upcoming_event = array();
	foreach ($upcoming_events as $event)
	{
		if ($index < substr($event['Event_Date'], 0, 7))
		{
			$index = substr($event['Event_Date'], 0, 7);
			$month = $this->m_btf2_chat->convert_date($event['Event_Date'])." ".substr($event['Event_Date'], 0, 4);
			$upcoming_months[] = $month;
		}
		$upcoming_event[$month][] = $event;
	}
	$p_id = 1; // past collapse id for each month
	$p_m = 1; // past modal id for each event
	$index = '9999-99';
	$month = "";
	$past_months = array();
	$past_event = array();
	foreach ($past_events as $event)
	{
		if ($index > substr($event['Event_Date'], 0, 7))
		{
			$index = substr($event['Event_Date'], 0, 7);
			$month = $this->m_btf2_chat->convert_date($event['Event_Date'])." ".substr($event['Event_Date'], 0, 4);
			$past_months[] = $month;
		}
		$past_event[$month][] = $event;
	}
?>
      <div class="starter-template">
        <a href="<?php echo base_url().'main/project_home/'.$project_id;?>" style="color:#333">
					<h1>
						<?php echo $project_info['Name'] . ' Schedule';?>
					</h1>
				</a>
				<?php include 'project_buttons.php'; ?>
				<h4>Project events and tasks</h4>
				<div class="container" style="width:100%">
				  <ul class="nav nav-tabs">
				    <li class="active"><a data-toggle="tab" href="#upcoming">Coming up</a></li>
				    <li><a data-toggle="tab" href="#past">Past events</a></li>
				  </ul>
				  <div class="tab-content">
				    <div id="upcoming" class="tab-pane fade in active">
							</br>
				      <a role="button" class="btn btn-primary" href="<?php echo base_url().'main/create_event/'.$project_id;?>">
								Create event
							</a>
							</br></br>
							<div class="panel-group" id="accordion1">
								<?php
								if(!empty($upcoming_months))
								{
									foreach ($upcoming_months as $month)
									{
										include 'upcoming_schedule.php'; // php file to list all events in $month
										$u_id++;
									} // End foreach upcoming month
								} else {
									echo 'No upcoming events/tasks for this project.';
								}
								?>
							</div> <!-- End #accordion -->
						</div> <!-- End #upcoming -->
				    <div id="past" class="tab-pane fade">
							</br>
							<div class="panel-group" id="accordion">
								<?php
								if(!empty($past_months))
								{
									foreach ($past_months as $month)
									{
										include 'past_schedule.php'; // php file to list all events in $month
										$p_id++;
									} // End foreach past month
								} else {
									echo 'No past events/tasks for this project yet.';
								}
								?>
							</div> <!-- End #accordion -->
				    </div> <!-- End #past -->
					</div> <!-- End .tab-content -->
				</div> <!-- End .container -->
      </div>
