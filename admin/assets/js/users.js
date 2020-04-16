$(document).ready(function(){
var url ="http://localhost/chat/src/Api/UserApi.php";

dataUsers = {
  'get_voyants':  [
      { "data": "username" },
      { "data": "email" },
      { "data": "loginStatus",
          render:function (data,index,row) {
              if(data==1){
                  return `<span class='badge badge-success'>online</span>`;
              }else{
                  return `<span class='badge badge-danger'>offline</span>`;
              }
          }
      },
      { "data": "lastLogin","width":"20%"

      },

      {"data": null, "orderable":false, "width":"40%",
          "className":"text-center",
          render: function (data,index,row) {

              var html = `<button data-id=${row.id}  type="button" class="btn btn-primary btn-pill btn-sm " data-toggle="modal" data-target="#viewUser" id="view"> detail </button><button data-id=${row.id}  type="button" class="btn btn-warning btn-pill btn-sm " data-toggle="modal" data-target="#updateUser" id="update"> modifier </button>
        <button data-id=${row.id}  type="button" class="btn btn-danger btn-pill btn-sm " id='delete'> Supprime </button>`;
              return html;
          }
      }
  ],
    'get_clients':[
        { "data": "username" },
        { "data": "email" },
        { "data": "loginStatus",
            render:function (data,index,row) {
                if(data==1){
                    return `<span class='badge badge-success'>online</span>`;
                }else{
                    return `<span class='badge badge-danger'>offline</span>`;
                }
            }
        },
        { "data": "lastLogin","width":"20%"

        },

        {"data": null, "orderable":false, "width":"200px",
            "className":"text-center",
            render: function (data,index,row) {

                var html = `<button data-id=${row.id}  type="button" class="btn btn-primary btn-pill btn-sm " data-toggle="modal" data-target="#viewUser" id="view"> detail </button><button data-id=${row.id}  type="button" class="btn btn-warning btn-pill btn-sm " data-toggle="modal" data-target="#updateUser" id="update"> modifier </button>
        <button data-id=${row.id}  type="button" class="btn btn-danger btn-pill btn-sm " id='delete'> Supprime </button>`;
                return html;
            }
        } ,
        {"data":"status",
            "className":"valign-center",
            "type":"html",
            "createdCell": function (td, cellData, rowData, row, col) {
                    td.dataset.id=rowData.id;
                    if(cellData=="ON"){
                        checked = true;
                    }else{
                        checked=false
                    }
                    var input = $(`<input type="checkbox"  value=${cellData}  "/>`);
                    input.prop('checked',checked);

                var div = $(document.createElement('div'))
                    .addClass('ui-switcher')
                    .attr('aria-checked', input.is(':checked'));
                input.hide();
                input.append(div);
                div.on('click',function(e){
                   input.trigger(e.type);

                    div.attr('aria-checked', input.is(':checked'));
                });

                $(td).empty().append(div,input);


                }

        },{
            "data":"firstname"
          },
        {
            "data":"lastname"
        },
        {
            "data":"tel"
        }

    ]

};
var table = $('#usersTable').DataTable({

    "scrollX":true,
    "ajax": {
        "data": {"action": show_users},
        "url": url,
        "type": "POST",
        "dataSrc": "",
        error:function (error) {
    console.log(error);
        }


    },
    "columns":dataUsers[show_users]

});


$('#usersTable').on( 'click', 'tbody td button#delete', function (e) {

    var user_id = $(this).data().id;
    deleteUser(user_id);
} );

$('#usersTable').on( 'click', 'tbody td button#update', function (e) {


    var user_id = $(this).data().id;
    getUser(user_id,Userform);
} );


$('#usersTable').on( 'click', 'tbody td button#view', function (e) {


    var user_id = $(this).data().id;
    getUser(user_id,userDetail);
} );

$('#usersTable').on( 'change', 'tbody td input:checkbox', function (e) {
        this.setAttribute('checked',this.checked) ;

        if(this.checked==true){
            this.value="ON"
        }else{
            this.value="OFF";
        }
        var user_id = $(this).closest('td').data('id');
        var status= this.value;
        updateEtat(user_id,status);
});


function deleteUser(user_id){
    var data = {
        action:"delete_user",
        user_id:user_id
    };
    $.ajax({
        url:url,
        data:data,
        type:'POST',
        dataType:"JSON",
        success:function (data) {
            table.ajax.reload();

        }
    })


}


function getUser(user_id,callback){

    var data = {
        action:"get_user",
        user_id:user_id
    };
    $.ajax({
        url:url,
        data:data,
        type:'POST',
        dataType:"JSON",
        success:function (data) {

            callback(data);

        },
        error:function (error) {
            console.log(error);
        }
    })

}

function updateEtat(user_id,status) {

    var data = {
        action:"update_user_status",
        user_id:user_id,
        status:status
    };
    $.ajax({
        url:url,
        data:data,
        type:'POST',
        dataType:"JSON",
        success:function (data) {
            console.log(data);
        },
        error:function (error) {
            console.log(error);
        }
    })
}

/* modal Update form */
function Userform(data){

    $('#formupdateUser').find("input#name").val(data.username);
    $('#formupdateUser').find("input#mail").val(data.email);
    $('#formupdateUser').find("input#img").val(data.image);
    $('#formupdateUser').find("input#userId").val(data.id);
}
/* modal view User  */

function userDetail(data){

    $('#viewUser').find("#userImg").html(`<img class='d-block mx-auto rounded-circle' src='${data.image_path}' width='100' height='100' /> `);
    $('#viewUser').find('#email').text(data.email);
    $('#viewUser').find('#username').text(data.username);
    if(data.loginStatus==0){
        $('#viewUser').find('#status').html(`<span class="badge badge-danger">offline</span>`);
    }else{
        $('#viewUser').find('#status').html(`<span class="badge badge-success">online</span>`);
    }
    $('#viewUser').find('#datelogin').text(data.lastLogin);
    $('#viewUser').find('#firstname').text(data.firstname);
    $('#viewUser').find('#lastname').text(data.lastname);
    $('#viewUser').find('#tel').text(data.tel);

}

// form add User
$('#formaddUser').validate({
    lang:'fr',
    rules:{
        username:{
            required:true,
            maxlength:20
        },
        password:{
            required:true,
            minlength:4
        },
        cpassword:{
            required:true,
            minlength:4,
            equalTo:"#password"
        },

    },
    messages:{
        username:{
            required:"champ obligatoire",
            maxLength:jQuery.validator.format("votre pseudo doit comporter 20 caractères maximum")
        },
        password:{
            required:"Veuillez préciser  votre Mot De Passe",
            minlength:jQuery.validator.format("Au moins {0} caractères requis!")
        },
        cpassword:{
            required:"Veuillez préciser  votre Mot De Passe",
            equalTo:"Les mots de passe ne correspondent pas. ",
            minlength:jQuery.validator.format("Au moins {0} caractères  requis!")
        }

    },
    submitHandler:function(form){
        // submit add user


        var form = $('#formaddUser');
        var formData = new FormData(form[0]);

        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            dataType: 'JSON',
            processData: false,
            contentType: false,
            success:function(data){
                table.ajax.reload();
                $('#addUser').modal('hide');
                $('.errors').html("");
                document.getElementById('formaddUser').reset();
            },
            complete: function (data) {

                var response = data.responseJSON;

                if (response.errors) {

                    var errors = "";
                    response.errors.forEach(function (error) {
                        errors += `<p style="color:red">${error}</p>`;

                    });
                    $('.errors').html(errors);

                }

            }
        });


    }
});



// form Update

$('#formupdateUser').validate({
    lang:'fr',
    rules: {
        username: {
            required: true
        },
        email: {
            required: true,

        }
    },
    messages:{
        username:"champ obligatoire",
        email:"champ obligatoire",

    },
    submitHandler:function () {

        var form = $('#formupdateUser');
        var formData = new FormData(form[0]);


        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            dataType: 'JSON',
            processData: false,
            contentType: false,
            complete: function (data) {
                var response = data.responseJSON;

                if (response.errors) {

                    var errors = "";
                    response.errors.forEach(function (error) {
                        errors += `<p style="color:red">${error}</p>`;

                    });
                    $('.errors').html(errors);

                }else{
                    table.ajax.reload();
                    $('#updateUser').modal('hide');
                    $('.errors').html("");
                    document.getElementById('formupdateUser').reset();
                }

            }
        });

    }
});



});