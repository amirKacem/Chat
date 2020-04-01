$(function() {
// connect to the socket server

var conn = new WebSocket('ws://localhost:8083');

    conn.onopen = function(e) {

        var d = new Date(); // for now
        datetext = d.getHours()+":"+d.getMinutes()+":"+d.getSeconds();

    }

    conn.onerror = function(e) {
        console.log('Error: Could not connect to server.');
    }

    conn.onclose = function(e) {
        console.log('Connection closed'+e);
        var timestamp = '[' + Date.now() + '] ';
        var d = new Date(); // for now
        datetext = d.getHours()+":"+d.getMinutes()+":"+d.getSeconds();
        console.log(datetext);
        location.reload(true);


    }

// handle new message received from the socket server
    conn.onmessage = function(e) {
        // message is data property of event object
        var message = JSON.parse(e.data);
        if(message.type==="chat"){

            chat.render(message.data);
        }else if(message.type==="connect" || message.type==="disconnect"){

            chat.renderUsers(message.voyants);
        }


    }

    var user_id = $('#user_id').val();
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
        init: function(){
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
                <img src='https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/chat_avatar_01.jpg' class='chat-img' alt='test'>
                <i class='fa fa-circle ${status}'></i>  ${data.username}</span>
            <span class='message-data-time'>${data.date_send}</span>
            </div>
            <div class='message my-message'> ${data.content}</div>
            </li>`;
            }else{
                html = ` <li class='clearfix' >
                    <div class='message-data align-right' >
                    <span class='message-data-time' > ${data.username} </span > &nbsp; &nbsp;
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
            console.log(this.msg);
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


    chat.init();



    var searchFilter = {
      options: { valueNames: ['name'] },
      init: function() {
        var userList = document.querySelectorAll('#userslist li div.name');
        //var noItems = $('<li id="no-items-found">NoN Voyant exist</li>');

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





});
