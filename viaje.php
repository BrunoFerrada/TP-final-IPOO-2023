<?php

class Viaje {
    private $idViaje;
    private $destino;
    private $cantMaxPasajeros;
    private $empresa;
    private $empleado;
    private $importe;
    private $colPasajeros;
    private $mensajeoperacion;

    public function __construct() {
        $this->idViaje = 0;
        $this->destino = "";
        $this->cantMaxPasajeros = 0;
        $this->empresa = new Empresa();
        $this->empleado = new ResponsableV();
        $this->importe = 0;
        $this->colPasajeros = [];
    }

    /** Devuelve el valor actual almacenado en el atributo idViaje
    * @return int $idViaje */
    public function getIdViaje() {
        return $this->idViaje;
    }

    /** Coloca el valor pasado por parámetro en el atributo idViaje
    * @param int $idViaje */
    public function setIdViaje($idViaje) {
        $this->idViaje = $idViaje;
    }

    /** Devuelve el valor actual almacenado en el atributo destino
    * @return string $destino */
    public function getDestino() {
        return $this->destino;
    }

    /** Coloca el valor pasado por parámetro en el atributo destino
    * @param string $destino */
    public function setDestino($destino) {
        $this->destino = $destino;
    }

    /** Devuelve el valor actual almacenado en el atributo cantMaxPasajeros
    * @return int $cantMaxPasajeros */
    public function getCantMaxPasajeros() {
        return $this->cantMaxPasajeros;
    }

    /** Coloca el valor pasado por parámetro en el atributo cantMaxPasajeros
    * @param int $cantMaxPasajeros */
    public function setCantMaxPasajeros($cantMaxPasajeros) {
        $this->cantMaxPasajeros = $cantMaxPasajeros;
    }

    /** Devuelve el valor actual almacenado en el atributo empresa
    * @return Empresa $empresa */
    public function getEmpresa() {
        return $this->empresa;
    }

    /** Coloca el valor pasado por parámetro en el atributo empresa 
    * @param Empresa $empresa */
    public function setEmpresa($empresa) {
        $this->empresa = $empresa;
    }

    /** Devuelve el valor actual almacenado en el atributo empleado
    * @return Responsable $empleado */
    public function getEmpleado() {
        return $this->empleado;
    }

    /** Coloca el valor pasado por parámetro en el atributo empleado 
    * @param Responsable $empleado */
    public function setEmpleado($empleado) {
        $this->empleado = $empleado;
    }

    /** Devuelve el valor actual almacenado en el atributo importe
    * @return $Importe */
    public function getImporte() {
        return $this->importe;
    }

    /** Coloca el valor pasado por parámetro en el atributo importe 
    * @param float $importe */
    public function setImporte($importe) {
        $this->importe = $importe;
    }

    /** Devuelve el valor actual almacenado en el atributo pasajeros
    * @return array $pasajeros */
    public function getColPasajeros() {
        return $this->colPasajeros;
    }

    /** Coloca el valor pasado por parámetro en el atributo pasajeros 
    * @param array $pasajeros */
    public function setColPasajeros($colPasajeros) {
        $this->colPasajeros = $colPasajeros;
    }

    public function getMensajeOperacion(){
		return $this->mensajeoperacion;
	}

    public function setMensajeOperacion($mensajeoperacion) {
		$this->mensajeoperacion = $mensajeoperacion;
	}

    public function __toString() {
        return "Id del viaje: " . $this->getIdViaje() . "\n" . 
               "Destino: " . $this->getDestino() . "\n" . 
               "Cantidad máxima de pasajeros: " . $this->getCantMaxPasajeros() . "\n" . 
               "Id de la empresa: " . $this->getEmpresa() . "\n" . 
               "Número del empleado: " . $this->getEmpleado() . "\n" . 
               "Importe: " . $this->getImporte() . "\n" .
               "Pasajeros: " . $this->mostrarPasajeros() . "\n\n";  
    }

    public function mostrarPasajeros(){
        $mensaje = '';
        $colPasajeros = $this->getColPasajeros();
        if(count($colPasajeros)>0){
            for ($i=0; $i < count($colPasajeros); $i++) { 
                $mensaje .= '---------------' . "\n" . $colPasajeros[$i]."\n";
            }
    }
    return $mensaje;
    }

    //esta funcionn trae los datos de los pasajeros de la base de datos, y los setea en la clase
    public function traePasajeros(){
        $pasajero = new Pasajero();
        $condicion = "idviaje =".$this->getidViaje();
        $colPasajeros = $pasajero->listarPasajeros($condicion);
        $this->setColPasajeros($colPasajeros);
    }   

    

