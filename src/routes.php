<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

require_once "../app/clases/pedidoApi.php";
require_once "../app/models/pedido.php";
require_once "../app/clases/empleadoApi.php";
require_once "../app/models/empleado.php";
require_once "../app/clases/mesaApi.php";
require_once "../app/models/mesa.php";
require_once "../app/models/producto.php";
require_once "../app/models/encuesta.php";
require_once "../app/clases/encuestaApi.php";
require_once "../app/models/tipo_empleado.php";
require_once "../app/models/logger.php";
require_once "../app/models/operacion.php";
require_once "../app/models/factura.php";
require_once "middleware.php";

return function (App $app) {
    $container = $app->getContainer();

    //EMPLEADO
    $app->post('/empleado/login/', \EmpleadoApi::class . ':LoginEmpleado'); //OK
    $app->post('/empleado/alta/', \EmpleadoApi::class . ':AltaEmpleado') //OK
        ->add(\Middleware::class . ':ValidarSocio')
        ->add(\Middleware::class . ':ValidarToken');
    $app->get('/empleado/ingresos/', \EmpleadoApi::class . ':IngresosAlSistema') //OK
        ->add(\Middleware::class . ':ValidarSocio')
        ->add(\Middleware::class . ':ValidarToken');
    $app->get('/empleado/listado/', \EmpleadoApi::class . ':ListadoEmpleados') //OK
        ->add(\Middleware::class . ':ValidarSocio')
        ->add(\Middleware::class . ':ValidarToken');  
    $app->post('/empleado/eliminar/', \EmpleadoApi::class . ':EliminarEmpleado') //OK
        ->add(\Middleware::class . ':ValidarSocio')
        ->add(\Middleware::class . ':ValidarToken');
    $app->post('/empleado/suspender/', \EmpleadoApi::class . ':SuspenderEmpleado') //OK
        ->add(\Middleware::class . ':ValidarSocio')
        ->add(\Middleware::class . ':ValidarToken');
    $app->get('/empleado/listado/puesto/', \EmpleadoApi::class . ':VerEmpleadosPorPuesto') //OK
        ->add(\Middleware::class . ':ValidarToken'); 
    $app->get('/empleado/operaciones/', \EmpleadoApi::class . ':CantidadOperacionesPorEmpleado') //OK
        ->add(\Middleware::class . ':ValidarSocio')
        ->add(\Middleware::class . ':ValidarToken'); 
    $app->get('/empleado/operaciones/sector/', \EmpleadoApi::class . ':CantidadOperacionesPorSector') //OK
        ->add(\Middleware::class . ':ValidarSocio')
        ->add(\Middleware::class . ':ValidarToken');  
    $app->get('/empleado/operaciones/sector/empleado/', \EmpleadoApi::class . ':CantidadOperacionesPorSectorYEmpleado') //OK
        ->add(\Middleware::class . ':ValidarSocio')
        ->add(\Middleware::class . ':ValidarToken');  
    

    // //PEDIDOS
    $app->post('/', \PedidoApi::class . ':CargarPedido') //OK
        ->add(\Middleware::class . ':SumarOperacion')
        ->add(\Middleware::class . ':ValidarMozo')
        ->add(\Middleware::class . ':ValidarToken');
    $app->post('/pedido/tomar/', \PedidoApi::class . ':TomarPedido') //OK
        ->add(\Middleware::class . ':SumarOperacion')
        ->add(\Middleware::class . ':ValidarToken');
    $app->get('/pedido/pendientes/', \PedidoApi::class . ':VerPedidosPendientes') //OK
        ->add(\Middleware::class . ':SumarOperacion')
        ->add(\Middleware::class . ':ValidarToken');
    $app->get('/pedido/estados/', \PedidoApi::class . ':VerEstadoPedidos') //OK
        ->add(\Middleware::class . ':SumarOperacion')
        ->add(\Middleware::class . ':ValidarSocio')
        ->add(\Middleware::class . ':ValidarToken');  
    $app->post('/pedido/tiempo_restante/', \PedidoApi::class . ':TiempoRestante'); //OK
    $app->post('/pedido/servir/', \PedidoApi::class . ':ServirPedido')
        ->add(\Middleware::class . ':SumarOperacion')
        ->add(\Middleware::class . ':ValidarToken');
        

    // //MESA
    $app->post('/mesa/cargar/', \MesaApi::class . ':CargarMesa') //OK
        ->add(\Middleware::class . ':SumarOperacion')
        ->add(\Middleware::class . ':ValidarMozo')
        ->add(\Middleware::class . ':ValidarToken');





    // $app->get('/pedido/mas_vendido/', \PedidoApi::class . ':LoMasVendido')
    // ->add(\Middleware::class . ':ValidarSocio')
    // ->add(\Middleware::class . ':ValidarToken');





    
    
    // $app->get('/pedido/menos_vendido/', \PedidoApi::class . ':LoMenosVendido')
    // ->add(\Middleware::class . ':ValidarSocio')
    // ->add(\Middleware::class . ':ValidarToken');
    // $app->get('/pedido/retrasados/', \PedidoApi::class . ':PedidosRetrasados')
    // ->add(\Middleware::class . ':ValidarSocio')
    // ->add(\Middleware::class . ':ValidarToken');
    // $app->post('/pedido/cancelar/', \PedidoApi::class . ':CancelarPedido')
    // ->add(\Middleware::class . ':SumarOperacion')
    // ->add(\Middleware::class . ':ValidarMozo')
    // ->add(\Middleware::class . ':ValidarToken');
    // $app->get('/pedido/cancelados/', \PedidoApi::class . ':PedidosCancelados')
    // ->add(\Middleware::class . ':ValidarSocio')
    // ->add(\Middleware::class . ':ValidarToken');

    
    // $app->post('/mesa/estado/esperando/', \MesaApi::class . ':CambiarEstadoClienteEsperandoPedido')
    // ->add(\Middleware::class . ':SumarOperacion')
    // ->add(\Middleware::class . ':ValidarMozo')
    // ->add(\Middleware::class . ':ValidarToken'); 
    // $app->post('/mesa/estado/comiendo/', \MesaApi::class . ':CambiarEstadoClienteComiendo')
    // ->add(\Middleware::class . ':SumarOperacion')
    // ->add(\Middleware::class . ':ValidarMozo')
    // ->add(\Middleware::class . ':ValidarToken'); 
    // $app->post('/mesa/estado/pagando/', \MesaApi::class . ':CambiarEstadoClientePagando')
    // ->add(\Middleware::class . ':SumarOperacion')
    // ->add(\Middleware::class . ':ValidarMozo')
    // ->add(\Middleware::class . ':ValidarToken');
    // $app->post('/mesa/estado/cerrada/', \MesaApi::class . ':CambiarEstadoCerrada')
    // ->add(\Middleware::class . ':SumarOperacion')
    // ->add(\Middleware::class . ':ValidarSocio')
    // ->add(\Middleware::class . ':ValidarToken');
    // $app->get('/mesa/mas_usada/', \MesaApi::class . ':LaMasUsada')
    // ->add(\Middleware::class . ':ValidarSocio')
    // ->add(\Middleware::class . ':ValidarToken');
    // $app->get('/mesa/menos_usada/', \MesaApi::class . ':LaMenosUsada')
    // ->add(\Middleware::class . ':ValidarSocio')
    // ->add(\Middleware::class . ':ValidarToken');
    // $app->get('/mesa/facturacion/mayor/', \MesaApi::class . ':LaQueMasFacturo')
    // ->add(\Middleware::class . ':ValidarSocio')
    // ->add(\Middleware::class . ':ValidarToken');
    // $app->get('/mesa/facturacion/menor/', \MesaApi::class . ':LaQueMenosFacturo')
    // ->add(\Middleware::class . ':ValidarSocio')
    // ->add(\Middleware::class . ':ValidarToken');
    // $app->get('/mesa/factura/mayor/', \MesaApi::class . ':FacturaMayorImporte')
    // ->add(\Middleware::class . ':ValidarSocio')
    // ->add(\Middleware::class . ':ValidarToken');  
    // $app->get('/mesa/factura/menor/', \MesaApi::class . ':FacturaMenorImporte')
    // ->add(\Middleware::class . ':ValidarSocio')
    // ->add(\Middleware::class . ':ValidarToken');
    // $app->get('/mesa/facturacion/fechas/', \MesaApi::class . ':FacturacionEntreFechas')
    // ->add(\Middleware::class . ':ValidarSocio')
    // ->add(\Middleware::class . ':ValidarToken');
    // $app->get('/mesa/comentarios/mejores/', \MesaApi::class . ':MejoresComentarios')
    // ->add(\Middleware::class . ':ValidarSocio')
    // ->add(\Middleware::class . ':ValidarToken'); 
    // $app->get('/mesa/comentarios/peores/', \MesaApi::class . ':PeoresComentarios')
    // ->add(\Middleware::class . ':ValidarSocio')
    // ->add(\Middleware::class . ':ValidarToken'); 

    //ENCUESTA
    // $app->post('/encuesta/', \EncuestaApi::class . ':RegistrarEncuesta');

};
