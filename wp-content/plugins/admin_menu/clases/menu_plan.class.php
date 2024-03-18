<?php


class menu_plan {



    public function ObtenerMenu($idmenu){
        global $wpdb;
        $tabla = "{$wpdb->prefix}menu";
        //$query = "SELECT * FROM $tabla WHERE idmenu = 1";
        $query = "SELECT * FROM $tabla WHERE idmenu = '$idmenu'";
        $datos = $wpdb->get_results($query,ARRAY_A);
        if(empty($datos)){
            $datos = array();
        }
        return $datos[0];
    }

    public function ObtenerUserCopyDish($id_user){
        global $wpdb;
        $user_copydish = "{$wpdb->prefix}user_copydish";
        //$query = "SELECT * FROM $tabla WHERE idmenu = 1";
        $query = "
        SELECT 
        case when  flag_active_dish =true and 
        flag_active_dish_ingredient  =true and 
        flag_active_dish_part_day  = true 
        then 1 else 0 end  active ,
        flag_active_dish,
        flag_active_dish_ingredient ,
        flag_active_dish_part_day
        FROM $user_copydish 
        WHERE id_user =$id_user
        ";
        $datos = $wpdb->get_results($query,ARRAY_A);
        if(empty($datos)){
            $datos = array();
        }
        return $datos;
        
        
    }

    public function ObtenerMenuDetalle($id_part_day){
        global $wpdb;
        $dish = "{$wpdb->prefix}dish";

        $current_user = wp_get_current_user(); 
        $id_user=$current_user->ID;

        $dish_part_day="{$wpdb->prefix}dish_part_day "; 
        if ($id_user<>'0')
        {
        $query = "select a.name plato ,a.id ,b.id_part_day from $dish a
        inner join $dish_part_day b on a.id=b.id_dish 
        where b.id_part_day =$id_part_day 
        and a.id_user=$id_user   
        ORDER BY RAND() 
        limit 7 ";
        }
        else
        {
        $query = "select a.name plato ,a.id ,b.id_part_day from $dish a
        inner join $dish_part_day b on a.id=b.id_dish 
        where b.id_part_day =$id_part_day 
        and a.id_user=1   
        ORDER BY RAND() 
        limit 7 ";
        }
        $datos = $wpdb->get_results($query,ARRAY_A);
        if(empty($datos)){
            $datos = array();
        }
        return $datos;
    }


    public function ObtenerRecomRandDetalle($ids_dish_exclude,$id_part_day){
        global $wpdb;
        $dish = "{$wpdb->prefix}dish";

        $current_user = wp_get_current_user(); 
        $id_user=$current_user->ID;

        if($id_user=='0')
        {
            $id_user='1';
        }

        $dish_part_day="{$wpdb->prefix}dish_part_day "; 
        $query = "select a.name name_dish ,a.id id_dish,b.id_part_day from $dish a
        inner join $dish_part_day b on a.id=b.id_dish 
        where  a.id not in ($ids_dish_exclude) 
        and b.id_part_day =$id_part_day 
        and a.id_user=$id_user  
        ORDER BY RAND() 
        limit 1 ";
        $datos = $wpdb->get_results($query,ARRAY_A);
        if(empty($datos)){
            $datos = array();
        }
        return $datos;
    }


    public function CopiaPlatos($id_user,$user_copy_dish){
        global $wpdb;
       
        $dish = "{$wpdb->prefix}dish";
        $dish_part_day = "{$wpdb->prefix}dish_part_day";
        $dish_ingredient = "{$wpdb->prefix}dish_ingredient";
        $user_copydish = "{$wpdb->prefix}user_copydish";
        $query = "
        insert into $dish 
        ( 
        name,
        id_type_dish,
        description ,
        id_user,
        id_reference 
        ) 
        SELECT 
        name,
        id_type_dish,
        description ,
        $id_user id_user ,
        id id_reference 
        FROM $dish WHERE id_user=1 and is_enabled=true;
        ";
        $datos = $wpdb->get_results($query,ARRAY_A);

        $query = "
        insert into $dish_part_day
        (id_dish,id_part_day)
        SELECT a.id , c.id_part_day  FROM $dish a
        inner join $dish b on a.id_reference=b.id 
        inner join $dish_part_day c on b.id=c.id_dish
        WHERE a.id_user=$id_user;

        ";
        $datos = $wpdb->get_results($query,ARRAY_A);




        $query = "
        insert into $dish_ingredient 
        (id_dish,sequence,id_ingredient,id_unit_measure,quantity)
        SELECT a.id , 
        c.sequence,c.id_ingredient,c.id_unit_measure,c.quantity
        FROM $dish a
        inner join $dish b on a.id_reference=b.id 
        inner join $dish_ingredient c on b.id=c.id_dish
        WHERE a.id_user=$id_user;        
        
        ";
        $datos = $wpdb->get_results($query,ARRAY_A);


        $query = "
        insert into $user_copydish 
        ( 
        id_user, flag_active_dish  , flag_active_dish_ingredient  , flag_active_dish_part_day 
        ) values
        (
        $id_user ,true,true,true
        );
        ";
        $datos = $wpdb->get_results($query,ARRAY_A);



        if(empty($datos)){
            $datos = array();
        }
        return $datos;
    }

    public function ObtenerRecomDetalle($id_dish,$id_part_day){
        global $wpdb;
        $dish = "{$wpdb->prefix}dish";

        $current_user = wp_get_current_user(); 
        $id_user=$current_user->ID;
        if($id_user=='0')
        {
            $id_user='1';
        }

        $dish_part_day="{$wpdb->prefix}dish_part_day "; 
        $query = "select a.name name_dish ,a.id id_dish,b.id_part_day from $dish a
        inner join $dish_part_day b on a.id=b.id_dish 
        where  
        a.id != $id_dish and 
        b.id_part_day =$id_part_day 
        and a.id_user=$id_user   
        ORDER BY RAND() 
        limit 1 ";
        $datos = $wpdb->get_results($query,ARRAY_A);
        if(empty($datos)){
            $datos = array();
        }

        return $datos;
    }


