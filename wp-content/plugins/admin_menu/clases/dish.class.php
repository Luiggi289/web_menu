<?php

class dish{

    public function ObtenerDish($id_dish){
        global $wpdb;
        $t_dish = "{$wpdb->prefix}dish";
        $t_unit_measure  = "{$wpdb->prefix}unit_measure ";
        $t_dish_ingredient = "{$wpdb->prefix}dish_ingredient";        
        $t_part_day = "{$wpdb->prefix}part_day";
        $t_ingredient ="{$wpdb->prefix}ingredient";
        $t_type_dish ="{$wpdb->prefix}type_dish";  
        $t_dish_part_day ="{$wpdb->prefix}dish_part_day";
        $t_unit_measure ="{$wpdb->prefix}unit_measure";
        
        $query = "
        SELECT 
        a.id ,a.name ,a.id_type_dish ,b.name name_type_dish , a.description 
        ,COALESCE(d.name,'') name_part_day_des 
        ,COALESCE(d.id,0) id_part_day_des 
        ,COALESCE(e.name,'') name_part_day_alm 
        ,COALESCE(e.id,0) id_part_day_alm 
        ,COALESCE(f.name,'') name_part_day_cen 
        ,COALESCE(f.id,0) id_part_day_cen 
        FROM $t_dish a
        inner join $t_type_dish  b on a.id_type_dish=b.id
        left join $t_dish_part_day  c on a.id=c.id_dish
        left join $t_part_day  d on c.id_part_day=d.id and d.id=1
        left join $t_part_day  e on c.id_part_day=e.id and e.id=2
        left join $t_part_day  f on c.id_part_day=f.id and f.id=3
        WHERE a.id='$id_dish'
        
        
        ";
        $datos = $wpdb->get_results($query,ARRAY_A);
        if(empty($datos)){
            $datos = array();
        }

        $query_ingredient = "
        SELECT 
        a.id_ingredient,
        CONCAT(UCASE(LEFT(b.name, 1)), LCASE(SUBSTRING(b.name, 2)))  name_ingredient
        ,a.id_unit_measure ,a.quantity ,c.name name_unit_measure
        FROM $t_dish_ingredient a
        inner join $t_ingredient  b on a.id_ingredient =b.id
        inner join $t_unit_measure  c on a.id_unit_measure =c.id
        WHERE a.id_dish='$id_dish'
        order by a.sequence 
        ";
        $datos_ingredient = $wpdb->get_results($query_ingredient,ARRAY_A);
        if(empty($datos_ingredient)){
            $datos_ingredient = array();
        }


        
        $result = Array
        (
            'dish' => $datos ,
            'ingredient' =>$datos_ingredient
        );


       return json_encode($result);
      
    }

    public function ObtenerListDish(){
        global $wpdb;
        $tabla = "{$wpdb->prefix}dish";
        $tabla2 = "{$wpdb->prefix}type_dish";
        $tabla3 = "{$wpdb->prefix}dish_part_day";

        $current_user = wp_get_current_user(); 
        $id_user=$current_user->ID;
        if($id_user==0)
        {
            $id_user=1;
        }
        
        
        //$query = "SELECT a.id,a.name,a.id_type_dish ,b.name_type_dish FROM $tabla a inner join $tabla2 b on a.id_type_dish=b.id ";
        $query = "SELECT a.id,a.name,a.id_type_dish ,b.name name_type_dish 
        ,CASE WHEN  IFNULL(c.id_part_day ,0) <> 0 THEN 1 ELSE 0 END id_type_menu_1
        ,CASE WHEN  IFNULL(d.id_part_day ,0) <> 0 THEN 1 ELSE 0 END  id_type_menu_2
        ,CASE WHEN  IFNULL(e.id_part_day ,0) <> 0 THEN 1 ELSE 0 END  id_type_menu_3
        FROM $tabla a  
        left join $tabla2 b on a.id_type_dish=b.id
        left join $tabla3 c on a.id=c.id_dish and c.id_part_day =1
        left join $tabla3 d on a.id=d.id_dish and d.id_part_day =2
        left join $tabla3 e on a.id=e.id_dish and e.id_part_day =3
        where id_user=$id_user and a.is_enabled=true
        order by a.id desc 
        ";
        $datos = $wpdb->get_results($query,ARRAY_A);
        if(empty($datos)){
            $datos = array();
           
        }
        return $datos;
    }

