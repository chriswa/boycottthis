<?php
/*
Plugin Name: Custom
Description: Custom code for BoycottThis
Required System Plugin: Yes
*/

// Emails
// ======

// $member = createMember(array('email' => @$_REQUEST['email']));
function createMember($member) {
  $member = array_merge($member, array( 'uniq' => uniqid() ));
  $member = dbx_createAndSave('members', $member);
  sendMemberEmail('WELCOME', $member);
  return $member;
}

function sendMemberEmail($templateId, $member) {
  // TODO
  /*
  $emailHeaders = emailTemplate_loadFromDB(array(
    'template_id'      => $templateId,
    'placeholders'     => $placeholders,
  ));
  $errors       = sendMessage($emailHeaders);
  if ($errors) { alert("Mail Error: $errors"); }
  */
}

// Issues/Organizations Relationship
// =================================

// add jqueryui js and css to page header
addAction('admin_head', 'customAutocomplete_admin_head');
function customAutocomplete_admin_head() {
  list($pluginPath, $pluginUrl) = getPluginPathAndUrl();
  ?>
    <?php if (!@$SETTINGS['advanced']['useDatepicker']): ?>
      <script type="text/javascript" src="3rdParty/jqueryUI/js/jquery-ui-1.8.18.custom.min.js"></script>
      <link   type="text/css"       href="3rdParty/jqueryUI/css/smoothness/jquery-ui-1.8.18.custom.css" rel="stylesheet" />
    <?php endif ?>
  <?php
}

// show autocomplete field on edit page
addFilter('edit_show_field', 'customAutocomplete_edit_show_field', null, 3);
function customAutocomplete_edit_show_field($displayDefault, $fieldSchema, $record) {
  global $tableName;

  $fieldName = $fieldSchema['name'];
  
  if ($tableName !== 'issues' || $fieldName !== 'organization') { return $displayDefault; }

  if ($fieldSchema['type']        != 'list')  { return $displayDefault; }
  if ($fieldSchema['optionsType'] != 'table') { return $displayDefault; }
  $foreignTable      = $fieldSchema['optionsTablename'];
  $foreignValueField = $fieldSchema['optionsValueField'];
  $foreignLabelField = $fieldSchema['optionsLabelField'];
  
  $foreignRecords = mysql_select($foreignTable, true);
  $validLabels    = array_pluck($foreignRecords, $foreignLabelField);
  $initialValue   = dbx_first(array_pluck(array_where($foreignRecords, array($foreignValueField => @$record[$fieldName])), $foreignLabelField));
  
  ?>
    <tr>
     <td><?php echo $fieldSchema['label'] ?></td>
     <td>
      <div class="ui-widget">
        <input class="text-input" id="<?php echo $fieldName ?>" name="<?php echo $fieldName ?>" value="<?php echo htmlspecialchars($initialValue) ?>" />
        <span id="<?php echo $fieldName ?>-will-create" style="color: red; display: none;">(New, will be created)</span>
      </div>
      
      <style>
        #<?php echo $fieldName ?> {
          width: 400px;
          font-family: Arial;
        }
        .ui-widget {
          font-family: Arial;
        }
      </style>
      <script>
      $(function() {
        var validLabels = <?php echo json_encode($validLabels) ?>;
        var field = $( "#<?php echo $fieldName ?>" ).autocomplete({
          source: validLabels
        });
        setInterval(function() {
          var label = field.val();
          $('#<?php echo $fieldName ?>-will-create').toggle(label !== '' && $.inArray(label, validLabels) === -1);
        }, 500);
      });
      </script>
     </td>
    </tr>
  <?php

  return false;
}

// when saving, convert label into value (and create a new record if necessary!)
addAction('record_presave', 'customAutocomplete_record_presave', null, 3);
function customAutocomplete_record_presave($tableName, $isNewRecord, $oldRecord) {
  global $schema;
  if ($tableName !== 'issues') { return; }
  $fieldName = 'organization';
  
  $fieldSchema = $schema[$fieldName];
  $foreignTable      = $fieldSchema['optionsTablename'];
  $foreignValueField = $fieldSchema['optionsValueField'];
  $foreignLabelField = $fieldSchema['optionsLabelField'];
  
  // find record matching the entered label
  $label         = @$_REQUEST[$fieldName];
  $foreignRecord = null;
  if ($label !== '') {
    $criteria = array( $foreignLabelField => $label );
    $foreignRecord = mysql_get($foreignTable, null, $criteria);
  
    // create a new record if one wasn't found!
    if (!$foreignRecord) {
      $foreignRecord = dbx_createAndSave($foreignTable, $criteria);
    }
  }
  
  $_REQUEST[$fieldName] = @$foreignRecord[$foreignValueField];
}



