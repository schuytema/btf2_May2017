<div class="starter-template">
  <!--h2><?php echo 'You have met an unfortunate fate...'; ?></h2-->
  <img src="<?php echo base_url(); ?>img/home_logo_2.png"/>
  <h2>Upgrade Account</h2>
  <?php
  if ($notify)
  {
    echo "Thank you for wanting to enhance your Breakthrough Foundry experience!</br>Your request has been received and we will notify you once we launch this exciting new online collaboration platform.";
  } else {?>
  <p>
    Thank you for wanting to enhance your Breakthrough Foundry experience! We are currently in Beta and are developing the powerful new features of our advanced subscription levels. Please let us know below if you’d like to be notified when we full launch this exciting new online collaboration platform.
  </p>
  <form action="<?php echo base_url(); ?>main/add_email_for_upgrade_notification" method="post">
    <div class="form-group">
      <label style="text-align=:left" for="email">Please enter your email:</label>
      <input type="text" class="form-control" id="email" name="email" placeholder="myemail@example.com" />
    </div>
    <button type="submit" class="btn btn-default">Notify Me!</button>
  </form><?php
  }?>

  <!--You've run out of project slots! Although currently getting more is as easy as pressing a button...</br>
  <?php echo $message;?>
  </br>
  <hr-->

  <!--a class="btn btn-primary" href="<?php echo base_url().'main/submit_upgrade/'.$user_info->id;?>">Give me the power!</a-->
  <!--div id="upgrade-options" style="width:100%; border: 0px solid #333; padding:3%; min-height: 350px; margin-left: autho; margin-right: auto;">
    <div id="upgrade-projects" style="width: 31%; min-width:250px; display:inline-block; height:300px; border: 1px solid #333; margin-left: 1; margin-right: 1%; margin-bottom:20px; padding-top:2%">
      <h3>Unlimited Projects</h3>
      </br>
      <span style="font-size:60px; color: #84e031">$50.00</span>
      </br></br>
      <a class="btn btn-primary" href="#<?php //echo base_url().'main/submit_upgrade/'.$user_info->id.'/projects';?>" style="display:inline-block;">Give me the power!</a>
    </div>
    <div id="upgrade-groups" style="width: 31%; min-width:250px; display:inline-block; min-height:300px; border: 1px solid #333; margin-left: 1%; margin-right: 1%; margin-bottom:20px; padding-top:2%">
      <h3>Groups Upgrade</h3>
      </br>
      <span style="font-size:60px; color: #84e031">$25.00</span>
      </br></br>
      <a class="btn btn-primary" href="#<?php //echo base_url().'main/submit_upgrade/'.$user_info->id.'/groups';?>" style="display:inline-block;">Give me the power!</a>
    </div>
    <div id="upgrade-all" style="width: 31%; min-width:250px; display:inline-block; height:300px; border: 1px solid #333; margin-left: 1%; margin-right: 1; margin-bottom:20px; padding-top:2%">
      <h3>All Upgrade</h3>
      </br>
      <span style="font-size:60px; color: #84e031">$80.00</span>
      </br></br>
      <a class="btn btn-primary" href="#<?php //echo base_url().'main/submit_upgrade/'.$user_info->id.'/all';?>" style="display:inline-block;">Give me the power!</a>
    </div>
  </div-->
</div>