    public function formOpen(){

        $current_user = wp_get_current_user(); 
        $id_user=$current_user->ID;
         $html = "
            <div class='wrap'>

                    <label hidden  id='lbl-id-user'  class='nav-link' >$id_user</label>
                
                    <button id='btnnuevo' class='btn btn-secondary' style='margin-left:5px' attr-IdDish='0' attr-action='insert'>Añadir nuevo</button>                
                <br>
         ";

        return $html;
    }

    function fromInput($dish,$id_dish,$id_type_dish,$name_type_dish,$id_type_menu_1,$id_type_menu_2,$id_type_menu_3){
              $html="
              <tr>
                  <td>$id_dish</td>
                  <td>$dish</td>
                  <td>
                       <button class='btn btn-secondary btn-sm btnver' style='margin-left:5px'  attr-IdDish='$id_dish' attr-Name='$dish' attr-NameTypeDish='$name_type_dish' attr-IdTypeMenu1='$id_type_menu_1' attr-IdTypeMenu2='$id_type_menu_2' attr-IdTypeMenu3='$id_type_menu_3'  >
                       
                        <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-eye' viewBox='0 0 16 16'>
                            <path d='M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z'/>
                            <path d='M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z'/>
                        </svg>
                       </button>
                       <button class='btn btn-secondary btn-sm btnedit' style='margin-left:5px'  attr-IdDish='$id_dish' attr-Name='$dish' attr-IdTypeDish='$id_type_dish' attr-IdTypeMenu1='$id_type_menu_1' attr-IdTypeMenu2='$id_type_menu_2' attr-IdTypeMenu3='$id_type_menu_3' attr-action='update' >
                        <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pencil-square' viewBox='0 0 16 16'>
                            <path d='M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z'/>
                            <path fill-rule='evenodd' d='M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z'/>
                        </svg>
                                            
                       </button>
                       <button class='btn btn-secondary btn-sm btndel' style='margin-left:5px' attr-IdDish='$id_dish' >
                       <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash3' viewBox='0 0 16 16'>
                          <path d='M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z'/>
                        </svg>
                       </button>
                  </td>
              </tr>
          ";
   
          return $html;
      }
      public function ObtenerTypeDish(){
        global $wpdb;
        $tabla = "{$wpdb->prefix}type_dish";
        $query = "SELECT * FROM $tabla ";
        $datos = $wpdb->get_results($query,ARRAY_A);
        if(empty($datos)){
            $datos = array();
        }
        return $datos;
    }

    public function ObtenerUnitMeasure (){
        global $wpdb;
        $tabla = "{$wpdb->prefix}unit_measure";
        $query = "SELECT * FROM $tabla ";
        $datos = $wpdb->get_results($query,ARRAY_A);
        if(empty($datos)){
            $datos = array();
        }
        return $datos;
    }