// Issues/Updates Relationship
// ===========================

// replace Issue (list) field on Update edit page with hidden field
addFilter('edit_show_field', 'custom_edit_show_field', null, 3);
function custom_edit_show_field($retval, $fieldSchema, $record) {
  global $tableName;
  $fieldName = $fieldSchema['name'];
  
  if ($retval && $tableName === 'updates' && $fieldName === 'issue') {
    echo "<tr><td>";
    ?><input type="hidden" name="issue" value="<?php echo coalesce(@$record['issue'], intval(@$_REQUEST['issuesNum'])) ?>"/><?php
    echo "</td><td>";
    echo "</td></tr>\n";
    return false;
  }
  
  return $retval;
}

// when editing an Update, show the Issue's summary and resolved fields
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

// when saving an Update, save the Issue's summary and resolved fields
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


// Links Fields
// ============

// 'links' fields are represented by a json array with 'title' and 'url' fields; their UI is a dynamically expanding table

$GLOBALS['LINKS_FIELDS'] = array('issues.links', 'updates.links');

// replace field on edit page
addFilter('edit_show_field', 'customLinks_edit_show_field', null, 3);
function customLinks_edit_show_field($retval, $fieldSchema, $record) {
  global $tableName;
  $fieldName = $fieldSchema['name'];
  
  if ($retval && in_array("$tableName.$fieldName", $GLOBALS['LINKS_FIELDS'])) {
    echo "<tr><td>Links</td><td>";
    customLinks_showLinksField($fieldName, @$record[$fieldName]);
    echo "</td></tr>\n";
    return false;
  }
  
  return $retval;
}
function customLinks_showLinksField($fieldName, $value) {
  $rows = coalesce(json_decode($value, true), array());
  $tableId = $fieldName . 'Table';
  echo '<table id="' . $tableId . '">' . "\n";
  echo "<thead><tr><td>URL</td><td>Title</td></tr></thead>";
  echo '<tr id="linkTmpl" style="display: none;">' . _custom_showLinksField_row($fieldName) . "</tr>\n"; // clone target
  foreach ($rows as $row) {
    echo '<tr>' . _custom_showLinksField_row($fieldName, $row['title'], $row['url']) . "</tr>\n";
  }
  echo "</table>\n";
  ?>
    <script>
      $(function() {
        var addNewRowIfRequired = function() {
          if ($('#<?php echo $tableId ?> tr:visible:last input[value=""]').length < 2) {
            $('#linkTmpl').clone().show().appendTo('#<?php echo $tableId ?>');
          }
          $('#<?php echo $tableId ?> button').show();
          $('#<?php echo $tableId ?> tr:last button').hide();
        }
        addNewRowIfRequired();
        $('#<?php echo $tableId ?> button').live('click', function() { $(this).closest('tr').remove(); addNewRowIfRequired(); return false; });
        $('#<?php echo $tableId ?> input').live('change', addNewRowIfRequired);
      });
    </script>
  <?php
}
function _custom_showLinksField_row($fieldName, $title = '', $url = '') {
  return '<td><input name="'.$fieldName.'_url[]" class="text-input" style="width: 200px;" placeholder="http://example.com/" value="' . htmlspecialchars($url) . '"/></td>'
  . '<td><input name="'.$fieldName.'_title[]" class="text-input" style="width: 200px;" placeholder="Link Text (optional)" value="' . htmlspecialchars($title) . '"/></td>'
  . '<td><button>x</button></td>';
}

// when saving, collect rows up into json
addAction('record_presave', 'custom_record_presave', null, 3);
function custom_record_presave($tableName, $isNewRecord, $oldRecord) {
  foreach ($GLOBALS['LINKS_FIELDS'] as $tableAndFieldName) {
    list($t, $fieldName) = explode('.', $tableAndFieldName);
    if ($t !== $tableName) { continue; }
    
    $links  = array();
    $urls   = $_REQUEST[$fieldName.'_url'];
    $titles = $_REQUEST[$fieldName.'_title'];
    for ($i = 0; $i < sizeof($urls); $i++) {
      $url   = $urls[$i];
      if (!$url) { continue; } // skip empty rows (especially the first, which is the hidden template row, and the last, which is the blank "add-new" row)
      if (!preg_match('#^\w+://#', $url)) { $url = 'http://' . $url; }
      $links[] = array( 'url' => $url, 'title' => $titles[$i] );
    }
    $_REQUEST[$fieldName] = json_encode($links);
  }
}

