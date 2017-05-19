<?php
$project_info = $this->m_btf2_projects->get_project_info($project_id);
$project_folder = 'project_'.$project_id.'/';
$displayed_files = Array();
if (S3::getBucket('btf2_project_'.$project_id) == FALSE)
{
  S3::putBucket('btf2_project_'.$project_id);
}
$files = S3::getBucket('btf2_project_'.$project_id);
$user_info = $this->ion_auth->user()->row();
if (isset($_POST['searchBar']) && $_POST['searchBar'] != NULL)
{
  foreach ($files as $object)
  {
    $name = '*' . strtolower($object['name']);
    if (strpos($name, strtolower($_POST['searchBar'])))
    {
      $displayed_files[] = $object;
    }
  }
}
else {
  $displayed_files = $files;
}
function sortByOrder($a, $b) {
  return $b['time'] - $a['time'];
}

?>
<div class="starter-template">
  <a href="<?php echo base_url().'main/project_home/'.$project_id;?>" style="color:#333">
    <h1><?php echo $project_info['Name'] . ' Files'; ?></h1>
  </a>
  <?php include 'project_buttons.php'; ?>
  <form id="bc" name="bc" action="<?php echo base_url();?>s3test/addObject" method="post" enctype="multipart/form-data">
  	<h3 id="uploadText">Upload File</h3>
    <font style="color:#339933"><?php echo $msg;?></font>
    <div align="center">
      <label style="display:block" id="selectText">Select File</label>
      <input style="display:inline-block; background:#F0F0F0" type="file" id="userFile" name="userFile" onchange="getFileName(this.files)" required="required">
      <input style="display:inline-block" type="submit" name="submit" id="submit" value="Upload" onclick="return confirm(CreateConfirm());">
      <input type="hidden" name="FK_Project_Id" value="<?php echo $project_id;?>">
    </div>
  </form>
</div>
<form id="search" name="search" action="<?php echo base_url();?>s3test/files/<?php echo $project_id;?>" method="post">
<div class="row">
  <label for="searchBar">Search by name:</label>
  <div class="input-group">
    <input class="form-control" id="searchBar" name="searchBar"></input>
    <span class="input-group-btn">
      <button class="btn btn-default" type="submit">Go!</button>
    </span>
  </div>
</div>
  <input type="hidden" name="FK_Project_Id" value="<?php echo $project_id;?>">
</form>
  <?php
  usort($files, 'sortByOrder');
  echo '<div style="max-height:50vh;overflow-y:scroll">';
	echo '<table class="table table-striped">';
	echo '<thead>';
	echo '<tr>';
  echo '<th width="10%"></th>';
	echo '<th width="50%">Name</th>';
	echo '<th width="20%">Uploaded</th>';
	echo '<th width="20%">Size</th>';
	echo '</tr>';
	echo '</thead>';
  foreach($displayed_files as $object) {
      echo '<tr>';
      if ($this->m_btf2_projects->is_admin($user_info->id, $project_info['PK_Project_Id']))
      {
      $confirm = 'Are you sure you wish to delete ' . $object['name'] . '?';?>
      <td>
      <a onclick="return confirm('<?php echo $confirm;?>');" href="<?php echo base_url();?>s3test/delete/<?php echo $project_id . '/' . $object['name'];?>" type="button" style="float:left" class="btn btn-default btn-xs"><?php
        echo '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>';
      echo '</a>';
      echo '</td>';
      }
      echo '<td>';?>
      <a href="https://s3.amazonaws.com/btf2_project_<?php echo $project_id;?>/<?php echo $object['name'];?>" target="_blank"><?php echo $object['name'];?></a>
      <?php echo '</td>';
      echo '<td>';
      echo date('D, M d, Y', $object['time']);
      echo '</td>';
      echo '<td>';
      if ($object['size'] > 1000 && $object['size'] < 1000000)
      {
        $size = round(intval($object['size']) / 1000, 2);
        echo $size . ' KB';
      }
      elseif ($object['size'] > 1000000){
        $size = round(intval($object['size']) / 1000000, 2);
        echo $size . ' MB';
      }
      else {
        echo $object['size'] . ' B';
      }
      echo '</td>';
      echo '</tr>';
  }
  echo '</table>';
  echo '</div>';?>

  <script>
		function check_width(){
      var elem1 = document.getElementById("uploadText");
			var elem2 = document.getElementById("selectText");
      var elem3 = document.getElementById("userFile");
      var elem4 = document.getElementById("submit");
			if(screen.width <= 500) {
				elem2.remove();
        elem3.remove();
        elem4.remove();
        $("h3").append("</br><h5><b>Sorry! For compatibility reasons file uploading is only supported on computers and laptops.</b></h5>");
			}
		}
		check_width();

	getFileName = function(e){
		//alert(e[0].name + ' : ' + e[0].size);
		if($('#filesize').val() < e[0].size){
			alert('File size is high');
		}
	}

  function CreateConfirm(){
    var file = document.getElementById("userFile");
    var SlashLoc = file.value.indexOf("fakepath")
    var confirm = 'Are you sure you wish to upload ' + file.value.substring(SlashLoc+9) + '? (You will need to contact a project admin to remove it!)';
    return confirm;
  }
</script>