    public function ObtenerPlatoxName($id_part_day,$name){
        global $wpdb;
        $name = strtoupper($name);

        $current_user = wp_get_current_user(); 
        $id_user=$current_user->ID;

        if($id_user=='0')
        {
            $id_user='1';
        }

        $dish = "{$wpdb->prefix}dish";
        $dish_part_day = "{$wpdb->prefix}dish_part_day";
        $query = "select a.name  ,a.id ,b.id_part_day 
        from $dish a
        inner join $dish_part_day b on a.id=b.id_dish 
        where b.id_part_day =$id_part_day  
        and a.id_user=$id_user
        and upper(a.name) like '%$name%' 
         ";
        $datos = $wpdb->get_results($query,ARRAY_A);
        if(empty($datos)){
            $datos = array();
        }
        return $datos;
    }

    public function ObtenerPlatoRand($id_part_day,$id_dish){
        global $wpdb;

        $current_user = wp_get_current_user(); 
        $id_user=$current_user->ID;

        if($id_user==0)
        {
            $id_user='1';
        }

        $dish = "{$wpdb->prefix}dish";
        $dish_part_day = "{$wpdb->prefix}dish_part_day";
        $query = "
        SELECT * FROM (
        select  concat('(Recomendado) ',a.name) as name,a.id ,b.id_part_day 
        from $dish a
        inner join $dish_part_day b on a.id=b.id_dish 
        where b.id_part_day =$id_part_day  
        and a.id_user=$id_user   
        and a.id <> $id_dish
        ORDER BY RAND() 
        LIMIT 1
        ) A
        UNION ALL
        select a.name  ,a.id ,b.id_part_day 
        from $dish a
        inner join $dish_part_day b on a.id=b.id_dish 
        where b.id_part_day =$id_part_day  
        and a.id_user=$id_user 
        order by 1
         ";
        $datos = $wpdb->get_results($query,ARRAY_A);
        if(empty($datos)){
            $datos = array();
        }
        return $datos;
    }


    function InsertarMenuWeek($name,$shortcode,$start_date,$end_date){
        global $wpdb;
        $menu_week = "{$wpdb->prefix}menu_week";

        $current_user = wp_get_current_user(); 
        $id_user=$current_user->ID;
            
        $datos = [
            'name' => $name,
            'shortcode' => $shortcode,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'id_user'=> $id_user
        ];

        $respuesta = $wpdb ->insert($menu_week,$datos);

        $this_id = $wpdb->insert_id;
        return $this_id;
    
    }

    function EliminarMenuWeekDet($id_menu_week){
    global $wpdb;
    $tabla = "{$wpdb->prefix}menu_week_det";
    $wpdb->delete($tabla,array('id_menu_week' =>$id_menu_week));
    
    }

    function EliminarMenuWeekDetDish($id_menu_week,$id_dish,$date_menu,$id_part_day){
        global $wpdb;
        $tabla = "{$wpdb->prefix}menu_week_det";
        $resultato= $wpdb->delete($tabla,array
                                (
                                    'id_menu_week' =>$id_menu_week ,
                                    'id_dish' =>$id_dish ,
                                    'date_menu' =>$date_menu ,
                                    'id_part_day' =>$id_part_day 
                                ));
        
        return $resultato;
        }

    function InsertarMenuWeekDet($id_menu_week,$id_dish,$date_menu,$id_part_day,$id_day)
    {
        global $wpdb;
        $menu_week_det = "{$wpdb->prefix}menu_week_det";

            
        $datos = [
            'id_menu_week' => $id_menu_week,
            'id_dish' => $id_dish,
            'date_menu' => $date_menu,
            'id_part_day' => $id_part_day,
            'id_day' => $id_day
        ];

        $respuesta = $wpdb ->insert($menu_week_det,$datos);
        //$this_id = $wpdb->insert_id;
    
    }

    
    public function ObtenerIdMenuSemana($start_date){
        global $wpdb;
        $menu_week = "{$wpdb->prefix}menu_week";
        $menu_week_det = "{$wpdb->prefix}menu_week_det";
        $dish = "{$wpdb->prefix}dish";
        $part_day = "{$wpdb->prefix}part_day";
        $day = "{$wpdb->prefix}day";

        $current_user = wp_get_current_user(); 
        $id_user=$current_user->ID;
       // if($id_user==0)
        //{
        //    $id_user=1;
       // }
        //$query = "SELECT * FROM $tabla WHERE idmenu = '$idmenu'";
        $query = "
        SELECT 
        a.id id_menu_week
        FROM $menu_week A
        WHERE a.start_date='$start_date'
         and a.id_user=$id_user
        ";
        $datos = $wpdb->get_results($query,ARRAY_A);
        if(empty($datos)){
            $datos = array();
        }
       return $datos;
    }

    public function ObtenerMenuSemana($start_date){
        global $wpdb;
        $menu_week = "{$wpdb->prefix}menu_week";
        $menu_week_det = "{$wpdb->prefix}menu_week_det";
        $dish = "{$wpdb->prefix}dish";
        $part_day = "{$wpdb->prefix}part_day";
        $day = "{$wpdb->prefix}day";

        $current_user = wp_get_current_user(); 
        $id_user=$current_user->ID;
       // if($id_user==0)
        //{
        //    $id_user=1;
       // }
        //$query = "SELECT * FROM $tabla WHERE idmenu = '$idmenu'";
        $query = "
        SELECT 
        a.id id_menu_week,b.id_dish 
        ,c.name dish_name
        ,b.date_menu
        ,b.id_part_day
        ,d.name part_day_name
        ,b.id_day 
        FROM $menu_week A
        INNER JOIN $menu_week_det B 
        ON A.id=B.id_menu_week
        INNER JOIN $dish C
        ON B.id_dish=C.id
        INNER JOIN $part_day D 
        ON B.id_part_day=D.id
        INNER JOIN $day E 
        ON B.id_day=E.id
        WHERE a.start_date='$start_date'
        and a.id_user=$id_user
        order by id_day,id_part_day,id_menu_week,b.created_date_time  
        ";
        $datos = $wpdb->get_results($query,ARRAY_A);
        if(empty($datos)){
            $datos = array();
        }
       return $datos;
      
    }


