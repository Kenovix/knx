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
		
		$securityContext = $this->container->get('security.context');

		$menu->addChild('Parametrizar', array('uri' => '#'));	
			$menu['Parametrizar']->addChild('Empresa', array('route' => 'empresa_list'));
			$menu['Parametrizar']->addChild('Servicio', array('route' => 'servicio_list'));
			$menu['Parametrizar']->addChild('Almacen', array('route' => 'almacen_list'));
			$menu['Parametrizar']->addChild('Cliente', array('route' => 'cliente_list'));
			$menu['Parametrizar']->addChild('Cargo', array('route' => 'cargo_list'));
			$menu['Parametrizar']->addChild('Proveedor', array('route' => 'proveedor_list'));
			$menu['Parametrizar']->addChild('Paciente', array('uri' => '#'));
				$menu['Parametrizar']['Paciente']->addChild('Consultar', array('route' => 'paciente_list', 'routeParameters' => array('char' => 'A')));
				$menu['Parametrizar']['Paciente']->addChild('Listar', array('route' => 'paciente_list', 'routeParameters' => array('char' => 'A')));
			
		$menu->addChild('farmacia', array('uri' => '#'));
			$menu['farmacia']->addChild('Nueva', array('route' => 'empresa_list'));
			$menu['farmacia']->addChild('Ingresos', array('route' => 'ingreso_list'));
			$menu['farmacia']->addChild('Movimientos', array('route' => 'cargo_list'));
			$menu['farmacia']['Movimientos']->addChild('Traspasos', array('route' => 'traslado_list', 'routeParameters' => array('char' => 'A')));
			$menu['farmacia']['Movimientos']->addChild('Devoluciones', array('route' => 'categoria_list', 'routeParameters' => array('char' => 'A')));
			$menu['farmacia']['Nueva']->addChild('Farmacia', array('route' => 'farmacia_list', 'routeParameters' => array('char' => 'A')));
			$menu['farmacia']['Nueva']->addChild('Categoria', array('route' => 'categoria_list', 'routeParameters' => array('char' => 'A')));
			$menu['farmacia']['Nueva']->addChild('IMV', array('route' => 'imv_list', 'routeParameters' => array('char' => 'A')));
				
		
		
		$usuario = $securityContext->getToken()->getUser();
		
		$menu->addChild($usuario->getUsername(), array('uri' => '#'));
		$menu[$usuario->getUsername()]->addChild('Perfil', array('uri' => '#'));
		$menu[$usuario->getUsername()]->addChild('Salir', array('route' => 'logout'));

		return $menu;
	}
}