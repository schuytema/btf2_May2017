<?php
	$user_info = $this->ion_auth->user()->row();
	$project_info = $this->m_btf2_projects->get_project_info($project_id);
	$uri = "$_SERVER[REQUEST_URI]"; // get the uri to retrieve the channel name
	$channel_name = substr("$_SERVER[REQUEST_URI]", strpos($uri, '-') + 1);
	if (isset($_SESSION['time']))
	{
		$time_zone = new DateTimeZone('Etc/' . str_replace(' ', '', str_replace('!', '-', str_replace('-', '+', str_replace('+', '!', $_SESSION['time'])))));
	}
	else {
		session_start();
		$time_zone = new DateTimeZone('Etc/' . str_replace(' ', '', str_replace('!', '-', str_replace('-', '+', str_replace('+', '!', $_SESSION['time'])))));
		/*$query = $this->db->query("SELECT last_login FROM users WHERE id = $user_info->id");
		if ($query->num_rows())
		{
			$results = $query->result_array();
			foreach($results AS $row)
			{
				$last_login = $row['last_login'];
			}
		}
		$_SESSION['last_login'] = $last_login;
		$gmt = gmdate("Y M D H:i:s");
		$time_zone = new DateTimeZone('Etc/' . str_replace(' ', '', str_replace('!', '-', str_replace('-', '+', str_replace('+', '!', $_SESSION['last_login'])))));*/
	}
	if (substr($channel_name, 0, 1) == '-') // private chats are channels preceeded by another '-'
	{
		$channel = substr($channel_name, 1); // get the private channel name without the '-'
		$user = $this->m_btf2_users->get_user_info_from_id($channel);
		$user_first_name = $user['first_name'];
		$channel_id = $this->db->query("SELECT id FROM users WHERE first_name = '$user_first_name'");
		$channel = array();
		if ($channel_id->num_rows())
		{
			$results = $channel_id->result_array();
			foreach($results AS $row)
			{
				$channel[] = $row;
			}
		}
		// get all previous messages in this private chat
		$chat_log = $this->m_btf2_chat->get_existing_private_chat($project_id, $channel[0]['id'], $user_info->id);
	} else { // otherwise it is a public channel. Get all previous messages in this channel
		$chat_log = $this->m_btf2_chat->get_existing_chat($project_id, $channel_name);
	}
	$_SESSION['msg'] = NULL;
?>

			<div class="starter-template" onload="scrollBottom()">
				<a href="<?php echo base_url().'main/project_home/'.$project_id;?>" style="color:#333">
					<h1>
						<?php echo $project_info['Name'] . ' Chat Log';?>
					</h1> <!-- Start the page with the project's name -->
				</a>
				<?php
				include 'project_buttons.php';
				if (substr($channel_name, 0, 1) != '-') // if it is a public channel
				{
					echo '<h4>Channel: ' . $channel_name . '</h4>';
				} else { // otherwise it is a private chat
					$user_full_name = $user['first_name'] . ' ' . $user['last_name'];
					echo '<h4>Chat with ' . $user_full_name . '</h4>';
				}
				?>
				<div style="max-height:40vh; overflow-x:hidden; overflow-y:scroll" id="chat-box">
					<div id="chat-messages">
						<?php
						if ($chat_log != NULL) // Check to see that the database actually has messages saved for this project/channel.
						{
							foreach ($chat_log as $message) // Parse through each message one at a time to display them properly
							{
								$member_info = $this->m_btf2_users->get_user_info_from_id($message['FK_User_Id']); // Pulls the name of the user who submitted the message.
								?>
								<p align="left"> <!-- Message header: [Sender][date] -->
									<span style="display:inline-block; float:left">
										<font size="2"> <!-- Display the name obtained from get_user_info_from_id(id) -->
											<b>
												<?php if ($member_info['first_name'] != $user_info->first_name)
												{?>
														<a style="color:#000000" href="<?php echo base_url().'main/chat_list/'.$project_info['PK_Project_Id'].'/--'.$message['FK_User_Id']?>"><?php echo $member_info['first_name'] . ' ' . $member_info['last_name'] . ' ';?></a><?php
												}
												else {
													echo $member_info['first_name'] . ' ' . $member_info['last_name'] . ' ';
												}?>
											</b>
										</font> <!-- Display the time the message was submitted -->
										<font size="1" color="gray">
											<?php
											$time = new DateTime($message['Create_Date']);
											$time->setTimezone($time_zone);
											echo $time->format('F d, Y') . ' at ' . $time->format('H:i');
											?>
										</font>
									</span>
									<?php // Display delete button next to messages by this user, or all messages if this user is admin
									$is_admin = $this->m_btf2_projects->is_admin($user_info->id, $project_info['PK_Project_Id']);
									if ($message['FK_User_Id'] == $user_info->id || $is_admin)
									{
										if(substr($channel_name, 0, 1)=='-'){
												$channel = 'Private'.substr($channel_name, 1);
										} else {
												$channel = $channel_name;
										}
										$confirm = 'Are you sure you want to delete this message?';?>
										<a onclick="return confirm('<?php echo $confirm;?>');" href="<?php echo base_url();?>main/delete_message/<?php echo $message['PK_Chat_Message_Id'].'/'.$channel;?>" type="button" style="display:inline-block; float:right" class="btn btn-default btn-xs"><?php
											echo '<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>';
										echo '</a>';
									}
									?>
								</p> <!-- End of message header -->
								<p align="left"> <!-- Message text -->
									<br/>
										<?php echo $message['Message'];	?>
									<hr style="margin:-6px">
								</p>
								<?php
							} // end foreach ($chat_log as $message)
						} // end if ($chat_log != NULL)
						else // If no messages are found in the database, display this message.
						{
							echo 'No new messages.';
						}
						?>
					</div> <!-- close #chat-messages-->
				</div> <!-- close #chat-box -->
				<?php
				// **Begin Bootstrap section** Instructs bootstrap to use main/add_message/ to use the info provided in the form.
				echo '<form id="form" action="'.base_url().'main/add_message/'.$project_id.'" method="post">';
				?>
				<!-- This is the input message textbox. It is stored in a 'Message' value used later. -->
				<div class="form-group" id="message-input">
					<input type="text" class="form-control" style="margin-top:15px" placeholder="Add a message..." id="Message" name="Message" autocomplete="off" value="<?php echo $_SESSION['msg'];?>" autofocus>
				</div>
				<!-- These variables are parts of the form that the user cannot interact with; they store the user ID, project ID, and channel name respectively. -->
				<input type="hidden" name="FK_User_Id" value="<?php echo set_value('FK_User_Id', (isset($new_message)) ? $new_message['FK_User_Id'] : $user_info->id); ?>">
				<input type="hidden" name="FK_Project_Id" value="<?php echo $project_id; ?>">
				<input type="hidden" name="Channel" value="<?php echo $channel_name; ?>">
			</div>

				<script>
					function scrollBottom()
		      {
		        $('#chat-box').animate({ scrollTop: 9999999999999999 }, 'fast');
		        return false;
		      }

					var int = setInterval(function()
					{
						$('#chat-messages').load(document.URL +  ' #chat-messages');
						$('#chat-box').animate({ scrollTop: 9999999999999999 }, 'fast');
						return false;
					}, 5000);

					$('#chat-box').scroll(function(){
        		clearInterval(int);
					});
				</script>
