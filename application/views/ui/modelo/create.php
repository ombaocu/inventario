<?php
/**
 * Created by PhpStorm.
 * User: Orlando
 * Date: 20/08/2016
 * Time: 17:56
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
                        <input type="text" class="form-control" ng-model="item.nombre" required ng-disabled="!item.editing" ng-pattern="/^[a-zA-ZñÑáéíóúÁÉÍÓÚüÜ 0-9-'\s]*$/">
                        <span class="help-block" style="color: red !important;" ng-if="formErrors.nombre">{{formErrors.nombre}}</span>
                    </div>



                    <div class="form-group filled">
                        <label class="control-label"><span style="color: red;">*</span> Marcas</label>
                        <ui-select ng-model="item.marca" theme="select2" title="Seleccione un Clasificador" ng-disabled="!item.editing" search-enabled="false" >
                            <ui-select-match>{{$select.selected.nombre}}</ui-select-match>
                            <ui-select-choices repeat="item in marcasSelected">
                                <div ng-bind-html="item.nombre"></div>
                            </ui-select-choices>
                        </ui-select>
                        <span class="help-block" style="color: red !important;" ng-if="formErrors.status">{{formErrors.status}}</span>
                    </div>
<!--                    -->
<!--                    <div class="form-group {{filledClass}}" ng-if="item.clasificador && settings.cmd == 'New'"">-->
<!--                        <label class="control-label"><span style="color: red;">*</span> Marca</label>-->
<!--                        <select class="form-control" ng-model="item.marca" required>-->
<!--                            <option></option>-->
<!--                            <option ng-repeat="m in marcasSelected" value="{{m.id}}">{{m.nombre}}</option>-->
<!--                        </select>-->
<!--                        <span class="help-block" style="color: red !important;" ng-if="formErrors.marca">{{formErrors.marca}}</span>-->
<!--                    </div>-->
<!---->
<!--                    <div class="form-group {{filledClass}}" ng-if="item.clasificador && settings.cmd != 'New'">-->
<!--                        <label class="control-label"><span style="color: red;">*</span> Marca - {{ item.marca }}</label>-->
<!--                        <select class="form-control" ng-model="item.marca" required>-->
<!--                            <option></option>-->
<!--                            <option  ng-if="m.marca.id == item.marca" selected ng-repeat="m in marcasSelected" value="{{m.id}}">{{m.nombre}}</option>-->
<!--                            <option  ng-if="m.marca.id != item.marca" ng-repeat="m in marcasSelected" value="{{m.id}}">{{m.nombre}}</option>-->
<!--                        </select>-->
<!--                        <span class="help-block" style="color: red !important;" ng-if="formErrors.marca">{{formErrors.marca}}</span>-->
<!--                    </div>-->

                </fieldset>

                <div class="form-group" style="float: right;">
                    <button type="submit" class="btn btn-lg btn-primary" ng-hide="!item.editing">GUARDAR</button>
                </div>
            </form>

        </div>
    </div>
</div>
