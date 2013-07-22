<?php

namespace knx\ParametrizarBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
	public function superAdminMenu(FactoryInterface $factory, array $options)
	{
		$security = $this->container->get('security.context');
		
		$menu = $factory->createItem('root');
		$menu->setChildrenAttributes(array('id' => 'menu'));
		
		$menu->addChild('Parametrizar', array('uri' => '#'));	
			$menu['Parametrizar']->addChild('Empresa', array('route' => 'empresa_list'));
			$menu['Parametrizar']->addChild('Servicio', array('route' => 'servicio_list'));
			$menu['Parametrizar']->addChild('Almacen', array('route' => 'almacen_list'));
			$menu['Parametrizar']->addChild('Cliente', array('route' => 'cliente_list'));
			$menu['Parametrizar']->addChild('Cargo', array('route' => 'cargo_list'));
			$menu['Parametrizar']->addChild('Proveedor', array('route' => 'proveedor_list'));
			$menu['Parametrizar']->addChild('Categoría pyp', array('route' => 'pyp_list'));
			
		$menu->addChild('Farmacia', array('uri' => '#'));
			$menu['Farmacia']->addChild('Nueva', array('route' => 'empresa_list'));
			$menu['Farmacia']->addChild('Ingresos', array('route' => 'ingreso_list'));
			$menu['Farmacia']->addChild('Movimientos', array('route' => 'cargo_list'));
			$menu['Farmacia']['Movimientos']->addChild('Traspasos', array('route' => 'traslado_list', 'routeParameters' => array('char' => 'A')));
			$menu['Farmacia']['Movimientos']->addChild('Devoluciones', array('route' => 'categoria_list', 'routeParameters' => array('char' => 'A')));
			$menu['Farmacia']['Nueva']->addChild('Farmacia', array('route' => 'farmacia_list', 'routeParameters' => array('char' => 'A')));
			$menu['Farmacia']['Nueva']->addChild('Categoria', array('route' => 'categoria_list', 'routeParameters' => array('char' => 'A')));
			$menu['Farmacia']['Nueva']->addChild('IMV', array('route' => 'imv_list', 'routeParameters' => array('char' => 'A')));
				
		$menu->addChild('Facturación', array('uri' => '#'));
			$menu['Facturación']->addChild('Facturar', array('uri' => '#'));
				$menu['Facturación']['Facturar']->addChild('Consulta', array('route' => 'facturacion_actividad_new'));
				$menu['Facturación']['Facturar']->addChild('Procedimiento', array('route' => 'facturacion_actividad_new'));
				$menu['Facturación']['Facturar']->addChild('Medicamento', array('route' => 'facturacion_actividad_new'));
			
		$menu->addChild('Historia', array('uri' => '#'));
		
		$menu->addChild('Usuarios', array('uri' => '#'));
			$menu['Usuarios']->addChild('Listar', array('route' => 'usuario_list'));
			$menu['Usuarios']->addChild('Crear', array('route' => 'fos_user_registration_register'));
		
		$usuario = $security->getToken()->getUser();
		
		$menu->addChild($usuario->getUsername(), array('uri' => '#'));
		$menu[$usuario->getUsername()]->addChild('Perfil', array('route' => 'fos_user_profile_show'));
		$menu[$usuario->getUsername()]->addChild('Salir', array('route' => 'logout'));

		return $menu;
	}
}