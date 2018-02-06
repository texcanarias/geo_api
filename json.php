<?php

/**
 * Con el fichero serializado.data que contiene datos obtenido del API de gelocalización inversa
 * https://developers.google.com/maps/documentation/geocoding/intro?hl=es-419
 * Se obtienen los valores principales.
 * 
 */

if ($output_json = file_get_contents("serializado.data")){
    if ($output = json_decode($output_json)){
        $res = procesado($output);

        echo '<pre>';
        print_r($res);
        echo '</pre>';        
    }
    else{
        echo "Error en la decodificación ...";
    }
}
else{
    echo "Error de lectura ...";
}


/**
 * Obtener una estructura de datos útil a partir del json proporcionado por google.
 */
function procesado($data){
    $data_col = [];

    if("OK" == $data->status){
        foreach($data->results as $local){
            $PlaceId = $local->place_id;
            $Diana = $local->geometry->location_type;
            $Tipo = getTipo($local->types);
            $Descrip = $local->formatted_address;
            $Clave = getClave($Tipo, $Descrip);

            if("APPROXIMATE" == $Diana || "ROOFTOP" == $Diana ){
                $data_col[$PlaceId] = array("tipo" => $Tipo, "descripcion" => $Descrip, "clave" => $Clave);
            }

        }
    }
    else{
        echo "La respuesta no ha sido correcta.";
    }

    return $data_col;

}

/**
 * El tipo de localización más significativo para alamacenar las localizaciones.
 */
function getTipo($Tipos){
    $Seleccionado = "";
    foreach($Tipos as $Tipo){
        if ("political" != $Tipo){
            if(strlen($Seleccionado) < strlen($Tipo)){
                $Seleccionado = $Tipo;
            }
        }
    }
    return $Seleccionado;
}

/**
 * Mediante la clave procesamos el texto que indica la localización más significativa.
 */
function getClave($Tipo, $Descrip){
    $Clave = explode(",",$Descrip);
    if("street_address" == $Tipo){
        $Clave = $Clave[0].",".$Clave[1];
        return trim($Clave);
    }
    $Clave = $Clave[0];
    if("postal_code" == $Tipo){
        $Clave = explode(" ",$Clave)[0];
    }
    return trim($Clave);
}