$(function() {
  
  // set up overlay dialogs
  
  $('#boycott-overlay').dialog({
    autoOpen: false,
    height: 310,
    width: 450,
    position: ['center', 100],
    modal: true,
    title: 'Boycott counted!',
    buttons: {
      Submit: function() {
        
      },
      Skip: function() { $(this).dialog('close'); }
    },
    close: function() {}
  });
  
  // buttons
  
  $('.boycott-button').click(function() {
    var issueNum = $(this).data('issue');
    var uniqId   = $(this).data('uniq');
    var organizationNum = $(this).data('organization');
    $.post('add-boycott.php', { issue: issueNum, uniq: uniqId }, function(response) {
      
      // update pledge counts for issue and organization
      $('.pledge-count[data-issue='        + issueNum        + ']').text(response['newIssuePledgeCount']);
      $('.pledge-count[data-organization=' + organizationNum + ']').text(response['newOrganizationPledgeCount']);
      
      // show boycott-overlay dialog
      $('#boycott-overlay').dialog('open');
      // insert pledgeNum into hidden field so we can update it if the user gives us login info
      $('#boycott-overlay input[name=pledgeNum]').val(response['pledgeNum']);
      // insert issue into hidden field so we can create a pledge if we didn't due to anonymity and ip conflict
      $('#boycott-overlay input[name=issue]').val(issueNum);
      // 
      $('#boycott-overlay input[name=uniq]').val(uniqId);
      
    }, 'json');
  });
  
});