    public function ObtenerListaCompraSemana($start_date){
        global $wpdb;
        $t_menu_week = "{$wpdb->prefix}menu_week";
        $t_menu_week_det = "{$wpdb->prefix}menu_week_det";
        $t_dish = "{$wpdb->prefix}dish";
        $t_ingredient = "{$wpdb->prefix}ingredient";
        $t_dish_ingredient="{$wpdb->prefix}dish_ingredient";
        $t_day = "{$wpdb->prefix}day";
        //$query = "SELECT * FROM $tabla WHERE idmenu = '$idmenu'";

        $current_user = wp_get_current_user(); 
        $id_user=$current_user->ID;
      //  if($id_user==0)
      //  {
       //     $id_user=1;
       // }

        $query = "
        SELECT distinct
        CONCAT(UCASE(LEFT(e.name, 1)), LCASE(SUBSTRING(e.name, 2)))   ingredient_name
        ,e.id id_ingredient 
        FROM $t_menu_week A
        INNER JOIN $t_menu_week_det B 
        ON A.id=B.id_menu_week
        INNER JOIN $t_dish C
        ON B.id_dish=C.id
        INNER JOIN $t_dish_ingredient d
        ON c.id=d.id_dish
        INNER JOIN $t_ingredient e
        ON d.id_ingredient=e.id
        WHERE a.start_date='$start_date' 
        and a.id_user=$id_user
        ";
        $datos = $wpdb->get_results($query,ARRAY_A);
        if(empty($datos)){
            $datos = array();
        }
       return $datos;
      
    }
    

    public function ObtenerMenuDia(){
        global $wpdb;
        $tabla1 = "{$wpdb->prefix}day";
        //$query = "SELECT * FROM $tabla WHERE idmenu = '$idmenu'";
        $query = "SELECT  a.name dia , id FROM  $tabla1 a ";
        $datos = $wpdb->get_results($query,ARRAY_A);
        if(empty($datos)){
            $datos = array();
        }
        return $datos;
    }



    public function formOpen($titulo,$dia){
        $user_id = get_current_user_id();
        $html = "";

        return $html;
    }


    public function formClose(){
        $html = "
              <br>
                
            </form>
          </div>  
        ";

        return $html;
    }

    public function FromBuscarPlato($id_part_day,$name)
    {
        $html="";
        
        $listaMenuDetalle = $this->ObtenerPlatoxName($id_part_day,$name);


        foreach ($listaMenuDetalle as $key => $value2) 
        {
            $id   = $value2['id'];
            $name = $value2['name'];
            $html.="
            <tr>
                <td>$id </td>
                <td>$name</td>
                <td>
                    <button  class='btn btn-secondary btn_sel_plato'id='btn_sel_plato'  >
                        <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-check-circle' viewBox='0 0 16 16'>
                        <path d='M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z'/>
                        <path d='M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z'/>
                        </svg>
                    </button></td>
            </tr>
            ";
        }

        /*$html2="<tr>
        <td>id </td>
        <td>name</td>
        <td><button  class='btn btn-secondary'id='btn-sel-plato' >Seleccionar</button></td>
        </tr>";*/
        return $html;//$html;//$html;
    }


    public function FromBuscarPlatoRand($id_part_day,$id_dish)
    {
        $html="";
        
        $listaMenuDetalle = $this->ObtenerPlatoRand($id_part_day,$id_dish);


        foreach ($listaMenuDetalle as $key => $value2) 
        {
            $id   = $value2['id'];
            $name = $value2['name'];
            $html.="
            <tr>
                <td>$id </td>
                <td>$name</td>
                <td>
                    <button  class='btn btn-secondary btn_sel_plato'id='btn_sel_plato'  >
                        <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-check-circle' viewBox='0 0 16 16'>
                        <path d='M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z'/>
                        <path d='M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z'/>
                        </svg>
                    </button></td>
            </tr>
            ";
        }

        /*$html2="<tr>
        <td>id </td>
        <td>name</td>
        <td><button  class='btn btn-secondary'id='btn-sel-plato' >Seleccionar</button></td>
        </tr>";*/
        return $html;//$html;//$html;
    }

    function fromModalNuevo(){

        $html1= '<!-- fromModalNuevo -->
        <div class="modal fade" id="modalselnuevo" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                        <div class="modal-header">
                
                            <h5 class="modal-title" >Buscar platos</h5>
                    
                            <button type="button" class="close btn_cerrar_modal" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>  
            
                        <div class="modal-body">
                                    <div class= "form-group">
                                            
                                    
                                            <label hidden id="txtid_part_day"> 0</label>
                                            <label hidden id="txtindexrow"> 0</label>
                                            <label hidden id="txtindexcol"> 0</label>
                                            <div class="input-group mb-3">
                                            <input type="text" class="form-control" placeholder="Buscar Plato" id="txtplatobuscar" >
                                            <button class="btn btn-outline-secondary" type="button" id="button-buscar-dish">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                                                </svg>
                                            </button>

                                            <table class="table" id="tabla_buscar_plato" class="wp-list-table widefat fixed striped pages">
                                                <thead>
                                                    <th >Id</th>
                                                    <th >Plato</th>
                                                    <th >Accion</th>
                                                </thead>

                                                <tr>
                                                    <td ></td>
                                                    <td ></td>
                                                    <td ></td>
                                                </tr>
                                            </table>

                                            </div>

                                           
                                    </div>
                                            
                        </div>
                        
                     

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn_cerrar_modal"  data-dismiss="modal">Cerrar</button>
                           
                        </div>

                </div>
             </div>
        </div>';
       
        return $html1;
    }


