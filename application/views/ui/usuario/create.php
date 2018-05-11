<?php
/**
 * Created by PhpStorm.
 * User: Orlando
 * Date: 11/09/2016
 * Time: 7:10
 */
?>
<div class="aside bs-docs-aside" style="margin-bottom: 0px !important; margin-top: 0px !important; min-width: 400px; margin-left: 0px !important; z-index: 99999 !important;" tabindex="-1" role="dialog">
    <div class="close">
        <div class="btn btn-round btn-info" ng-click="$hide()"><i class="md md-close"></i></div>
    </div>

    <div class="aside-dialog">
        <div class="aside-body bs-sidebar" style="overflow: auto;">

            <form class="form-floating" novalidate="novalidate" ng-submit="saveItem(item)">
                <fieldset>
                    <legend><span ng-bind-html="item.icon"></span> {{cmd}} {{settings.singular}}</legend>

                    <div class="form-group {{filledClass}}">
                        <label class="control-label"><span style="color: red;">*</span> Usuario</label>
                        <input type="text" class="form-control" ng-model="item.username" required ng-disabled="settings.cmd == 'Edit'" ng-pattern="/^[a-zA-Z\s]*$/">
                        <span class="help-block" style="color: red !important;" ng-if="formErrors.username">{{formErrors.username}}</span>
                    </div>

                    <div class="form-group {{filledClass}}" >
                        <label class="control-label"><span style="color: red;">*</span> Correo</label>
                        <input type="email" class="form-control" ng-model="item.email" required ng-disabled="settings.cmd == 'Edit'">
                        <span class="help-block" style="color: red !important;" ng-if="formErrors.email">{{formErrors.email}}</span>
                    </div>

                    <div class="form-group {{filledClass}}">
                        <label class="control-label"><span style="color: red;">*</span> Nombre</label>
                        <input type="text" class="form-control" ng-model="item.first_name" required ng-disabled="!item.editing" ng-pattern="/^[a-zA-ZñÑáéíóúÁÉÍÓÚüÜ '\s]*$/">
                        <span class="help-block" style="color: red !important;" ng-if="formErrors.first_name">{{formErrors.first_name}}</span>
                    </div>

                    <div class="form-group {{filledClass}}">
                        <label class="control-label"><span style="color: red;">*</span> Apellidos</label>
                        <input type="text" class="form-control" ng-model="item.last_name" required ng-disabled="!item.editing" ng-pattern="/^[a-zA-ZñÑáéíóúÁÉÍÓÚüÜ '\s]*$/">
                        <span class="help-block" style="color: red !important;" ng-if="formErrors.last_name">{{formErrors.last_name}}</span>
                    </div>

                    <div class="form-group filled">
                        <label class="control-label"><span style="color: red;">*</span> Dependencia</label>
                        <ui-select ng-model="item.dependencia" theme="select2" title="seleccione una dependencia" ng-disabled="!item.editing" search-enabled="false">
                            <ui-select-match>{{$select.selected.nombre}}</ui-select-match>
                            <ui-select-choices repeat="c in dependencias">
                                <div ng-bind-html="c.nombre"></div>
                            </ui-select-choices>
                        </ui-select>
                        <span class="help-block" style="color: red !important;" ng-if="formErrors.dependecia">{{formErrors.dependecia}}</span>
                    </div>

                    <div class="form-group {{filledClass}}">
                        <label class="control-label"><span style="color: red;">*</span> Empresa</label>
                        <input type="text" class="form-control" ng-model="item.company" required ng-disabled="!item.editing">
                        <span class="help-block" style="color: red !important;" ng-if="formErrors.company">{{formErrors.company}}</span>
                    </div>

                    <div class="form-group {{filledClass}}">
                        <label class="control-label"><span style="color: red;">*</span> Telefono</label>
                        <input type="text" class="form-control" ng-model="item.phone" required ng-disabled="!item.editing">
                        <span class="help-block" style="color: red !important;" ng-if="formErrors.phone">{{formErrors.phone}}</span>
                    </div>

                    <div class="form-group {{filledClass}}" ng-if="settings.cmd == 'New'">
                        <label class="control-label"><span style="color: red;">*</span> Clave</label>
                        <input type="password" class="form-control" ng-model="item.password" required ng-disabled="!item.editing">
                        <span class="help-block" style="color: red !important;" ng-if="formErrors.password">{{formErrors.password}}</span>
                    </div>

                    <div class="form-group {{filledClass}}" ng-if="settings.cmd == 'New'">
                        <label class="control-label"><span style="color: red;">*</span> Confirma Clave</label>
                        <input type="password" class="form-control" ng-model="item.cpassword" required ng-change="checkPass(item)">
                        <span class="help-block" style="color: red !important;" ng-if="formErrors.cpassword">{{formErrors.cpassword}}</span>
                    </div>
                    <div class="progress" style="margin:0">
                        <div class="pwstrength_viewport_progress"></div>
                    </div>

                    <div class="form-group filled">
                        <label class="control-label">Roles</label>
                        <ui-select multiple ng-model="item.roles" theme="select2" ng-disabled="disabled" close-on-select="false" title="Seleccione los grupos">
                            <ui-select-match placeholder="">{{$item.name}}</ui-select-match>
                            <ui-select-choices repeat="item in roles | filter:$select.search">
                                <div ng-bind-html="item.name | highlight: $select.search"></div>
                                <small ng-bind-html="item.descripcion | highlight: $select.search"></small>

                            </ui-select-choices>
                        </ui-select>
                    </div>

                </fieldset>

                <div class="form-group" style="float: right;">
                    <button type="submit" class="btn btn-lg btn-primary" ng-hide="!item.editing">Guardar</button>
                </div>
            </form>

        </div>
    </div>
</div>
