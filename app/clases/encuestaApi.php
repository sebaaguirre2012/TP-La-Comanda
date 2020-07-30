<?php

class EncuestaApi {
    public function RegistrarEncuesta($request, $response, $args) {
        $parametros = $request->getParsedBody();

        $codigoMesa = $parametros['codigo_mesa'];
        $puntuacionMesa = $parametros['puntuacion_mesa'];
        $puntuacionRestaurante = $parametros['puntuacion_restaurante'];
        $puntuacionMozo = $parametros['puntuacion_mozo'];
        $puntuacionCocinero = $parametros['puntuacion_cocinero'];
        $comentario = $parametros['comentario'];

        if(strlen($comentario) > 66) {
            $mensaje = array("Estado" => "ERROR", "Mensaje" => "El comentario no puede superar los 66 caracteres.");
            return $response->withJson($mensaje, 200);
        }

        $encuesta = new App\Models\Encuesta;
        $mesa = new App\Models\Mesa;

        $mesaActual = $mesa->where('codigo', '=', $codigoMesa)->first();

        if($mesaActual != null) {
            try {
                date_default_timezone_set("America/Argentina/Buenos_Aires");
                $fecha = date('Y-m-d');
                $encuesta->fecha = $fecha;
                $encuesta->codigo_mesa = $codigoMesa;
                $encuesta->puntuacion_mesa = $puntuacionMesa;
                $encuesta->puntuacion_restaurante = $puntuacionRestaurante;
                $encuesta->puntuacion_mozo = $puntuacionMozo;
                $encuesta->puntuacion_cocinero = $puntuacionCocinero;
                $encuesta->comentario = $comentario;
                $encuesta->save();
                $mensaje = array("Estado" => "Ok", "Mensaje" => "Encuesta registrada.");
            }
            catch(Exception $e) {
                $mensaje = array("Estado" => "ERROR", "Mensaje" => $e->getMessage());
            }
        }
        else
            $mensaje = array("Estado" => "ERROR", "Mensaje" => "Mesa inexistente.");
    
        return $response->withJson($mensaje, 200);
    }
}

