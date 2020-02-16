// const check = () => {
//   if (!('serviceWorker' in navigator)) {
//     throw new Error('No Service Worker support!')
//   }else if (!('PushManager' in window)) {
//     throw new Error('No Push API Support!')
//   }
// }

// const showLocalNotification = (title, body, swRegistration) => {
//     const options = {
//         body, // here you can add more properties like icon, image, vibrate, etc.
//     };
//     swRegistration.showNotification(title, options);
// }

const requestNotificationPermission = async () => {
    const permission = await window.Notification.requestPermission();
    if(permission !== 'granted'){
        throw new Error('Permission not granted for Notification');
    }else console.log("Notification_GRANTED")
}

// const registerServiceWorker = async () => {
//     const swRegistration = await navigator.serviceWorker.register(rootURL+'webroot/js/service.js'); //notice the file name
//     return swRegistration;
// }

var todayEvents = []
const notifyEvents = async () => {
  var today = new Date()
  var hours = today.getHours()
  var minutes = today.getMinutes()
  console.log("NOW: " + hours + "/" + minutes)

  todayEvents.forEach((item, i) => {
    console.log("\n" + item.title + ": " + item.hours + "/" + item.minutes)
    if(item.hours == hours && item.minutes == minutes){
      var body = "Hurry, you have " + item.title + " right now"
      var notification = new Notification(item.title + " now!", {
        body: body,
        silent: false,
        icon: imagesURL+'/logo_notificacao.png'
      })
      alert(body)
    }
  });

}

const initEventListener = () => {
  var today = new Date();
  var seconds = 60 - today.getSeconds();

  events.forEach((item, i) => {

    console.log(JSON.stringify(item))

    var today = new Date()
    var month = today.getMonth() + 1
    var year = today.getYear() + 1900
    var day = today.getDate();

    var eventDate = new Date(item.date)
    var eventDay = eventDate.getDate()
    var eventMonth = eventDate.getMonth() + 1
    var eventYear = eventDate.getYear() + 1900
    var eventHours = eventDate.getHours();
    var eventMinutes = eventDate.getMinutes();

    if(eventDay == day && eventMonth == month && eventYear == year){
      item.hours = eventHours
      item.minutes = eventMinutes
      todayEvents.push(item)
    }
  });


  setTimeout(function(){
    notifyEvents()
    updateDate()
    setInterval(function(){
      notifyEvents()
      updateDate()
    }, 60 * 1000);
  }, seconds * 1000);
}

const updateDate = () => {
  var today = new Date();
  utc = today.getTime() + (today.getTimezoneOffset() * 60000);
  today = new Date(utc + (3600000*11));
  var dd = today.getDate();
  var mm = today.getMonth()+1;
  var yyyy = today.getFullYear();
  var hours = today.getHours();
  var minutes = today.getMinutes();
  if(dd<10) dd='0'+dd;
  if(mm<10) mm='0'+mm;
  if(hours<10) hours='0'+hours;
  if(minutes<10) minutes='0'+minutes;
  today = dd+'/'+mm+'/'+yyyy+' '+hours+':'+minutes;
  console.log(today);
  $('#txt_time').text(today);
}

const main = async () => {
    // check();
    // const swRegistration = await registerServiceWorker();
    const permission =  await requestNotificationPermission();

    // showLocalNotification('This is title', 'this is the message', swRegistration);
    //var notification = new Notification("Hi there!");
    initEventListener()
    updateDate()
}
main();
/*--------------------NOTIFICATIONS-SECTION-END--------------------*/

/*--------------------FUNCTIONS-SECTION--------------------*/
const removeFromTodayEvents = (id) => {
  console.log("remove " + id)
  todayEvents.forEach((item, i) => {
    if(item.id == id){
      todayEvents.splice(i, 1 );
      console.log("removed " + id)
      return;
    }
  });
}


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

function freezeScroll(){
  $('html').css({
      'overflow': 'hidden',
      'height': '100%'
  });
}

function unfreezeScroll(){
  $('html').css({
      'overflow': 'auto',
      'height': 'auto'
  });
}

function previousMonth() {
  plusMonths--;
  loadMonthCalendar();
}

function nextMonth() {
  plusMonths++;
  loadMonthCalendar();
}

