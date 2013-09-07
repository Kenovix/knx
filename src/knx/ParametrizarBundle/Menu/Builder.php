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
			$menu['Parametrizar']->addChild('Centro de costo', array('route' => 'servicio_list'));
			$menu['Parametrizar']->addChild('Almacen', array('route' => 'almacen_list'));
			$menu['Parametrizar']->addChild('Cliente', array('route' => 'cliente_list'));
			$menu['Parametrizar']->addChild('Cargo', array('route' => 'cargo_list'));
			$menu['Parametrizar']->addChild('Proveedor', array('route' => 'proveedor_list'));
			$menu['Parametrizar']->addChild('Categoría pyp', array('route' => 'pyp_list'));

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
				$menu['Facturación']['Facturar']->addChild('Procedimiento', array('route' => 'facturacion_consulta_new'));
				$menu['Facturación']['Facturar']->addChild('Medicamento', array('route' => 'facturacion_consulta_new'));




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