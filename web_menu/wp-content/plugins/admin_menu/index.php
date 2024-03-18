<?php
/*
Plugin Name: Plan de Menu
Description: Este es un plugin para planificar platos de menus
Version: 0.0.1
*/
//requires
require_once dirname(__FILE__) . '/clases/menu_plan.class.php';
require_once dirname(__FILE__) . '/clases/dish.class.php';

function Activar(){


}


function Desactivar() {
    flush_rewrite_rules();
}

function Borrar(){

}

register_activation_hook(__FILE__,'Activar');
register_deactivation_hook(__FILE__,'Desactivar');
register_uninstall_hook(__FILE__,'Borrar');

//add_action('admin_menu','CrearMenu');

function CrearMenu(){
    add_menu_page(
        'Super Encuestas Titulo', //Titulo de la pagina
        'Super Encuentas Menu', // Titulo del menu
        'manage_options',//Capability
        plugin_dir_path(__FILE__).'admin/lista_encuestas.php',//Slug
        null, //function del contenido
        plugin_dir_url(__FILE__).'admin/img/icon.png',
        '1'//priority
    );
/*
    add_submenu_page(
      'sp_menu',//parent slug
      'Ajustes', // Titulo pagina
        'Ajustes',//Tituo menu
        'manage_options',
       'sp_menu_ajustes',
       'submenu'
   );*/
}

function MostrarContenido(){    
    echo "<h1>Contenido de la pagina</h1>";
}
/*
function submenu(){
    echo "<h1>submenu</h1>";
}*/

//encolar bootstrap


//wp_enqueue_script('JqueryJs2',plugins_url('https://code.jquery.com/jquery-1.11.2.min.js',__FILE__),array('jquery'));
//wp_enqueue_script('JqueryJs3','https://code.jquery.com/ui/1.13.2/jquery-ui.js');
//wp_enqueue_script('JqueryJs4',plugins_url('http://http://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js',__FILE__),array('jquery'));
wp_enqueue_script('bootstrapJs',plugins_url('admin/bootstrap/js/bootstrap.min.js',__FILE__),array('jquery'));
//wp_enqueue_script('bootstrapJs2',plugins_url('admin/bootstrap/js/bootstrap.bundle.min.js',__FILE__),array('jquery'));
//wp_enqueue_script('bootstrapJsSelect',plugins_url('admin/bootstrap/js/bootstrap-select.js',__FILE__),array('jquery'));
//wp_enqueue_script('bootstrapJsSelect1',plugins_url('admin/bootstrap/js/popper.min.js',__FILE__),array('jquery'));
//   wp_enqueue_script('bootstrapJsSelect2',plugins_url('admin/bootstrap/js/popper.js',__FILE__),array('jquery'));

//wp_enqueue_script( 'jquery-ui-autocomplete' );

//wp_enqueue_script( 'jquery' );

//wp_register_script( 'my-autocomplete', get_template_directory_uri() . '/js/my-autocomplete.js', array( 'jquery', 'jquery-ui-autocomplete' ), '1.0', false );
//wp_localize_script( 'my-autocomplete', 'MyAutocomplete', array( 'url' => admin_url( 'admin-ajax.php' ) ) );
//wp_enqueue_script( 'my-autocomplete' );


//encolar CSS

wp_enqueue_style('bootstrapCSS',plugins_url('admin/bootstrap/css/bootstrap.min.css',__FILE__));

wp_enqueue_script('bootstrapCS2',"https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js");

//wp_enqueue_style('bootstrapCSSSelect',plugins_url('admin/bootstrap/css/bootstrap-select.css',__FILE__));


//encolar js propio

function EncolarJS($hook){

    if($hook == "testplugin/admin/lista_encuestas.php"){
        wp_enqueue_script('JsExterno',plugins_url('admin/js/lista_encuestas.js',__FILE__),array('jquery'));
        wp_localize_script('JsExterno','SolicitudesAjax',[
        'url' => admin_url('admin-ajax.php'),
        'seguridad' => wp_create_nonce('seg')
        ]);
    }
    
    if(is_page( "dish"))
    {
        wp_enqueue_script('JsExtDish',plugins_url('admin/js/dish.js',__FILE__),array('jquery'));
        wp_localize_script('JsExtDish','dcms_vars',['ajaxurl'=>admin_url('admin-ajax.php')]);
    }
    if(is_page( "plan"))
    {
        wp_enqueue_script('JsExtMenuPlan',plugins_url('admin/js/menu_plan.js',__FILE__),array('jquery'));
        wp_localize_script('JsExtMenuPlan','dcms_vars',['ajaxurl'=>admin_url('admin-ajax.php')]);
    }
    if(is_page( "lista-compra"))
    {
        wp_enqueue_script('JsExtListaCompra',plugins_url('admin/js/lista_compra.js',__FILE__),array('jquery'));
        wp_localize_script('JsExtListaCompra','dcms_vars',['ajaxurl'=>admin_url('admin-ajax.php')]);
    }
    return;
    
}
add_action('wp_enqueue_scripts','EncolarJS');

