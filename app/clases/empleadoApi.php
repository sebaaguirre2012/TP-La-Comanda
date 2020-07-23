<?php

require_once 'token.php';

class EmpleadoApi {

    public function LoginEmpleado($request, $response, $args) {
        $parametros = $request->getParsedBody();
        $usuario = $parametros['usuario'];
        $clave = $parametros['clave'];

        $empleadoDao = new App\Models\Empleado;
        $tipoEmpleadoDao = new App\Models\TipoEmpleado;
        $logger = new App\Models\Logger;
        $horaActual = new DateTime('now', new DateTimeZone('America/Argentina/Buenos_Aires'));

        try {            
            $empleado = $empleadoDao->where([['usuario', '=', $usuario], ['clave', '=', $clave]])->first();

            if($empleado) {
                $tipoEmpleado = $tipoEmpleadoDao->where('id', '=', $empleado->id_tipo_empleado)->first();

                $datos = [
                    'id' => $empleado->id,
                    'usuario' => $usuario,
                    'clave' => $clave,
                    'id_tipo_empleado' => $tipoEmpleado->id,
                    'tipo_empleado' => $tipoEmpleado->tipo_empleado,
                    'id_sector' => $empleado->id_sector
                ];   

                $token = Token::CrearToken($datos);
                $logger->id_empleado = $empleado->id;
                $logger->fecha_ingreso = $horaActual;
                $logger->hora_ingreso = $horaActual;
                $logger->save();

                $mensaje = array("Mensaje" => "Bienvenido " . $usuario, "Token " => $token);
            }
            else {
                $mensaje = array("Estado" => "Error", "Mensaje " => "Usuario y/o clave incorrectos");
            }
        }
        catch(Exception $e) {
            $error = $e->getMessage();
            $mensaje = array("Estado" => "Error", "Mensaje " => $error);
        }

        return $response->withJson($mensaje, 200);
    }    

    public function AltaEmpleado($request, $response, $args) {
        $parametros = $request->getParsedBody();
        $usuario = $parametros['usuario'];
        $clave = $parametros['clave'];
        $tipoEmpleado = strtolower($parametros['tipo_empleado']);
        $id_tipoEmpleado;
        $id_sector;

        switch($tipoEmpleado) {
            case 'socio':
                $id_tipoEmpleado = 1;
                $id_sector = 1;
                break;
            case 'mozo':
                $id_tipoEmpleado = 2;
                $id_sector = 2;
                break;
            case 'cocinero':
                $id_tipoEmpleado = 3;
                $id_sector = 3;
                break;
            case 'bartender':
                $id_tipoEmpleado = 4;
                $id_sector = 4;
                break;
            case 'cervecero':
                $id_tipoEmpleado = 5;
                $id_sector = 5;
                break;
            case 'pastelero':
                $id_tipoEmpleado = 6;
                $id_sector = 6;
                break;
        }
        try {
            $empleado = new App\Models\Empleado;
            
            //TODO
            $auiliar = $empleado->where('usuario', '=', $usuario)->first();
            if($auiliar != null)
                $mensaje = array("Estado" => "Ok", "Mensaje" => "El nombre de usuario ya existe.");
                
            else {
                $empleado->usuario = $usuario;
                $empleado->clave = $clave;
                $empleado->id_tipo_empleado = $id_tipoEmpleado;
                $empleado->id_sector = $id_sector;
                $empleado->estado = 'A';
                $empleado->save();
                $mensaje = array("Estado" => "Ok", "Mensaje" => "El alta se realizó correctamente");
            }
        }
        catch(Exception $e) {
            $error = $e->getMessage();
            $mensaje = array("Estado" => "ERROR", "Mensaje" => $error);
        }
        return $response->withJson($mensaje, 200);    
    }

