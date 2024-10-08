

jQuery(document).ready(function($){

    //alert($("#tabla_plan").html());


    $("#tabla_plan").on('click','.btn-act-plato',function()
    {
        


       
        link = $(this);
	 	//id   = link.attr('href').replace(/^.*#more-/,'');
         var currentRow=($(this).parents('tr'));

         var ids_platos='0';
         $("#tabla_plan tbody tr").each(function (index) {
            
           $(this).children("td").each(function (index2) {
               

                   if( index2== 3)
                   {
                    ids_platos = ids_platos+','+ $(this).text();
                       
                   }
               
           
           })
     
       });
       
         
		$.ajax({
			url : dcms_vars.ajaxurl+'?ids='+ids_platos,
			type: 'get',
			data: {
				action : 'dcms_ajax_get_plan_plato',
				id_post: 'test-ajax'//id
			},
			beforeSend: function(){

			},
			success: function(resultado){
                var obj = jQuery.parseJSON( resultado);
                currentRow.find("td:eq(1)").text(obj.plato); 
                currentRow.find("td:eq(3)").text(obj.id_plato); 
               
                
                
				 //$('#post-'+id).find('.entry-content').html(resultado);		
			}


		});

            

    });
       // $('.selectpicker').selectpicker();
       
    

    //$('#id-name').multiselect();
    $("#btn-recomendar").click(function(){
    /*    
    var array_menu_week_det = new Array();
        
    $("#tabla_plan tbody tr").each(function (index) {
        var campo1, campo2, campo3;
        
        var obj = new Object();
        $(this).children("td").each(function (index2) {
            switch (index2) {
                case 0:
                    campo1 = $(this).text();
                    
                    break;
                case 1:
                    campo2 = $(this).text();
                    break;
                case 2:
                    campo3 = $(this).text();
                    break;
                case 4:
                    campo3 = $(this).text();
                    
                    obj.id_dish = $(this).find("button").html();
                    obj.value = 3.1415;
                    break;
            }
            

        })

        array_menu_week_det.push(obj);
    });
      
    */

    //var jsonArray = JSON.parse(JSON.stringify(pluginArrayArg))

    //alert(JSON.stringify(array_menu_week_det));
    //setCookie('PruebaCookie', 'cvalue', 1);
    //localStorage.setItem("miGato", "Juan");
    //alert(localStorage.getItem("miGato"));
    //alert(getCookie('TestCookie'));
    var obj=new Object();
    obj.id =  $("#lbl-id-menu-week").html();
    obj.name = '';
    obj.shortcode = '';
    obj.start_date =  $("#lblfechaini").html();
    obj.end_date = $("#lblfechafin").html();

        link = $(this);
	 	//id   = link.attr('href').replace(/^.*#more-/,'');
        
		$.ajax({
			url : dcms_vars.ajaxurl,
			type: 'post',
			data: {
				action : 'dcms_ajax_cargar_filas',
				id_post: 'test-ajaxdd',
                datos : JSON.stringify(obj)
			},
			beforeSend: function(){
				link.html('Cargando ...');
			},
			success: function(resultado){
                link.html('Recomendar');

                
                var obj = jQuery.parseJSON( resultado);
                
                $("#lbl-id-menu-week").html(obj.general.id_menu_week);

                html="";
                det=obj.data;

                $('#tabla_plan > tbody').empty();
                $('#tabla_plan > thead').empty();
                $('#tabla_plan tbody').append(det);
                	
			}


		});

       // alert('pruebe'+dcms_vars.ajaxurl);

    });

    $(".btnver").click(function(){
        

    });
   


   
   $(".btnedit").click(function(){

    });

    $("#btnatras").click(function(){
        
        var txtfechainiprev = $("#lblfechainiprev").html();//$("#lblfechaini").html();
        var lbl_id_user = $("#lbl-id-user").html()
        var txtfechainiprev_aux=txtfechainiprev+'T00:00:00';
        var newDate = new Date(txtfechainiprev_aux);
        $('#calendar-container').datepicker('setDate', newDate);

        
        if (lbl_id_user!='0')
        {
            $.ajax({
                url : dcms_vars.ajaxurl+'?start_date='+txtfechainiprev,
                type: 'get',
                data: {
                    action : 'dcms_cargar_semana',
                    id_post: 'test-ajax'//id
                },
                beforeSend: function(){

                },
                success: function(resultado){
                    var obj = jQuery.parseJSON( resultado);
                    $("#lblfechaini").html(txtfechainiprev);                
                    $("#lblfechainiprev").html(obj.general.start_date_prev);
                    $("#lblfechafin").html(obj.general.end_date);
                    $("#lblfechanext").html(obj.general.next_date);
                    $("#lbl-id-menu-week").html(obj.general.id_menu_week);

                    html="";
                    det=obj.data;

                    $('#tabla_plan').html(det) ;

                }


            });


        }
        else{
           
            $("#p_mensaje").html("Para ver tu planificación de otras fechas necesitas iniciar sesión");
            $("#modalsoliniciosession").modal("show");
        }

        
		});

        $("#btnadelante").click(function(){

           
        
            var txtfechanext = $("#lblfechanext").html();//$("#lblfechaini").html();

            var lbl_id_user = $("#lbl-id-user").html()
            alert(txtfechanext);
            var txtfechanext_aux=txtfechanext+'T00:00:00';
            var newDate = new Date(txtfechanext_aux);
            $('#calendar-container').datepicker('setDate', newDate);


        
            if (lbl_id_user!='0')
            {
    
                $.ajax({
                    url : dcms_vars.ajaxurl+'?start_date='+txtfechanext,
                    type: 'get',
                    data: {
                        action : 'dcms_cargar_semana',
                        id_post: 'test-ajax'//id
                    },
                    beforeSend: function(){
        
                    },
                    success: function(resultado){
                        var obj = jQuery.parseJSON( resultado);
                        $("#lblfechaini").html(txtfechanext);
                        $("#lblfechainiprev").html(obj.general.start_date_prev);
                        $("#lblfechafin").html(obj.general.end_date);
                        $("#lblfechanext").html(obj.general.next_date);
                        $("#lbl-id-menu-week").html(obj.general.id_menu_week);

                        det=obj.data;
                        html="";
                        det=obj.data;

                        $('#tabla_plan').html(det) ;
	
                    }
        
        
                });
            }
            else{
           
                $("#p_mensaje").html("Para planificar la siguiente semana necesitas iniciar sesión");
                $("#modalsoliniciosession").modal("show");
            }
    
    
            });
      
    $("#btn_guardar").click(function(){
        var lbl_id_user = $("#lbl-id-user").html()
        
        if (lbl_id_user=='0')
        {
        $("#p_mensaje").html("Para guardar tu plan necesitas iniciar sesión");
        $("#modalsoliniciosession").modal("show");
        }
        else
        {
            alert('Tu Plan se guardo correctamente')
        }
    });
  
    $("#tabla_plan").on('click','.btnnuevodish, .btnagregardish',function(){

        $("#modalselnuevo").modal("show");
        $("#txtplatobuscar").val("");
        var IndexcurrentRow=($(this).parents('tr')).index();
        id_part_day=IndexcurrentRow-1;        
        $("#txtid_part_day").html(id_part_day);
        $("#txttype_action").html("agregar");          
        $('#tabla_buscar_plato > tbody').empty();
        var currentRow=($(this).parents('tr'));
        var currentIndex=($(this).parents('td'));
        $("#txtindexrow").html(currentRow.index());
        $("#txtindexcol").html(currentIndex.index());
        /*
        $.ajax({
            url : dcms_vars.ajaxurl+'?id_part_day='+id_part_day +'&id_dish='+  id_dish,
            type: 'get',
            data: {
                action : 'dcms_buscar_plato_rand',
                id_post: 'test-ajax'//id
            },
            beforeSend: function(){

            },
            success: function(resultado){
                
                $('#tabla_buscar_plato > tbody').empty();
                $('#tabla_buscar_plato tbody').append(resultado);
                
            }


        });
        */


        

    });

    $("#tabla_plan").on('click','.btn_plato_camb',function(){

        $("#modalselnuevo").modal("show");
        $("#txtplatobuscar").val("");
        id_part_day=$(this).parent().parent().siblings().attr("attr-IdPartDay");
        id_dish=$(this).parent().parent().siblings().attr("attr-IdDish");

        $("#txtid_dish").html(id_dish);  
        $("#txtid_part_day").html(id_part_day);  
        $("#txttype_action").html("cambiar");        
        $('#tabla_buscar_plato > tbody').empty();
        var currentRow=($(this).parents('tr'));
        var currentIndex=($(this).parents('td'));
        $("#txtindexrow").html(currentRow.index());
        $("#txtindexcol").html(currentIndex.index());
        
        $.ajax({
            url : dcms_vars.ajaxurl+'?id_part_day='+id_part_day +'&id_dish='+  id_dish,
            type: 'get',
            data: {
                action : 'dcms_buscar_plato_rand',
                id_post: 'test-ajax'//id
            },
            beforeSend: function(){

            },
            success: function(resultado){
                
                $('#tabla_buscar_plato > tbody').empty();
                $('#tabla_buscar_plato tbody').append(resultado);
                
            }


        });


        

    });


    $("#tabla_plan").on('click','.btn-plato',function(){
        var id_dish=$(this).val();
       
    });


    $(".btn_cerrar_modal").click(function(){
        $('#modalselnuevo').modal('hide');
    });

    $(".btn_cerrar_modal2").click(function(){
        $('#modalsoliniciosession').modal('hide');
    });
    
    $("#btn_inicio_sesion").click(function(){
       
        var URLredirect = location.protocol + '//' + location.host+'/web_menu/login/';
        window.location.href = URLredirect;
    });

    $("#button-buscar-dish").click(function(){

        var name_plato=$("#txtplatobuscar").val();
        var id_part_day=$("#txtid_part_day").html();
        $.ajax({
            url : dcms_vars.ajaxurl+'?id_part_day='+id_part_day +'&name='+  name_plato,
            type: 'get',
            data: {
                action : 'dcms_buscar_plato',
                id_post: 'test-ajax'//id
            },
            beforeSend: function(){

            },
            success: function(resultado){
                $('#tabla_buscar_plato > tbody').empty();
                $('#tabla_buscar_plato tbody').append(resultado);
                
            }


        });
    });
    

    $("#tabla_plan").on('click','.btn_plato_ver',function(){

        

       // $("#titulo_modal_dish").html('Ver Plato');
        $("#txtId").val($(this).parent().parent().siblings().attr("attr-IdDish"));
        $("#modal-ver-plato").modal("show");

       // $("#txtnombre").attr("readonly", true);
        $("#textareadescription").attr("readonly", true);  
       // $("#sel_type_dish").attr("disabled", "disabled"); 
        //$("#btnguardardish").attr("hidden", true);
               
        

        cargar_objetos_dish($(this),'see');
        

        $("#id_type_menu_1").prop('checked', false).change();
        $("#id_type_menu_2").prop('checked', false).change();
        $("#id_type_menu_3").prop('checked', false).change();
        $("#id_type_menu_1").attr("disabled", true);
        $("#id_type_menu_2").attr("disabled", true);
        $("#id_type_menu_3").attr("disabled", true);
        /*
        
        */

       // console.log('click nuevo');
    });

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
                //alert(obj.dish[0].name);
                $("#txtnombre").html(   'Plato      : '+obj.dish[0].name);
                $("#txttipoplato").html('Tipo Plato : '+obj.dish[0].name_type_dish);
               // $("#txttipomenu").html('Tipo Menu : '+obj.dish[0].name_part_day);
                /*$("#sel_type_dish").val(obj.dish[0].id_type_dish);
                */ 

                if(obj.dish[0].id_part_day_des!='0')
                {
                    $("#id_type_menu_1").prop('checked', true).change();
                }
                if(obj.dish[0].id_part_day_alm!='0')
                {
                    $("#id_type_menu_2").prop('checked', true).change();
                }
                if(obj.dish[0].id_part_day_cen!='0')
                {
                    $("#id_type_menu_3").prop('checked', true).change();
                }
                
                $("#textareadescription").val(obj.dish[0].description);  
                             

                var tr_html="";
                $.each( obj.ingredient, function( key, value ) {

                    tr_html=obtenerhtmltableingred(value.name_ingredient,value.quantity,value.name_unit_measure);
                    $('#tabla_det_ingred tbody').append(tr_html);                    
                    
                  });

                 if(type_action=='see')
                 {
                    $("#tabla_det_ingred *").attr("disabled","disabled"); 
                 }
                 

                    
                 
                 //$("#tabla_det_ingred *").attr("readonly", true); 

                //$('#tabla_plan > tbody').empty();
                //$('#tabla_plan tbody').append(det);

			}


		});

    };

    var obtenerhtmltableingred = function(name_ingredient,quantity,unit_measure) {

       // var json_unit_measure = $('#lbljson_unit_measure').html();
       // var obj = jQuery.parseJSON( json_unit_measure);
        var html_sel_unit_measure="";
/*
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
        */
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
                                <input type='text' \
                                 value ='"+unit_measure+"'> \
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


    $(".btn-cerrar-modal-new-dish").click(function(){
        
        $('#modal-ver-plato').modal('hide');
    });

    var obtenerhtmldish = function(id_dish,id_part_day,dish) {

        var html="  <div class='btn-group dropend' role='group'> \
        <button type='button' class='btn btn-outline-secondary btn-sm btn_plato' data-bs-toggle='dropdown' aria-expanded='false' \
            attr-IdDish='"+id_dish+"' attr-IdPartDay='"+id_part_day+"' style='background-color: #A8E6A9 ; color:black ; border: 1.5px solid white  '> \
            "+dish.replace("(Recomendado)","")+" ⁝ \
        </button> \
        <ul class='dropdown-menu'> \
        <li><a class='dropdown-item btn_plato_ver'  href='#'>Ver</a></li> \
        <li><a class='dropdown-item btn_plato_camb' href='#'>Cambiar</a></li> \
        <li><a class='dropdown-item btn_plato_eli'  href='#'>Eliminar</a></li> \
        </ul> \
    </div>   ";
    return html;

    };

    $("#tabla_buscar_plato").on('click','.btn_sel_plato',function(){
    
        
        var IndexcurrentRow =$("#txtindexrow").html();
        var IndexcurrentCol =$("#txtindexcol").html();
        var id_menu_week =$("#lbl-id-menu-week").html(); 
        var id_part_day = $("#txtid_part_day").html();
        var id_dish_old = $("#txtid_dish").html();
        var type_action = $("#txttype_action").html();
        var currentseldt=($(this).parents('td'));
        var currentRowPlan=$('#tabla_plan tbody tr:nth('+IndexcurrentRow+')');
        var id_dish=currentseldt.prev().prev().html();
        var plato=currentseldt.prev().html().replace("(Recomendado)","");
        
        
        if(type_action=='agregar')
        {
            var html=obtenerhtmldish(id_dish,id_part_day,plato);
            currentRowPlan.find('td:eq('+IndexcurrentCol+')').find('.btnnuevodish').before(html);
        }
        if(type_action=='cambiar')
            {
                id_boton=id_dish_old+"_"+id_part_day;
                currentRowPlan.find('td:eq('+IndexcurrentCol+')').find('#'+id_boton).html(plato+" ⁝");
                currentRowPlan.find('td:eq('+IndexcurrentCol+')').find('#'+id_boton).attr('attr-IdDish', id_dish);
//                currentRowPlan.find('td:eq('+IndexcurrentCol+')').find('.btn_plato').value(id_dish+" ⁝");
                //alert($("#tabla_plan tr").eq(IndexcurrentRow).find('td:eq('+IndexcurrentCol+')').find('.btn_plato').html());
            }
       
        var date_menu = $("#tabla_plan tr").eq(1).find("td").eq(IndexcurrentCol).text();
        var id_day = $("#tabla_plan tr").eq(2).find("td").eq(IndexcurrentCol).text();

        
        var obj=new Object();
        obj.id_menu_week =  id_menu_week;
        obj.id_dish = id_dish;
        obj.date_menu = date_menu;
        obj.id_part_day = id_part_day;
        obj.id_day = id_day;
        if(type_action=='cambiar')
        {
            obj.id_dish_old = id_dish_old;
        }
            

        if(type_action=='agregar')
        {
            $.ajax({
                url : dcms_vars.ajaxurl,
                type: 'post',
                data: {
                    action : 'dcms_ajax_insert_menu_week_det',
                    id_post: 'test-ajaxdd',
                    datos : JSON.stringify(obj)
                },
                beforeSend: function(){
                },
                success: function(resultado){

                        
                }

            });
        }
        if(type_action=='cambiar')
        {
            $.ajax({
                url : dcms_vars.ajaxurl,
                type: 'post',
                data: {
                    action : 'dcms_ajax_update_menu_week_det',
                    id_post: 'test-ajaxdd2',
                    datos : JSON.stringify(obj)
                },
                beforeSend: function(){
                },
                success: function(resultado){

                        
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('Error: ', textStatus, errorThrown);
                }

            });
        }
        

        $('#modalselnuevo').modal('hide');
 
    });
        
    $("#tabla_plan").on('click','.btn_plato_eli',function(){
    
        var id_menu_week =$("#lbl-id-menu-week").html(); 
        //var id_dish= $(this).parent().parent().siblings().val();
        var id_dish=$(this).parent().parent().siblings().attr("attr-IdDish");
        var id_part_day=$(this).parent().parent().siblings().attr("attr-IdPartDay");

        var IndexcurrentCol=($(this).parents('td')).index();
        //var IndexcurrentRow=($(this).parents('tr')).index();

        var date_menu = $("#tabla_plan tr").eq(1).find("td").eq(IndexcurrentCol).text();
 

        
        var obj=new Object();
        obj.id_menu_week =  id_menu_week;
        obj.id_dish = id_dish;
        obj.date_menu = date_menu;
        obj.id_part_day = id_part_day;

        
        
        $.ajax({
			url : dcms_vars.ajaxurl,
			type: 'post',
			data: {
				action : 'dcms_ajax_eliminar_menu_week_det',
				id_post: 'test-ajaxdd',
                datos : JSON.stringify(obj)
			},
			beforeSend: function(){
			},
			success: function(resultado){
                
               
                	
			}

		});
        $(this).parent().parent().parent().remove();
        

    });

    $("#btn-limpiar").click(function(){



        


        var id_menu_week =$("#lbl-id-menu-week").html(); 
        
        
        var obj=new Object();
        obj.id_menu_week =  id_menu_week;

        
        
        $.ajax({
			url : dcms_vars.ajaxurl,
			type: 'post',
			data: {
				action : 'dcms_ajax_eliminar_menu_week',
				id_post: 'test-ajaxdd',
                datos : JSON.stringify(obj)
			},
			beforeSend: function(){
			},
			success: function(resultado){
            
            $('#tabla_plan .btn_plato').remove();   
                	
			}

		});
        
       
        

    });

        

    $("#tabla_plan").on('click','.btn_plato_recom',function(){
    
        var id_menu_week =$("#lbl-id-menu-week").html(); 
        //var id_dish= $(this).parent().parent().siblings().val();
        var id_dish=$(this).parent().parent().siblings().attr("attr-IdDish");
        
        var id_part_day=0;

        var IndexcurrentCol=($(this).parents('td')).index();
        var IndexcurrentRow=($(this).parents('tr')).index();
        
        var date_menu=($(this).parents('tr')).find("td:eq(3)").text();
        var id_day=($(this).parents('tr')).find("td:eq(2)").text();
        

        if(IndexcurrentCol==1)
        {id_part_day=1;}
        if(IndexcurrentCol==4)
        {id_part_day=2;}
        if(IndexcurrentCol==5)
        {id_part_day=3;}

        
        //var currentRow=(obj_this.parents('tr'));
       
        var ids_platos='0';
        $("#tabla_plan tbody tr").each(function (index) {
            
            var loopRowPlan=$('#tabla_plan tbody tr:nth('+index+')');

            $(this).children("td").each(function (index2) {
                

                    if( index2==IndexcurrentCol ) // 
                    {

                        
                        //aux_id_plato=loopRowPlan.find('td:eq('+index2+')').find('.btn_plato').val();

                        loopRowPlan.find('td:eq('+index2+')').children().each(function( index ) {
                            aux_id_plato=$( this).find('.btn_plato').val();
                            //console.log( index + ": " + $( this).find('.btn_plato').val() );

                            if(aux_id_plato != undefined)
                                {
                                ids_platos = ids_platos+','+aux_id_plato;
                                }

                          });

                        
                        
                        
                    //ids_platos = ids_platos+','+'0';
                        
                    }
                
            
            })
    
        });
        
        var obj=new Object();
        obj.id_menu_week =  id_menu_week;
        obj.id_dish = id_dish;
        obj.date_menu = date_menu;
        obj.id_part_day = id_part_day;
        obj.ids_dish = ids_platos;
        obj.id_day = id_day;
        
        var obj_this=$(this);
        
        $.ajax({
			url : dcms_vars.ajaxurl,
			type: 'post',
			data: {
				action : 'dcms_ajax_cambiar_menu_week_det',
				id_post: 'test-ajaxdd',
                datos : JSON.stringify(obj)
			},
			beforeSend: function(){
			},
			success: function(resultado)
            {

                //obj_this.parent().parent().parent().remove();
                var currentRowPlan=$('#tabla_plan tbody tr:nth('+IndexcurrentRow+')');
                var obj_result = jQuery.parseJSON( resultado);
                
                var html=obtenerhtmldish(obj_result.id_dish,id_part_day,obj_result.name_dish);
                obj_this.parent().parent().parent().html(html);
               // currentRowPlan.find('td:eq('+IndexcurrentCol+')').find('.btnnuevodish').before(html);


        
                
               
                	
			}

		});
       


        

        
        

    });

    ///////

    function formatDate(date) {
        var year = date.getFullYear();
        var month = (date.getMonth() + 1).toString().padStart(2, '0'); // Meses en base 0
        var day = date.getDate().toString().padStart(2, '0');
        return `${year}-${month}-${day}`;
    }
    
    var dateFormat = "dd-mm-yy";

    $('#calendar-container').datepicker({
        showOtherMonths: true,
        selectOtherMonths: true,
        firstDay: 0,  // Para que la semana comience el domingo
        onSelect: function(dateText) {

            // Obtener la fecha seleccionada
            var date_sel = $(this).datepicker('getDate');

            var date=new Date(date_sel.getTime() );

            var startDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() );

            var startDate_prev = new Date(startDate); // Copiar el valor de startDate
            startDate_prev.setDate(startDate.getDate() - 7); // Restar 7 días

            // Formatear startDate a 'YYYY-MM-DD'
            var formattedStartDate = formatDate(startDate);
            var formattedStartDatePrev = formatDate(startDate_prev);

            $("#lblfechaini").html(formattedStartDate);                
            $("#lblfechainiprev").html(formattedStartDatePrev);
            

                ///
                var lbl_id_user = $("#lbl-id-user").html()
        
                if (lbl_id_user!='0')
                {
                    $.ajax({
                        url : dcms_vars.ajaxurl+'?start_date='+formattedStartDate,
                        type: 'get',
                        data: {
                            action : 'dcms_cargar_semana',
                            id_post: 'test-ajax'//id
                        },
                        beforeSend: function(){
        
                        },
                        success: function(resultado){
                            var obj = jQuery.parseJSON( resultado);
                            $("#lblfechaini").html(formattedStartDate);                
                            $("#lblfechainiprev").html(obj.general.start_date_prev);
                            $("#lblfechafin").html(obj.general.end_date);
                            $("#lblfechanext").html(obj.general.next_date);
                            $("#lbl-id-menu-week").html(obj.general.id_menu_week);
        
                            html="";
                            det=obj.data;
        
                            $('#tabla_plan').html(det) ;
        
                        }
        
        
                    });
        
        
                }
                else{
                   
                    $("#p_mensaje").html("Para ver tu planificación de otras fechas necesitas iniciar sesión");
                    $("#modalsoliniciosession").modal("show");
                }
                ///
        }
    });


    //////

   });

   
   function setCookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    let expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
  }

   function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
  }

   function eliminarplato(element) {
    // $(".btn_plato_eli").click(function(){
        
       
      // element.parentElement.parentElement.parentElement.remove();



       //element.remove();
       //$("#modalselnuevo").modal("show");
  
     }
     
     