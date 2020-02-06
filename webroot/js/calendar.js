
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

$(document).on('click', '.accept-invite', function(e) {

  var id = this.id;

  $('html').css({
      'overflow': 'hidden',
      'height': '100%'
  });

  $('#ev_'+id).addClass('spinner');

  $.ajax({
    type:'post',
    headers: { 'X-CSRF-Token': csrfToken },
    url: acceptURL,
    data:'id='+id,
    dataType: 'json',
    success: function(result){
      $('#ev_'+id).removeClass('spinner');
      $('html').css({
          'overflow': 'auto',
          'height': 'auto'
      });
      $('#ev_'+id).remove();
      var event = getEvent(parseInt(id));
      addEventInvitedCard(event.id, event.title, event.date, event.invitedBy);
    },
    error: function(xhr, status, error) {
      $('#ev_'+id).removeClass('spinner');
      alert(xhr.responseText);
      alert(status);
      alert(error);
    }
  });
  return false;
});

$(document).on('click', '.day', function(e) {
  $('#edt_date').val(this.id);
});


var modal_users;
var id_event = -1;

$(document).on('click', '.invite', function(e) {
  id_event = this.id;
  modal_users = document.getElementById('boxusers');
  modal_users.style.display = "block";
  $('#users_div > ul > li > input').each(function () {
    var id_user = this.id.substring(5);
    var user;
    users.forEach((item, index) => {
      if(item.id == id_user){
        user = item;
        return;
      }
    });

    if(user.events.includes(parseInt(id_event))){
      this.disabled = true;
      this.checked = true;
    }else{
      this.disabled = false;
      this.checked = false;
    }
  });
});

$(document).on('click', '.close', function(e) {
  modal_users.style.display = "none";
});

window.onclick = function(event) {
  if (event.target == modal_users) {
    modal_users.style.display = "none";
  }
}

$(document).on('click', '#btn_invite', function(e) {
  var users_ids = [];
  $('#users_div > ul > li > input').each(function () {
    if(this.checked && !this.disabled) {
      var userId = this.id.substring(5);
      users_ids.push(userId);
    }
  });
  modal_users.style.display = "none";

  var request = {
    users: users_ids,
    id_event: id_event
  };

  $('#addEvent').addClass('spinner');

  $.ajax({
    type:'post',
    headers: { 'X-CSRF-Token': csrfToken },
    url: inviteURL,
    data:'request='+JSON.stringify(request),
    dataType: 'json',
    success: function(result){
      $('#addEvent').removeClass('spinner');
      $('html').css({
          'overflow': 'auto',
          'height': 'auto'
      });
      alert('Invitations sent successfuly');

      users_ids.forEach((item, index) => { //I'm aware it would be better if I just request from the server which users are already invited to the event when click on the event, but I still have somethings to do, maybe I'll change it later, is it necessary?
        users.forEach((user, j) => {
          if(user.id == parseInt(item)){
            user.events.push(parseInt(id_event));
            return;
          }
        })
      });
    },
    error: function(xhr, status, error) {
      $('#addEvent').removeClass('spinner');
      alert(xhr.responseText);
      alert(status);
      alert(error);
    }
  });


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

function addEventInvitedCard(id, title, date, invitedBy){
  var template = $.trim($('#template-event-invited').html());
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
    .replace(/:::user_name/, "")
    .replace(/:::invitedBy/, invitedBy);


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

function getEvent(id){
  var event;
  invitedEvents.forEach((item, index) => {
    if(item.id == id){
      event = item;
      return;
    }
  });
  var date = getTimestampFromDay(event);
  event.date = date;
  return event;
}

function getTimestampFromDay(day){
  var d = day.day;
  var month =  day.month;
  return day.year + "-" + month + "-" + d + "T" + day.time;
}