    function fromModalVer(){

        $html1= '';
        return $html1;

    }
                
   
    function fromModalNuevo(){

        $html_unit_measure ="";
        $lista = $this->ObtenerUnitMeasure ();
        $json_unit_measure=json_encode($lista );
        foreach($lista as $key => $value )
        {
            $id= $value['id'];
            $name = $value['name'];
            $html_unit_measure.="<option value='$id'>$name</option> ";
        } 

        $html1= '<!-- fromModalNuevo -->
        <div class="modal fade" id="modalnuevo" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
              
                        <h5 class="modal-title" id="titulo_modal_dish">Nuevo Plato</h5>
                
                        <button type="button" class="close btn-cerrar-modal-new-dish" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="formulario"  >
                                     
                                 <div class="modal-body">
                                        <div class= "form-group">
                                           
                                            <div class="col-sm-8">

                                                <label id="lbljson_unit_measure" hidden > '.$json_unit_measure.'</label>
                                                <input class="form-control" type="hidden" id="txtId" name="txtId" >
                                                <label for="txtnombre" >Plato</label> 
                                                <input class="form-control" type="text" id="txtnombre" name="txtnombre" placeholder="Nombre Plato" aria-label="default input example">                                             
                                                <label id="txtnombreval" ></label> 
                                                <br>
                                                <label >Tipo Plato</label>
                                                
                                                    <select class="form-control" id="sel_type_dish" name="sel_type_dish">';
                                                        $html2="";
                                                        $lista = $this->ObtenerTypeDish();
                                                        foreach($lista as $key => $value )
                                                        {
                                                            $id= $value['id'];
                                                            $name = $value['name'];
                                                            $html2.="
                                                            
                                                                <option value='$id'>$name</option>
                                                                
                                                                ";
                                                        } 

                                                        $html3='       
                                                    </select> 
                                                   
                                                    <label id="sel_type_dishval" ></label> 

                                            </div>
                                        </div>

                                        <div class= "form-group">
                                            <label for="txtnombre" class="col-sm-4 col-form-label">Tipo Menu</label>
                                            </div> 
                                            &nbsp;&nbsp;&nbsp;
                                            <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="id_type_menu_1" name="id_type_menu_1" value="1">
                                            <label class="form-check-label" for="inlineCheckbox1">Desayuno</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="id_type_menu_2" name="id_type_menu_2" value="2">
                                            <label class="form-check-label" for="inlineCheckbox2">Almuerzo</label>
                                            </div>

                                            <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="id_type_menu_3" name="id_type_menu_3" value="3">
                                            <label class="form-check-label" for="inlineCheckbox2">Cena</label>
                                            </div>

                                        
                                            <table class="wp-list-table widefat fixed striped pages" id ="tabla_det_ingred">  <thead> 
                                            <th style=display:none;>Id</th>
                                            <th >Ingrediente</th>
                                            <th >Cantidad</th>
                                            <th >Unidad</th>
                                            <th ></th>
                                            </thead>
                                            <tr>

                                                <td style=display:none;>Id</td>
                                                <td ><input type="text" id="uname" name="name"
                                                placeholder="Ingresar ingrediente">
                                                </td>
                                                <td >
                                                <input type="number" id="tentacles" name="tentacles"
                                                    min="1" max="10000">
                                            </td>
                                                <td >
                                                    <select class="form-control" id="sel_unidad" name="sel_unidad">
                                                    '.$html_unit_measure.'
                                                    </select>
                                                </td>
                                                <td >
                                                    <button class="btn btn-secondary btn-sm btn_add_ingred" style="margin-left:5px">
                                                    
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                                                    </svg>
                                                    </button>

                                                    <button class="btn btn-secondary btn-sm btn_del_ingred" style="margin-left:5px">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                                        <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z"/>
                                                        </svg>
                                                    </button>
                                                </td>

                                            </tr>
                                            </table>
                                        
                                        
                                    </div>

                                <br>

                                <div class= "form-group">
                                           
                                            <div class="col-sm-8">

                                                <label for="txtinstrucciones" >Instrucciones</label> 
                                                <br>
                                                <textarea class="form-control" id="textareadescription" rows="3"></textarea>
                                                <br>
                                            </div>
                                </div>
                                
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-cerrar-modal-new-dish" data-dismiss="modal">Cerrar</button>
                                    <button type="button" class="btn btn-primary" name="btnguardar" id="btnguardardish">Guardar</button>
                                </div>
                    </div>
                    

                </div>
            </div>
        </div>';
       
        return $html1.$html2.$html3;
        
    }

    

