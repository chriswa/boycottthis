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
    $('#boycott-overlay').dialog('open');
  });
  
});