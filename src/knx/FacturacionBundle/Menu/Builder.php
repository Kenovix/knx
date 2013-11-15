<?php

namespace knx\FacturacionBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
	public function facturaMenu(FactoryInterface $factory, array $options)
	{
		$security = $this->container->get('security.context');
		$usuario = $security->getToken()->getUser();

		$menu = $factory->createItem('root');
		$menu->setChildrenAttributes(array('id' => 'menu'));
		if($security->isGranted('ROLE_SUPER_ADMIN')){
                    
                    $menu->addChild('Parametrizar', array('uri' => '#'));
			$menu['Parametrizar']->addChild('Almacen', array('route' => 'almacen_list'));
			$menu['Parametrizar']->addChild('Cargo', array('route' => 'cargo_list'));
			$menu['Parametrizar']->addChild('Categoría pyp', array('route' => 'pyp_list'));
			$menu['Parametrizar']->addChild('Centro de costo', array('route' => 'servicio_list'));
			$menu['Parametrizar']->addChild('Cliente', array('route' => 'cliente_list'));
			$menu['Parametrizar']->addChild('Empresa', array('route' => 'empresa_list'));
			$menu['Parametrizar']->addChild('Proveedor', array('route' => 'proveedor_list'));
			$menu['Parametrizar']->addChild('Paciente', array('uri' => '#'));
			$menu['Parametrizar']['Paciente']->addChild('Listar/Nuevo', array('route' => 'paciente_list', 'routeParameters' => array('char' => 'A')));
			$menu['Parametrizar']['Paciente']->addChild('Cargar/Archivo', array('route' => 'file_new_csv'));
				
		
			$menu->addChild('farmacia', array('uri' => '#'));
			$menu['farmacia']->addChild('Nueva', array('uri' => '#'));
			$menu['farmacia']->addChild('Ingresos', array('route' => 'ingreso_list'));
			$menu['farmacia']->addChild('Movimientos', array('uri' => '#'));
			$menu['farmacia']->addChild('Pyp', array('route' => 'imvpyp_search'));
			$menu['farmacia']['Movimientos']->addChild('Traslados', array('uri' => '#'));
			$menu['farmacia']['Movimientos']['Traslados']->addChild('Listar/Nuevo', array('route' => 'traslado_list', 'routeParameters' => array('char' => 'A')));
			$menu['farmacia']['Movimientos']['Traslados']->addChild('Imprimir', array('route' => 'traslado_searchprint'));
			$menu['farmacia']['Movimientos']->addChild('Devoluciones Proveedor', array('uri' => '#'));
			$menu['farmacia']['Movimientos']['Devoluciones Proveedor']->addChild('Listar/Nuevo', array('route' => 'devolucion_list', 'routeParameters' => array('char' => 'A')));
			$menu['farmacia']['Movimientos']['Devoluciones Proveedor']->addChild('Imprimir', array('route' => 'devolucion_searchprint'));
			$menu['farmacia']['Nueva']->addChild('Farmacia', array('route' => 'farmacia_list', 'routeParameters' => array('char' => 'A')));
			$menu['farmacia']['Nueva']->addChild('Categoria', array('route' => 'categoria_list', 'routeParameters' => array('char' => 'A')));
			$menu['farmacia']['Nueva']->addChild('Existencia', array('uri' => '#'));
			$menu['farmacia']['Nueva']['Existencia']->addChild('Listar/Nueva', array('route' => 'imv_search'));
			$menu['farmacia']['Nueva']['Existencia']->addChild('Imprimir', array('route' => 'imv_searchimprimir'));
			$menu['farmacia']->addChild('Almacen', array('uri' => '#'));
			$menu['farmacia']['Almacen']->addChild('Consultar', array('route' => 'almacenimv_search'));
			$menu['farmacia']['Almacen']->addChild('Imprimir', array('route' => 'almacenimv_searcha'));
		
			$menu->addChild('Facturación', array('uri' => '#'));
			$menu['Facturación']->addChild('Facturar', array('uri' => '#'));
			$menu['Facturación']['Facturar']->addChild('Consulta', array('route' => 'facturacion_consulta_new'));
			$menu['Facturación']['Facturar']->addChild('Procedimiento', array('route' => 'facturacion_procedimiento_new'));
			$menu['Facturación']['Facturar']->addChild('Medicamento', array('route' => 'facturacion_insumo_new'));
			$menu['Facturación']['Facturar']->addChild('Reportes', array('route' => 'reporte_cargo_new'));

			$menu->addChild('Historia', array('uri' => '#'));
			$menu['Historia']->addChild('Diagnosticos', array('route' => 'cie_list'));
			$menu['Historia']->addChild('Examenes', array('route' => 'examen_list'));
			$menu['Historia']->addChild('Medicamentos', array('route' => 'medicamento_list'));
			$menu['Historia']->addChild('Busqueda', array('route' => 'paciente_filtro'));
	
			$menu->addChild('Usuarios', array('uri' => '#'));
			$menu['Usuarios']->addChild('Listar', array('route' => 'usuario_list'));
			$menu['Usuarios']->addChild('Crear', array('route' => 'fos_user_registration_register'));
		
			$menu->addChild($usuario->getUsername(), array('uri' => '#'));
			$menu[$usuario->getUsername()]->addChild('Cambiar contraseña', array('route' => 'fos_user_change_password'));
			$menu[$usuario->getUsername()]->addChild('Salir', array('route' => 'logout'));
		
		}elseif ($security->isGranted('ROLE_FACTURADOR')){
			
			$menu->addChild('Facturación', array('uri' => '#'));
			$menu['Facturación']->addChild('Facturar', array('uri' => '#'));
			$menu['Facturación']['Facturar']->addChild('Consulta', array('route' => 'facturacion_consulta_new'));
			$menu['Facturación']['Facturar']->addChild('Procedimiento', array('route' => 'facturacion_procedimiento_new'));
			$menu['Facturación']['Facturar']->addChild('Medicamento', array('route' => 'facturacion_insumo_new'));
			$menu['Facturación']['Facturar']->addChild('Reportes', array('route' => 'reporte_cargo_new'));
                        
                        
			
			$menu->addChild($usuario->getUsername(), array('uri' => '#'));
			$menu[$usuario->getUsername()]->addChild('Salir', array('route' => 'logout'));
			
                }elseif($security->isGranted('ROLE_ADMIN')){
                    
                    
                     $menu->addChild('farmacia', array('uri' => '#'));
			$menu['farmacia']->addChild('Nueva', array('uri' => '#'));
			$menu['farmacia']->addChild('Ingresos', array('route' => 'ingreso_list'));
			$menu['farmacia']->addChild('Movimientos', array('uri' => '#'));
			$menu['farmacia']->addChild('Pyp', array('route' => 'imvpyp_search'));
                        $menu['farmacia']['Movimientos']->addChild('Traslados', array('uri' => '#'));
			$menu['farmacia']['Movimientos']['Traslados']->addChild('Listar/Nuevo', array('route' => 'traslado_list', 'routeParameters' => array('char' => 'A')));
			$menu['farmacia']['Movimientos']['Traslados']->addChild('Imprimir', array('route' => 'traslado_searchprint'));
			$menu['farmacia']['Movimientos']->addChild('Devoluciones Proveedor', array('uri' => '#'));
			$menu['farmacia']['Movimientos']['Devoluciones Proveedor']->addChild('Listar/Nuevo', array('route' => 'devolucion_list', 'routeParameters' => array('char' => 'A')));
			$menu['farmacia']['Movimientos']['Devoluciones Proveedor']->addChild('Imprimir', array('route' => 'devolucion_searchprint'));
			$menu['farmacia']['Nueva']->addChild('Farmacia', array('route' => 'farmacia_list', 'routeParameters' => array('char' => 'A')));
			$menu['farmacia']['Nueva']->addChild('Categoria', array('route' => 'categoria_list', 'routeParameters' => array('char' => 'A')));
			$menu['farmacia']['Nueva']->addChild('Existencia', array('uri' => '#'));
			$menu['farmacia']['Nueva']['Existencia']->addChild('Listar/Nueva', array('route' => 'imv_search'));
			$menu['farmacia']['Nueva']['Existencia']->addChild('Imprimir', array('route' => 'imv_searchimprimir'));
			$menu['farmacia']->addChild('Almacen', array('uri' => '#'));
			$menu['farmacia']['Almacen']->addChild('Consultar', array('route' => 'almacenimv_search'));
			$menu['farmacia']['Almacen']->addChild('Imprimir', array('route' => 'almacenimv_searcha'));
			
                      $menu->addChild('Facturación', array('uri' => '#'));
			$menu['Facturación']->addChild('Facturar', array('uri' => '#'));
			$menu['Facturación']['Facturar']->addChild('Consulta', array('route' => 'facturacion_consulta_new'));
			$menu['Facturación']['Facturar']->addChild('Procedimiento', array('route' => 'facturacion_procedimiento_new'));
			$menu['Facturación']['Facturar']->addChild('Medicamento', array('route' => 'facturacion_insumo_new'));
			$menu['Facturación']['Facturar']->addChild('Reportes', array('route' => 'reporte_cargo_new'));
                        
			$menu->addChild('Usuarios', array('uri' => '#'));
			$menu['Usuarios']->addChild('Listar', array('route' => 'usuario_list'));
			$menu['Usuarios']->addChild('Crear', array('route' => 'fos_user_registration_register'));
		
			$menu->addChild($usuario->getUsername(), array('uri' => '#'));
			$menu[$usuario->getUsername()]->addChild('Salir', array('route' => 'logout'));
                    
                    
                    
                    
                    
                    
                }
               
	
		return $menu;
	}
        
                
}