    function fromModalSolicitudIniciarSesion(){

        $html1= '<!-- fromModalSolInicioSesion -->
        <div class="modal fade" id="modalsoliniciosession" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                        <div class="modal-header">
                
                            <h5 class="modal-title" >Buscar platos</h5>
                    
                            <button type="button" class="close btn_cerrar_modal_ini_sesion" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>  
            
                        <div class="modal-body">
                                    <div class="modal-body">
                                            
                                    
                                    <p id ="p_mensaje"> </p>

                                           
                                    </div>
                                            
                        </div>
                        
                     

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn_cerrar_modal_ini_sesion"  data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" name="btn_inicio_sesion" id="btn_inicio_sesion" >Iniciar Sesión</button>
                        </div>

                </div>
             </div>
        </div>';
       
        return $html1;
    }


    function fromModalConfirmEliminar(){

        $html1= '<!-- fromModalSolInicioSesion -->
        <div class="modal fade" id="modal_confirm_eli" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                        <div class="modal-header">
                
                            <h5 class="modal-title" >Desea Eliminar</h5>
                    
                            <button type="button" class="close btn_cerrar_modal_confirm_eli" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>  
            
                        <div class="modal-body">
                        <label hidden id="lbl_id_dish_eli"  class="nav-link" ></label>
                        <label hidden id="lbl_index_eli"  class="nav-link" ></label>
                                    <div class="modal-body">
                                            
                                    
                                    <p id ="p_mensaje_confirm_eli">¿ Está seguro que desea eliminar el registro? </p>

                                           
                                    </div>
                                            
                        </div>
                        
                     

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn_cerrar_modal_confirm_eli"  data-dismiss="modal"> Cerrar </button>
                            <button type="button" class="btn btn-primary" name="btn_aceptar_eliminar" id="btn_aceptar_eliminar" > Eliminar </button>
                        </div>

                </div>
             </div>
        </div>';
       
        return $html1;
    }

    function formInsertarPlato(){

        global $wpdb;
        $tabla = "{$wpdb->prefix}dish";
        $tabla2 = "{$wpdb->prefix}dish_part_day";
        if(isset($_POST['btnguardar'])){

            $nombre = $_POST['txtnombre'];
            $idtype = $_POST['exampleFormControlSelect1'];
            $datos = [
            'id' => null,
           'name' => $nombre,
            'id_type_dish' => $idtype
        ];

        $datos_udpate = [
           'name' => $nombre,
            'id_type_dish' => $idtype
        ];

        $this_id=$_POST['txtId'];
        
        if ($this_id<>'0')
        {
        $respuesta = $wpdb ->update($tabla,$datos_udpate,[ 'id' => $this_id] );
        $respuesta2 = $wpdb ->delete($tabla2,[ 'id_dish' => $this_id] );
        
        }
       if ($this_id=='0')
        {
        $respuesta = $wpdb ->insert($tabla,$datos);
        $this_id = $wpdb->insert_id;
        }
        

       

       if(isset($_POST['id_type_menu_1'])){
        $datos1 = [
                'id_dish' => $this_id,
                'id_part_day' => $_POST['id_type_menu_1']
            ];
            $wpdb->insert($tabla2,$datos1);
        }

        if(isset($_POST['id_type_menu_2'])){
        $datos2 = [
            'id_dish' => $this_id,
            'id_part_day' => $_POST['id_type_menu_2']
        ];
        $wpdb->insert($tabla2,$datos2);
        }
        if(isset($_POST['id_type_menu_3'])){
        $datos3 = [
            'id_dish' => $this_id,
            'id_part_day' => $_POST['id_type_menu_3']
        ];
        $wpdb->insert($tabla2,$datos3);
        }

        



       //header('Location: '.'http://localhost/wordpressplugin/index.php/dish/?preview=true');
       header('Location: '.'../web_menu/dish/');
       
       //return $respuesta;     
    
    }

    }

