<?php
/**
 * Created by PhpStorm.
 * User: Orlando
 * Date: 17/08/2016
 * Time: 4:53
 */
?>
<aside class="sidebar fixed" ng-controller="MenuController">
    <div class="brand-logo">
        <img src="<?php echo base_url($frameworks_dir . '/frontend/images/logo.png'); ?>">
    </div>

    <ul ng-cloak>
        <a menu-link href="#/" icon="md md-home">Inicio</a>
        <li menu-toggle path="/ui-elements" name="INVENTARIO" icon="md md-assignment"
            ng-if="permisos.listar_productos">
            <a menu-link href="#/producto" ng-if="permisos.listar_productos" >Productos</a>
        </li>
        <li menu-toggle path="/ui-elements" name="Seguridad" icon="md md-security" ng-if="permisos.listar_users || permisos.listar_groups">
            <a menu-link href="#/usuario" ng-if="permisos.listar_users">Usuarios</a>
            <a menu-link href="#/rol" ng-if="permisos.listar_groups">Roles</a>
<!--            <a menu-link href="#/permiso">Permisos por Roles</a>-->
        </li>
        <li menu-toggle path="/ui-elements" name="Reportes" icon="fa fa-file-text">
            <a menu-link href="#/reportes/show/inventario" >Inventario</a>

        </li>



    </ul>
</aside>
