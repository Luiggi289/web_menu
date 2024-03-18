jQuery(document).ready(function($){





    $("#btnatras").click(function(){
        
        var txtfechainiprev = $("#lblfechainiprev").html();//$("#lblfechaini").html();
        
        

        $.ajax({
			url : dcms_vars.ajaxurl+'?start_date='+txtfechainiprev,
			type: 'get',
			data: {
				action : 'dcms_cargar_lista_compra',
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

                $('#tabla_plan > tbody').empty();
                $('#tabla_plan tbody').append(det);

			}


		});



		});

    $("#btnadelante").click(function(){

        
            var txtfechanext = $("#lblfechanext").html();//$("#lblfechaini").html();
            
            
    
            $.ajax({
                url : dcms_vars.ajaxurl+'?start_date='+txtfechanext,
                type: 'get',
                data: {
                    action : 'dcms_cargar_lista_compra',
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

                    $('#tabla_plan > tbody').empty();
                    $('#tabla_plan tbody').append(det);
	
                }
    
    
            });
    

            });
      




    $(".btn_cerrar_modal").click(function(){
        $('#modalselnuevo').modal('hide');
    });
    

    

    

   });

   

 