    function fromModalSolicitudIniciarSesion(){

        $html1= '<!-- fromModalSolInicioSesion -->
        <div class="modal fade" id="modalsoliniciosession" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                        <div class="modal-header">
                
                            <h5 class="modal-title" >Buscar platos</h5>
                    
                            <button type="button" class="close btn_cerrar_modal2" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>  
            
                        <div class="modal-body">
                                    <div class="modal-body">
                                            
                                    
                                    <p id ="p_mensaje"> </p>

                                           
                                    </div>
                                            
                        </div>
                        
                     

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn_cerrar_modal2"  data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" name="btn_inicio_sesion" id="btn_inicio_sesion" >Iniciar Sesión</button>
                        </div>

                </div>
             </div>
        </div>';
       
        return $html1;
    }

    function obtenerCeldaPlato($plato,$id_plato,$id_part_day){
        $html="";
        if ($plato!="")
        {

            $html="
            <div class='btn-group dropend' role='group'>
                                
                                <button type='button' class='btn btn-outline-secondary btn-sm btn_plato' data-bs-toggle='dropdown' aria-expanded='false'
                                 value='$id_plato' style='color: black;border-radius: 12px ;  border: 1px solid #DAD4D3'>
                                $plato ⁝
                                </button>
                                <ul class='dropdown-menu'>
                                <li><a class='dropdown-item btn_plato_ver' attr-IdDish='$id_plato' href='#'>Ver  </a></li>
                                <li><a class='dropdown-item btnagregardish' attr-IdDish='$id_plato'  attr-IdPartDay='$id_part_day' href='#'>Agregar</a></li>
                                <li><a class='dropdown-item btn_plato_camb' attr-IdDish='$id_plato' href='#'>Cambiar</a></li>
                                <li><a class='dropdown-item btn_plato_recom' attr-IdDish='$id_plato' href='#'>Recomendar</a></li>
                                <li><a  class='dropdown-item btn_plato_eli' onclick='eliminarplato(this)' href='#'>Eliminar</a></li>
                                </ul>
            </div>

                            
                            
            ";
        }
        


        return $html;
    
    }

    function fromInput($array_plato_desayuno,$array_plato_almuerzo,$array_plato_cena,$dia,$id_dia
    ,$id_dish_desayuno,$id_dish_almuerzo,$id_dish_cena, $date_menu
    ){
       $celda_plato_desayuno="";
       $celda_plato_almuerzo="";
       $celda_plato_cena="";
       
       

       foreach ($array_plato_desayuno as $key => $value2) {
        $celda_plato_desayuno.=$this->obtenerCeldaPlato($value2['dish_name'],$value2['id_dish'],1);
        }

        foreach ($array_plato_almuerzo as $key => $value2) {
            $celda_plato_almuerzo.=$this->obtenerCeldaPlato($value2['dish_name'],$value2['id_dish'],2);
            }

        foreach ($array_plato_cena as $key => $value2) {
            $celda_plato_cena.=$this->obtenerCeldaPlato($value2['dish_name'],$value2['id_dish'],3);
            }
       // if($tipo == 1){
            $html="
            <tr>
                <td>$dia </td>
                <td>$celda_plato_desayuno
                <button type='button'  attr-IdDish='$id_dish_desayuno' attr-IdPartDay='1' id='btnnuevodish' class='btn btn-outline-secondary btn-sm btnnuevodish' style='color: black;border-radius: 8px ;  border: 1px solid #DAD4D3'>+</button>
                </td>
                <td style=display:none; >$id_dia</td>
                <td style=display:none;>$date_menu</td>
                <td>$celda_plato_almuerzo
                <button type='button'  attr-IdDish='$id_dish_almuerzo'  attr-IdPartDay='2' id='btnnuevodish' class='btn btn-outline-secondary btn-sm btnnuevodish' style='color: black;border-radius: 8px ;  border: 1px solid #DAD4D3'>+</button>
                </td>
                <td>
                $celda_plato_cena
                <button type='button'  attr-IdDish='$id_dish_cena' attr-IdPartDay='3' id='btnnuevodish' class='btn btn-outline-secondary btn-sm btnnuevodish' style='color: black;border-radius: 8px ;  border: 1px solid #DAD4D3'>+</button>
                </td>
                <td style=display:none;>$id_dish_almuerzo</td> 
                <td style=display:none;>$id_dish_cena</td>
               
            </tr>
        ";
                
            
        
       // }//elseif ($tipo == 2) {
            
       // }else{

       // }
        return $html;
    }


    function fromInputIngredient($item,$id_ingredient, $ingredient_name)
    {

       
            $html="
            <tr>
                <td>$item </td>
                <td style=display:none; >$id_ingredient </td>
                <td>$ingredient_name  </td>           
               
            </tr>
        ";
                
            
        
       // }//elseif ($tipo == 2) {
            
       // }else{

       // }
        return $html;
    }

    Function fromFilter($dia){
        $html="
                <div class='form-check form-check-inline'>
                    <input class='form-check-input' type='checkbox' id='inlineCheckbox1' value='option1'>
                    <label class='form-check-label' for='inlineCheckbox1'>$dia</label>
                </div>      
    ";
    return $html;

    
    }

    function fromModalVerDish(){

        $html_unit_measure ="";
        //$lista = $this->ObtenerUnitMeasure ();
        //$json_unit_measure=json_encode($lista );
        //foreach($lista as $key => $value )
        //{
          //  $id= $value['id'];
           // $name = $value['name'];
           // $html_unit_measure.="<option value='$id'>$name</option> ";
        //} 

        $html1= '<!-- fromModalNuevo -->
        <div class="modal fade" id="modal-ver-plato" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
              
                        <h5 class="modal-title" id="titulo_modal_dish">Ver Plato</h5>
                
                        <button type="button" class="close btn-cerrar-modal-new-dish" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="formulario"  >
                                     
                                 <div class="modal-body">
                                        <div class= "form-group">
                                           
                                            <div class="col-sm-8">

                                               
                                                <input class="form-control" type="hidden" id="txtId" name="txtId" >
                                                <label for="txtnombre" id= "txtnombre" >Plato : Plato Prueba</label>
                                                <br>
                                                <br>
                                                <label for="txttipoplato" id="txttipoplato" >Tipo : Tipo Prueba </label> 

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
                                                    Unidad Medida
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
                                   
                                </div>
                    </div>
                    

                </div>
            </div>
        </div>';
       
        return $html1;
        
    }