var loading = 0;
function loadMonthCalendar() {
  loading ++;
  $('#calendarContainer').addClass('spinner');
  $.ajax({
    url: calendarElementURL,
    data: {
      plusMonths: plusMonths
    },
    type:'post',
    headers: { 'X-CSRF-Token': csrfToken },
    cache: false,
    success: function(html){
      loading--;
      if(loading == 0) $('#calendarContainer').removeClass('spinner');
      $("#calendarContainer").html(html);
    },
    error: function(xhr, status, error) {
      loading--;
      if(loading == 0) $('#calendarContainer').removeClass('spinner');
      var errorBody = JSON.parse(xhr.responseText);
      if(errorBody.error != undefined){
          snackbarError(errorBody.error);
      }else console.log(JSON.stringify(errorBody));
    }
  })
}

/*--------------------FUNCTIONS-SECTION-END--------------------*/

/*--------------------LISTENERS-SECTION--------------------*/

$(document).on('click', '#exportDataLink', function(e) {
  $('#exportDataModal').show();
})

$(document).on('click', '#btn_export_data', function(e) {
  var start_export_date = $('#start_export_date').val();
  var end_export_date = $('#end_export_date').val();

  if(start_export_date.length == 0){
    snackbarError("Start Date is empty");
    return false;
  }

  if(end_export_date.length == 0){
    snackbarError("End Date is empty");
    return false;
  }

  $('#btn_export_data').addClass('spinner');

  $.ajax({
    type:'post',
    headers: { 'X-CSRF-Token': csrfToken },
    url: exportDataURL,
    data: {
      startDate: start_export_date,
      endDate: end_export_date
    },
    dataType: 'json',
    success: function(result){
      $('#btn_export_data').removeClass('spinner');
      var url = result.file_url
      snackbar("Downloading CSV file ...")
      window.location = url;
    },
    error: function(xhr, status, error) {
      $('#btn_export_data').removeClass('spinner');
      console.log(JSON.stringify(xhr.responseText));
      var errorBody = JSON.parse(xhr.responseText);
      if(errorBody.error != undefined){
          snackbarError(errorBody.error);
      }
    }
  });

})

$(document).on('click', '.delete', function(e) {

  var id = this.id;


  $('#ev_'+id).addClass('spinner');
  // freezeScroll();

  $.ajax({
    type:'post',
    headers: { 'X-CSRF-Token': csrfToken },
    url: deleteURL,
    data: {
      id: id
    },
    dataType: 'json',
    success: function(result){
      // unfreezeScroll();
      $('#ev_'+id).remove();
      removeFromTodayEvents(id)
    },
    error: function(xhr, status, error) {
      $('#ev_'+id).removeClass('spinner');
      var errorBody = JSON.parse(xhr.responseText);
      if(errorBody.error != undefined){
          snackbarError(errorBody.error);
      }else console.log(JSON.stringify(errorBody));
    }
  });
  return false;
});

$(document).on('click', '.accept-invite', function(e) {

  var id = this.id;

  $('#ev_'+id).addClass('spinner');
  // freezeScroll();

  $.ajax({
    type:'post',
    headers: { 'X-CSRF-Token': csrfToken },
    url: acceptURL,
    data: {
      id: id
    },
    dataType: 'json',
    success: function(result){
      $('#ev_'+id).removeClass('spinner');
      // unfreezeScroll();
      $('#ev_'+id).remove();
      var event = getEvent(parseInt(id));
      addEventInvitedCard(event.id, event.title, event.date, event.invitedBy);
    },
    error: function(xhr, status, error) {
      $('#ev_'+id).removeClass('spinner');
      console.log(JSON.stringify(errorBody));
      var errorBody = JSON.parse(xhr.responseText);
      if(errorBody.error != undefined){
          snackbarError(errorBody.error);
      }else console.log(JSON.stringify(errorBody));
    }
  });
  return false;
});

$(document).on('click', '.reject-invite', function(e) {

  var id = this.id;


  $('#ev_'+id).addClass('spinner');
  // freezeScroll();

  $.ajax({
    type:'post',
    headers: { 'X-CSRF-Token': csrfToken },
    url: rejectURL,
    data: {
      id: id
    },
    dataType: 'json',
    success: function(result){
      // unfreezeScroll();
      $('#ev_'+id).removeClass('spinner');
      $('#ev_'+id).remove();
    },
    error: function(xhr, status, error) {
      $('#ev_'+id).removeClass('spinner');
      var errorBody = JSON.parse(xhr.responseText);
      if(errorBody.error != undefined){
          snackbarError(errorBody.error);
      }else console.log(JSON.stringify(errorBody));
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
    var id_user = this.id.substring("user_invite_".length);
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
      $('#remove_invite_user_'+id_user).show();
    }else{
      this.disabled = false;
      this.checked = false;
      $('#remove_invite_user_'+id_user).hide();
    }
  });
});

