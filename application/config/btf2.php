<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['work_units'] = array(
    'hours'=>"hours",
    'cash'=>"cash",
    'in kind'=>"in kind",
    'tangible property'=>"tangible property",
    'other'=>"other"
);

$config['status'] = array(
    'logged'=>"logged",
    'approved'=>"approved",
    'denied'=>"denied"
);

$config['project_status'] = array(
    'active'=>"active",
    'suspended'=>"suspended",
    'completed'=>"completed"
);

$config['task_status'] = array(
  'Not Started'=>"Not Started",
  'Incomplete (Stalled)'=>"Incomplete",
  'In Progress'=>"In Progress",
  'Nearing Completion'=>"Nearing Completion",
  'Complete'=>"Complete"
);

$config['task_sort_filters'] = array(
  'Date (ascending)' => "Date (ascending)",
  'Date (descending)' => "Date (descending)",
  'Tasks assigned to me' => "Tasks assigned to me",
  'My requested tasks' => "My requested tasks",
  'Completion status' => "Completion status"
);

$config['btf_max_interests'] = 10;
$config['btf_max_skills'] = 10;
$config['UL0_projects'] = 1;
$config['UL1_projects'] = 5;
$config['UL2_projects'] = 999;

$config['btf_mug_path'] = 'user_mugs/';
