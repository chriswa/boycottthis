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
  if (($tableName === 'issues' || $tableName === 'updates') && $fieldName === 'links') {
    echo "<tr><td>Links</td><td>";
    custom_showLinksField($record['links']);
    echo "</td></tr>\n";
    return false;
  }
  
  return true;
}

function custom_showLinksField($value) {
  $rows = coalesce(json_decode($value, true), array());
  echo '<table id="linkTable">' . "\n";
  echo "<thead><tr><td>URL</td><td>Title</td></tr></thead>";
  echo '<tr id="linkTmpl" style="display: none;">' . _custom_showLinksField_row() . "</tr>\n"; // clone target
  foreach ($rows as $row) {
    echo '<tr>' . _custom_showLinksField_row($row['title'], $row['url']) . "</tr>\n";
  }
  echo "</table>\n";
  ?>
    <script>
      $(function() {
        var addNewRowIfRequired = function() {
          if ($('#linkTable tr:visible:last input[value=""]').length < 2) {
            $('#linkTmpl').clone().show().appendTo('#linkTable');
          }
          $('#linkTable button').show();
          $('#linkTable tr:last button').hide();
        }
        addNewRowIfRequired();
        $('#linkTable button').live('click', function() { $(this).closest('tr').remove(); addNewRowIfRequired(); return false; });
        $('#linkTable input').live('change', addNewRowIfRequired);
      });
    </script>
  <?php
}
function _custom_showLinksField_row($title = '', $url = '') {
  return '<td><input name="links_url[]" class="text-input" style="width: 200px;" placeholder="http://example.com/" value="' . htmlspecialchars($url) . '"/></td>'
  . '<td><input name="links_title[]" class="text-input" style="width: 200px;" placeholder="Link Text (optional)" value="' . htmlspecialchars($title) . '"/></td>'
  . '<td><button>x</button></td>';
}

addAction('record_presave', 'custom_record_presave', null, 3);
function custom_record_presave($tableName, $isNewRecord, $oldRecord) {
  if ($tableName === 'issues' || $tableName === 'updates') {
    $links  = array();
    $urls   = $_REQUEST['links_url'];
    $titles = $_REQUEST['links_title'];
    for ($i = 1; $i < sizeof($urls) - 1; $i++) {
      $url = $urls[$i];
      if (!preg_match('#^\w+://#', $url)) { $url = 'http://' . $url; }
      $links[] = array( 'url' => $url, 'title' => $titles[$i] );
    }
    $_REQUEST['links'] = json_encode($links);
  }
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