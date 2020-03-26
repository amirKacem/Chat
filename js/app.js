$(function() {
// connect to the socket server

var conn = new WebSocket('ws://localhost:8080');

conn.onopen = function(e) {
    console.log('Connected to server:', conn);
    var d = new Date(); // for now
    datetext = d.getHours()+":"+d.getMinutes()+":"+d.getSeconds();
    console.log(datetext);
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
}

// handle new message received from the socket server
conn.onmessage = function(e) {
    // message is data property of event object
    var message = JSON.parse(e.data);
    console.log(message);
  if(message.type=="chat"){

     chat.render(message.data);
  }else{
      console.log(message);
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
        //conn.send(JSON.stringify(message));
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
            html = `
            <li class='clearfix' >
                    <div class='message-data align-right' >
                    <span class='message-data-time' > ${data.username} </span > &nbsp; &nbsp;
            <span class='message-data-name' >${data.username}</span > <i class='fa fa-circle ${status}' ></i >
            <img src = '${data.image_path}' class='chat-img' alt = 'test' >
                </div >
                <div class='message other-message float-right' > ${data.content}</div >
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
                    url:'http://localhost/chat/src/Api/MsgApi.php',
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



        /*var searchFilter = {
          options: { valueNames: ['name'] },
          init: function() {
            var userList = new List('people-list', this.options);
            var noItems = $('<li id="no-items-found">No items found</li>');

            userList.on('updated', function(list) {
              if (list.matchingItems.length === 0) {
                $(list.list).append(noItems);
              } else {
                noItems.detach();
              }
            });
          }
        };

        searchFilter.init();*/




});
