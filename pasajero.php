<?php

class pasajero {
    private $nombre;
    private $apellido;
    private $nroDocumento;
    private $telefono;
    private $refViaje;
    private $mensajeoperacion;

    public function __construct() {
        $this->nombre = "";
        $this->apellido = "";
        $this->nroDocumento = "";
        $this->telefono = "";
        $this->refViaje = new Viaje();
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function getApellido() {
        return $this->apellido;
    }

    public function setApellido($apellido) {
        $this->apellido = $apellido;
    }

    public function getNroDocumento() {
        return $this->nroDocumento;
    }

    public function setNroDocumento($nroDocumento) {
        $this->nroDocumento = $nroDocumento;
    }

    public function getTelefono() {
        return $this->telefono;
    }

    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    /** Devuelve el valor actual almacenado en el atributo refViajes
    * @return Viaje $refViajes */
    public function getRefViaje() {
        return $this->refViaje;
    }

    /** Coloca el valor pasado por parámetro en el atributo refViajes 
    * @param Viaje $refViajes */
    public function setRefViaje($refViaje) {
        $this->refViaje = $refViaje;
    }

    public function getMensajeOperacion(){
		return $this->mensajeoperacion;
	}

    public function setMensajeOperacion($mensajeoperacion) {
		$this->mensajeoperacion = $mensajeoperacion;
	}

    public function __toString() {
        return "Nombre: " . $this->getNombre() . "\n" .
               "Apellido: " . $this->getApellido() . "\n" . 
               "Número de documento: " . $this->getNroDocumento() . "\n" . 
               "Teléfono: " . $this->getTelefono() . "\n\n";
    }

    public function cargarPasajeros($nroDocumentoCargar, $nombreCargar, $apellidoCargar, $telefonoCargar, $refViajeCargar) {
        $this->nroDocumento = $nroDocumentoCargar;
        $this->nombre = $nombreCargar;
        $this->apellido = $apellidoCargar;
        $this->telefono = $telefonoCargar;
        $this->refViaje = $refViajeCargar;
    }

    public function listarPasajeros($condicion = "") {
        $arregloPasajero = null;
        $base = new BaseDatos();
        $consultaPasajero = "Select * from pasajero";
        if ($condicion != "") {
            $consultaPasajero = $consultaPasajero. ' where ' .$condicion;
        }
        $consultaPasajero .=" order by papellido";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaPasajero)) {
                $arregloPasajero = array();
                while ($row2 = $base->Registro()) {
                    $numDoc = $row2 ['pdocumento'];
                    $nombre = $row2 ['pnombre'];
                    $apellido = $row2 ['papellido'];
                    $telefono = $row2 ['ptelefono'];

                    $objViaje = new Viaje();
                    $objViaje->buscarViaje($row2 ['idviaje']);

                    $pasaj = new Pasajero();
                    $pasaj->cargarPasajeros($numDoc, $nombre, $apellido, $telefono, $objViaje);

                    array_push($arregloPasajero, $pasaj);
                }
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $arregloPasajero;
    }

    public function buscarPasajero($dni) {
        $base= new BaseDatos();
        $consultaPasajero = "select * from pasajero where pdocumento=".$dni;
        $resp = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaPasajero)) {
                if ($row2 = $base->Registro()) {
                    $objViaje = new Viaje();
                    $objViaje-> buscarViaje($row2['idviaje']);
                    $this->cargarPasajeros($dni, $row2['pnombre'], $row2['papellido'], $objViaje, $row2['ptelefono']);
                    $resp = true;
                }
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $resp;
    }

    public function insertarPasajero() {
        $base = new BaseDatos();
        $resp = false;
        $consultaInsertar = "INSERT INTO pasajero(pdocumento, pnombre, papellido, ptelefono, idviaje)
        VALUES (".$this->getNroDocumento().", '".$this->getNombre()."', '".$this->getApellido()."', '".$this->getTelefono()."', '".$this->getRefViaje()->getIdViaje()."')";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaInsertar)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $resp;
    }

    public function modificarPasajero(){
	    $resp =false; 
	    $base=new BaseDatos();
		$consultaModifica="UPDATE pasajero SET papellido='".$this->getApellido()."',pnombre='".$this->getNombre()."'
                           ,ptelefono='".$this->getTelefono()."',idviaje='".$this->getRefViaje()->getIdViaje()."' WHERE pdocumento=". $this->getNroDocumento();
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

    public function eliminarPasajero(){
		$base=new BaseDatos();
		$resp=false;
		if($base->Iniciar()){
				$consultaBorra="DELETE FROM pasajero WHERE pdocumento=".$this->getNroDocumento();
				if($base->Ejecutar($consultaBorra)){
				    $resp=  true;
				}else{
						$this->setmensajeoperacion($base->getError());
					
				}
		}else{
				$this->setmensajeoperacion($base->getError());
			
		}
		return $resp; 
	}

}