<?php

class Empresa {
    private $idEmpresa;
    private $nombre;
    private $direccion;
    private $mensajeoperacion;

    public function __construct() {
        $this->idEmpresa = "";
        $this->nombre = "";
        $this->direccion = "";
    }

    public function getIdEmpresa() {
        return $this->idEmpresa;
    }

    public function setIdEmpresa($idEmpresa) {
        $this->idEmpresa = $idEmpresa;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function getdireccion() {
        return $this->direccion;
    }

    public function setDireccion($direccion) {
        $this->direccion = $direccion;
    }

    public function getMensajeOperacion(){
		return $this->mensajeoperacion;
	}

    public function setMensajeOperacion($mensajeoperacion) {
		$this->mensajeoperacion = $mensajeoperacion;
	}

    public function __toString() {
        return "Id de la empresa: " . $this->getIdEmpresa() . "\n" . 
               "Nombre: " . $this->getNombre() . "\n" . 
               "Dirección: " . $this->getdireccion() . "\n\n";
    }

    public function cargarEmpresa($idEmpresaCargar, $nombreCargar, $direccionCargar) {
        $this->setIdEmpresa($idEmpresaCargar);
        $this->setNombre($nombreCargar);
        $this->setDireccion($direccionCargar);    
    }

    public function buscarEmpresa($idEmpresa){
		$base=new BaseDatos();
		$consultaEmpresa="Select * from empresa where idempresa=".$idEmpresa;
		$resp= false;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaEmpresa)){
				if($row2=$base->Registro()){
					$this->cargarEmpresa($idEmpresa,$row2['enombre'],$row2['edireccion']);
					$resp= true;
				}				
			
		 	}	else {
		 			$this->setMensajeOperacion($base->getError());
		 		
			}
		 }	else {
		 		$this->setMensajeOperacion($base->getError());
		 	
		 }		
		 return $resp;
	}

    public function insertarEmpresa(){
		$base=new BaseDatos();
		$resp= false;
		$consultaInsertar="INSERT INTO empresa(enombre, edireccion)
				VALUES ('".$this->getNombre()."','".$this->getDireccion()."')";
		
		if($base->Iniciar()){
            $id = $base->devuelveIDInsercion($consultaInsertar);
			if($id != null){
				$resp=  true;
				$this->setIdEmpresa($id);
			}	else {
					$this->setMensajeOperacion($base->getError());
					
			}

		} else {
				$this->setMensajeOperacion($base->getError());
			
		}
		return $resp;
	}

    /**
	 * @param string
	 * @return array $arregloEmpresa
	 */
	public function listarEmpresa($condicion=""){
        $arregloEmpresa = null;
        $base = new BaseDatos();
        $consultaEmpresa = 'Select * FROM empresa';
        if($condicion !=""){
            $consultaEmpresa = $consultaEmpresa. ' where ' .$condicion;
        }
        $consultaEmpresa .=' order by enombre';

        if($base->Iniciar()){
            if($base->Ejecutar($consultaEmpresa)){
                $arregloEmpresa = array();
                while($row2 = $base->Registro()){

                    $idEmpresa = $row2['idempresa'];
                    $nombre = $row2['enombre'];
                    $direccion = $row2['edireccion'];

                    $empre= new Empresa();
                    $empre->cargarEmpresa($idEmpresa, $nombre, $direccion);

                    array_push($arregloEmpresa, $empre);
                }

            }else{
                $this->setMensajeOperacion($base->getError());
            }

        }else{
            $this->setMensajeOperacion($base->getError());
        }
        return $arregloEmpresa;
    }

    public function modificarEmpresa(){
	    $resp =false; 
	    $base=new BaseDatos();
		$consultaModifica="UPDATE empresa SET enombre ='".$this->getNombre()."',edireccion='".$this->getdireccion()."' WHERE idempresa = ".$this->getIdEmpresa();
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

    public function eliminarEmpresa(){
		$base=new BaseDatos();
		$resp=false;
		if($base->Iniciar()){
				$consultaBorra="DELETE FROM empresa WHERE idempresa=".$this->getIdEmpresa();
				if($base->Ejecutar($consultaBorra)){
				    $resp = true;
				}else{
						$this->setmensajeoperacion($base->getError());
					
				}
		}else{
				$this->setmensajeoperacion($base->getError());
			
		}
		return $resp; 
	}
}