    // función para armar la pagina principal
    function Armador($idmenu){
        //$enc = $this->ObtenerMenu($idmenu);
        // $nombre = $enc['name'];


        
        $html_ver_plato = $this->fromModalVerDish();

        $html_sol_iniciosesion =$this->fromModalSolicitudIniciarSesion();

         
        $modal_nuevo = $this->fromModalNuevo();


        //obtener todos los platos
        $platos = "";
        $dias = "";
        $fechaActual = date('Y-m-d');

        if (date("D")=="Mon"){
            $fechaIni=$fechaActual;
        }
        else{
            $fechaIni = date("Y-m-d", strtotime('last Monday', time()));
        }

        $fechaIniPrev=strtotime('-7 day', strtotime($fechaIni));
        $fechaIniPrev = date('Y-m-d', $fechaIniPrev);
        $fechaFin = strtotime('+6 day', strtotime($fechaIni));
        $fechaFin = date('Y-m-d', $fechaFin);
        $fechaNext = strtotime('+7 day', strtotime($fechaIni));
        $fechaNext = date('Y-m-d', $fechaNext);
    
        $listadias = $this->ObtenerMenuDia();
        $current_user = wp_get_current_user(); 
        $id_user=$current_user->ID;
        $id_menu_week   ="0";


        $listaIdMenuDetalle = $this->ObtenerIdMenuSemana($fechaIni);
        foreach ($listaIdMenuDetalle as $key => $value2) 
        {
            $id_menu_week   = $value2['id_menu_week'];
        }

        if( $id_menu_week=="0")
        {
            if($id_user<>'0')
            {
                
            $id_menu_week= $this->InsertarMenuWeek('','',$fechaIni,$fechaFin );
            //$listaMenuDetalle = $this->ObtenerMenuSemana($fechaIni);
            }  
        }

        $head ="
        
        $modal_nuevo
        <div class='row'>
        

        
            <div class='col-md-10'>
                <nav class='nav nav-pills nav-justified'>
                    <button type='button' class='btn btn-secondary' id='btnatras'>
                    <svg xmlns='http://www.w3.org/2000/svg' width='10' height='10' fill='currentColor' class='bi bi-chevron-compact-left' viewBox='0 0 16 16'>
                        <path fill-rule='evenodd' d='M9.224 1.553a.5.5 0 0 1 .223.67L6.56 8l2.888 5.776a.5.5 0 1 1-.894.448l-3-6a.5.5 0 0 1 0-.448l3-6a.5.5 0 0 1 .67-.223z'/>
                    </svg> 
                    </button>
                    <label hidden id='lblfechainiprev'   class='nav-link' >$fechaIniPrev</label>
                    <label id='lblfechaini'  class='nav-link' >$fechaIni</label>
                    <label hidden id='lbl-id-menu-week'  class='nav-link' >$id_menu_week</label>
                    <label hidden id='lbl-id-user'  class='nav-link' >$id_user</label>
                    <label class='nav-link' > -</label>
                    <label id='lblfechafin' class='nav-link' >$fechaFin</label>
                    <label hidden id='lblfechanext' class='nav-link' >$fechaNext</label>

            
                    <button type='button' class='btn btn-secondary'  id='btnadelante' >
                    <svg xmlns='http://www.w3.org/2000/svg' width='10' height='10' fill='currentColor' class='bi bi-chevron-compact-right' viewBox='0 0 16 16'>
                    <path fill-rule='evenodd' d='M6.776 1.553a.5.5 0 0 1 .671.223l3 6a.5.5 0 0 1 0 .448l-3 6a.5.5 0 1 1-.894-.448L9.44 8 6.553 2.224a.5.5 0 0 1 .223-.671z'/>
                    </svg>
                    </button>
                    &nbsp;
            
                    <div  class='align-text-bottom' >
                    <button  class='btn btn-secondary' name='btnactualizar' id='btn-recomendar' >Recomendar</button>
                    <button  class='btn btn-secondary' name='btn_guardar' id='btn_guardar' >Guardar</button>
                    </div>
                    
                
                </nav>
            </div>

            
        
      
        </div>
        </br>
        <div class='row'>
        ";
        $tabla="<table class='table' id='tabla_plan' class='wp-list-table widefat fixed striped pages'> 
        <thead> 
            <th >Dia</th>
            <th >Desayuno</th>
            <th style=display:none;>id_dia</th>
            <th style=display:none;>Fecha</th>
            <th >Almuerzo</th>
            <th >Cena</th>
            <th style=display:none;>id_plato_almuerzo</th>
            <th style=display:none;>id_plato_cena</th>
        </thead>";
        $id_platos ="0";

        
       $platos=$this->ObtenerPlatos($fechaIni,$fechaFin,$id_menu_week);
       


        //$html = $this->formOpen($nombre,$dia);
       // $html .=$dias;
        $html =$head.$html_ver_plato.$html_sol_iniciosesion;
        $html .= $tabla.$platos."</table></div>";
        $html .= $this->formClose();
        

        return $html;

    }