    public function IngresosAlSistema($request, $response, $args) {
        $fecha = $_GET['fecha'];
        $fecha_desde = $_GET['fecha_desde'];
        $fecha_hasta = $_GET['fecha_hasta'];
        
        $logger = new App\Models\Logger;
        date_default_timezone_set("America/Argentina/Buenos_Aires");

        if($fecha != 0) {         
            $fecha = strtotime($fecha);
            $fecha = date('Y-m-d H:i:s' , $fecha); 
             
            $logueos = $logger->rightJoin('empleados as em', 'loggers.id_empleado', '=', 'em.id')
                ->where('fecha_ingreso', '=', $fecha)
                ->where('em.estado', '!=', 'E')->get();           
    
            for($i = 0; $i < count($logueos); $i++) {
                echo 'Empleado: '. $logueos[$i]->usuario . PHP_EOL . 
                     'Fecha de ingreso: ' . $logueos[$i]->fecha_ingreso . PHP_EOL .
                     'Hora de ingreso: ' . $logueos[$i]->hora_ingreso . PHP_EOL .
                     '-------------------------------------------------------'. PHP_EOL;
            }
        }
        else {
            $fecha_desde = strtotime($fecha_desde);
            $fecha_desde = date('Y-m-d H:i:s' , $fecha_desde);  
            $fecha_hasta = strtotime($fecha_hasta);
            $fecha_hasta = date('Y-m-d H:i:s', $fecha_hasta);

            $logueos = $logger->rightJoin('empleados as em', 'loggers.id_empleado', '=', 'em.id')
                ->where('fecha_ingreso', '>=', $fecha_desde)
                ->where('fecha_ingreso', '<=', $fecha_hasta)
                ->where('em.estado', '!=', 'E')->get();

            for($i = 0; $i < count($logueos); $i++) {
                echo 'Empleado: '. $logueos[$i]->usuario . PHP_EOL . 
                     'Fecha de ingreso: ' . $logueos[$i]->fecha_ingreso . PHP_EOL .
                     'Hora de ingreso: ' . $logueos[$i]->hora_ingreso . PHP_EOL .
                     '-------------------------------------------------------'. PHP_EOL;
            }
        } 
    }

    public function ListadoEmpleados($request, $response, $args) {
        $empleado = new App\Models\Empleado;
        $empleados = $empleado->where('estado', '!=', 'E')->get();

        for($i = 0; $i < count($empleados); $i++) {
            echo 'Id: ' . $empleados[$i]->id . ". Usuario: " . $empleados[$i]->usuario . ". Estado: " . $empleados[$i]->estado . PHP_EOL;
        }        
    }

    public function EliminarEmpleado($request, $response, $args) {
        $parametros = $request->getParsedBody();
        $idEmpleado = $parametros['id_empleado'];

        try {
            $empleadoDao = new App\Models\Empleado;

            $empleado = $empleadoDao->where('id', '=', $idEmpleado)
                                    ->where('estado', '!=', 'E')->first();

            if($empleado != null) {
                $empleado->estado = 'E';
                $empleado->save();
                $mensaje = array("Estado" => "OK", "Mensaje" => "Se eliminó empleado: " . $empleado->usuario);
            }
            else
                $mensaje = array("Estado" => "ERROR", "Mensaje" => "No existe empleado con el Id: " . $idEmpleado);
            
        }
        catch(Exception $e) {
            $error = $e->getMessage();
            $mensaje = array("Estado" => "ERROR", "Mensaje" => $error);
        }
        return $response->withJson($mensaje, 200);
    }

    public function SuspenderEmpleado($request, $response, $args) {
        $parametros = $request->getParsedBody();
        $idEmpleado = $parametros['id_empleado'];

        try {
            $empleadoDao = new App\Models\Empleado;
            $empleado = $empleadoDao->where('id', '=', $idEmpleado)->first();
            if($empleado != null){
                $empleado->estado = 'S';
                $empleado->save();
                $mensaje = array("Estado" => "OK", "Mensaje" => "El empleado " . $empleado->usuario . " fue suspendido.");
            }
            else
                $mensaje = array("Estado" => "ERROR", "Mensaje" => "No existe empleado con el Id: " . $idEmpleado);
        }
        catch(Exception $e) {
            $error = $e->getMessage();
            $mensaje = array("Estado" => "ERROR", "Mensaje" => $error);
        }
        return $response->withJson($mensaje, 200); 
    }

