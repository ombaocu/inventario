<?php
/**
 * Created by PhpStorm.
 * User: Orlando
 * Date: 17/08/2016
 * Time: 5:15
 */
?>
<div style="z-index: 4000 !important;" class="dropdown-menu dropdown-menu-right theme-picker mat-grow-top-right">
    <div class="container-fluid m-v-15" ng-click="$event.stopPropagation()" style="z-index: 4000;">
        <div class="pull-right m-r-10">
            <button type="button" class="close" ng-click="$hide()">&times;</button>
        </div>
        <h3 class="no-margin p-t-5"><i class="md md-person"></i> {{currentUser.username}}</h3>
        <hr />
        <h5>Cambiar clave</h5>
        <div class="bs-component" ng-if="error">
            <div class="alert alert-dismissible alert-danger">
                {{error}}
            </div>
        </div>
        <div class="bs-component" ng-if="message">
            <div class="alert alert-dismissible alert-success">
                {{message}}
            </div>
        </div>
        <form style="z-index: 4000;">
            <div class="row m-t-20">
                <div class="col-md-12">
                    <div>
                        <div class="form-group">
                            <input type="password" class="form-control" ng-model="OldPassword" placeholder="Clave actual" />
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" ng-model="Password" placeholder="Nueva clave" />
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" ng-model="RPassword" placeholder="Confirmar clave" />
                        </div>
                    </div>
                    <br />
                    <div class="pull-right">
                        <button type="button" class="btn btn-primary" ng-click="ChangePassword(OldPassword, Password, RPassword)">Cambiar</button>
                    </div>
                </div>
            </div>
            <hr />
            <div class="pull-left m-r-10">
<!--                <a href="--><?php //echo site_url('auth/logout'); ?><!--">-->
                    <button type="button" class="btn btn-danger" ng-click="logout()">Cerrar sesión</button>
<!--                </a>-->
            </div>
        </form>
    </div>
</div>