    function ObtenerPlatos($fechaIni,$fechaFin,$id_menu_week)
    {
        
        $listaMenuDetalle = $this->ObtenerMenuSemana($fechaIni);

        $platos ="";
        
        if (count($listaMenuDetalle)>0)
        {

            $begin = new DateTime($fechaIni );
            $end = new DateTime($fechaFin );

            $fechaFin_Aux = strtotime('+1 day', strtotime($fechaFin));
            $fechaFin_Aux = date('Y-m-d', $fechaFin_Aux);        
            $end = new DateTime($fechaFin_Aux );

            $interval = new DateInterval('P1D');
            $daterange = new DatePeriod($begin, $interval ,$end);
           

    

            foreach($daterange as $date)
            {
                
                $id_dia=$date->format('w');
                $dia_mes=$date->format('d');
                $date_menu=$date->format('Y-m-d');  
                if($id_dia=='0')
                {
                    $id_dia='7';
                }
            
                $dia=$this->ObtenerDia($id_dia,$dia_mes);
                $existe_fecha="0";
                $plato_desayuno="";
                $plato_almuerzo="";
                $plato_cena="";
                $id_dish_desayuno="0";
                $id_dish_almuerzo="0";
                $id_dish_cena="0";
                $array_plato_desayuno = Array();
                $array_plato_almuerzo = Array();
                $array_plato_cena = Array();
                    foreach ($listaMenuDetalle as $key => $value2) {
                        if($value2['date_menu']==$date_menu)
                        {
                            $existe_fecha="1";
                            if($value2['id_part_day']==1)
                            {    
                                $array_plato_desayuno_aux  =  
                                Array
                                (
                                    'dish_name' =>$value2['dish_name'],
                                    'id_dish' =>$value2['id_dish']
                                );
                                $id_dish_desayuno=$value2['id_dish'];

                                array_push($array_plato_desayuno, $array_plato_desayuno_aux );
                            }
                            if($value2['id_part_day']==2)
                            {                            
                                $array_plato_almuerzo_aux  =  
                                Array
                                (
                                    'dish_name' =>$value2['dish_name'],
                                    'id_dish' =>$value2['id_dish']
                                );
                                $id_dish_almuerzo=$value2['id_dish'];

                                array_push($array_plato_almuerzo, $array_plato_almuerzo_aux );
                            }
                            if($value2['id_part_day']==3)
                            {                            
                                $array_plato_cena_aux  =  
                                Array
                                (
                                    'dish_name' =>$value2['dish_name'],
                                    'id_dish' =>$value2['id_dish']
                                );

                                $id_dish_cena=$value2['id_dish'];

                                array_push($array_plato_cena, $array_plato_cena_aux );
                            }
                            
                        }
                        
                        
                    }
                $platos .= $this->fromInput($array_plato_desayuno,$array_plato_almuerzo,$array_plato_cena
                                            ,$dia,$id_dia,$id_dish_desayuno,$id_dish_almuerzo,$id_dish_cena
                                        ,$date_menu);
                

                

        // function fromInput($array_plato_desayuno,$array_plato_almuerzo,$array_plato_cena,$dia,$id_dia
        //,$id_dish_desayuno,$id_dish_almuerzo,$id_dish_cena, $date_menu

            }
            

        }

        else 

        {

            $fechaActual = date('Y-m-d');

            if (date("D")=="Mon"){
                $fechaIniActual=$fechaActual;
            }
            else{
                $fechaIniActual = date("Y-m-d", strtotime('last Monday', time()));
            }
    
            //$fechaIniPrev=strtotime('-7 day', strtotime($fechaIniActual));
            //$fechaIniPrev = date('Y-m-d', $fechaIniPrev);
            
            if ($fechaIniActual==$fechaIni)
            {
            $datos='{"id":"'.$id_menu_week.'","start_date":"'.$fechaIni.'","end_date":"'.$fechaFin.'"} ';
            $platos_json= json_decode($this->ArmadorTabla($datos));
            $platos=$platos_json->data;
            }

        }

        return $platos ;
   
    }




    function ArmadorTablaListaCompra($start_date){

        
        $id_menu_week   = "0";

        $listaMenuDetalle = $this->ObtenerMenuSemana($start_date);
        foreach ($listaMenuDetalle as $key => $value2) 
        {
            $id_menu_week   = $value2['id_menu_week'];
        }
   
       $start_date_prev=strtotime('-7 day', strtotime($start_date));
       $start_date_prev = date('Y-m-d', $start_date_prev);
       $end_date=strtotime('+6 day', strtotime($start_date));
       $end_date = date('Y-m-d', $end_date);
       $next_date=strtotime('+7 day', strtotime($start_date));
       $next_date = date('Y-m-d', $next_date);
       $platos=$this->ObtenerListaCompra($start_date,$end_date);
       $html = $platos;
       $result = Array
            (
                'general' =>  array(
                    'start_date_prev'  => "$start_date_prev",
                    'end_date'  => "$end_date",
                    'next_date'  => "$next_date",
                    'id_menu_week'  => "$id_menu_week"
                ) ,
                'data' =>$html
                );

        return json_encode($result);
        

    }



    function ObtenerListaCompra($fechaIni,$fechaFin)
    {
        
        $listaCompra = $this->ObtenerListaCompraSemana($fechaIni);
        //$begin = new DateTime($fechaIni );
        //$end = new DateTime($fechaFin );

        //$fechaFin_Aux = strtotime('+1 day', strtotime($fechaFin));
        //$fechaFin_Aux = date('Y-m-d', $fechaFin_Aux);        
        //$end = new DateTime($fechaFin_Aux );

        //$interval = new DateInterval('P1D');
        //$daterange = new DatePeriod($begin, $interval ,$end);
        $htmlIngredientes ="";
        
        $item=0;
        foreach ($listaCompra as $key => $value2) 
        {            
            $item=$item+1;

            $htmlIngredientes .= $this->fromInputIngredient($item,$value2['id_ingredient'],$value2['ingredient_name']);
            
        }
        
        
        return $htmlIngredientes ;
   
    }