// showHideDependantFields
// =======================

// For each section and list field, copy this block
// Fields will start hidden if they appear in any "fields to show" list 
$GLOBALS['SHOWHIDE_DEPENDANT_FIELDS_CONFIG']['issues']['resolved'] = array(
// list option   => fields to show
  '0' => array(''),
  '1' => array('date_resolved'),
);

// register hooks
addFilter('edit_buttonsRight', 'showHideDependantFields_edit_filter', null, 3);

// prep hide rules
foreach ($GLOBALS['SHOWHIDE_DEPENDANT_FIELDS_CONFIG'] as $tableName => $tableData) {
  $GLOBALS['SHOWHIDE_DEPENDANT_FIELDS_HIDE'][$tableName] = array();
  foreach ($tableData as $fieldName => $fieldData) {
    foreach ($fieldData as $listValue => $fieldsToShow) {
      foreach ($fieldsToShow as $fieldToShow) {
        $GLOBALS['SHOWHIDE_DEPENDANT_FIELDS_HIDE'][$tableName][$fieldToShow] = true;
      }
    }
  }
}

// add javascript to edit pages
function showHideDependantFields_edit_filter($html, $tablename, $record) {
  $showRules = @$GLOBALS['SHOWHIDE_DEPENDANT_FIELDS_CONFIG'][$tablename];
  if (!$showRules) { return $html; }
  $hideRules = array_keys(@$GLOBALS['SHOWHIDE_DEPENDANT_FIELDS_HIDE'][$tablename]);
  ?>
  <script>
    var showHideDependantFields_showRules = <?php echo json_encode($showRules); ?>;
    var showHideDependantFields_hideRules = <?php echo json_encode($hideRules); ?>;
    $(function(){
      for (var fieldName in showHideDependantFields_showRules) {
        $('SELECT[name="' + fieldName + '"]').change(showHideDependantFields_update);
        $('INPUT[name="' + fieldName + '"]').change(showHideDependantFields_update);
      }
      showHideDependantFields_update();
    });
    function showHideDependantFields_update() {
      // hide all
      
      for (i in showHideDependantFields_hideRules) {
        $('*[name="'  + showHideDependantFields_hideRules[i] +  '"]').closest('TR').hide();
        $('*[name^="' + showHideDependantFields_hideRules[i] + ':"]').closest('TR').hide();
        $('#' + showHideDependantFields_hideRules[i] +     '_iframe').closest('TR').hide();
	
	
      }
      // for each list field, show requested fields
      for (var listFieldName in showHideDependantFields_showRules) {
        var value = '';
        if ($('SELECT[name="' + listFieldName + '"]').length) {
          value = $('SELECT[name="' + listFieldName + '"] OPTION:selected').val();
        }
        else if ($('INPUT[name="' + listFieldName + '"][type=checkbox]').length) {
          var $checkbox = $('INPUT[name="' + listFieldName + '"][type=checkbox]');
          var $hidden   = $('INPUT[name="' + listFieldName + '"][type=hidden]');
          value = $checkbox.is(':checked') ? $checkbox.val() : $hidden.val();
        }
        else if ($('INPUT[name="' + listFieldName + '"]').length) {
          value = $('INPUT[name="' + listFieldName + '"]').val();
        }
        else { /* could not find field! */ }
        var fieldsToShow = showHideDependantFields_showRules[listFieldName][value];
        if (!fieldsToShow) { fieldsToShow = showHideDependantFields_showRules[listFieldName]['_any_value_']; }
	
        if (fieldsToShow) {
          for (i in fieldsToShow) {
            $('*[name="'  + fieldsToShow[i] +  '"]').closest('TR').show();
            $('*[name^="' + fieldsToShow[i] + ':"]').closest('TR').show();
	    $('#' + fieldsToShow[i] +     '_iframe').closest('TR').show();
	    
	    //resize element if iframe is being shown
	    var closestTR = $('#' + fieldsToShow[i] +     '_iframe').closest('TR');
	    if (closestTR.css('display') == "table-row") {
	      resizeIframe(fieldsToShow[i] + '_iframe');
	    }
          }
        }
      }
    }
  </script>
  <?php

  return $html;
}

// Utility
// =======

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