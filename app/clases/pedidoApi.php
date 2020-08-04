<?php

class PedidoApi {
    public function CargarPedido($request, $response, $args) {
        try {     
            $parametros = $request->getParsedBody();    
            $idMesa = $parametros['id_mesa'];
            $idProducto = $parametros['id_producto'];
            $cantidad = $parametros['cantidad'];
            $nombreCliente = $parametros['nombre_cliente'];
            $codigo = substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyz", 3)), 0, 5);
            date_default_timezone_set("America/Argentina/Buenos_Aires");
            $fecha = date('Y-m-d');
            $horaInicial = date('H:i');

            $pedido = new App\Models\Pedido;

            $pedido->id_mesa = $idMesa;
            $pedido->id_producto = $idProducto;
            $pedido->cantidad = $cantidad;
            $pedido->nombre_cliente = $nombreCliente;
            $pedido->id_estado_pedido = 1;
            $pedido->codigo = $codigo;
            $pedido->hora_inicial = $horaInicial;
            $pedido->fecha = $fecha;

            if($_FILES) {
                $nombreFoto = PedidoApi::GuardarFoto($_FILES['foto'], $codigo, $idMesa);
                $pedido->nombre_foto = $nombreFoto;
            }         
            
            $pedido->save();
            $respuesta = array("Estado" => "El pedido se registró correctamente", "Código de pedido" => $codigo);
        }
        catch(Exception $e) {
            $respuesta = array("Estado" => "ERROR", "Mensaje" => $e->getMessage());
        }

        return $response->withJson($respuesta, 200);
    }
 
    static function GuardarFoto($foto, $codigo, $idMesa) {        
        $ruta = $foto['tmp_name'];
        $extension = explode(".", $foto['name']);
        $index = count($extension) - 1; 
        $nombreFoto = $codigo . "_" . $idMesa . "." . $extension[$index];
        $rutafoto = "../app/fotos/" . $nombreFoto;
        
        move_uploaded_file($ruta, $rutafoto);     
        
        return $nombreFoto;
    }
    
    public function VerPedidosPendientes($request, $response, $args) {
        $payload = $request->getAttribute("payload")["Payload"];
        $infoEmpleado = $payload->data;

        $pedido = new App\Models\Pedido;

        $pedidosPendientes = $pedido->rightJoin('productos', 'pedidos.id_producto', '=', 'productos.id')
                                    ->where('pedidos.id_estado_pedido', '=', 1)
                                    ->get();
        
        if ($pedidosPendientes != null && count($pedidosPendientes) > 1) {
            for($i = 0; $i < count($pedidosPendientes); $i++) {
                echo    PHP_EOL . "Producto: " . $pedidosPendientes[$i]->nombre .    
                        PHP_EOL . "Cantidad: " . $pedidosPendientes[$i]->cantidad .                         
                        PHP_EOL . "Mesa: " . $pedidosPendientes[$i]->id_mesa . 
                        PHP_EOL . "Cliente: " . $pedidosPendientes[$i]->nombre_cliente .
                        PHP_EOL . "Codigo: " . $pedidosPendientes[$i]->codigo .
                        PHP_EOL . "------------------------";
            }
        }
        else
            echo 'No hay pedidos pendientes';
    }
    
    public function TomarPedido($request, $response, $args) {
        try {
            $payload = $request->getAttribute("payload")["Payload"];
            $infoEmpleado = $payload->data;
            $idSector = $infoEmpleado->id_sector;

            $parametros = $request->getParsedBody();
            $codigo = $parametros['codigo'];
            $tiempoEstimado = $parametros['tiempo_estimado'];
            $pedido = new App\Models\Pedido;
            $pedidoATomar = $pedido ->where('codigo', '=', $codigo)
                                    ->firstOrFail();        
            $idSectorProducto = $pedido ->join('productos', 'pedidos.id_producto', '=', 'productos.id')
                                        ->where('productos.id', '=', $pedidoATomar->id_producto)
                                        ->select('productos.id_sector')
                                        ->firstOrFail();

            $horaActual = new DateTime('now', new DateTimeZone('America/Argentina/Buenos_Aires'));
            $horaActual->add(new DateInterval('PT' . $tiempoEstimado . 'M'));
            $horaEntregaEstimada = $horaActual->format('H:i');

            $pedidoATomar->id_estado_pedido = 2;
            $pedidoATomar->tiempo_estimado = ($tiempoEstimado * 100);
            $pedidoATomar->hora_entrega_estimada = $horaEntregaEstimada;
            $pedidoATomar->id_empleado = $infoEmpleado->id;
            $pedidoATomar->save();

            $mensaje = array("Estado" => "Ok", "Mensaje " => "Pedido en preparación.");
        }
        catch(Exception $e) {
            $mensaje = array("Estado" => "ERROR", "Mensaje " => "Verifique los datos ingresados.");
        }  

        return $response->withJson($mensaje, 200);
    }
  
