<?php
  // 
  require_once "../../../lib/viewer_functions.php";
  
  // TEST 1 - recordset, one field deep
  //$records = mysql_select('every_field_multi', 'TRUE');
  //dbx_inject($records, 'createdBy');
  //showme($records);
  
  // TEST 2 - single record, one field deep
  //$record = array_value(mysql_select('every_field_multi'), 0);
  //dbx_inject($record, 'createdBy', 'photo');
  //showme($record);
  
  // TEST 3 - recordset, multiple fields deep, injection overlap
  $records = mysql_select('every_field_multi', 'TRUE');
  //dbx_showRelationships($records[0]);
  dbx_inject($records, 'createdBy', 'photo');
  dbx_inject($records, 'createdBy', 'attachments');
  dbx_inject($records, 'self_multi_list', 'createdBy');
  showme('=== TEST 2 RESULTS ===');
  showme($records);
  
?>