//add_action('admin_enqueue_scripts','EncolarJS');
//wp_enqueue_script('JsExtMenuPlan',plugins_url('admin/js/menu_plan.js',__FILE__),array('jquery'));}

//wp_localize_script('JsExtMenuPlan','dcms_vars',['ajaxurl'=>admin_url('admin-ajax.php')]);




//Devolver datos a archivo js
add_action('wp_ajax_nopriv_dcms_ajax_cargar_filas','dcms_enviar_contenido');
add_action('wp_ajax_dcms_ajax_cargar_filas','dcms_enviar_contenido');

function dcms_enviar_contenido()
{

    $_short = new menu_plan;

    $id='1';

    if( $_POST["datos"])
    {
        $datos= $_POST["datos"];
    }

    $html = $_short->ArmadorTabla($datos);
    echo $html;

	wp_die();
}


//Devolver datos a archivo js
add_action('wp_ajax_nopriv_dcms_cargar_semana','dcms_cargar_semana');
add_action('wp_ajax_dcms_cargar_semana','dcms_cargar_semana');

function dcms_cargar_semana()
{

    $_short = new menu_plan;

    
    if( $_GET["start_date"])
    {
        $start_date= $_GET["start_date"];
    }

    $result = $_short->ArmadorTablaMenuSemana($start_date);
    echo $result;

	wp_die();
}


add_action('wp_ajax_nopriv_dcms_cargar_lista_compra','dcms_cargar_lista_compra');
add_action('wp_ajax_dcms_cargar_lista_compra','dcms_cargar_lista_compra');

function dcms_cargar_lista_compra()
{

    $_short = new menu_plan;

    
    if( $_GET["start_date"])
    {
        $start_date= $_GET["start_date"];
    }

    $result = $_short->ArmadorTablaListaCompra($start_date);
    echo $result;

	wp_die();

}

add_action('wp_ajax_nopriv_dcms_cargar_dish','dcms_cargar_dish');
add_action('wp_ajax_dcms_cargar_dish','dcms_cargar_dish');

function dcms_cargar_dish()
{

    $_short = new dish;
    if( $_GET["id_dish"])
    {
        $id_dish= $_GET["id_dish"];
    }

    $result = $_short->ObtenerDish($id_dish);
    echo $result;

	wp_die();
}

add_action('wp_ajax_nopriv_dcms_buscar_plato','dcms_buscar_plato');
add_action('wp_ajax_dcms_buscar_plato','dcms_buscar_plato');

function dcms_buscar_plato()
{

    $_short = new menu_plan;

    
    if( $_GET["id_part_day"])
    {
        $id_part_day= $_GET["id_part_day"];
    }
    if( $_GET["name"])
    {
        $name= $_GET["name"];
    }
    
    $result = $_short->FromBuscarPlato($id_part_day,$name);
    echo $result;

	wp_die();
}

add_action('wp_ajax_nopriv_dcms_ajax_insert_menu_week_det','dcms_insert_menu_week_det');
add_action('wp_ajax_dcms_ajax_insert_menu_week_det','dcms_insert_menu_week_det');
function dcms_insert_menu_week_det()
{
    $html="fdddd";
   $_short = new menu_plan;
   
    if( $_POST["datos"])
    {
        $datos= $_POST["datos"];
    }
    
    
    $obj = json_decode(str_replace("\\", "", $datos));

    $result = $_short->InsertarMenuWeekDet(
        $obj->id_menu_week,
        $obj->id_dish,
        $obj->date_menu,
        $obj->id_part_day,
        $obj->id_day
    );
    echo $result;
	wp_die();
}