    function ObtenerDia($id_dia,$dia_mes)
    {

         switch ($id_dia) {
                case 1:
                    $dia="Lun. ".$dia_mes;
                    break;
                case 2:
                        $dia="Mar. ".$dia_mes;
                        break;
                case 3:
                        $dia="Mie. ".$dia_mes;
                        break;
                case 4:
                    $dia="Jue. ".$dia_mes;
                    break;
                case 5:
                    $dia="Vie. ".$dia_mes;
                    break;
                case 6:
                    $dia="Sab. ".$dia_mes;
                    break;
                case 7:
                    $dia="Dom. ".$dia_mes;
                    break;
                
            }
        return $dia;
    }
    function ArmadorTabla($datos) // RECOMENDAR
    {
      //  $enc = $this->ObtenerMenu($idmenu);
       //  $nombre = $enc['name'];

        //obtener todos los platos
        //setcookie("probando_Cookie2",'value55   ', time()+3600);  /* expira en 1 hora */

        $platos = "";
        $dias = "";
        $listadias = $this->ObtenerMenuDia();

        $tabla="";
        $id_platos_desayuno ="0";
        $id_platos_almuerzo ="0";
        $obj = json_decode(str_replace("\\", "", $datos));//json_decode($datos);
        //$obj2= $obj->start_date;

        $id_menu_week= $obj->id;

        $current_user = wp_get_current_user(); 
        $id_user=$current_user->ID;
        $user_copy_dish = $this->ObtenerUserCopyDish($id_user);
        
        if ($id_user!=0)
        {
            if ($id_user!=1)
            {
            
                if( count($user_copy_dish)==0 )
                {
                    $this-> CopiaPlatos($id_user,'');
                }
                else if ( $user_copy_dish[0]['active']=='0' )
                {
                    $this-> CopiaPlatos($id_user,'');
                }
            }
        }
        

        $begin = new DateTime($obj->start_date );
        $end = new DateTime($obj->end_date );
        $end = $end->modify( '+1 day' );

        
        $interval = new DateInterval('P1D');
        $daterange = new DatePeriod($begin, $interval ,$end);

        if($id_menu_week !="0")
            {
                $this->EliminarMenuWeekDet($id_menu_week );
            }

        $listaMenuDetalleDesayuno = $this->ObtenerMenuDetalle(1);
        $listaMenuDetalleAlmuerzo = $this->ObtenerMenuDetalle(2); 

        $indice=0;
        foreach($daterange as $date){

            $id_dia=$date->format('w');
            $dia_mes=$date->format('d');
            $date_menu=$date->format('Y-m-d');
            if($id_dia=='0')
            {
                $id_dia='7';
            }
           
            $dia=$this->ObtenerDia($id_dia,$dia_mes);
            $idmenu_det = 1;
            $encid ='encid';

            $array_plato_desayuno = Array();
            $array_plato_almuerzo = Array();
            $array_plato_cena = Array();
            $plato_desayuno="";
            $id_plato_desayuno="0";
            $plato_almuerzo="";
            $id_plato_almuerzo="0";

            if(array_key_exists($indice, $listaMenuDetalleDesayuno))
            {
                $plato_desayuno = $listaMenuDetalleDesayuno[$indice]['plato'];
                $id_plato_desayuno = $listaMenuDetalleDesayuno[$indice]['id'];
                //$id_platos_desayuno .=",".$id_plato_desayuno;
            }

            if(array_key_exists($indice, $listaMenuDetalleAlmuerzo))
            {
                $plato_almuerzo = $listaMenuDetalleAlmuerzo[$indice]['plato'];
                $id_plato_almuerzo = $listaMenuDetalleAlmuerzo[$indice]['id'];
                //$id_platos_almuerzo .=",".$id_platos_almuerzo;
            }

                       

            
            if($id_menu_week !="0")
            {
                $this->InsertarMenuWeekDet($id_menu_week,$id_plato_desayuno,$date_menu,1,$id_dia);
                $this->InsertarMenuWeekDet($id_menu_week,$id_plato_almuerzo,$date_menu,2,$id_dia);
                $this->InsertarMenuWeekDet($id_menu_week,$id_plato_almuerzo,$date_menu,3,$id_dia);
            }
            


            $array_plato_desayuno_aux  =  
                            Array
                            (
                                'dish_name' =>$plato_desayuno,
                                'id_dish' =>$id_plato_desayuno
                            );
             array_push($array_plato_desayuno, $array_plato_desayuno_aux );

            $array_plato_almuerzo_aux  =  
                            Array
                            (
                                'dish_name' =>$plato_almuerzo,
                                'id_dish' =>$id_plato_almuerzo
                            );
            array_push($array_plato_almuerzo, $array_plato_almuerzo_aux );

            $array_plato_cena_aux  =  
                            Array
                            (
                                'dish_name' =>$plato_almuerzo,
                                'id_dish' =>$id_plato_almuerzo
                            );
            array_push($array_plato_cena, $array_plato_cena_aux );
           
                          //  $platos .= json_encode($array_plato_desayuno);
                         // $platos .= $this->fromInput($array_plato_desayuno,Array(),Array()
                         // ,'ds','','','',''
                         // ,'');
                            
                          
             $platos .= $this->fromInput($array_plato_desayuno,$array_plato_almuerzo,$array_plato_almuerzo,$dia ,$id_dia
                ,$id_plato_desayuno
                ,$id_plato_almuerzo
                ,$id_plato_almuerzo
                ,$date_menu
                );
                
        $indice=$indice+1;
        }
        

       // $platos=json_encode($listaMenuDetalleDesayuno);
        $html = $tabla.$platos;
        

        $result = Array
        (
            'general' =>  array(
            'id_menu_week'  => "$id_menu_week"
            ) ,
            'data' =>$html
        );

        return json_encode($result);

    }

    function ArmadorTablaMenuSemana($start_date){

        $tabla="";
        $id_menu_week   = "0";

        $listaMenuDetalle = $this->ObtenerMenuSemana($start_date);
        foreach ($listaMenuDetalle as $key => $value2) 
        {
            $id_menu_week   = $value2['id_menu_week'];
        }
   
       $start_date_prev=strtotime('-7 day', strtotime($start_date));
       $start_date_prev = date('Y-m-d', $start_date_prev);
       $end_date=strtotime('+6 day', strtotime($start_date));
       $end_date = date('Y-m-d', $end_date);
       $next_date=strtotime('+7 day', strtotime($start_date));
       $next_date = date('Y-m-d', $next_date);
       $platos=$this->ObtenerPlatos($start_date,$end_date,$id_menu_week);
       $html = $tabla.$platos;
       $result = Array
            (
                'general' =>  array(
                    'start_date_prev'  => "$start_date_prev",
                    'end_date'  => "$end_date",
                    'next_date'  => "$next_date",
                    'id_menu_week'  => "$id_menu_week"
                ) ,
                'data' =>$html
                );

        return json_encode($result);
        

    }

/*
    function GetPlanPlato($ids_platos){

        
        $listaMenuDetalle = $this->ObtenerMenuDetalle($ids_platos,2);
        foreach ($listaMenuDetalle as $key => $value2) {
                $plato = $value2['plato'];
                $id_plato = $value2['id'];
  
        }
       
        return  json_encode(array("id_plato"=> $id_plato, "plato"=>$plato));


        
    }
    */