$(document).on('click', '.remove-invite', function(e){
  var id_user = this.id.substring("remove_invite_user_".length);


  $('#ev_'+id_event).addClass('spinner');
  // freezeScroll();

  $.ajax({
    type:'post',
    headers: { 'X-CSRF-Token': csrfToken },
    url: removeInviteUrl,
    data: {
      id_event: id_event,
      id_user: id_user
    },
    dataType: 'json',
    success: function(result){
      $('#ev_'+id_event).removeClass('spinner');
      $('#user_invite_'+id_user).removeAttr("disabled").prop("checked", false);
      $('#remove_invite_user_'+id_user).hide();
      users.forEach((user, j) => {
        console.log(user.id+"|"+parseInt(id_user))
        if(user.id == parseInt(id_user)){
          user.events.splice( user.events.indexOf(id_event), 1 );
          return;
        }
      })
      // unfreezeScroll();
      snackbar("User was successfuly uninvited");
    },
    error: function(xhr, status, error) {
      $('#ev_'+id_event).removeClass('spinner');
      var errorBody = JSON.parse(xhr.responseText);
      if(errorBody.error != undefined){
          snackbarError(errorBody.error);
      }else console.log(JSON.stringify(errorBody));
    }
  });
  return false;
});

$(document).on('click', '#closeBoxUsersModal', function(e) {
  modal_users.style.display = "none";
});

$(document).on('click', '#closeExportDataModal', function(e) {
  $('#exportDataModal').hide();
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
      var userId = this.id.substring("user_invite_".length);
      users_ids.push(userId);
    }
  });
  modal_users.style.display = "none";

  var request = {
    users: users_ids,
    id_event: id_event
  };

  // freezeScroll();
  $('#addEvent').addClass('spinner');

  $.ajax({
    type:'post',
    headers: { 'X-CSRF-Token': csrfToken },
    url: inviteURL,
    data:'request='+JSON.stringify(request),
    dataType: 'json',
    success: function(result){
      $('#addEvent').removeClass('spinner');
      // unfreezeScroll();
      snackbar('Invitations sent successfuly');

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
      // unfreezeScroll();
      var errorBody = JSON.parse(xhr.responseText);
      if(errorBody.error != undefined){
          snackbarError(errorBody.error);
      }else console.log(JSON.stringify(errorBody));
    }
  });


});

$(document).on('click', '#addEvent', function(e) {
  var date = $('#edt_date').val();
  var title = $('#edt_title').val();

  if(title.length == 0){
    snackbarError("Title is empty");
    return false;
  }

  if(date.length == 0){
    snackbarError("You need to choose the date and time");
    return false;
  }

  $('#addEvent').addClass('spinner');
  // freezeScroll();

  $.ajax({
    type:'post',
    headers: { 'X-CSRF-Token': csrfToken },
    url: addURL,
    data: {
      date: date,
      title: title
    },
    dataType: 'json',
    success: function(result){
      $('#addEvent').removeClass('spinner');
      // unfreezeScroll();

      snackbar("The event has been added successfuly");

      var id = result.id;

      var localeDate = new Date(date+'+11:00')
      var today = new Date()

      if(localeDate.getDate() == today.getDate() && localeDate.getMonth() == today.getMonth() && localeDate.getYear() == today.getYear()){
        todayEvents.push({
          id: id,
          title: title,
          hours: localeDate.getHours(),
          minutes: localeDate.getMinutes()
        })
      }

      console.log(date)
      addEventCard(id, title, date);
    },
    error: function(xhr, status, error) {
      $('#addEvent').removeClass('spinner');
      // unfreezeScroll();
      var errorBody = JSON.parse(xhr.responseText);
      if(errorBody.error != undefined){
          snackbarError(errorBody.error);
      }else console.log(JSON.stringify(errorBody));
    }
  });


});
