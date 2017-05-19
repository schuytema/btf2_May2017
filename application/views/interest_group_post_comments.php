<div style="border-top:1px dotted #BFBFBF;margin:5px; padding-top:10px">
  <?php
  $comments = $this->m_btf2_interest_groups->get_feed_comments($feed_id);
  if($comments!=null)
  {
    foreach ($comments as $comment)
    {
      $commenter = $this->m_btf2_users->get_user_info_from_id($comment['FK_User_Id']);?>
      <div style="display:inline-block; width:100%; margin-left:auto; margin-right:auto; min-height:40px; text-align:left;">
        <span style="display:inline-block;width:48%;text-align:left;">
          <a href="<?php echo base_url().'main/user_profile/'.$comment['FK_User_Id'];?>" style="display:inline-block">
          <?php echo $commenter['first_name'].' '.$commenter['last_name'];?>
          </a>
        </span><?php
        if($comment['FK_User_Id'] == $user_info->id || $is_admin)
        {?>
          <div style="display:inline-block;float:right;text-align:right;margin-right:0px;margin-left:auto;">
            <a onclick="return confirm('Are you sure you want to delete this comment?');" href="<?php echo base_url();?>main/delete_feed_comment/<?php echo $comment['PK_Comment_Id'];?>" type="button" style="color:#333333;">
              <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
            </a>
          </div><?php
        } else {
          echo '<span class="glyphicon glyphicon-remove" aria-hidden="true" style="color:#FFFFFF;display:inline-block;float:right;text-align:right;margin-right:0px;margin-left:auto;"></span>';
        }?>
        <span style="display:inline-block;float:right;text-align:right;color:#AAAAAA;font-size:12px;">
          <i><?php
          $time = new DateTime($comment['Comment_Date']);
          $time->setTimezone($time_zone);
          echo $time->format('F d, Y') . ' at ' . $time->format('H:i');
          ?></i>
          &nbsp;
        </span>
        <p style="width:95%">
          <?php echo nl2br($this->m_btf2_interest_groups->make_clickable($comment['Content']));?>
        </p>
        <hr size="2">
      </div><?php
    }
  }
  ?>
</div>