add_action('wp_ajax_nopriv_dcms_ajax_insert_dish','dcms_insert_dish');
add_action('wp_ajax_dcms_ajax_insert_dish','dcms_insert_dish');
function dcms_insert_dish()
{
    
    $_short = new dish;
   
    if( $_POST["datos"])
    {
        $datos= $_POST["datos"];
    }
    

    $result = $_short->formInsertarDish($datos);
    echo $result ;
	wp_die();
    
}


add_action('wp_ajax_nopriv_dcms_ajax_update_dish','dcms_update_dish');
add_action('wp_ajax_dcms_ajax_update_dish','dcms_update_dish');
function dcms_update_dish()
{
    
    $_short = new dish;
   
    if( $_POST["datos"])
    {
        $datos= $_POST["datos"];
    }
    

    $result = $_short->formActualizarDish($datos);
    echo $result ;
	wp_die();
    
}

add_action('wp_ajax_nopriv_dcms_ajax_eliminar_menu_week_det','dcms_eliminar_menu_week_det');
add_action('wp_ajax_dcms_ajax_eliminar_menu_week_det','dcms_eliminar_menu_week_det');
function dcms_eliminar_menu_week_det()
{
    $html="fdddd";
   $_short = new menu_plan;
   
    if( $_POST["datos"])
    {
        $datos= $_POST["datos"];
    }
    
    
    $obj = json_decode(str_replace("\\", "", $datos));

    $result = $_short->EliminarMenuWeekDetDish(
        $obj->id_menu_week,
        $obj->id_dish,
        $obj->date_menu,
        $obj->id_part_day
    );
    echo $result;
	wp_die();
}

add_action('wp_ajax_nopriv_dcms_ajax_get_plan_plato','get_plan_plato');
add_action('wp_ajax_dcms_ajax_get_plan_plato','get_plan_plato');

function get_plan_plato()
{

    $_short = new menu_plan;

    
    if( $_GET["ids"])
    {
        $id= $_GET["ids"];
    }

    $result = $_short->GetPlanPlato($id);
    echo $result;

	wp_die();
}

add_action('wp_ajax_nopriv_dcms_ajax_get_unidad_medida','get_unidad_medida');
add_action('wp_ajax_dcms_ajax_get_unidad_medida','get_unidad_medida');

function get_unidad_medida()
{

    $_short = new dish;


    $result = $_short->ObtenerUnitMeasure();
    echo json_encode($result);

	wp_die();
}


add_action('wp_ajax_nopriv_dcms_ajax_get_table_platos','get_table_platos');
add_action('wp_ajax_dcms_ajax_get_table_platos','get_table_platos');

function get_table_platos()
{

    $_short = new dish;
    $result = $_short->formTableDish();
    echo $result;

	wp_die();
}

function EliminarEncuesta(){
    $nonce = $_POST['nonce'];
    if(!wp_verify_nonce($nonce, 'seg')){
        die('no tiene permisos para ejecutar ese ajax');
    }

    $id = $_POST['id'];
    global $wpdb;
    $tabla = "{$wpdb->prefix}encuestas";
    $tabla2 = "{$wpdb->prefix}encuestas_detalle";
    $wpdb->delete($tabla,array('EncuestaId' =>$id));
    $wpdb->delete($tabla2,array('EncuestaId' =>$id));
     return true;
}

add_action('wp_ajax_peticioneliminar','EliminarEncuesta');

//shortcode
function imprimirshortcodeMenuPlan(){
    $_short = new menu_plan;
    //obtener el id por parametro
    //$id= $atts['id'];
    $id='1';

   //var_dump($atts);     
    //Imprimir el formulario
    $html = $_short->Armador($id);
    return $html;

}
add_shortcode("short_menu_plan","imprimirshortcodeMenuPlan");


function imprimirshortcodeListaCompra(){
    $_short = new menu_plan;

    $html = $_short->ArmadorListaCompra();
    return $html;

}
add_shortcode("short_lista_compra","imprimirshortcodeListaCompra");


add_action('wp_ajax_nopriv_dcms_cargar_lista_compra','get_table_platos');
add_action('wp_ajax_dcms_cargar_lista_compra','get_table_platos');


function imprimirshortcodeDish(){
    $_short = new dish;
    //obtener el id por parametro
    $id= '';//$atts['id'];

   //var_dump($atts);     
   //Imprimir el formulario
    $html = $_short->Armador($id);
    return $html;

}

add_shortcode("short_dish","imprimirshortcodeDish");