    public function VerEstadoPedidos($request, $response, $args) {
        $pedido = new App\Models\Pedido;

        $pedidos = $pedido  ->join('estado_pedidos', 'pedidos.id_estado_pedido', '=', 'estado_pedidos.id')
                            ->select('pedidos.id', 'estado_pedidos.estado', 'pedidos.codigo', 'pedidos.nombre_cliente')
                            ->get();

        if (count($pedidos) > 1) {
            echo 'Estado de Pedidos' . PHP_EOL . PHP_EOL;
            for($i = 0; $i < count($pedidos); $i++){
                echo    "Pedido : " . $pedidos[$i]->id . PHP_EOL . 
                        "Estado : " . $pedidos[$i]->estado . PHP_EOL . 
                        "Código : " . $pedidos[$i]->codigo . PHP_EOL .
                        "Cliente : " . $pedidos[$i]->nombre_cliente . PHP_EOL .                 
                        "-------------------------------------------------" . PHP_EOL;
            }
        }
        else    
            echo 'No hay pedidos.';
    }
    
    public function TiempoRestante($request, $response, $args) {
        $parametros = $request->getParsedBody();
        $codigoPedido = $parametros['codigo_pedido'];
        $codigoMesa = $parametros['codigo_mesa'];

        $mesa = new App\Models\Mesa;
        $idMesa = $mesa ->where('codigo', '=', $codigoMesa)
                        ->select('id')
                        ->first();

        $pedido = new App\Models\Pedido;
        $pedidoActual = $pedido ->where('codigo', '=', $codigoPedido)
                                ->first();
    
        $mensaje = array("Estado" => "Error", "Mensaje " => "Verifique los datos ingresados.");                      
        if ($pedidoActual != null && $idMesa != null) {
            $entrega = $pedidoActual->hora_entrega_estimada;
      
            if($pedidoActual->id_estado_pedido == 2 && $pedidoActual->id_mesa == $idMesa->id) {            
                $horaActual = new DateTime('now', new DateTimeZone('America/Argentina/Buenos_Aires'));
                $horaEntregaEstimada = new DateTime($entrega, new DateTimeZone('America/Argentina/Buenos_Aires'));
                
                if($horaActual > $horaEntregaEstimada) 
                    $mensaje = array("Estado" => "Retrasado", "Mensaje " => "Se retrasó el pedido.");
            
                else {
                    $intervalo = $horaEntregaEstimada->diff($horaActual);
                    $mensaje = array("Estado" => "OK", "Tiempo restante" => $intervalo->format('%H:%I:%S'));
                }  
            }
        }
        return $response->withJson($mensaje, 200);
    }
 
    public function ServirPedido($request, $response, $args) {
        $payload = $request->getAttribute("payload")["Payload"];
        $infoEmpleado = $payload->data;
        $id = $infoEmpleado->id;
        $horaEntrega = date('H:i');
        $pedido = new App\Models\Pedido;
        try {
            $pedidoAServir = $pedido->where('id_estado_pedido', '=', 2)
                                    ->where('id_empleado', '=', $id)
                                    ->firstOrFail();        
            $pedidoAServir->id_estado_pedido = 3;
            $pedidoAServir->hora_entrega = $horaEntrega;
            $pedidoAServir->save();
            $mensaje = array("Estado" => "Ok", "Mensaje " => "El pedido " . $pedidoAServir->codigo . " está listo.");
        }
        catch(Exception $e) {
            $mensaje = array("Estado" => "Error", "Mensaje " => "No hay pedidos pendientes.");
        }       
        return $response->withJson($mensaje, 200);
    }
    
    public function CancelarPedido($request, $response, $args) {
        try {
            $parametros = $request->getParsedBody();
            $codigo = $parametros['codigo'];
            
            $pedido = new App\Models\Pedido;
            $pedidoActual = $pedido ->where('codigo', '=', $codigo)
                                    ->first();

            if ($pedidoActual != null){
                $pedidoActual->id_estado_pedido = 4;
                $pedidoActual->save();
                $mensaje = array("Estado" => "Ok", "Mensaje" => "Pedido " . $codigo . " cancelado.");
            }
            else
                $mensaje = array("Estado" => "ERROR", "Mensaje" => "Mo hay pedidos con ese código.");            
        }
        catch(Exception $e) {
            $mensaje = array("Estado" => "Error", "Mensaje" => $e->getMessage());
        }

        return $response->withJson($mensaje, 200);        
    }
   