    public function cargarViaje($idViajeCargar, $destinoCargar,$cantMaxPasajerosCargar, $empresaCargar, $empleadoCargar, $importeCargar, $colPasajerosCargar) {
        $this->setIdViaje($idViajeCargar);
        $this->setDestino($destinoCargar);
        $this->setCantMaxPasajeros($cantMaxPasajerosCargar);
        $this->setEmpresa($empresaCargar);
        $this->setEmpleado($empleadoCargar);
        $this->setImporte($importeCargar);
        $this->setColPasajeros($colPasajerosCargar);  
    }

    public function listarViaje($condicion = "") {
        $arregloViaje = null;
        $base = new BaseDatos();
        $consultaViaje = "Select * FROM viaje";
        if ($condicion != "") {
            $consultaViaje = $consultaViaje. 'WHERE' .$condicion;
        }
        $consultaViaje .=" order by idViaje";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaViaje)) {
                $arregloViaje = array();
                while ($row2 = $base->Registro()) {
                    $objViaje = new Viaje();
                    $objViaje->buscarViaje($row2['idviaje']);
                    array_push($arregloViaje, $objViaje);
            
                } 
            }else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());  
        }
        return $arregloViaje;
   }

    public function buscarViaje($idViaje){
        $base=new BaseDatos();
        $consultaViaje="Select * from viaje where idViaje=".$idViaje;
        $resp= false;
        if($base->Iniciar()){
            if($base->Ejecutar($consultaViaje)){
                if($row2=$base->Registro()){
                   $objEmpresa = new Empresa();
                   $objEmpresa-> buscarEmpresa($row2['idempresa']);
                   $objResponsable = new ResponsableV();
                   $objResponsable-> buscarResponsable($row2['rnumeroempleado']);
                   $this->cargarViaje($idViaje, $row2['vdestino'],$row2['vcantmaxpasajeros'], $objEmpresa,$objResponsable, $row2['vimporte'],[]);
                    $resp= true;
                }
    
             }    else {
                     $this->setmensajeoperacion($base->getError());
    
            }
         }    else {
                 $this->setmensajeoperacion($base->getError());
    
         }
         return $resp;
    }

    public function insertarViaje(){
        $base = new BaseDatos();
        $resp = false;
        $consultaInsentar = "INSERT INTO viaje(vdestino, vcantmaxpasajeros, idempresa, rnumeroempleado, vimporte)
        VALUES ('".$this->getDestino()."', ".$this->getCantMaxPasajeros().", ".$this->getEmpresa()->getIdEmpresa().", ".$this->getEmpleado()->getNroEmpleado().", ".$this->getImporte().")";
        if($base->Iniciar()){
            $id = $base->devuelveIDInsercion($consultaInsentar);
            if($id !=null){
                $resp=  true;
                $this->setIdViaje($id);
            }    else {
                    $this->setMensajeOperacion($base->getError());
    
            }
    
        } else {
                $this->setMensajeOperacion($base->getError());
    
        }
        return $resp;
    
    }

    public function modificarViaje(){
	    $resp =false; 
	    $base=new BaseDatos();
		$consultaModifica = "UPDATE viaje SET vdestino = '".$this->getDestino()."', vcantmaxpasajeros = '".$this->getCantMaxPasajeros()."', idempresa = '".$this->getEmpresa()->getIdEmpresa()."', rnumeroempleado = '". $this->getEmpleado()->getNroEmpleado()."' WHERE idviaje = ".$this->getIdViaje();
		if($base->Iniciar()){
			if($base->Ejecutar($consultaModifica)){
			    $resp=  true;
			}else{
				$this->setmensajeoperacion($base->getError());
				
			}
		}else{
				$this->setmensajeoperacion($base->getError());
			
		}
		return $resp;
	}

    public function eliminarViaje(){
		$base=new BaseDatos();
		$resp=false;
		if($base->Iniciar()){
            $consultaEliminaPasajeros = "DELETE FROM pasajero WHERE idviaje = " . $this->getIdViaje();
            if ($base->Ejecutar($consultaEliminaPasajeros)) {
				$consultaBorra="DELETE FROM viaje WHERE idviaje=".$this->getIdViaje();
				if($base->Ejecutar($consultaBorra)){
				    $resp = true;
				}else{
					$this->setmensajeoperacion($base->getError());
					
				}
            } else {
                $this->setmensajeoperacion($base->getError());
            }
		}else{
				$this->setmensajeoperacion($base->getError());
			
		}
		return $resp; 
	}
}
