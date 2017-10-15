
</br>
<div class="starter-tamplate">
  <?php
  if($message == 1)
  {?>
    <p style="color:red">You have exhausted your projects. If you would like to create a new project please upgrade your account first.</p><?php
  }
  ?>
  <form action="<?php echo base_url();?>main/upgrade_submit/'<?php echo $user_info->id.'/'.$code;?>" method="post">
  </form>
</div>
