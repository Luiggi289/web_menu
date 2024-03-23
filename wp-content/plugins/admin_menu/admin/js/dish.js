

jQuery(document).ready(function($){


 
    $("#formulario").submit(function() {
        var x = "enviar";
        if (x!="enviar") {
            alert("Valor introducido no válido");		
            return false;
        } else 
            return true;			
        });
      


    //$('#id-name').multiselect();
    $("#btnnuevo").click(function(){
        var lbl_id_user = $("#lbl-id-user").html();
        
        if (lbl_id_user!='0')
        {
            $("#sel_type_dishval").text("");
            $("#txtnombreval").text("");
            $("#general_dishval").text("");
            $("#check_type_menu").text("");
            $("#titulo_modal_dish").html('Nuevo Plato');
            $("#modalnuevo").modal("show");
            $("#txtId").val('0');
            $("#id_type_menu_1").prop('checked', false).change();
            $("#id_type_menu_2").prop('checked', false).change();
            $("#id_type_menu_3").prop('checked', false).change();
            $("#id_type_menu_1").attr("disabled", false);
            $("#id_type_menu_2").attr("disabled", false);
            $("#id_type_menu_3").attr("disabled", false);
            $("#txtnombre").attr("readonly", false);
            $("#sel_type_dish").attr("disabled", false);
            $("#txtnombre").val("");
            $("#sel_type_dish").val("");
            $("#textareadescription").val("");
            $("#textareadescription").attr("readonly", false);        
            $("#btnguardardish").attr("hidden", false);

            $('#tabla_det_ingred > tbody').empty();
                    
            tableHTML = obtenerhtmltableingred('','1',1) ;

            $('#tabla_det_ingred tbody').append(tableHTML); 

        // console.log('click nuevo');
        }
        else
        {

            $("#p_mensaje").html("Para crear un nuevo plato necesitas iniciar sesión");
            $("#modalsoliniciosession").modal("show");
            

        }
    });

    
    $("#tabla_dish").on('click','.btnver',function(){

        
        
        $("#titulo_modal_dish").html('Ver Plato');
        $("#txtId").val($(this).attr("attr-IdDish"));
        $("#modalnuevo").modal("show");

        $("#txtnombre").attr("readonly", true);
        $("#textareadescription").attr("readonly", true);  
        $("#sel_type_dish").attr("disabled", "disabled"); 
        $("#btnguardardish").attr("hidden", true);
               
        
       
       cargar_objetos_dish($(this),'see');
        

        $("#id_type_menu_1").prop('checked', false).change();
        $("#id_type_menu_2").prop('checked', false).change();
        $("#id_type_menu_3").prop('checked', false).change();
        $("#id_type_menu_1").attr("disabled", true);
        $("#id_type_menu_2").attr("disabled", true);
        $("#id_type_menu_3").attr("disabled", true);
        
        if($(this).attr("attr-IdTypeMenu1")=='1')
        {
            $("#id_type_menu_1").prop('checked', true).change();
        }
        if($(this).attr("attr-IdTypeMenu2")=='1')
        {
            $("#id_type_menu_2").prop('checked', true).change();
        }
        if($(this).attr("attr-IdTypeMenu3")=='1')
        {
            $("#id_type_menu_3").prop('checked', true).change();
        }

       // console.log('click nuevo');
    });
   // $('.selectpicker').selectpicker();
   


   $("#tabla_dish").on('click','.btnedit',function(){

    var lbl_id_user = $("#lbl-id-user").html();
        
        if (lbl_id_user!='0')
        {

            $("#titulo_modal_dish").html('Editar Plato');
            $("#modalnuevo").modal("show");
            $("#txtId").val($(this).attr("attr-IdDish"));
            $("#txtnombre").attr("readonly", false);
            $("#sel_type_dish").attr("disabled", false);
            $("#textareadescription").attr("readonly", false);

            $("#id_type_menu_1").attr("disabled", false);
            $("#id_type_menu_2").attr("disabled", false);
            $("#id_type_menu_3").attr("disabled", false);
            $("#btnguardardish").attr("hidden", false);
            
            cargar_objetos_dish($(this),'edit');

            


            $("#id_type_menu_1").prop('checked', false).change();
            $("#id_type_menu_2").prop('checked', false).change();
            $("#id_type_menu_3").prop('checked', false).change();

            
            if($(this).attr("attr-IdTypeMenu1")=='1')
            {
                $("#id_type_menu_1").prop('checked', true).change();
            }
            if($(this).attr("attr-IdTypeMenu2")=='1')
            {
                $("#id_type_menu_2").prop('checked', true).change();
            }
            if($(this).attr("attr-IdTypeMenu3")=='1')
            {
                $("#id_type_menu_3").prop('checked', true).change();
            }

        }
        else
        {

            $("#p_mensaje").html("Para editar un plato necesitas iniciar sesión");
            $("#modalsoliniciosession").modal("show");
            

        }
   // console.log('click nuevo');
    });

    $("#tabla_dish").on('click','.btndel',function(){
        

        var lbl_id_user = $("#lbl-id-user").html()
        
        if (lbl_id_user!='0')
        {
            var lbl_id_user = $("#lbl-id-user").html();
            var id_dish=$(this).attr("attr-IdDish");
            $("#lbl_id_dish_eli").html(id_dish);

            var index_row=($(this).parents('tr').index());
            $("#lbl_index_eli").html(index_row);

            if (lbl_id_user!='0')
                {
                    //$("#lbl_id_dish_eli").html(id_dish);
                    $("#modal_confirm_eli").modal("show");
                }

        }
        else
        {

            $("#p_mensaje").html("Para eliminar un plato necesitas iniciar sesión");
            $("#modalsoliniciosession").modal("show");
            

        }
        

    });

    $("#btn_aceptar_eliminar").click(function(){

        var lbl_id_user = $("#lbl-id-user").html();
        var id_dish=$("#lbl_id_dish_eli").html();
        var index_eli=parseInt($("#lbl_index_eli").html())+1;   
        var obj=new Object();
        obj.id=id_dish;
            
            if (lbl_id_user!='0')
            {
                $.ajax({
                    url : dcms_vars.ajaxurl,
                    type: 'post',
                    data: {
                        action : 'dcms_ajax_delete_dish',
                        id_post: 'test-ajaxdd',
                        datos : JSON.stringify(obj)
                    },
                    beforeSend: function(){
                    },
                    success: function(resultado){
                        $('#modal_confirm_eli').modal('hide');
                        //$("#tabla_dish tbody tr").eq(index_eli).remove();
                        document.getElementById("tabla_dish").deleteRow(index_eli);
                        //$("#tabla_dish tr:eq(index_eli)").remove();
                        //var table = document.getElementById("tabla_dish");
                        //table.deleteRow(index_eli);
                        
                       
                    }
    
                });

            }

    });

  
    $(".btn-cerrar-modal-new-dish").click(function(){
        
        $('#modalnuevo').modal('hide');
    });

    $(".btn_cerrar_modal_ini_sesion").click(function(){
        $('#modalsoliniciosession').modal('hide');
    });

    $(".btn_cerrar_modal_confirm_eli").click(function(){
        
        $('#modal_confirm_eli').modal('hide');
    });


    $("#btn_inicio_sesion").click(function(){
       
        var URLredirect = location.protocol + '//' + location.host+'/web_menu/login/';
        window.location.href = URLredirect;
    });

    $("#modalver").click(function(){
        
       // $('#modalver').modal('hide');
    });

    $("#tabla_det_ingred").on('click','.btn_add_ingred',function(){
        
        var table = document.getElementById("tabla_det_ingred");
        var currentIndexRow=($(this).parents('tr')).index();

        
        var row = table.insertRow(currentIndexRow+2);
        row.innerHTML = obtenerhtmltableingred('','1',1) ;
                        //);
    });

    $("#tabla_det_ingred").on('click','.btn_del_ingred',function(){

        $(this).parent().parent().remove();
        
        
    });

    var validar_campos = function() {
        
        var valid=true; 
        if($("#sel_type_dish").val()==null)
        {
            $("#sel_type_dishval").text("Seleccione un tipo de plato");
            $("#sel_type_dishval").css("color", "red");
            valid= false;

        }
        else{
            $("#sel_type_dishval").text("");
        }


        if( $("#txtnombre").val().trim()=="")
        {
            $("#txtnombreval").text("Añade un nombre de plato");
            $("#txtnombreval").css("color", "red");
            valid= false;

        }
        else
        {
            $("#txtnombreval").text("");
        }

        if(
            $("#id_type_menu_1").is(':checked')==false && 
            $("#id_type_menu_2").is(':checked')==false &&
            $("#id_type_menu_3").is(':checked')==false
        )
        {
            $("#check_type_menu").text("Seleccione un tipo de menú");
            $("#check_type_menu").css("color", "red");
            //alert("Añade un chek")
            valid= false;
        }
        else
        {
            $("#check_type_menu").text("");
        }

        if(valid==false)
        {
            $("#general_dishval").text("Complete los campos obligatorios");
            $("#general_dishval").css("color", "red");
        }
        else
        {
            $("#general_dishval").text("");
            
        }
        



        return valid;
    }

    $("#btnguardardish").click(function(){

        if (validar_campos())
        {

            var id_dish=$("#txtId").val();
        
            var data_ingredient = [];
            $("#tabla_det_ingred tbody tr").each(function (index) {
            var ingredient_det=new Object();  
            ingredient_det.sequence=  index+1;    
            //alert($(this).attr("hidden"));
            

                $(this).children("td").each(function (index2) {

                        if( index2== 1)
                        {
                            ingredient_det.name=$(this).children().val(); ;                        
                        }
                        if( index2== 2)
                        {
                            ingredient_det.quantity =$(this).children().val(); ;                        
                        }  
                        if( index2== 3)
                        {
                            ingredient_det.id_unit_measure =$(this).children().val(); ;                        
                        }                              
                })

                if(ingredient_det.name!='')
                {
                    data_ingredient.push(ingredient_det); 
                }

                
            
            });
            var data_dish_part_day = [];
            
            if($("#id_type_menu_1").is(':checked'))
            {
                dish_part_day=new Object(); 
                dish_part_day.id_dish=id_dish;
                dish_part_day.id_part_day='1';
                data_dish_part_day.push(dish_part_day);
            }
            if($("#id_type_menu_2").is(':checked'))
            {   
                dish_part_day=new Object(); 
                dish_part_day.id_dish=id_dish;
                dish_part_day.id_part_day='2';
                data_dish_part_day.push(dish_part_day);
            }
            if($("#id_type_menu_3").is(':checked'))
            {
                dish_part_day=new Object(); 
                dish_part_day.id_dish=id_dish;
                dish_part_day.id_part_day='3';
                data_dish_part_day.push(dish_part_day);
            }
            

            var obj=new Object();
            
            obj.id_type_dish = $("#sel_type_dish").val();
            obj.name = $("#txtnombre").val();
            obj.description = $("#textareadescription").val();
            
            obj.id=id_dish;
            obj.ingredient = data_ingredient;
            obj.part_day=data_dish_part_day;

            //alert(JSON.stringify(obj));

            if(id_dish==0)
            {
                $.ajax({
                    url : dcms_vars.ajaxurl,
                    type: 'post',
                    data: {
                        action : 'dcms_ajax_insert_dish',
                        id_post: 'test-ajaxdd',
                        datos : JSON.stringify(obj)
                    },
                    beforeSend: function(){
                    },
                    success: function(resultado){
                        
                        $.ajax({
                            url : dcms_vars.ajaxurl,
                            type: 'get',
                            data: {
                                action : 'dcms_ajax_get_table_platos',
                                id_post: 'test-ajax'//id
                            },
                            success: function(resultado){
                                $('#tabla_dish > tbody').empty();
                                $('#tabla_dish tbody').append(resultado);                    
                            }
                            
                        });
                        $('#modalnuevo').modal('hide');
                    }

                });
            }
            else
            {   
                $.ajax({
                    url : dcms_vars.ajaxurl,
                    type: 'post',
                    data: {
                        action : 'dcms_ajax_update_dish',
                        id_post: 'test-ajaxdd',
                        datos : JSON.stringify(obj)
                    },
                    beforeSend: function(){
                    },
                    success: function(resultado){
                        //location.reload();
                        $('#modalnuevo').modal('hide');
                    }

                });
                
            }
        }
        else // Falta ingresar datos
        {
            //alert("Ingresar los campos obligatorios")

        }

    });


    var obtenerhtmltableingred = function(name_ingredient,quantity,id_unit_measure) {

        var json_unit_measure = $('#lbljson_unit_measure').html();
        var obj = jQuery.parseJSON( json_unit_measure);
        var html_sel_unit_measure="";

        $.each( obj, function( key, value ) {

            if(value.id==id_unit_measure)
            {
                html_sel_unit_measure=html_sel_unit_measure + "<option value='"+value.id+"' selected='selected' >"+value.name+"</option>" ;                           
            }
            else
            {
            html_sel_unit_measure=html_sel_unit_measure + "<option value='"+value.id+"' >"+value.name+"</option>" ;                
            }

          });
        
        var text ="\
                        <tr>\
                            <td style=display:none;>Id</td> \
                            <td ><input type='text' id='uname' name='name' \
                            placeholder='Ingresar ingrediente' value ='"+name_ingredient+"'> \
                            </td>\
                            <td > \
                            <input type='number' id='tentacles' name='tentacles' min='1' max='10000' value='"+quantity +"'> \
                            </td> \
                            <td >\
                                <select class='form-control' id='sel_unidad' name='sel_unidad'>\
                                "+html_sel_unit_measure+"\
                                </select>\
                            </td>\
                            <td >\
                                <button class='btn btn-secondary btn-sm btn_add_ingred' style='margin-left:5px'>\
                                <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-plus-circle' viewBox='0 0 16 16'> \
                                    <path d='M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z'/> \
                                    <path d='M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z'/> \
                                </svg>  \
                                </button> \
                                <button class='btn btn-secondary btn-sm btn_del_ingred' style='margin-left:5px'> \
                                    <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash3' viewBox='0 0 16 16'>\
                                        <path d='M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z'/>\
                                    </svg>\
                                </button>\
                            </td> \
                        </tr> " ;
        return text;
    };

    var cargar_objetos_dish = function(object,type_action) {

       

        
        var id_dish=$("#txtId").val();
       
        
        $('#tabla_det_ingred > tbody').empty();
        $("#txtnombre").val('');
        $("#txttipoplato").val('');
        $("#sel_type_dish").val('');
        $("#textareadescription").val('');
        
        $.ajax({
			url : dcms_vars.ajaxurl+'?id_dish='+id_dish,
			type: 'get',
			data: {
				action : 'dcms_cargar_dish',
				id_post: 'test-ajax'//id
			},
			beforeSend: function(){

			},
			success: function(resultado){
                var obj = jQuery.parseJSON( resultado);  
                 
                $("#txtnombre").val(obj.dish[0].name);
                $("#txttipoplato").val(obj.dish[0].name_type_dish);
                $("#sel_type_dish").val(obj.dish[0].id_type_dish);
                $("#textareadescription").val(obj.dish[0].description);                

                var tr_html="";
                $.each( obj.ingredient, function( key, value ) {
                    tr_html=obtenerhtmltableingred(value.name_ingredient,value.quantity,value.id_unit_measure);
                    $('#tabla_det_ingred tbody').append(tr_html);                    
                    
                  });

                 if(type_action=='see')
                 {
                    $("#tabla_det_ingred *").attr("disabled","disabled"); 
                 }
                 if (type_action=='edit')
                 {
                    var rowCount = $('#tabla_det_ingred >tbody >tr').length;
                    if(rowCount==0)
                    {
                        tr_html=obtenerhtmltableingred('',1,1);
                        $('#tabla_det_ingred tbody').append(tr_html);   
                    }
                 }
                 

                    
                 
                 //$("#tabla_det_ingred *").attr("readonly", true); 

                //$('#tabla_plan > tbody').empty();
                //$('#tabla_plan tbody').append(det);

			}


		});

    };

});

