
$(document).on('click', '.delete', function(e) {

  var id = this.id;

  $('html').css({
      'overflow': 'hidden',
      'height': '100%'
  });

  $('#ev_'+id).addClass('spinner');

  $.ajax({
    type:'post',
    headers: { 'X-CSRF-Token': csrfToken },
    url: deleteURL,
    data:'id='+id,
    dataType: 'json',
    success: function(result){
      $('html').css({
          'overflow': 'auto',
          'height': 'auto'
      });
      $('#ev_'+id).remove();
    },
    error: function(xhr, status, error) {
      var err = eval("(" + xhr.responseText + ")");
      $('#ev_'+id).removeClass('spinner');
      alert(xhr.responseText);
      alert(err.Message);
      alert(status);
      alert(error);
    }
  });
  return false;
});


$(document).on('click', '.day', function(e) {
  $('#edt_date').val(this.id);
});


function addEventCard(id, title, date){
  var template = $.trim($('#template-inputs').html());
  var dateObj = new Date(date);
  var hours = dateObj.getHours();
  if (hours < 10) hours = "0"+hours;
  var minutes = dateObj.getMinutes();
  if (minutes < 10) minutes = "0"+minutes;

  var time = hours + ":" + minutes + ":00";
  var newItemHtml = template
    .replace(/:::id/g, id)
    .replace(/:::title/, title)
    .replace(/:::time/, time)
    .replace(/:::user_name/, "");


  var day = dateObj.getDate();
  var month = dateObj.getMonth() + 1;

  var key = '#div_ev_'+day+'_'+month;
  var appendTo = $(key);
  appendTo.append(newItemHtml);
}

$(document).on('click', '#addEvent', function(e) {
  var date = $('#edt_date').val();
  var title = $('#edt_title').val();

  if(title.length == 0){
    alert("Title is empty");
    return false;
  }

  if(date.length == 0){
    alert("You need to choose the date and time");
    return false;
  }

  $('#addEvent').addClass('spinner');

  $.ajax({
    type:'post',
    headers: { 'X-CSRF-Token': csrfToken },
    url: addURL,
    data:'date='+date+'&title='+title,
    dataType: 'json',
    success: function(result){
      $('#addEvent').removeClass('spinner');
      $('html').css({
          'overflow': 'auto',
          'height': 'auto'
      });

      var id = result.id;
      addEventCard(id, title, date);
    },
    error: function(xhr, status, error) {
      var err = eval("(" + xhr.responseText + ")");
      $('#addEvent').removeClass('spinner');
      alert(xhr.responseText);
      alert(err.Message);
      alert(status);
      alert(error);
    }
  });


});