    public function VerEmpleadosPorPuesto($request, $response, $args) {
        try {
            if(isset($_GET['puesto']))
                $puesto = $_GET['puesto'];
            else
                $puesto = '';;

            $empleado = new App\Models\Empleado;

            $empleados = $empleado ->join('tipo_empleados', 'empleados.id_tipo_empleado', '=', 'tipo_empleados.id')
                                    ->where('tipo_empleados.tipo_empleado', '=', $puesto)
                                    ->select('*')->get();
            
            if($empleados != null){
                echo 'Listado de ' . strtolower($puesto) . 's' . PHP_EOL . PHP_EOL;
                for($i = 0; $i < count($empleados); $i++) {
                    echo 'Usuario: ' . $empleados[$i]->usuario . PHP_EOL;
                }  
            }                   
        }
        catch(Exception $e) {
            $error = $e->getMessage();
            $mensaje = array("Estado" => "Error", "Mensaje" => $error);
            return $response->withJson($mensaje, 200);
        }
    }

    public function CantidadOperacionesPorEmpleado($request, $response, $args) {
        $fecha = $_GET['fecha'];
        $fecha_desde = $_GET['fecha_desde'];
        $fecha_hasta = $_GET['fecha_hasta'];
        $empleado = $_GET['empleado'];

        $operacion = new App\Models\Operacion;
        $empleadoDao = new App\Models\Empleado;

        if($fecha != 0) {         
            $fecha = strtotime($fecha);
            $fecha = date('Y-m-d H:i:s' , $fecha);             

            $operaciones = $operacion->join('empleados', 'operaciones.id_empleado', '=', 'empleados.id')
                                    ->where('operaciones.fecha', '=', $fecha)
                                    ->where('empleados.usuario', '=', $empleado)
                                    ->where('empleados.estado', '!=', 'E')
                                    ->select('empleados.id as idEmpleado', 'empleados.usuario')->get();

            $empleados = $empleadoDao->all();
            $existe = false;

            for($i = 0; $i < count($empleados); $i++) {
                if($empleados[$i]->usuario == $empleado) {
                    if(count($operaciones) > 0)
                        echo 'Empleado: ' . $empleado . ' realizó ' . count($operaciones) . ' operaciones.';
                    else
                        echo $empleado . ' No registra operaciones.';

                    $existe = true;
                    break;    
                }
            }
            if(!$existe)
                echo 'Nombre de empleado inexistente.';
        }
        else {
            $fecha_desde = strtotime($fecha_desde);
            $fecha_desde = date('Y-m-d H:i:s' , $fecha_desde);  
            $fecha_hasta = strtotime($fecha_hasta);
            $fecha_hasta = date('Y-m-d H:i:s' , $fecha_hasta);

            $operaciones = $operacion->join('empleados', 'operaciones.id_empleado', '=', 'empleados.id')
                                    ->where('operaciones.fecha', '>=', $fecha_desde)
                                    ->where('operaciones.fecha', '<=', $fecha_hasta)
                                    ->where('empleados.estado', '!=', 'E')
                                    ->where('empleados.usuario', '=', $empleado)
                                    ->select('empleados.id as idEmpleado', 'empleados.usuario')->get();

            $empleados = $empleadoDao->all();
            $existe = false;

            for($i = 0; $i < count($empleados); $i++) {
                if($empleados[$i]->usuario == $empleado) {
                    if(count($operaciones) > 0)
                        echo 'Empleado: ' . $empleado . ' realizó ' . count($operaciones) . ' operaciones.';
                    else
                        echo $empleado . ' No registra operaciones.';
                    $existe = true;
                    break;    
                }
            }
            if(!$existe)
                echo 'Nombre de empleado inexistente.';         
        }
    }

