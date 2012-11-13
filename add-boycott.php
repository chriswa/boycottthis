<?php
  require_once "_app_init.php";
  
  // process boycott-overlay form?
  // =============================
  
  if (@$_REQUEST['boycott-overlay']) {
    
    // determine $member
    $member = null;
    if (@$_REQUEST['email']) {
      $memberCriteria = array('email' => @$_REQUEST['email']);
      $member = mysql_get('members', null, $memberCriteria);
      if (!$member) {
        $member = createMember($memberCriteria);
      }
    }
    
    // create a pledge now if we didn't create a pledge previously (because of duplicate ip addresses, but we now have a member (i.e. email address))
    $pledge = null;
    if ($member && !@$_REQUEST['pledgeNum']) {
      $pledge = dbx_createAndSave('pledges', $criteria);
    }
    elseif (@$_REQUEST['pledgeNum']) {
      $pledge = mysql_get('pledges', @$_REQUEST['pledgeNum'], array('uniq' => @$_REQUEST['uniq']));
    }
    
    // update pledge with member and/or subscription
    if ($pledge) {
      $columns = array(
        'member'     => @$member['num'],
        'subscribed' => @$_REQUEST['subscribed'],
      );
      mysql_update('pledges', @$_REQUEST['pledgeNum'], null, $columns);
    }
    exit;
  }
  
  // attempt to create a pledge
  // ==========================
  
  $issue = mysql_get('issues', @$_REQUEST['issue']);
  if (!$issue) { dieWith404("Issue not found!"); }
  $organization = mysql_get('organizations', $issue['organization']);
  if (!$organization) { dieWith404("Organization not found!"); }
  
  $pledge = null;
  
  $criteria = array(
    'ip'           => $_SERVER['REMOTE_ADDR'],
    'issue'        => $issue['num'],
    'organization' => $organization['num'],
  );
  if (mysql_count('pledges', $criteria) === 0) {
    
    // create pledge!
    $pledge = dbx_createAndSave('pledges', array_merge($criteria, array('uniq' => @$_REQUEST['uniq'])));
    
    $issue['pledge_count'] += 1;
    mysql_query(mysql_escapef("UPDATE {$TABLE_PREFIX}issues SET pledge_count = pledge_count + 1", $issue['num']));
    
    unset($criteria['issue']);
    if (mysql_count('pledges', $criteria) === 0) {
      $organization['pledge_count'] += 1;
      mysql_query(mysql_escapef("UPDATE {$TABLE_PREFIX}organizations SET pledge_count = pledge_count + 1", $organization['num']));
    }
  }
  
  
  // json output?
  if (isAjaxRequest()) {
    echo json_encode(array(
      'pledgeNum'                  => @$pledge['num'],
      'newIssuePledgeCount'        => $issue['pledge_count'],
      'newOrganizationPledgeCount' => $organization['pledge_count'],
    ));
    exit;
  }
  
?>

TODO: support for non-Ajax requests (users who have JS disabled)
