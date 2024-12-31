function copyLicense(id){
  // create fake input to select the text
  var temp = $("<input>");
  $("body").append(temp);

  // copy text
  var license = $('#license_' + id).html().trim();
  temp.val(license).select();
  var successful = document.execCommand('copy');

  // remove fake input
  temp.remove();

  showFlash(id, "Copied!");
}

function showFlash(id, message){
    //show text and then remove after a few seconds
  $('#js-copy-alert-' + id).html(message);
  $('#js-copy-alert-' + id).delay(100).fadeIn('normal',function(){
      $(this).delay(2500).fadeOut();
  });
}