    public function CantidadOperacionesPorSector($request, $response, $args) {
        $fecha = $_GET['fecha'];
        $fecha_desde = $_GET['fecha_desde'];
        $fecha_hasta = $_GET['fecha_hasta'];
        $operacion = new App\Models\Operacion;

        $operacionesLocal = 0;
        $operacionesSalon = 0;
        $operacionesCocina = 0;
        $operacionesBarraTragos = 0;
        $operacionesBarraCervezas = 0;        
        $operacionesCandyBar = 0;

        if($fecha != "") {         
            $fecha = strtotime($fecha);
            $fecha = date('Y-m-d H:i:s' , $fecha);
            $operaciones = $operacion->join('empleados', 'operaciones.id_empleado', '=', 'empleados.id')
                                     ->where('operaciones.fecha', '=', $fecha)
                                     ->where('empleados.estado', '!=', 'E')
                                     ->select('operaciones.id as id', 'empleados.id as idEmpleado', 'empleados.usuario', 'empleados.id_sector')->get();

            for($i = 0; $i < count($operaciones); $i++) {
                if($operaciones[$i]->id_sector == 1)
                    $operacionesLocal++;
                if($operaciones[$i]->id_sector == 2)
                    $operacionesSalon++;
                if($operaciones[$i]->id_sector == 3)
                    $operacionesCocina++;
                if($operaciones[$i]->id_sector == 4)
                    $operacionesBarraTragos++;
                if($operaciones[$i]->id_sector == 5)
                    $operacionesBarraCervezas++;
                 if($operaciones[$i]->id_sector == 6)
                    $operacionesCandyBar++;
            }

            echo    'Cantidad de Operaciones por sector' . PHP_EOL . PHP_EOL .
                    'Local: ' . $operacionesLocal . ' operaciones.' . PHP_EOL .
                    'Salon: ' . $operacionesSalon . ' operaciones.' . PHP_EOL .
                    'Cocina: ' . $operacionesCocina . ' operaciones.' . PHP_EOL .
                    'Barra de tragos y vinos: ' . $operacionesBarraTragos . ' operaciones.' . PHP_EOL .
                    'Barra de cervezas: ' . $operacionesBarraCervezas . ' operaciones.' . PHP_EOL .                    
                    'Candy Bar: ' . $operacionesCandyBar . ' operaciones.' . PHP_EOL;
        }
        else
        {
            $fecha_desde = strtotime($fecha_desde);
            $fecha_desde = date('Y-m-d H:i:s' , $fecha_desde);  
            $fecha_hasta = strtotime($fecha_hasta);
            $fecha_hasta = date('Y-m-d H:i:s' , $fecha_hasta);

            $operaciones = $operacion->join('empleados', 'operaciones.id_empleado', '=', 'empleados.id')
                                     ->where('operaciones.fecha', '>=', $fecha_desde)
                                     ->where('operaciones.fecha', '<=', $fecha_hasta)
                                     ->where('empleados.estado', '!=', 'E')
                                     ->select('operaciones.id as id', 'empleados.id as idEmpleado', 'empleados.usuario', 'empleados.id_sector')->get();

            for($i = 0; $i < count($operaciones); $i++)
            {
                if($operaciones[$i]->id_sector == 1)
                    $operacionesLocal++;
                if($operaciones[$i]->id_sector == 2)
                    $operacionesSalon++;
                if($operaciones[$i]->id_sector == 3)
                    $operacionesCocina++;
                if($operaciones[$i]->id_sector == 4)
                    $operacionesBarraTragos++;
                if($operaciones[$i]->id_sector == 5)
                    $operacionesBarraCervezas++;
                 if($operaciones[$i]->id_sector == 6)
                    $operacionesCandyBar++;
            }

            echo    'Cantidad de Operaciones por sector' . PHP_EOL . PHP_EOL .
                    'Local: ' . $operacionesLocal . ' operaciones.' . PHP_EOL .
                    'Salon: ' . $operacionesSalon . ' operaciones.' . PHP_EOL .
                    'Cocina: ' . $operacionesCocina . ' operaciones.' . PHP_EOL .
                    'Barra de tragos y vinos: ' . $operacionesBarraTragos . ' operaciones.' . PHP_EOL .
                    'Barra de cervezas: ' . $operacionesBarraCervezas . ' operaciones.' . PHP_EOL .                    
                    'Candy Bar: ' . $operacionesCandyBar . ' operaciones.' . PHP_EOL;
        }

    }