    public function PedidosCancelados($request, $response, $args) {
        $fecha = $_GET['fecha'];
        $fecha_desde = $_GET['fecha_desde'];
        $fecha_hasta = $_GET['fecha_hasta'];

        $pedido = new App\Models\Pedido;

        if($fecha != "") {         
            $fecha = strtotime($fecha);
            $fecha = date('Y-m-d H:i:s' , $fecha); 

            $pedidosCancelados = $pedido->where('pedidos.id_estado_pedido', '=', '4')
                                        ->where('pedidos.fecha', '=', $fecha)
                                        ->get();
        }
        else {
            $fecha_desde = strtotime($fecha_desde);
            $fecha_desde = date('Y-m-d H:i:s' , $fecha_desde);  
            $fecha_hasta = strtotime($fecha_hasta);
            $fecha_hasta = date('Y-m-d H:i:s' , $fecha_hasta);

            $pedidosCancelados = $pedido->where('pedidos.id_estado_pedido', '=', '4')
                                        ->where('pedidos.fecha', '>=', $fecha_desde)
                                        ->where('pedidos.fecha', '<=', $fecha_hasta)
                                        ->get();
        }
        
        if(count($pedidosCancelados) > 0) {
            echo 'Pedidos cancelados' . PHP_EOL . PHP_EOL;

            for($i = 0; $i < count($pedidosCancelados); $i++)
                    echo 'Codigo de pedido: ' . $pedidosCancelados[$i]->codigo . PHP_EOL;
        }
        else
            echo 'No hay pedidos cancelados.';
    }
   
    public function LoMasVendido($request, $response, $args) {
        $fecha = $_GET['fecha'];
        $fecha_desde = $_GET['fecha_desde'];
        $fecha_hasta = $_GET['fecha_hasta'];

        $pedido = new App\Models\Pedido;

        if($fecha != "") {         
            $fecha = strtotime($fecha);
            $fecha = date('Y-m-d H:i:s' , $fecha);
            
            $productosVendidos = $pedido ->join('productos', 'pedidos.id_producto', '=', 'productos.id')
                                         ->where('pedidos.fecha', '=', $fecha)
                                         ->select('pedidos.id_producto', 'productos.nombre')
                                         ->orderBy('pedidos.id_producto')
                                         ->get();        
        }
        else {
            $fecha_desde = strtotime($fecha_desde);
            $fecha_desde = date('Y-m-d H:i:s' , $fecha_desde);  
            $fecha_hasta = strtotime($fecha_hasta);
            $fecha_hasta = date('Y-m-d H:i:s' , $fecha_hasta);

            $productosVendidos = $pedido ->join('productos', 'pedidos.id_producto', '=', 'productos.id')
                                         ->where('pedidos.fecha', '>=', $fecha_desde)
                                         ->where('pedidos.fecha', '<=', $fecha_hasta)
                                         ->select('pedidos.id_producto', 'productos.nombre', 'pedidos.cantidad')
                                         ->orderBy('pedidos.id_producto')
                                         ->get();
        }   
        PedidoApi::ProductoMasVendido($productosVendidos);
    }

    static function ProductoMasVendido($productosVendidosDao) {
        // $aux = array();
        // foreach($productosVendidosDao as $prod)
        //     array_push($aux, $prod->id_producto); 

        // $ids = array_unique($aux);

        // $productosVendidos = [];
    
        // for($i = 0; $i < count($productosVendidosDao); $i++)
        //     $productosVendidos[] = array("nombre" => $productosVendidosDao[$i]->nombre, "cantidad" => $productosVendidosDao[$i]->cantidad);
        
        $productosVendidos = [];
    
        for($i = 0; $i < count($productosVendidosDao); $i++)
            $productosVendidos[] = $productosVendidosDao[$i]->nombre;
            
        $productosVendidos[] = -1;
        $productoMasVendido;
        $cantidad = 0;

        if(count($productosVendidos) > 1) {
            $contador = 1;            
    
            for($i = 0; $i <= count($productosVendidos); $i++) {
                if($productosVendidos[$i + 1] == -1) {
                    if($contador > $cantidad) {
                        $cantidad = $contador;
                        $productoMasVendido = $productosVendidos[$i];
                        $contador = 1;        
                    } 
                    break;            
                }

                if($productosVendidos[$i + 1] == $productosVendidos[$i])
                    $contador++;

                else {
                    if($contador > $cantidad) {
                        $cantidad = $contador;
                        $productoMasVendido = $productosVendidos[$i];
                        $contador = 1;        
                    }                    
                }  
            }
            echo    'Producto mas vendido: ' . $productoMasVendido . PHP_EOL . 
                    'Cantidad de pedidos: ' . $cantidad . PHP_EOL;
        }
        else
            echo 'Sin operaciones' . PHP_EOL;
    }
  
