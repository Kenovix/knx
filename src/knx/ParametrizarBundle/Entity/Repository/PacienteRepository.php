<?php
namespace knx\ParametrizarBundle\Entity\Repository;
use Doctrine\ORM\EntityRepository;

class PacienteRepository extends EntityRepository {

	// $em = $this->getEntityManager();
	
	public function validarInformacion($objPacientes,$DatosTemporal)
	{
		$start = 0;
		$end = count($objPacientes);
		$arraySalida = array();
	
		// se pasan las identificaciones del objPaciente a un array de solo identificaciones de tipo int
		for($i=0; $i<count($objPacientes); $i++)
		{
			$arrayPacientes[] = (int)$objPacientes[$i]['identificacion'];
		}
	
		// se ordena el array
		$arrayOrdenado = $this->quicksort($arrayPacientes, $start, $end-1);
	
		// se pasan las identificaciones del archivo temporal a un array de solo identificaciones de tipo int
		for($i=0; $i<count($DatosTemporal); $i++)
		{
			// se verifica si el dato existe en el array
			$salida = $this->binarySearch((int)$DatosTemporal[$i][1], $arrayOrdenado, $start, $end-1);
				
			if($salida){
				$arraySalida[] = $DatosTemporal[$i][1];
			}
		}
			return $arraySalida;
	}
	
	// Algoritmo de busqueda binaria para las identificaciones
	public function binarySearch($key, $collection, $start, $end)
	{
	 $valorCentral=0;
	 $central=0;
	
		while($start<=$end)
		{
			$central = (int)(($start+$end)/2);
			$valorCentral = $collection[$central];
			if($key == $valorCentral){
				return true;
			}
			else if($key < $valorCentral){
				$end = $central-1;
			}
			else{
				$start = $central+1;
			}
		}
		return false;
	}
	
	
	// algoritmo de ordenamiento QUICKSORT para mayor eficiencia de su ordenamiento el array debe estar no ordenado
	public function quicksort($A, $izq, $der )
	{
		$i = $izq;
		$j = $der;
		$x = $A[ ($izq + $der) /2 ];
		do{
			while(($A[$i]<$x)&&($j<=$der)){
				$i++;
			}
			while(($x<$A[$j])&&($j>$izq)){
				$j--;
			}
			if($i<=$j){
				$aux = $A[$i]; $A[$i] = $A[$j]; $A[$j] = $aux;
				$i++;  $j--;
			}
		}while($i<=$j);
	
		if( $izq < $j )
			$this->quicksort( $A, $izq, $j );
		if( $i < $der )
			$this->quicksort( $A, $i, $der );
	
			return $A;
	}
	
	public function existTipoId($tipoId)
	{
		// "MS","PA","CC", "RC", "TI", "CE", "NV", "AS"
		switch ($tipoId) {
		    case "CC":
		        return true;
		        break;
		    case "TI":
		        return true;
		        break;
		    case "RC":
		        return true;
		        break;
	        case "PA":
	        	return true;
	        	break;
        	case "MS":
        		return true;
        		break;
        	case "CE":
        		return true;
        		break;
        	case "NV":
        		return true;
        		break;
        	case "AS":
        		return true;
        		break;
		}
	  return false;
	}
	public function existIdentificacion($identificacion)
	{
		// min = "10000",
		// max = "9999999999999",
		
		if(is_numeric($identificacion))
			if($identificacion > 10000 && $identificacion < 9999999999999)
				return true;
		return false;
	}
	public function existPriNombre($priNombre)
	{
		if(strlen($priNombre) > 0 && strlen($priNombre) <= 30)
			return true;
		return false;
	}
	public function existPriApellido($priApellido)
	{
		if(strlen($priApellido) > 0 && strlen($priApellido) <= 30)
			return true;
		return false;
	}
	public function existFN($fn)
	{
		if(strlen($fn) > 0 && strlen($fn) < 15)
			return true;
		return false;
	}
	public function existSexo($sexo)
	{
		switch ($sexo) {
			case "M":
				return true;
				break;
			case "F":
				return true;
				break;
		}
		return false;
	}
	public function existEstadoCivil($estadoCivil)
	{
		// "CASADO", "SOLTERO","UNION_LIBRE"
		switch ($estadoCivil) {
			case "CASADO":
				return true;
				break;
			case "SOLTERO":
				return true;
				break;
			case "UNION_LIBRE":
				return true;
				break;
		}
		return false;
	}
	public function existZona($zona)
	{
		// "U", "R"
		switch ($zona) {
			case "U":
				return true;
				break;
			case "R":
				return true;
				break;
		}
		return false;
	}
	public function existRango($rango)
	{
		// "A", "B", "C"
		switch ($rango) {
			case "A":
				return true;
				break;
			case "B":
				return true;
				break;
			case "C":
				return true;
				break;
		}
		return false;
	}
	public function existTipoAfi($tipoAfi)
	{
		// "B", "C"
		switch ($tipoAfi) {
			case "B":
				return true;
				break;
			case "C":
				return true;
				break;
		}
		return false;
	}
}
