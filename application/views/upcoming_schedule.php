<div class="panel panel-default" id="panel<?php echo $u_id;?>u">
	<div class="panel-heading">
		<h4 class="panel-title">
			<a data-toggle="collapse" data-target="#collapse<?php echo $u_id;?>u" href="#collapse<?php echo $u_id?>u" class="collapsed">
				<?php echo $month;?>
			</a>
		</h4>
	</div>
	<div id="collapse<?php echo $u_id;?>u" class="panel-collapse collapse<?php if($u_id == 1){echo " in";}?>">
		<div class="panel-body">
			<table class="table table-striped">
				<tr>
					<thead>
						<th width=10%></th>
						<th width=45%>Event</th>
						<th width=45% style="text-align:right">Date/Time</th>
					</thead>
				</tr>
				<?php
				foreach ($upcoming_event[$month] as $event)
				{
					if (substr($event['Description'], 0, 5) == "Event")
					{
						echo '<tr>';
						$description = substr($event['Description'], 7);
						echo '<td>';
						if($this->m_btf2_projects->is_admin($user_info->id, $project_info['PK_Project_Id']) || $user_info->id == $event['FK_User_Id'])
						{
							$confirm = 'Are you sure you wish to delete ' . $event['Event_Name'] . '?';?>
							<a onclick="return confirm('<?php echo $confirm;?>');" href="<?php echo base_url();?>main/delete_event/<?php echo $project_info['PK_Project_Id'].'/'.$event['Event_Id'];?>" type="button" style="float:left" class="btn btn-default btn-xs"><?php
								echo '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>';
							echo '</a>';
						}
					} else {
						echo '<tr style="background-color:#BCED91">';
						$description = $event['Description'];
						echo '<td>';
					}
					echo '</td>';
						$date = substr($this->m_btf2_chat->convert_date($event['Event_Date']), 0, 3).' '.substr($event['Event_Date'], 8, 2).', '.substr($event['Event_Date'], 0, 4);
						$time = new DateTime($event['Event_Date']);
						$time->setTimezone($time_zone);
						echo '<td align="left">';
							echo $event['Event_Name'];
							if($description != "")
              {
			          echo '<p style="text-align:left; font-style:italic; font-size:x-small; color:#999999">';
								  echo '<a data-toggle="modal" data-target="#myModal'.$u_m.'">details</a>';
							  echo '</p>';
              }
						echo '</td>';
						echo '<td align="right">';
							echo $date;
							echo '<p style="text-align:right; font-style:italic; color:#999999">';
								echo $time->format('H:i');
							echo '</p>';
						echo '</td>';
					echo '</tr>';
					?>
					<div id="myModal<?php echo $u_m; ?>" class="modal fade" role="dialog">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal">&times;</button>
									<h4 class="modal-title"><?php echo $event['Event_Name']?></h4>
								</div>
								<div class="modal-body">
									<p style="text-align:center"><i><?php echo $date.' '.$time;?></i></p>
									</br>
									<p style="text-align:left"><?php echo nl2br($description);?></p>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								</div>
							</div>
						</div>
					</div>
					<?php
					$u_m++;
				} // end foreach event
				?>
			</table>
		</div> <!-- End .panel-body -->
	</div> <!-- End #collapse(id) -->
</div> <!-- End #panel(id) -->
