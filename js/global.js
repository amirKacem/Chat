(function () {
    $('#Inscription').hide();
    $(".tabs #tab1").click(function(){
        $('#Connexion').hide();
        $('#Inscription').show();
    })
    $(".tabs #tab2").click(function(){
        $('#Inscription').hide();
               $('#Connexion').show();
    });

    $('#formLogin').validate({
        lang:'fr',
        rules: {
            username: {
                required: true


            },
            password: {
                required: true,
                minlength: 4
            }
        },
        messages:{
            username:"champ obligatoire",
            password: {
                required:"Veuillez préciser  votre Mot De Passe"
            }
            }

    })

    $('#formRegister').validate({
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
                equalTo:"#pass"
            }
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
        }
    })

})();