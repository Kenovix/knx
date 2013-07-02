<?php

namespace knx\FarmaciaBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
	public function farmaciaMenu(FactoryInterface $factory, array $options)
	{
		$security =  $this->container->get('security.context');
		
		$usuario = $security->getToken()->getUser();
		
		$menu = $factory->createItem('root');
		$menu->setChildrenAttributes(array('id' => 'menu'));
		
		if($security->isGranted('ROLE_ADMIN')){
			$menu->addChild('Parametrizar', array('uri' => '#'));
				$menu['Parametrizar']->addChild('Empresa', array('route' => 'empresa_list'));
				$menu['Parametrizar']->addChild('Servicio', array('route' => 'servicio_list'));
				$menu['Parametrizar']->addChild('Almacen', array('route' => 'almacen_list'));
				$menu['Parametrizar']->addChild('Cliente', array('route' => 'cliente_list'));
				$menu['Parametrizar']->addChild('Cargo', array('route' => 'cargo_list'));
				$menu['Parametrizar']->addChild('Proveedor', array('route' => 'proveedor_list'));
		}

		$menu->addChild('farmacia', array('uri' => '#'));	
			$menu['farmacia']->addChild('Nueva', array('route' => 'farmacia_list'));
			$menu['farmacia']->addChild('Ingresos', array('route' => 'ingreso_list'));
			$menu['farmacia']->addChild('Movimientos', array('route' => 'farmacia_list'));
			$menu['farmacia']->addChild('Pyp', array('route' => 'imvpyp_search'));
				$menu['farmacia']['Movimientos']->addChild('Traspasos', array('route' => 'traslado_list', 'routeParameters' => array('char' => 'A')));
				$menu['farmacia']['Movimientos']->addChild('Devoluciones Proveedor', array('route' => 'devolucion_list', 'routeParameters' => array('char' => 'A')));
				$menu['farmacia']['Nueva']->addChild('Farmacia', array('route' => 'farmacia_list', 'routeParameters' => array('char' => 'A')));
				$menu['farmacia']['Nueva']->addChild('Categoria', array('route' => 'categoria_list', 'routeParameters' => array('char' => 'A')));

				$menu['farmacia']['Nueva']->addChild('IMV', array('route' => 'imv_search'));
			//$menu['Parametrizar']->addChild('Usuarios', array('route' => 'usuario_list'));
			
		
		$menu->addChild('Hernando', array('uri' => '#'));
		//$menu[$actualUser->getUsername()]->addChild('Perfil', array('route' => 'usuario_show', 'routeParameters' => array('id' => $actualUser->getId())));
		//$menu[$actualUser->getUsername()]->addChild('Salir', array('route' => 'logout'));

		return $menu;
	}
}