    public function formInsertarDish($datos){
        
        global $wpdb;
        $dish = "{$wpdb->prefix}dish";
        $dish_part_day = "{$wpdb->prefix}dish_part_day";
        $ingredient = "{$wpdb->prefix}ingredient";
        $dish_ingredient = "{$wpdb->prefix}dish_ingredient";

        $obj = json_decode(str_replace("\\", "", $datos));
        $current_user = wp_get_current_user(); 

            $data = [
            'id' => null,
            'name' =>  $obj->name,
            'id_type_dish' => $obj->id_type_dish ,
            'description' => $obj->description ,
            'id_user' => $current_user->ID
            ];

        $respuesta = $wpdb ->insert($dish,$data);
        $this_id = $wpdb->insert_id;
        
        foreach ($obj->part_day as $key => $value) {
            $data_part_day = [
                'id_dish' => $this_id,
                'id_part_day' =>  $value->id_part_day
                ];
            $wpdb->insert($dish_part_day,$data_part_day);

        }
        $select_ingrediente="";
        $list_name_ingredient="";
        $index=0;
        foreach ($obj->ingredient as $key2 => $value2) {
            $name_ingredient=$value2->name;
            if ($index==0)
            {
                $list_name_ingredient.="'$name_ingredient'" ;
                $select_ingrediente.=" select UPPER('$name_ingredient' ) as name \n";
            }else
            {
                $list_name_ingredient.=",'$name_ingredient'" ;
                $select_ingrediente.=" union all select UPPER('$name_ingredient')  as name \n";
            }
            $index=$index+1;
        }

        $sql_ingredient="INSERT INTO $ingredient (name )
        WITH  ingredient_new  as (
        $select_ingrediente 
        )
        select UPPER(a.name) name  from  ingredient_new a
        left join $ingredient b
        on a.name=b.name
        where b.name is null
        ";

        $wpdb->query($sql_ingredient);


        global $wpdb;
        //$query = "SELECT * FROM $tabla WHERE idmenu = 1";
        $query_ingrediente = "SELECT id,name FROM $ingredient  WHERE name in ( $list_name_ingredient )";
        $list_ingredient = $wpdb->get_results($query_ingrediente,ARRAY_A);
        if(empty($list_ingredient)){
            $list_ingredient = array();
        }

        foreach ($obj->ingredient as $key3 => $value3) {
            $value3->name;

            foreach ($list_ingredient as $key4 => $value4) 
            {
                if( $value4['name']== $value3->name)
                {
                    $id_ingredient   = $value4['id'];
                }
            }

            $data_ingredient = [
                'sequence' => $value3->sequence,
                'id_dish' => $this_id,
                'id_ingredient' =>  $id_ingredient,
                'id_unit_measure' =>  $value3->id_unit_measure,
                'quantity' =>  $value3->quantity
                ];
            $wpdb ->insert($dish_ingredient,$data_ingredient);
        
        }
        return json_encode($data_ingredient);
        
    }


