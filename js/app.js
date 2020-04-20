$(function() {
    var user_id = $('#user_id').val();

// connect to the socket server
    var  conn = new WebSocket('ws://localhost:8083');
function startSocket(){
     conn = new WebSocket('ws://localhost:8083');

    conn.onopen = function(e) {
        var d = new Date(); // for now
        datetext = d.getHours()+":"+d.getMinutes()+":"+d.getSeconds();

        conn.send(JSON.stringify({type:"subscribe",userId:user_id}));

    };

    conn.onerror = function(e) {
        console.log('Error: Could not connect to server.');
    };

    conn.onclose = function(e) {
        console.log('Connection closed'+e);
        var timestamp = '[' + Date.now() + '] ';
        var d = new Date(); // for now
        datetext = d.getHours()+":"+d.getMinutes()+":"+d.getSeconds();

        conn = null;
        setTimeout(function(){
            console.log("reconnect");
            startSocket();
        },5000);


    };

// handle new message received from the socket server
    conn.onmessage = function(e) {
        // message is data property of event object
        var message = JSON.parse(e.data);
        console.log(message);
        if(message.type==="chat"){

            if(message.data.id !== user_id){

                if(Notif.request()){
                    Notif.send();
                }
                var audio = new Audio();
                audio.src = "assets/audio/audio.mp3";
                var playPromise = audio.play();

                if(playPromise!== undefined){
                    playPromise.catch( () => {
                        audio.play();
                    });
                }
            }
            chat.render(message.data);
        }else if(message.type==="subscribe" || message.type==="disconnect"){

            chat.renderUsers(message.voyants);
            if(message.type==="subscribe"){
            chat.renderMessages(message.messages);
            }
        }


    };

}
    startSocket();


    $('.message-form').on('submit', function(e) {
        // prevent form submission which causes page reload
        e.preventDefault();
        var textarea = $(this).find('textarea');
        if(textarea.val().trim(' ')!==''){
            console.log("test");
            // send message to server
            data = {
                'action':"send_msg",
                'user_id':user_id,
                'content':textarea.val()
            };
            chat.msg = data;
            chat.sendMessage();
            textarea.val('');

        }
    });
    $("#message-to-send").keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        //alert(code);
        if (code == 13) {
            e.preventDefault();
            if ($(this).val().trim(' ') !== '') {
                // send message to server
                data = {
                    'action': "send_msg",
                    'user_id': user_id,
                    'content': $(this).val()
                };
                chat.msg = data;
                chat.sendMessage();
                $(this).val('');


            }
        }
    });


    var chat = {
        msg: {},

        responseData:{},
        renderMessages : function(messages){
            var html='',status;
            messages.forEach(function(userMsg){
                if(userMsg.loginStatus==1){
                    status='online';
                }else{
                    status='offline';
                }
                if(userMsg.type=="V"){
                    html += `<li>
                    <div class='message-data'>
                    <span class='message-data-name'>
                    <img src='${userMsg.image_path}' class='chat-img' alt='test'>
                    <i class='fa fa-circle ${status}'></i>  ${userMsg.username}</span>
                <span class='message-data-time'>${userMsg.date_send}</span>
                </div>
                <div class='message my-message'> ${userMsg.content}</div>
                </li>`;
                }else{
                    html += ` <li class='clearfix' >
                        <div class='message-data align-right' >
                        <span class='message-data-time' > ${userMsg.date_send} </span > &nbsp; &nbsp;
                <span class='message-data-name' >${userMsg.username}</span > <i class='fa fa-circle ${status}' ></i >
                <img src = '${userMsg.image_path}' class='chat-img' alt = 'test' >
                    </div > <div class='message other-message float-right' > ${userMsg.content}</div >
                </li >`;
                }
            });

            $('ul.message-list').html(html);
            this.scrollToBottom();
        },
        renderUsers:function(users){
           var result ='';
            var statut;
            users.forEach(function(item) {

                if(item.loginStatus==1){
                    statut = "online";
                }else{
                    statut ="offline"
                }
                result += `<li class="clearfix">
                        <img src="${item.image_path}" alt="avatar" width="55" height="55" />
                        <div class="about">
                            <div class="name">${item.username}</div>
                            <div class="status">
                                <i class="fa fa-circle ${statut}"></i> ${statut}
                            </div>
                        </div>
                    </li>`;
            });

            $('#usersList').html(result);
            searchFilter.init();

        },
        render: function(data) {

            var html='',status;
            if(data.loginStatus==1){
                status='online';
            }else{
                status='offline';
            }
            if(data.type=="V"){
                html = `<li>
                <div class='message-data'>
                <span class='message-data-name'>
                <img src='${data.image_path}' class='chat-img' alt='test'>
                <i class='fa fa-circle ${status}'></i>  ${data.username}</span>
            <span class='message-data-time'>${data.date_send}</span>
            </div>
            <div class='message my-message'> ${data.content}</div>
            </li>`;
            }else{
                html = ` <li class='clearfix' >
                    <div class='message-data align-right' >
                    <span class='message-data-time' > ${data.date_send} </span > &nbsp; &nbsp;
            <span class='message-data-name' >${data.username}</span > <i class='fa fa-circle ${status}' ></i >
            <img src = '${data.image_path}' class='chat-img' alt = 'test' >
                </div > <div class='message other-message float-right' > ${data.content}</div >
            </li >`;
            }

            $('ul.message-list').append(html);

            this.scrollToBottom();

            // responses
            var contextResponse = {
                response:data,
                time: this.getCurrentTime()
            };
        },
        sendMessage: function() {

            var that = this;
            if(this.msg){
                $.ajax({
                    url:'http://localhost/Chat/src/Api/MsgApi.php',
                    method:'POST',
                    data:this.msg,
                    success:function (data) {
                        msg = {
                            type:"chat",
                            data:JSON.parse(data)
                        };

                        conn.send(JSON.stringify(msg));
                    },
                    error:function (xhr,status,errorThrow) {
                        console.log(xhr,status,errorThrow);

                    }

                })
            }

        },
        scrollToBottom: function() {

            $('.chat-history').scrollTop($('.chat-history')[0].scrollHeight);
        },
        getCurrentTime: function() {
            return new Date().toLocaleTimeString().
            replace(/([\d]+:[\d]{2})(:[\d]{2})(.*)/, "$1$3");
        }

    };



    var searchFilter = {
      options: { valueNames: ['name'] },
      init: function() {
        var userList = document.querySelectorAll('#userslist li div.name');


        $('#search').on('keyup', function(list) {
            var filter =$(this).val().toUpperCase();


            for(var i=0;i<userList.length;i++){
                var name = userList[i].textContent.toUpperCase();
                var li  = userList[i].parentElement.parentElement;
               if(name.indexOf(filter)>-1){

                    li.style.display ="";
               }else{
                   li.style.display ="none";
               }
            }
        });
      }
    };


    // Notfication
  var  Notif = {

      request: function()  {

          // Vérifier que l'objet existe
          if (typeof Notification === undefined) {
              return false;
          }


          if (Notification.permission == "granted") {
              return true;
          } else {
              Notification.requestPermission(function (result) {

                  if (result == "granted") {
                      return true
                  } else {
                      return false;
                  }
              });
          }
      },
      send: function () {
          var options = {
              "lang": "FR",
              "icon": "/favicon.ico",
              "tag": new Date(),
              "body": "Nouveau message dans le salon de tchat"
          };

          var notif = new Notification("Salon de chat", options);

          notif.onclick = function (event) {
              event.preventDefault();
              window.open("https://www.voyanceenligne.chat", "_blank");
          }
      }
  };


});




