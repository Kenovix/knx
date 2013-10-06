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
		$arrayPacientes = array();
	
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
	
	// verifica que no hallan datos repetidos en el archivo
	public function fileSearchData($DatosTemporal)
	{
		$start = 0;
		$end = count($DatosTemporal);
		$arraySalida = array();
		$arrayPacientes = array();
		
		for($i=0; $i<$end; $i++)
		{
			$arrayPacientes[] = (int)$DatosTemporal[$i][1];
		}
		// se ordena el array
		$end = count($arrayPacientes);
		$arrayOrdenado = $this->quicksort($arrayPacientes, $start, $end-1);		
		
		// se pasan las identificaciones del archivo temporal a un array de solo identificaciones de tipo int
		for($i=0; $i<($end-1); $i++)
		{
			if($arrayOrdenado[$i] == $arrayOrdenado[$i+1])
			{
				$arraySalida[] = $arrayOrdenado[$i];
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
	public function quicksort($A, $izq, $der)
	{
		// definimos los índices y calculamos el pivote = centro = x
		$i = $izq;
		$j = $der;
		$x = $A[ ($izq + $der) /2 ]; // ($A[$i]+$A[$j])/2;
		
		// iteramos hasta que i no sea menor que j
		do{
			// iteramos mientras que el valor de A[i] sea menor que x y j sea menor q la der
			while(($A[$i]<$x)&&($j<=$der)){
				$i++; // Incrementamos el índice
			}
			// iteramos mientras que el valor de A[j] sea mayor que x y j sea mayor que izq
			while(($x<$A[$j])&&($j>$izq)){
				$j--; // decrementamos el índice
			}
			// si i es menor o igual que j significa que los índices se han cruzado
			if($i<=$j){
				// creamos una variable temporal para guardar el valor de A[j]
				$aux = $A[$i]; 
				// intercambiamos los valores de A[i] y A[j]
				$A[$i] = $A[$j]; 
				$A[$j] = $aux;
				// incrementamos y decrementamos i y j respectivamente
				$i++;  $j--;
			}
		}while($i<=$j);
	
		// si first es menor que j mantenemos la recursividad
		if( $izq < $j ){
			$A= $this->quicksort( $A, $izq, $j );
		}			
		// si last es mayor que i mantenemos la recursividad
		if( $i < $der ){
			$A= $this->quicksort( $A, $i, $der );
		}		
	  // devolvemos la lista ordenada
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
}
