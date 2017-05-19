<style>
.bs-callout {
    padding: 20px;
    margin: 20px 0;
    border: 1px solid #eee;
    border-left-width: 5px;
    border-radius: 3px;
    line-height: 300%;
}
.bs-callout h4 {
    margin-top: 0;
    margin-bottom: 5px;
}
.bs-callout p:last-child {
    margin-bottom: 0;
}
.bs-callout code {
    border-radius: 3px;
}
.bs-callout+.bs-callout {
    margin-top: -5px;
}
.bs-callout-warning {
    border-left-color: #f0ad4e;
}
.bs-callout-warning h4 {
    color: #f0ad4e;
}
.bs-callout-success {
    border-left-color: #5cb85c;
}
.bs-callout-success h4 {
    color: #5cb85c;
}
</style>
<br>
	<div class="panel panel-primary">
	  <div class="panel-heading">
	    <h3 class="panel-title">
	    <?php
	    	$name = $user_info->first_name.' '.$user_info->last_name;
	    	echo $name;
        echo '<span title="Message this user">
              <form action="' . base_url() . 'main/message_user/' . $user_info->id . '" method="POST" style="display:inline-block;">
                <button type="submit" class="glyphicon glyphicon-envelope" style="background-color: transparent; border: 0px;">
                </button>
                <input type="hidden" name="PM_Target" value="' . $user_info->id . '">
              </form>
              </span>';
	    ?>
	    </h3>
	  </div>
	  <div class="panel-body">
	  	<img src="<?php echo base_url(); ?>user_mugs/<?php echo $user_info->mug; ?>" class="img-circle" alt="<?php echo $name; ?>" style="border: 3px solid #d3d3d3;" height="150" width="150">
	  	<p>
	    	<em>Last login: <?php echo $user_info->last_login; ?></em>
	    </p>
	    <p>
	    	<span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
	    	<?php echo $user_info->description; ?>
	    </p>
	    <p>
	    	<span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
	    	<?php echo $user_info->location; ?>
	    </p>
	    <div class="bs-callout bs-callout-warning">
	    	<h4>Interests</h4>
	    	<?php
	    	foreach ($interests as $interest)
	    	{
	    		echo '<button type="button" class="btn btn-warning btn-sm">'.ucwords($interest['Tag_Text']).'</button> ';
	    	}
	    	?>
	    </div>
	    <div class="bs-callout bs-callout-success">
	    	<h4>Skills</h4>
	    	<?php
	    	foreach ($skills as $skill)
	    	{
	    		echo '<button type="button" class="btn btn-success btn-sm">'.ucwords($skill['Tag_Text']).'</button> ';
	    	}
	    	?>
	    </div>
	  </div>
	</div>