    static function CalcularCantidadPorEmpleado($arrayDeIdEmpleado, $operaciones) {
        //Si el array del sector tiene al menos un dato mas que el -1
        if(count($arrayDeIdEmpleado) > 1) {
            $contador = 1;

            for($i = 0; $i <= count($arrayDeIdEmpleado); $i++) { 
                //Si es el ultimo registro, se imprime la cantidad del registro anterior
                if($arrayDeIdEmpleado[$i+1] == -1) {
                    for($j = 0; $j < count($operaciones); $j++) {
                        if($arrayDeIdEmpleado[$i] == $operaciones[$j]->idEmpleado) {
                            echo 'Empleado: ' . $operaciones[$j]->usuario . ". Operaciones: " . $contador . PHP_EOL;
                            break;
                        }                            
                    }
                    break;
                }
                
                else if($arrayDeIdEmpleado[$i + 1] == $arrayDeIdEmpleado[$i])
                    $contador++;
                
                else {
                    for($j = 0; $j < count($operaciones); $j++) {
                        if($arrayDeIdEmpleado[$i] == $operaciones[$j]->idEmpleado) {
                            echo 'Empleado: ' . $operaciones[$j]->usuario . " .Operaciones: " . $contador . "\n";
                            $contador = 1;
                            break;
                        }                            
                    }
                }                
            }
        }
        else
            echo 'NO HAY OPERACIONES' . PHP_EOL;
    }

    static function CalcularCantidadOperacionesPorEmpleado( $idDeEmpleadosLocal, $idDeEmpleadosSalon, $idDeEmpleadosVinos, 
                                                            $idDeEmpleadosCerveza, $idDeEmpleadosCocina, $idDeEmpleadosCandyBar, $operaciones) {
        echo 'LOCAL' . PHP_EOL;   
        EmpleadoApi::CalcularCantidadPorEmpleado($idDeEmpleadosLocal, $operaciones);

        echo PHP_EOL . '--------------------------------------------------' . PHP_EOL;
        
        echo 'SALON' . PHP_EOL;     
        EmpleadoApi::CalcularCantidadPorEmpleado($idDeEmpleadosSalon, $operaciones);

        echo PHP_EOL . '--------------------------------------------------' . PHP_EOL;
        
        echo 'BARRA DE TRAGOS Y VINOS' . PHP_EOL;      
        EmpleadoApi::CalcularCantidadPorEmpleado($idDeEmpleadosVinos, $operaciones);

        echo PHP_EOL . '--------------------------------------------------' . PHP_EOL;
        
        echo 'BARRA DE CERVEZAS ARTESANALES' . PHP_EOL;            
        EmpleadoApi::CalcularCantidadPorEmpleado($idDeEmpleadosCerveza, $operaciones);

        echo PHP_EOL . '--------------------------------------------------' . PHP_EOL;
        
        echo 'COCINA' . PHP_EOL;            
        EmpleadoApi::CalcularCantidadPorEmpleado($idDeEmpleadosCocina, $operaciones);

        echo PHP_EOL . '--------------------------------------------------' . PHP_EOL;
        
        echo 'CANDY BAR' . PHP_EOL;            
        EmpleadoApi::CalcularCantidadPorEmpleado($idDeEmpleadosCandyBar, $operaciones);
    }