    public function formActualizarDish($datos){
        
        global $wpdb;
        $t_dish = "{$wpdb->prefix}dish";
        $t_dish_part_day = "{$wpdb->prefix}dish_part_day";
        $ingredient = "{$wpdb->prefix}ingredient";
        $dish_ingredient = "{$wpdb->prefix}dish_ingredient";

        $obj = json_decode(str_replace("\\", "", $datos));

            $datos_udpate = [
            'name' =>  $obj->name,
            'id_type_dish' => $obj->id_type_dish,
            'description' => $obj->description
            ];

        
        $respuesta = $wpdb ->update($t_dish,$datos_udpate,[ 'id' => $obj->id] );
        $respuesta2 = $wpdb ->delete($t_dish_part_day,[ 'id_dish' => $obj->id] );
        
        foreach ($obj->part_day as $key => $value) {
            $data_part_day = [
                'id_dish' => $obj->id,
                'id_part_day' =>  $value->id_part_day
                ];
            $wpdb->insert($t_dish_part_day,$data_part_day);

        }
        $select_ingrediente="";
        $list_name_ingredient="";
        $index=0;
        foreach ($obj->ingredient as $key2 => $value2) {
            $name_ingredient=$value2->name;
            if ($index==0)
            {
                $list_name_ingredient.="'$name_ingredient'" ;
                $select_ingrediente.=" select UPPER('$name_ingredient') as name \n";
            }else
            {
                $list_name_ingredient.=",'$name_ingredient'" ;
                $select_ingrediente.=" union all select UPPER('$name_ingredient')  as name \n";
            }
            $index=$index+1;
        }

        $sql_ingredient="INSERT INTO $ingredient (name )
        WITH  ingredient_new  as (
        $select_ingrediente 
        )
        select UPPER(a.name) name  from  ingredient_new a
        left join $ingredient b
        on a.name=b.name
        where b.name is null
        ";

        $wpdb->query($sql_ingredient);


        global $wpdb;
        //$query = "SELECT * FROM $tabla WHERE idmenu = 1";
        $query_ingrediente = "SELECT id,name FROM $ingredient  WHERE name in ( $list_name_ingredient )";
        $list_ingredient = $wpdb->get_results($query_ingrediente,ARRAY_A);
        if(empty($list_ingredient)){
            $list_ingredient = array();
        }
        
        $wpdb ->delete($dish_ingredient,[ 'id_dish' => $obj->id] );

        foreach ($obj->ingredient as $key3 => $value3) {
            $value3->name;

            foreach ($list_ingredient as $key4 => $value4) 
            {
                if(strtoupper( $value4['name'])==strtoupper( $value3->name))
                {
                    $id_ingredient   = $value4['id'];
                }
            }

            $data_ingredient = [
                'sequence' =>  $value3->sequence,
                'id_dish' => $obj->id,
                'id_ingredient' =>  $id_ingredient,
                'id_unit_measure' =>  $value3->id_unit_measure,
                'quantity' =>  $value3->quantity
                ];
            $wpdb ->insert($dish_ingredient,$data_ingredient);
        
        }
        return json_encode($data_ingredient);
        
    }


    public function formEliminarDish($datos){
        
        global $wpdb;
        $dish = "{$wpdb->prefix}dish";
        $obj = json_decode(str_replace("\\", "", $datos));

        $id_dish=$obj->id;

        $query="UPDATE $dish SET is_enabled= false WHERE id=$id_dish ";

        $datos = $wpdb->get_results($query,ARRAY_A);

        if(empty($datos)){
            $datos = array();
        }
        return $datos;
        
    }

    function formTableDish()
    {

        $dishes = "";
        $listaplatos = $this->ObtenerListDish();
        
        foreach ($listaplatos as $key => $value) {
            $id_dish = $value['id'];
            //$html="----".$value;
            
            $dish = $value['name'];
            $id_type_dish = $value['id_type_dish'];
            $name_type_dish = $value['name_type_dish'];
            $id_type_menu_1 = $value['id_type_menu_1'];
            $id_type_menu_2 = $value['id_type_menu_2'];
            $id_type_menu_3 = $value['id_type_menu_3'];
           
            if($id_dish){
                
                $dishes .= $this->fromInput($dish,$id_dish,$id_type_dish,$name_type_dish,$id_type_menu_1,$id_type_menu_2,$id_type_menu_3);
            }
        }

        return $dishes;


    }

    function Armador($iddish){
       // $enc = $this->ObtenerDish($idmenu);
        // $nombre = $enc['name'];
        //obtener todos los platos
        
        $tabla="
        <table class='wp-list-table widefat fixed striped pages' id ='tabla_dish'>  <thead> 
        <th >Id</th>
        <th >Plato</th>
        <th >Acciones</th>
        </thead>";

        $html = $this->formOpen();
        $html .= $this->fromModalSolicitudIniciarSesion();
        $html .= $this->fromModalConfirmEliminar();
        $html .= $this->fromModalNuevo();
        $html .= $this->fromModalVer();
        $html .= $this->formInsertarPlato();
        //$html ="";
        //$html .=$dishes;
        $html .= $tabla;
        $html .= $this->formTableDish();
        $html .="</table>";
        //$html .= $this->formClose();

        return $html ;
        //return "prueba short 2";

    }
}