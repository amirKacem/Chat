(function () {
    $('#Inscription').hide();
    $(".tabs #tab1").click(function(){
        $('#Connexion').hide();
        $('#Inscription').show();
    });
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
            },
            }

    });

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
            },tel: {
                phone:true

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
    });
    jQuery.validator.addMethod("phone", function(phone_number, element) {
        phone_number = phone_number.replace(/\s+/g, "");
        return this.optional(element) || phone_number.length > 9 &&
            phone_number.match(/^(33|0)[1-9](\d{2}){4}$/);
    },
        "Veuillez indiquer un numéro de téléphone valide");

})();