<?php

namespace knx\HistoriaBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
	public function HistoriaMenu(FactoryInterface $factory, array $options)
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
		

		$menu->addChild('Historia', array('uri' => '#'));	
			$menu['Historia']->addChild('Diagnosticos', array('route' => 'cie_list'));
			$menu['Historia']->addChild('Examenes', array('route' => 'examen_list'));
			$menu['Historia']->addChild('Medicamentos', array('route' => 'medicamento_list'));
			$menu['Historia']->addChild('Urgencias', array('route' => 'historia_urgenciaList'));
			$menu['Historia']->addChild('Busqueda', array('route' => 'historia_search'));						

		return $menu;
	}
}