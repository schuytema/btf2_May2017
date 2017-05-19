<?php
	$user_info = $this->ion_auth->user()->row();
	$project_info = $this->m_btf2_projects->get_project_info($project_id);
?>
	<script>
	function disableText()
	{
		var box = document.getElementById("checkbox");
		var sel = document.getElementById("sel1");
		if(box.checked)
		{
			sel.disabled = false;
			sel.style.backgroundColor = "#FFFFFF";
		} else {
			sel.disabled = true;
			sel.style.backgroundColor = "#EEEEEE";
		}
	}
	</script>

			<div>
        <h1>Create Event</h1>
				<h3><?php echo $project_info['Name'];?></h3>
        <?php
        echo '<form action="'.base_url().'main/add_event" method="post">';
        ?>
					<div class="form-group">
						<label for="Event_Name">Event Name</label>
	          <input type='text' class="form-control" name="Event_Name" id="Event_Name"/>
	        </div>
	        <div class="form-group">
	          <label for="Description">Description</label>
	          <textarea class="form-control" rows="5" id="Description" name="Description"></textarea>
	        </div>
	        <div class="form-group">
						<label for="Event_Date">Event Date</label>
	        	<div class='input-group date' id='datetimepicker1'>
	          	<input type='text' class="form-control" name="Event_Date" id="Event_Date"/>
	      			<span class="input-group-addon">
	          		<span class="glyphicon glyphicon-calendar"></span>
	        		</span>
	          </div>
	        </div>
					<div class="form-group">
						<label for="Event_Date">Event Time</label>
		      	<input type="text" class="form-control" pattern="([0-1]{1}[0-9]{1}|2[0-3]{1}):[0-5]{1}[0-9]{1}" maxlength="5" placeholder="hh:mm" name="Event_Time" id="Event_Time"/>
					</div>
					<div class="form-group">
						<label for="sel1">Recurring event?</label>
						<input type="checkbox" id="checkbox" name="checkbox" value="checkbox" onclick="disableText();">
			      <select name="sel1" id="sel1" style="background-color:#EEEEEE" disabled>
			        <option>2</option>
			        <option>3</option>
			        <option>4</option>
			        <option>5</option>
							<option>6</option>
			        <option>7</option>
			        <option>8</option>
			        <option>9</option>
							<option>10</option>
			      </select>
						Weeks
					</div>
	        <button type="submit" id="submit" class="btn btn-primary">Create Event</button>
					<input type="hidden" name="FK_Project_Id" value="<?php echo $project_id; ?>">
					<input type="hidden" name="FK_User_Id" value="<?php echo $user_info->id; ?>">
	      </form>
			</div>