    public function CantidadOperacionesPorSectorYEmpleado($request, $response, $args) {    
        $fecha = $_GET['fecha'];
        $fecha_desde = $_GET['fecha_desde'];
        $fecha_hasta = $_GET['fecha_hasta'];
        $operacion = new App\Models\Operacion;

        $idDeEmpleadosLocal = [];
        $idDeEmpleadosSalon = [];
        $idDeEmpleadosVinos = [];
        $idDeEmpleadosCerveza = [];
        $idDeEmpleadosCocina = [];
        $idDeEmpleadosCandyBar = [];

        if($fecha != "") {         
            $fecha = strtotime($fecha);
            $fecha = date('Y-m-d H:i:s' , $fecha);
            $operaciones = $operacion->join('empleados', 'operaciones.id_empleado', '=', 'empleados.id')
                                    ->where('operaciones.fecha', '=', $fecha)
                                    ->where('empleados.estado', '!=', 'E')
                                    ->select('operaciones.id as id', 'empleados.id as idEmpleado', 'empleados.usuario', 'empleados.id_sector')
                                    ->orderBy('empleados.id_sector')->get();

            for($i = 0; $i < count($operaciones); $i++) {
                if($operaciones[$i]->id_sector == 1)
                    $idDeEmpleadosLocal[] = $operaciones[$i]->idEmpleado;
                if($operaciones[$i]->id_sector == 2)
                    $idDeEmpleadosSalon[] = $operaciones[$i]->idEmpleado;
                if($operaciones[$i]->id_sector == 3)
                    $idDeEmpleadosCocina[] = $operaciones[$i]->idEmpleado;
                if($operaciones[$i]->id_sector == 4)
                    $idDeEmpleadosVinos[] = $operaciones[$i]->idEmpleado;
                if($operaciones[$i]->id_sector == 5)
                    $idDeEmpleadosCerveza[] = $operaciones[$i]->idEmpleado;
                if($operaciones[$i]->id_sector == 6)
                    $idDeEmpleadosCandyBar[] = $operaciones[$i]->idEmpleado;
            }

            //Se agrega un -1 al final del array para identificar el ultimo registro
            $idDeEmpleadosLocal[] = -1;
            $idDeEmpleadosSalon[] = -1;
            $idDeEmpleadosVinos[] = -1;
            $idDeEmpleadosCerveza[] = -1;
            $idDeEmpleadosCocina[] = -1;
            $idDeEmpleadosCandyBar[] = -1;

            EmpleadoApi::CalcularCantidadOperacionesPorEmpleado($idDeEmpleadosLocal, $idDeEmpleadosSalon, $idDeEmpleadosVinos, $idDeEmpleadosCerveza, 
                                                                $idDeEmpleadosCocina, $idDeEmpleadosCandyBar, $operaciones);
        }
        else {
            $fecha_desde = strtotime($fecha_desde);
            $fecha_desde = date('Y-m-d H:i:s' , $fecha_desde);  
            $fecha_hasta = strtotime($fecha_hasta);
            $fecha_hasta = date('Y-m-d H:i:s' , $fecha_hasta);

            $operaciones = $operacion->join('empleados', 'operaciones.id_empleado', '=', 'empleados.id')
                                    ->where('operaciones.fecha', '>=', $fecha_desde)
                                    ->where('operaciones.fecha', '<=', $fecha_hasta)
                                    ->where('empleados.estado', '!=', 'E')
                                    ->select('operaciones.id as id', 'empleados.id as idEmpleado', 'empleados.usuario', 'empleados.id_sector')
                                    ->orderBy('empleados.id_sector')->get();

            for($i = 0; $i < count($operaciones); $i++) {
                if($operaciones[$i]->id_sector == 1)
                    $idDeEmpleadosLocal[] = $operaciones[$i]->idEmpleado;
                if($operaciones[$i]->id_sector == 2)
                    $idDeEmpleadosSalon[] = $operaciones[$i]->idEmpleado;
                if($operaciones[$i]->id_sector == 3)
                    $idDeEmpleadosCocina[] = $operaciones[$i]->idEmpleado;
                if($operaciones[$i]->id_sector == 4)
                    $idDeEmpleadosVinos[] = $operaciones[$i]->idEmpleado;
                if($operaciones[$i]->id_sector == 5)
                    $idDeEmpleadosCerveza[] = $operaciones[$i]->idEmpleado;
                if($operaciones[$i]->id_sector == 6)
                    $idDeEmpleadosCandyBar[] = $operaciones[$i]->idEmpleado;
            }

            //Se agrega un -1 al final del array para identificar el ultimo registro
            $idDeEmpleadosLocal[] = -1;
            $idDeEmpleadosSalon[] = -1;
            $idDeEmpleadosVinos[] = -1;
            $idDeEmpleadosCerveza[] = -1;
            $idDeEmpleadosCocina[] = -1;
            $idDeEmpleadosCandyBar[] = -1;

            EmpleadoApi::CalcularCantidadOperacionesPorEmpleado($idDeEmpleadosLocal, $idDeEmpleadosSalon, $idDeEmpleadosVinos, $idDeEmpleadosCerveza, 
                                                                $idDeEmpleadosCocina, $idDeEmpleadosCandyBar, $operaciones);
        }
    }
}