    public function LoMenosVendido($request, $response, $args) {
        $fecha = $_GET['fecha'];
        $fecha_desde = $_GET['fecha_desde'];
        $fecha_hasta = $_GET['fecha_hasta'];

        $pedido = new App\Models\Pedido;

        if($fecha != "") {         
            $fecha = strtotime($fecha);
            $fecha = date('Y-m-d H:i:s' , $fecha);
            
            $productosVendidosDao = $pedido ->join('productos', 'pedidos.id_producto', '=', 'productos.id')
                                            ->where('pedidos.fecha', '=', $fecha)
                                            ->select('pedidos.id_producto', 'productos.nombre')
                                            ->orderBy('pedidos.id_producto')
                                            ->get();         
        }
        else {
            $fecha_desde = strtotime($fecha_desde);
            $fecha_desde = date('Y-m-d H:i:s' , $fecha_desde);  
            $fecha_hasta = strtotime($fecha_hasta);
            $fecha_hasta = date('Y-m-d H:i:s' , $fecha_hasta);

            $productosVendidosDao = $pedido ->join('productos', 'pedidos.id_producto', '=', 'productos.id')
                                            ->where('pedidos.fecha', '>=', $fecha_desde)
                                            ->where('pedidos.fecha', '<=', $fecha_hasta)
                                            ->select('pedidos.id_producto', 'productos.nombre')
                                            ->orderBy('pedidos.id_producto')
                                            ->get();       
        }
        PedidoApi::ProductoMenosVendido($productosVendidosDao);       
    }

    static function ProductoMenosVendido($productosVendidosDao) {
        $productosVendidos = [];
    
        for($i = 0; $i < count($productosVendidosDao); $i++)
            $productosVendidos[] = $productosVendidosDao[$i]->nombre;

        $productosVendidos[] = -1;
        $productoMenosVendido;
        $cantidad = 999999999;

        if(count($productosVendidos) > 1) {
            $contador = 1;            
    
            for($i = 0; $i <= count($productosVendidos); $i++) {
                if($productosVendidos[$i+1] == -1) {
                    if($contador < $cantidad) {
                        $cantidad = $contador;
                        $productoMenosVendido = $productosVendidos[$i];
                        $contador = 1;        
                    } 
                    break;            
                }

                if($productosVendidos[$i+1] == $productosVendidos[$i])
                    $contador++;
                
                else {
                    if($contador < $cantidad) {
                        $cantidad = $contador;
                        $productoMenosVendido = $productosVendidos[$i];
                        $contador = 1;        
                    }                    
                }  
            }
            echo    'Producto menos vendido: ' . $productoMenosVendido . PHP_EOL . 
                    'Cantidad de pedidos: ' . $cantidad . PHP_EOL;
        }
        else
            echo 'No hay Pedidos' . PHP_EOL;
    }
  
    public function PedidosRetrasados($request, $response, $args) {
        $fecha = $_GET['fecha'];
        $fecha_desde = $_GET['fecha_desde'];
        $fecha_hasta = $_GET['fecha_hasta'];

        $pedido = new App\Models\Pedido;

        if($fecha != "") {
            $fecha = strtotime($fecha);
            $fecha = date('Y-m-d H:i:s' , $fecha);
            
            $pedidosDao = $pedido->where('id_estado_pedido', '=', 3)
                                 ->where('fecha', '=', $fecha)
                                 ->get();
        }
        else {
            $fecha_desde = strtotime($fecha_desde);
            $fecha_desde = date('Y-m-d H:i:s' , $fecha_desde);  
            $fecha_hasta = strtotime($fecha_hasta);
            $fecha_hasta = date('Y-m-d H:i:s' , $fecha_hasta);

            $pedidosDao = $pedido->where('id_estado_pedido', '=', 3)
                                 ->where('fecha', '>=', $fecha_desde)
                                 ->where('fecha', '<=', $fecha_hasta)
                                 ->get();
        }

        if(!$pedidosDao->isEmpty()) {
            echo 'PEDIDOS RETRASADOS' . PHP_EOL . PHP_EOL;
            for($i = 0; $i < count($pedidosDao); $i++) {           
                $entregaDao = $pedidosDao[$i]->hora_entrega;
                $entregaEstimadaDao = $pedidosDao[$i]->hora_entrega_estimada;
                $horaEntrega = new DateTime($entregaDao,new DateTimeZone('America/Argentina/Buenos_Aires'));
                $horaEntregaEstimada = new DateTime($entregaEstimadaDao ,new DateTimeZone('America/Argentina/Buenos_Aires'));

                if($horaEntrega > $horaEntregaEstimada){
                    echo 'Codigo de pedido: ' . $pedidosDao[$i]->codigo . PHP_EOL .
                        'Hora de entrega estimada: ' . $pedidosDao[$i]->hora_entrega_estimada . PHP_EOL .
                        'Hora de entrega: ' . $pedidosDao[$i]->hora_entrega . PHP_EOL .
                        '------------------------'. PHP_EOL;
                }
            }
        }
        else
            echo 'No hay pedidos retrasados.';

    }
}
