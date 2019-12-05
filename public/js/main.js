jQuery(document).ready(function($){

    $("body").on("click","button", function(e){

        e.preventDefault();

    })

    $("#btn-login").click(function(){

        if($("#txt-user").val() !== "" && $("#txt-pass").val() !== "")
        {

            $.ajax({
                type: "GET",
                url: "/callflex/API/Auth/Login",
                data: {user: $("#txt-user").val(), pass:$("#txt-pass").val()},
                success: function (response) {

                    if(response !== "OK")
                    {
                        
                        $(".alert-section").append('<div class="alert alert-dismissible fade show w-100 d-none" role="alert">'+
                        ''+
                        '</div>');
                        $(".alert-section > div:last-child").addClass("alert-danger").removeClass("d-none").html(response);
                        $(".alert-section > div:last-child").append('<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
                        '<span aria-hidden="true">&times;</span>'+
                        '</button>')

                    }else
                    {

                        window.location.href="/callflex/dashboard";

                    }
                    
                }
            });

        }

    })

    $("body").on("click", ".fa-toggle-off, .fa-toggle-on", function(){

        if($(this).hasClass("fa-toggle-off"))
        {
            $(this).removeClass("fa-toggle-off").addClass("fa-toggle-on")
        }else
        {
            $(this).removeClass("fa-toggle-on").addClass("fa-toggle-off")
        }

    })

    $row = undefined;

    $("body").on("click",".fa-trash-alt", function(){

        $row = $(this).parent().parent().parent();

        $('.modal-exclude').find('.modal-body p').html("Deseja realmente excluir <strong>" + $row.find("td").eq(0).html() + "</strong> ?");

        $('.modal-exclude').modal('show');

    });

    $("body").on("click",".fa-edit", function(){

        $row = $(this).parent().parent().parent();

        $('.modal-edit').find('.modal-body #txt-desc').val($row.find("td").eq(0).html());

        $('.modal-edit').find('.modal-body #txt-cod').val($row.find("td").eq(1).html());

        $('.modal-edit').modal('show');

    });

    $("body").on("click","#btn-del", function(){

        $.ajax({
            type: "GET",
            url: "/callflex/API/Produtos/Delete",
            data: {description: $row.find("td").eq(0).html(), code: $row.find("td").eq(1).html()},
            success: function (response) {

                $('.modal-conf').find('.modal-title').html("Excluir");

                $('.modal-conf').find('.modal-body p').html("Produto excluído com sucesso");

                $('.modal-conf').modal('show');

                $row.remove();
                
            }
        });

    });

    $("body").on("click","#btn-save", function(){

        $.ajax({
            type: "GET",
            url: "/callflex/API/Produtos/Update",
            data: {descriptionOld: $row.find("td").eq(0).html(), codeOld: $row.find("td").eq(1).html(), description: $("#txt-desc").val(), code: $("#txt-cod").val()},
            success: function (response) {

                $('.modal-conf').find('.modal-title').html("Editar");

                $('.modal-conf').find('.modal-body p').html("Produto alterado com sucesso");

                $('.modal-conf').modal('show');

                $row.find("td").eq(0).html($("#txt-desc").val());

                $row.find("td").eq(1).html($("#txt-cod").val());
                
            }
        });

    });

    $("#txt-search").keyup(function(){
        
        if($("#txt-search").val() !== "")
        {
            $.ajax({
                type: "GET",
                url: "/callflex/API/Produtos/Get",
                data: {busca: $("#txt-search").val()},
                success: function (response) {

                    $result = JSON.parse(response)
                    
                    if(typeof $result == 'object')
                    {
                        $(".table-products tbody").html("");

                        $.each($result, function(i,val)
                        {

                            $(".table-products tbody").append('<tr>'+
                            '<th scope="row">'+ val.id_product +'</th>'+
                            '<td>'+ val.description +'</td>'+
                            '<td>'+ val.code +'</td>'+
                            '<td>'+
                            '<div class="align-items-center d-flex justify-content-around py-1">'+
                            '<i class="far fa-edit mx-2 cursor-pointer"></i>'+
                            ''+
                            '<i class="far fa-eye mx-2 cursor-pointer"></i>'+
                            ''+
                            '<i class="far fa-trash-alt mx-2 cursor-pointer"></i>'+
                            ''+
                            '<i class="fas fa-toggle-on mx-2 cursor-pointer"></i>'+
                            '</div>'+
                            '</td>'+
                            '</tr>');

                        });

                        $(".btn-pages").hide();
                    }
                }
            });
        }else
        {
            $(".btn-pages").show();
        }

    })

    $("#cad_product button").click(function(){

        if($("#txt-desc").val() !== "" && $("#txt-cod").val() !== "")
        {

            $.ajax({
                type: "GET",
                url: "/callflex/API/Produtos/Add",
                data: {description: $("#txt-desc").val(), code:$("#txt-cod").val()},
                success: function (response) {

                    $(".alert-section").append('<div class="alert alert-dismissible fade show w-100 d-none" role="alert">'+
                    ''+
                    '</div>');
                    $(".alert-section > div:last-child").removeClass("alert-warning").addClass("alert-success").removeClass("d-none").html("O produto <strong>" + $("#txt-desc").val() + "</strong> foi inserido com sucesso");
                    $(".alert-section > div:last-child").append('<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
                    '<span aria-hidden="true">&times;</span>'+
                    '</button>')
                    $("#txt-desc").val("");
                    $("#txt-cod").val("");
                    
                }
            });

        }else
        {
            $(".alert-section").append('<div class="alert alert-dismissible fade show w-100 d-none" role="alert">'+
                    ''+
                    '</div>');
            $(".alert-section > div:last-child").removeClass("alert-success").addClass("alert-warning").removeClass("d-none").html("Ambos os campos devem ser preenchidos");
            $(".alert-section > div:last-child").append('<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
                    '<span aria-hidden="true">&times;</span>'+
                    '</button>')
        }

    })

})