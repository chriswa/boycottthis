$(function() {
  
  // set up overlay dialogs
  
  $('#boycott-overlay').dialog({
    autoOpen: false,
    height: 300,
    width: 350,
    modal: true,
    buttons: {
      Submit: function() {
        
      },
      Skip: function() { $(this).dialog('close'); }
    },
    close: function() {}
  });
  
});