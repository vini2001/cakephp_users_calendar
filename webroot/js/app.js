var showing = false;
function snackbar(message){
  if(showing) return;
  showing = true;
  $('#snackbarText').text(message);
  $('#snackbar-container').show();
  $('#snackbar-container').fadeTo(300, 1, function () {
    setTimeout(function () {
      $('#snackbar-container').fadeTo(1000, 0, function () {
        showing = false;
        $('#snackbar-container').hide();
      });
    }, 1000)
  });
}

function snackbarError(message){
  if(showing) return;
  showing = true;
  $('#snackbarText').text(message);
  $('#snackbar-container').show();
  $('.snackbar').addClass("sbError");
  $('#snackbar-container').fadeTo(300, 1, function () {
    setTimeout(function () {
      $('#snackbar-container').fadeTo(1000, 0, function () {
        $('#snackbar-container').hide();
        showing = false;
        $('.snackbar').removeClass("sbError");
      });
    }, 1000)
  });
}
