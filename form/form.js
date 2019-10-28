document.addEventListener("DOMContentLoaded", function (event) {
    $('#hidden').hide();
    $('#tabela').hide();
    $('#form_container').hide();

    var auth2;

    function renderButton() {
        gapi.signin2.render('meu-botao', {
            'scope': 'email profile https://www.googleapis.com/auth/plus.login', // solicitando acesso ao profile e ao e-mail do usuário
            'width': 250,
            'height': 50,
            'longtitle': true,
            'theme': 'dark',
            'onsuccess': onSuccess,
            'onfailure': onFailure
        });
    }

    function signOut() {
        auth2 = gapi.auth2.getAuthInstance();
        auth2.signOut().then(function () {
         console.log('User signed out.');
        });

        $('#form_container').hide();
      }

    function checkLogin() {
        auth2 = gapi.auth2.getAuthInstance();

        if (auth2.isSignedIn.get()) {
            var profile = auth2.currentUser.get().getBasicProfile();

            $('#nome').val(profile.getName());
            $('#email').val(profile.getEmail());

            $('#form_container').show();
          } else {
              console.log("não logado");
          }
    }

    /**
     Função executada quando o login é efetuado com sucesso
    */
    function onSuccess(googleUser) {
        // Recuperando o profile do usuário
        var profile = googleUser.getBasicProfile();
        // console.log("ID: " + profile.getId()); // Don't send this directly to your server!
        // console.log("Name: " + profile.getName());
        // console.log("Image URL: " + profile.getImageUrl());
        // console.log("Email: " + profile.getEmail());

        // Recuperando o token do usuario. Essa informação você necessita passar para seu backend
        var id_token = googleUser.getAuthResponse().id_token;
        // console.log("ID Token: " + id_token);

        checkLogin();
    }

    /**
     Função executada quando ocorrer falha no logn
    */
    function onFailure(error) {
        console.log(error);
    }

    $.ajax({
        type: "GET",
        url:"/~debian/api/equipe/read.php",
        dataType: "json",
        success: function (data) {
            $.each(data.records,function(i, obj)
            {
                var option ="<option value=" + obj.id + ">" + obj.nome + "</option>";
                $(option).appendTo('#element_4'); 
            });
        }
    });

    $.ajax({
        type: "GET",
        url:"/~debian/api/categoria/read.php",
        dataType: "json",
        success: function (data) {
            $.each(data.records,function(i, obj)
            {
                var option ="<option value=" + obj.id + ">" + obj.nome + "</option>";
                $(option).appendTo('#categoria'); 
            });
        }
    });

    $.ajax({
        type: "GET",
        url:"/~debian/api/arma/read.php",
        dataType: "json",
        success: function (data) {
            $.each(data.records,function(i, obj)
            {
                var option ="<option value=" + obj.id + ">" + obj.nome + "</option>";
                $(option).appendTo('#arma');
            });
        }
    });

    function editRobo() {
        alert("robo editado!");
            dialog.dialog( "close" );
    }

    $('#element_4').change(function() {
        $('#tabela').show();
        let equipeid = $('#element_4').val();

        $.ajax({
            type: "GET",
            url:"/~debian/api/robo/read.php?equipeid=" + equipeid,
            dataType: "json",

            success: function (data) {
                $('#table_body').empty();

                $.each(data.records,function(i, obj) {
                    var categoria;
                    $.ajax( {
                        type: "GET",
                        url:"/~debian/api/categoria/read.php?id=" + obj.categoriaid,
                        dataType: "json",
                        success: function (data) {
                            categoria = data.records[0].nome;
                            var button = $('<button class="editar btn btn-primary" id="editar">').text('Editar');
                            var tr = $('<tr>').append(
                                $('<td>').text(obj.id),
                                $('<td>').text(obj.nome),
                                $('<td>').text(categoria),
                                button).appendTo('#table_body');

                            button.click(function(event) {
                                $("#robo").text(obj.nome);
                                $('#categoria').val(obj.categoriaid);
                                $('#arma').val(obj.armaid);
                                $('#qtdarma').val(obj.qtdarmas);
                                $('#roda').val(obj.tiporoda);
                                $('#qtdroda').val(obj.qtdroda);
                                $('#carenagem').val(obj.carenagem);
                                $('#motor').val(obj.tipomotor);
                                $('#qtdmotor').val(obj.qtdmotor);

                                dialog.dialog( "open" );
                                console.log("ID: ", $(event.target).closest('tr').find('td:first').text());
                            });
                        }
                    });
                });
            }
        });
    });

    dialog = $( "#dialog-form" ).dialog({
        autoOpen: false,
        // height: 400,
        width: 400,
        modal: true,
        buttons: {
            "Salvar": editRobo,
            Cancel: function() {
            dialog.dialog( "close" );
            }
        },
        close: function() {
            form[ 0 ].reset();
        }
    });

        form = dialog.find( "form" ).on( "submit", function( event ) {
        event.preventDefault();
        editRobo();
        });

    $("#signOutLink").on("click", signOut);

    $(".editar").on("click", function() {
        dialog.dialog( "open" );
    });

    $('#roboid').change(function() {
        let id = $('#roboid').val();
        $.ajax({
            type: "GET",
            url:"/~debian/api/robo/read.php?id=" + id,
            dataType: "json",
            success: function (data) {
                $('#categoria').val(data.records[0].categoriaid);
                $('#arma').val(data.records[0].armaid);
                $('#qtdarma').val(data.records[0].qtdarmas);
                $('#roda').val(data.records[0].tiporoda);
                $('#qtdroda').val(data.records[0].qtdroda);
                $('#carenagem').val(data.records[0].carenagem);
                $('#motor').val(data.records[0].tipomotor);
                $('#qtdmotor').val(data.records[0].qtdmotor);
            }
        });
    });

    renderButton();
    checkLogin();
});