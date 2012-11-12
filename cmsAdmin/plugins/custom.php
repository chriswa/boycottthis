<?php
/*
Plugin Name: Custom
Description: Custom code for BoycottThis
Required System Plugin: Yes
*/

addFilter('edit_show_field', 'custom_edit_show_field', null, 3);
function custom_edit_show_field($retval, $fieldSchema, $record) {
  global $tableName;
  $fieldName = $fieldSchema['name'];
  
  if ($tableName === 'updates' && $fieldName === 'issue') {
    echo "<tr><td>";
    ?><input type="hidden" name="issue" value="<?php echo coalesce(@$record['issue'], intval(@$_REQUEST['issuesNum'])) ?>"/><?php
    echo "</td><td>";
    echo "</td></tr>\n";
    return false;
  }
  if ($tableName === 'updates' && $fieldName === '') {
    echo "<tr><td>";
    echo "</td><td>";
    echo "</td></tr>\n";
    return false;
  }
  
  return true;
}

addFilter('record_preedit', 'custom_record_preedit', null, 2);
function custom_record_preedit($tableName, $recordNum) {
  global $RECORD;
  if ($tableName === 'updates') {
    $issueNum = coalesce(@$RECORD['issue'], @$_REQUEST['issuesNum']);
    $issue = mysql_get('issues', $issueNum);
    $RECORD['issue_summary']  = $issue['summary'];
    $RECORD['issue_resolved'] = $issue['resolved'];
  }
}

addAction('record_save_posterrorchecking', 'custom_record_save_posterrorchecking', null, 3);
function custom_record_save_posterrorchecking($tableName, $recordExists, $oldRecord) {
  if ($tableName === 'updates') {
    $issueNum = coalesce(@$oldRecord['issue'], @$_REQUEST['issue']);
    $issue = mysql_get('issues', $issueNum);
    $issueChanges = array(
      'summary'  => @$_REQUEST['issue_summary'],
      'resolved' => @$_REQUEST['issue_resolved'],
    );
    if (@$_REQUEST['issue_resolved'] && !$issue['resolved']) {
      $issueChanges['date_resolved'] = mysql_datetime();
    }
    if (!@$_REQUEST['issue_resolved']) {
      $issueChanges['date_resolved'] = '0000-00-00 00:00:00';
    }
    mysql_update('issues', $issue['num'], null, $issueChanges);
  }
}

function pretty_relative_time($time) { 
  if ($time !== intval($time)) { $time = strtotime($time); } 
  $d = time() - $time; 
  if ($time < strtotime(date('Y-m-d 00:00:00')) - 60*60*24*3) { 
    $format = 'F j'; 
    if (date('Y') !== date('Y', $time)) { 
      $format .= ", Y"; 
    } 
    return date($format, $time); 
  } 
  if ($d >= 60*60*24) { 
    $day = 'Yesterday'; 
    if (date('l', time() - 60*60*24) !== date('l', $time)) { $day = date('l', $time); } 
    return $day . " at " . date('g:ia', $time); 
  } 
  if ($d >= 60*60*2) { return intval($d / (60*60)) . " hours ago"; } 
  if ($d >= 60*60)   { return "about an hour ago"; } 
  if ($d >= 60*2)    { return intval($d / 60) . " minutes ago"; } 
  if ($d >= 60)      { return "about a minute ago"; } 
  if ($d >= 2)       { return intval($d) . " seconds ago"; } 
  return "a few seconds ago"; 
}

?>