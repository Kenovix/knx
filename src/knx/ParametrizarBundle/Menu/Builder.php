<?php

namespace knx\ParametrizarBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
	public function superAdminMenu(FactoryInterface $factory, array $options)
	{
		$menu = $factory->createItem('root');
		$menu->setChildrenAttributes(array('id' => 'menu'));

		$menu->addChild('Parametrizar', array('uri' => '#'));	
			$menu['Parametrizar']->addChild('Empresa', array('route' => 'empresa_list'));
			$menu['Parametrizar']->addChild('Almacen', array('route' => 'almacen_list'));
			$menu['Parametrizar']->addChild('Cliente', array('route' => 'cliente_list'));
			$menu['Parametrizar']->addChild('Cargo', array('route' => 'cargo_list'));
			$menu['Parametrizar']->addChild('Proveedor', array('route' => 'proveedor_list'));
			$menu['Parametrizar']->addChild('Paciente', array('uri' => '#'));
				$menu['Parametrizar']['Paciente']->addChild('Consultar', array('route' => 'paciente_list', 'routeParameters' => array('char' => 'A')));
				$menu['Parametrizar']['Paciente']->addChild('Listar', array('route' => 'paciente_list', 'routeParameters' => array('char' => 'A')));
			//$menu['Parametrizar']->addChild('Usuarios', array('route' => 'usuario_list'));
				
		
		
		//$actualUser = $securityContext->getToken()->getUser();
		
		$menu->addChild('Hernando', array('uri' => '#'));
		//$menu[$actualUser->getUsername()]->addChild('Perfil', array('route' => 'usuario_show', 'routeParameters' => array('id' => $actualUser->getId())));
		//$menu[$actualUser->getUsername()]->addChild('Salir', array('route' => 'logout'));

		return $menu;
	}
}