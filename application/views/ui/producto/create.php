<?php
/**
 * Created by PhpStorm.
 * User: Orlando
 * Date: 11/09/2016
 * Time: 7:24
 */
?>
<div class="aside bs-docs-aside" style="margin-bottom: 0px !important; margin-top: 0px !important; margin-left: 0px !important; z-index: 99999 !important;" tabindex="-1" role="dialog">
    <div class="close">
        <div class="btn btn-round btn-info" ng-click="$hide()"><i class="md md-close"></i></div>
    </div>

    <div class="aside-dialog">
        <div class="aside-body bs-sidebar" style="overflow: auto;">

            <form class="form-floating" novalidate="novalidate" ng-submit="saveItem(item)">
                <fieldset>
                    <legend><span ng-bind-html="item.icon"></span> {{cmd}} {{settings.singular}}</legend>

                    <div class="form-group filled">
                        <label class="control-label">Foto</label>
                        <div class="form-group">
                            <br>
                            <ul style="clear:both" ng-show="item.file.length > 0" class="response list-unstyled">
                                <li class="sel-file" ng-repeat="f in item.file">
                                    <img ng-src="{{f.dataUrl}}" style="width: 100%; height: 100%;">
                                </li>
                            </ul>
                            <img  ng-src="{{item.imagen}}" style="width: 100%; height: 100%;" ng-show="!item.file">

                            <div class="btn btn-info" ng-multiple="false" ng-if="item.editing" required ngf-select ng-model="item.file">Seleccionar</div>
                            <span class="help-block" style="color: red !important;" ng-if="formErrors.picture">{{formErrors.picture}}</span>
                        </div>

                    </div>

                    <div class="form-group {{filledClass}}">
                        <label class="control-label"><span style="color: red;">*</span> Nombre</label>
                        <input type="text" class="form-control" ng-model="item.nombre" required ng-disabled="!item.editing">
                        <span class="help-block" style="color: red !important;" ng-if="formErrors.nombre">{{formErrors.nombre}}</span>
                    </div>

                    <div class="form-group {{filledClass}}">
                        <label class="control-label"><span style="color: red;">*</span> Código interno</label>
                        <input type="text" class="form-control" ng-model="item.codInt" required ng-disabled="!item.editing">
                        <span class="help-block" style="color: red !important;" ng-if="formErrors.codInt">{{formErrors.codInt}}</span>
                    </div>

                    <div class="form-group {{filledClass}}">
                        <label class="control-label"><span style="color: red;">*</span> Código Externo</label>
                        <input type="text" class="form-control" ng-model="item.codExt" required ng-disabled="!item.editing">
                        <span class="help-block" style="color: red !important;" ng-if="formErrors.codExt">{{formErrors.codExt}}</span>
                    </div>

                    <div class="form-group {{filledClass}}">
                        <label class="control-label"><span style="color: red;">*</span> Cantidad en almacen</label>
                        <input type="text" class="form-control" ng-model="item.cantidad" required ng-disabled="!item.editing">
                        <span class="help-block" style="color: red !important;" ng-if="formErrors.cantidad">{{formErrors.cantidad}}</span>
                    </div>

                    <!--------- clasificador ---------->
                    <div class="form-group {{filledClass}}" ng-if="settings.cmd != 'New'">
                        <label class="control-label"><span style="color: red;">*</span> Clasificador</label>
                        <select class="form-control" ng-model="item.clasificador" required ng-disabled="!item.editing">
                            <option></option>
                            <option ng-if="item.clasificador.id == m.id" selected ng-repeat="m in clasificadores" value="{{m}}">{{m.nombre}}</option>
                            <option ng-if="item.clasificador != m" ng-repeat="m in clasificador" value="{{m.id}}">{{m.nombre}}</option>
                        </select>
                        <span class="help-block" style="color: red !important;" ng-if="formErrors.clasificador">{{formErrors.clasificador}}</span>
                    </div>

                    <div class="form-group {{filledClass}}" ng-if="settings.cmd == 'New'">
                        <label class="control-label"><span style="color: red;">*</span> Clasificador</label>
                        <select class="form-control" ng-model="item.clasificador" required ng-disabled="!item.editing">
                            <option></option>
                            <option ng-repeat="m in clasificadores" value="{{m.id}}">{{m.nombre}}</option>
                        </select>
                        <span class="help-block" style="color: red !important;" ng-if="formErrors.clasificador">{{formErrors.clasificador}}</span>
                    </div>
                    <!--------- end clasificador ---------->

                    <!--------- marca ---------->
                    <div class="form-group {{filledClass}}" ng-if="settings.cmd != 'New'">
                        <label class="control-label"><span style="color: red;">*</span> Marca</label>
                        <select class="form-control" ng-model="item.marca" required ng-disabled="!item.editing">
                            <option></option>
                            <option ng-if="item.marca.id == m.id" selected ng-repeat="m in marcas" value="{{m}}">{{m.nombre}}</option>
                            <option ng-if="item.marca != m" ng-repeat="m in marcas" value="{{m.id}}">{{m.nombre}}</option>
                        </select>
                        <span class="help-block" style="color: red !important;" ng-if="formErrors.marca">{{formErrors.marca}}</span>
                    </div>

                    <div class="form-group {{filledClass}}" ng-if="settings.cmd == 'New'">
                        <label class="control-label"><span style="color: red;">*</span> Marca</label>
                        <select class="form-control" ng-model="item.marca" required ng-disabled="!item.editing">
                            <option></option>
                            <option ng-repeat="m in marcas" value="{{m.id}}">{{m.nombre}}</option>
                        </select>
                        <span class="help-block" style="color: red !important;" ng-if="formErrors.marca">{{formErrors.marca}}</span>
                    </div>
                    <!--------- end marca ---------->

                    <!--------- modelo ---------->
                    <div class="form-group {{filledClass}}" ng-if="item.marca && settings.cmd != 'New'">
                        <label class="control-label"><span style="color: red;">*</span> Modelo</label>
                        <select class="form-control" ng-model="item.modelo" required ng-disabled="!item.editing">
                            <option></option>
                            <option ng-if="m.marca.id==item.marca.id && item.modelo.id == m.id" selected ng-repeat="m in modelos" value="{{m.id}}">{{m.nombre}}</option>
                            <option ng-if="m.marca.id==item.marca" ng-repeat="m in modelos" value="{{m.id}}">{{m.nombre}}</option>
                        </select>
                        <span class="help-block" style="color: red !important;" ng-if="formErrors.modelo">{{formErrors.modelo}}</span>
                    </div>

                    <div class="form-group {{filledClass}}" ng-if="item.marca && settings.cmd == 'New'">
                        <label class="control-label"><span style="color: red;">*</span> Modelo</label>
                        <select class="form-control" ng-model="item.modelo" required ng-disabled="!item.editing">
                            <option></option>
                            <option ng-if="m.marca.id==item.marca" ng-repeat="m in modelos" value="{{m.id}}">{{m.nombre}}</option>
                        </select>
                        <span class="help-block" style="color: red !important;" ng-if="formErrors.modelo">{{formErrors.modelo}}</span>
                    </div>
                    <!--------- end modelo ---------->
                    <div class="form-group {{filledClass}}">
                        <label class="control-label"><span style="color: red;">*</span> Ubicación</label>
                        <input type="text" class="form-control" ng-model="item.ubicacion" ng-disabled="!item.editing">
                        <span class="help-block" style="color: red !important;" ng-if="formErrors.ubicacion">{{formErrors.ubicacion}}</span>
                    </div>

                    <div class="form-group {{filledClass}}">
                        <label class="control-label"><span style="color: red;">*</span> Númerp de serie</label>
                        <input type="text" class="form-control" ng-model="item.serie" ng-disabled="!item.editing">
                        <span class="help-block" style="color: red !important;" ng-if="formErrors.serie">{{formErrors.serie}}</span>
                    </div>

                    <div class="form-group {{filledClass}}">
                        <label class="control-label"><span style="color: red;">*</span> Largo</label>
                        <input type="text" class="form-control" ng-model="item.largo" ng-disabled="!item.editing">
                        <span class="help-block" style="color: red !important;" ng-if="formErrors.largo">{{formErrors.largo}}</span>
                    </div>

                    <div class="form-group {{filledClass}}">
                        <label class="control-label"><span style="color: red;">*</span> Ancho</label>
                        <input type="text" class="form-control" ng-model="item.ancho" ng-disabled="!item.editing">
                        <span class="help-block" style="color: red !important;" ng-if="formErrors.ancho">{{formErrors.ancho}}</span>
                    </div>

                    <div class="form-group {{filledClass}}">
                        <label class="control-label"><span style="color: red;">*</span> Alto</label>
                        <input type="text" class="form-control" ng-model="item.alto" ng-disabled="!item.editing">
                        <span class="help-block" style="color: red !important;" ng-if="formErrors.alto">{{formErrors.alto}}</span>
                    </div>

                    <div class="form-group {{filledClass}}">
                        <label class="control-label"><span style="color: red;">*</span> Notas</label>
                        <input type="text" class="form-control" ng-model="item.descripcion" ng-disabled="!item.editing">
                        <span class="help-block" style="color: red !important;" ng-if="formErrors.descripcion">{{formErrors.descripcion}}</span>
                    </div>

                    <div class="form-group {{filledClass}}">
                        <label class="control-label"><span style="color: red;">*</span> Peso</label>
                        <input type="text" class="form-control" ng-model="item.peso" ng-disabled="!item.editing">
                        <span class="help-block" style="color: red !important;" ng-if="formErrors.peso">{{formErrors.peso}}</span>
                    </div>



                </fieldset>

                <div class="form-group" style="float: right;">
                    <button type="submit" class="btn btn-lg btn-primary" ng-hide="!item.editing">Guardar</button>
                </div>
            </form>

        </div>
    </div>
</div>
