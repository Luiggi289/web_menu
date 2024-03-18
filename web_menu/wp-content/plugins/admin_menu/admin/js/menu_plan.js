jQuery(document).ready(function($){



        $("#tabla_plan").on('click','.btn-act-plato',function(){
        


        alert('actu');
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

                    $('#tabla_plan > tbody').empty();
                    $('#tabla_plan tbody').append(det);

                    /*currentRow.find("td:eq(1)").text(obj.plato); 
                    currentRow.find("td:eq(3)").text(obj.id_plato); 
                   */
                    
                    
                     //$('#post-'+id).find('.entry-content').html(resultado);		
                }
    
    
            });
    
    
    
            });
      
            
    $("#tabla_plan").on('click','.btnnuevodish',function(){

        $("#modalselnuevo").modal("show");
        $("#txtplatobuscar").val("");
        $("#txtid_part_day").html($(this).val());        
        $('#tabla_buscar_plato > tbody').empty();
        var currentRow=($(this).parents('tr'));
        var currentIndex=($(this).parents('td'));
        $("#txtindexrow").html(currentRow.index());
        $("#txtindexcol").html(currentIndex.index());


        

    });

    $("#tabla_plan").on('click','.btn-plato',function(){
        var id_dish=$(this).val();
       
    });


    $(".btn_cerrar_modal").click(function(){
        $('#modalselnuevo').modal('hide');
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
    

    $("#tabla_buscar_plato").on('click','.btn_sel_plato',function(){
    
        
        var IndexcurrentRow =$("#txtindexrow").html();
        var IndexcurrentCol =$("#txtindexcol").html();
        var id_menu_week =$("#lbl-id-menu-week").html(); 
        var currentseldt=($(this).parents('td'));
        var currentRowPlan=$('#tabla_plan tbody tr:nth('+IndexcurrentRow+')');
        var id_dish=currentseldt.prev().prev().html();

        var html="  <div class='btn-group dropend' role='group'> \
                        <button type='button' class='btn btn-outline-secondary btn-sm btn-plato' data-bs-toggle='dropdown' aria-expanded='false' \
                            value="+id_dish+" style='color: black;border-radius: 12px ;  border: 1px solid #DAD4D3'> \
                            "+currentseldt.prev().html()+" ⁝ \
                        </button> \
                        <ul class='dropdown-menu'> \
                        <li><a class='dropdown-item' href='#'>Ver</a></li> \
                        <li><a class='dropdown-item' href='#'>Recomendar</a></li> \
                        <li><a class='dropdown-item btn_plato_eli'  onclick='eliminarplato(this)' href='#'>Eliminar</a></li> \
                        </ul> \
                    </div>   ";
        currentRowPlan.find('td:eq('+IndexcurrentCol+')').find('.btnnuevodish').before(html);
        var date_menu=currentRowPlan.find("td:eq(3)").text();
        var id_day=currentRowPlan.find("td:eq(2)").text();
        var id_part_day=0;

        if(IndexcurrentCol==1)
        {id_part_day=1;}
        if(IndexcurrentCol==4)
        {id_part_day=2;}
        if(IndexcurrentCol==5)
        {id_part_day=3;}
        
        var obj=new Object();
        obj.id_menu_week =  id_menu_week;
        obj.id_dish = id_dish;
        obj.date_menu = date_menu;
        obj.id_part_day = id_part_day;
        obj.id_day = id_day;
        
        
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

        $('#modalselnuevo').modal('hide');
 
    });
        
    $("#tabla_plan").on('click','.btn_plato_eli',function(){
    
        var id_menu_week =$("#lbl-id-menu-week").html(); 
        var id_dish= $(this).parent().parent().siblings().val();
        var id_part_day=0;

        var IndexcurrentCol=($(this).parents('td')).index();
        var date_menu=($(this).parents('tr')).find("td:eq(3)").text();

        

        if(IndexcurrentCol==1)
        {id_part_day=1;}
        if(IndexcurrentCol==4)
        {id_part_day=2;}
        if(IndexcurrentCol==5)
        {id_part_day=3;}
        
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
        


    

   });

   

   function eliminarplato(element) {
    // $(".btn_plato_eli").click(function(){
        
       
      // element.parentElement.parentElement.parentElement.remove();



       //element.remove();
       //$("#modalselnuevo").modal("show");
  
     }
     
     