    function GetRecomPlato($ids_platos,$id_part_day,$id_dish){

        
        $listaMenuDetalle = $this->ObtenerRecomRandDetalle($ids_platos,$id_part_day);
        foreach ($listaMenuDetalle as $key => $value2) {
                $name_dish = $value2['name_dish'];
                $id_dish = $value2['id_dish'];
                
  
        }

        if(count($listaMenuDetalle)==0)
        {
            $listaMenuDetalle = $this->ObtenerRecomDetalle($id_dish,$id_part_day);
            foreach ($listaMenuDetalle as $key => $value2) {
                    $name_dish = $value2['name_dish'];
                    $id_dish = $value2['id_dish'];
                    
    
            }
        }

       
        return  json_encode(array("id_dish"=> $id_dish, "name_dish"=>$name_dish));


        
    }

    /*
    function GetPlanPlatoSemana($fecha_ini){

        
        $listaMenuDetalle = $this->ObtenerMenuDetalle($ids_platos,2);
        foreach ($listaMenuDetalle as $key => $value2) {
                $plato = $value2['plato'];
                $id_plato = $value2['id'];
  
        }
       
        return  json_encode(array("id_plato"=> $id_plato, "plato"=>$plato));


        
    }
*/

    function ArmadorListaCompra(){
       // $enc = $this->ObtenerMenu($idmenu);
        // $nombre = $enc['name'];
         

        $current_user = wp_get_current_user(); 
        $id_user=$current_user->ID;
        if ($id_user<>'0')
            {
            //obtener todos los platos
            $platos = "";
            $dias = "";
            $fechaActual = date('Y-m-d');

            if (date("D")=="Mon"){
                $fechaIni=$fechaActual;
            }
            else{
                $fechaIni = date("Y-m-d", strtotime('last Monday', time()));
            }

            $fechaIniPrev=strtotime('-7 day', strtotime($fechaIni));
            $fechaIniPrev = date('Y-m-d', $fechaIniPrev);
            $fechaFin = strtotime('+6 day', strtotime($fechaIni));
            $fechaFin = date('Y-m-d', $fechaFin);
            $fechaNext = strtotime('+7 day', strtotime($fechaIni));
            $fechaNext = date('Y-m-d', $fechaNext);
        
            $listadias = $this->ObtenerMenuDia();

            $listaMenuDetalle = $this->ObtenerMenuSemana($fechaIni);
            foreach ($listaMenuDetalle as $key => $value2) 
            {
                $id_menu_week   = $value2['id_menu_week'];
            }
            $head ="
            <div class='row'>
            

            
                <div class='col-md-10'>
                    <nav class='nav nav-pills nav-justified'>
                        <button type='button' class='btn btn-secondary' id='btnatras' >
                        <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-chevron-compact-left' viewBox='0 0 16 16'>
                            <path fill-rule='evenodd' d='M9.224 1.553a.5.5 0 0 1 .223.67L6.56 8l2.888 5.776a.5.5 0 1 1-.894.448l-3-6a.5.5 0 0 1 0-.448l3-6a.5.5 0 0 1 .67-.223z'/>
                        </svg> 
                        </button>
                        <label hidden id='lblfechainiprev'   class='nav-link' >$fechaIniPrev</label>
                        <label id='lblfechaini'  class='nav-link' >$fechaIni</label>
                        <label hidden id='lbl-id-menu-week'  class='nav-link' >$id_menu_week</label>
                        <label class='nav-link' > -</label>
                        <label id='lblfechafin' class='nav-link' >$fechaFin</label>
                        <label hidden id='lblfechanext' class='nav-link' >$fechaNext</label>

                
                        <button type='button' class='btn btn-secondary'  id='btnadelante' >
                        <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-chevron-compact-right' viewBox='0 0 16 16'>
                        <path fill-rule='evenodd' d='M6.776 1.553a.5.5 0 0 1 .671.223l3 6a.5.5 0 0 1 0 .448l-3 6a.5.5 0 1 1-.894-.448L9.44 8 6.553 2.224a.5.5 0 0 1 .223-.671z'/>
                        </svg>
                        </button>
                        &nbsp;

                        
                    
                    </nav>
                </div>

                
            
        
            </div>
            </br>
            <div class='row'>
            ";
            $tabla="<table class='table' id='tabla_plan' class='wp-list-table widefat fixed striped pages'> 
            <thead> 
                <th >Item</th>
                <th style=display:none; >Id_Ingrediente</th>
                <th >Ingrediente</th>
            </thead>";
            $id_platos ="0";

            
            $filas=$this->ObtenerListaCompra($fechaIni,$fechaFin);


            //$html = $this->formOpen($nombre,$dia);
        // $html .=$dias;
            $html =$head;
            $html .= $tabla.$filas."</table></div>";
            $html .= $this->formClose();
        
        }
        else{
            $host=$_SERVER['HTTP_HOST'];
            $protocol=$_SERVER['SERVER_PROTOCOL'];
            $html ="
            <p> Para ver la lista de compra de su plan necesita iniciar sessión </p>
            <div class='modal-footer'>
            <a type='button' class='btn btn-primary' name='btn_inicio_sesion' id='btn_inicio_sesion' href='$protocol://$host/web_menu/login/'>Iniciar Sesión</a>
            </div>
            ";

        }

        return $html;

    }

/*
    
    function GuardarDetalle($datos){
        global $wpdb;
        $tabla = "{$wpdb->prefix}encuestas_respuesta"; 
        //Forma largo de insertar en tabla
       // $respuesta =$wpdb->insert($tabla,$datos);
        //return $respuesta;
        
        //forma corta de insertar
        return  $wpdb->insert($tabla,$datos);
    }

    
*/

}

?>