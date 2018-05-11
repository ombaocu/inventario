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
                        <label class="control-label"><span style="color: red;">*</span> Nombre</label>
                        <input type="text" class="form-control" ng-model="item.name" required ng-disabled="!item.editing" ng-pattern="/^[a-zA-ZñÑáéíóúÁÉÍÓÚüÜ '\s]*$/">
                        <span class="help-block" style="color: red !important;" ng-if="formErrors.name">{{formErrors.name}}</span>
                    </div>

                    <div class="form-group {{filledClass}}">
                        <label class="control-label"><span style="color: red;">*</span> Descripción</label>
                        <input type="text" class="form-control" ng-model="item.description" required ng-disabled="!item.editing" ng-pattern="/^[a-zA-ZñÑáéíóúÁÉÍÓÚüÜ '\s]*$/">
                        <span class="help-block" style="color: red !important;" ng-if="formErrors.description">{{formErrors.description}}</span>
                    </div>

                    <div class="form-group {{filledClass}}">
                        <label class="control-label"><span style="color: red;">*</span> Color</label>
                        <input type="text" class="form-control" ng-model="item.bgcolor" required ng-disabled="!item.editing">
                        <span class="help-block" style="color: red !important;" ng-if="formErrors.bgcolor">{{formErrors.bgcolor}}</span>
                    </div>

                </fieldset>

                <div class="form-group" style="float: right;">
                    <button type="submit" class="btn btn-lg btn-primary" ng-hide="!item.editing">Guardar</button>
                </div>
            </form>

        </div>
    </div>
</div>
