<?php

namespace knx\FarmaciaBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
	public function farmaciaMenu(FactoryInterface $factory, array $options)
	{
		
		$menu = $factory->createItem('root');
		$menu->setChildrenAttributes(array('id' => 'menu'));

		$menu->addChild('farmacia', array('uri' => '#'));	
			$menu['farmacia']->addChild('Nueva', array('uri' => '#'));
			$menu['farmacia']->addChild('Ingresos', array('route' => 'ingreso_list'));
			$menu['farmacia']->addChild('Movimientos', array('uri' => '#'));
			$menu['farmacia']->addChild('Pyp', array('route' => 'imvpyp_search'));
				$menu['farmacia']['Movimientos']->addChild('Traslados', array('route' => 'traslado_list', 'routeParameters' => array('char' => 'A')));
				$menu['farmacia']['Movimientos']->addChild('Devoluciones Proveedor', array('route' => 'devolucion_list', 'routeParameters' => array('char' => 'A')));
				$menu['farmacia']['Nueva']->addChild('Farmacia', array('route' => 'farmacia_list', 'routeParameters' => array('char' => 'A')));
				$menu['farmacia']['Nueva']->addChild('Categoria', array('route' => 'categoria_list', 'routeParameters' => array('char' => 'A')));
				$menu['farmacia']['Nueva']->addChild('Stock', array('route' => 'imv_search'));
			//$menu['Parametrizar']->addChild('Usuarios', array('route' => 'usuario_list'));
				
		
		
		//$actualUser = $securityContext->getToken()->getUser();
		
		$menu->addChild('Hernando', array('uri' => '#'));
		//$menu[$actualUser->getUsername()]->addChild('Perfil', array('route' => 'usuario_show', 'routeParameters' => array('id' => $actualUser->getId())));
		//$menu[$actualUser->getUsername()]->addChild('Salir', array('route' => 'logout'));

		return $menu;
	}
}