<?php
/**
 * Created by PhpStorm.
 * User: Orlando
 * Date: 17/08/2016
 * Time: 4:53
 */
?>
<nav class="navbar navbar-default navbar-fixed-top" ng-class="{scroll: (scroll>10)}">
    <div class="container-fluid">
        <div class="navbar-header pull-left">
            <button type="button" class="navbar-toggle pull-left m-15" data-activates=".sidebar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <ul class="breadcrumb">
                <li><a href="#/">INVENTARIO</a></li>
                <li ng-bind="pageTitle" class="active">Loading..</li>
            </ul>
        </div>
        <ul class="nav navbar-nav navbar-right navbar-right-no-collapse">
            <li class="dropdown pull-right">
                <button class="dropdown-toggle pointer btn btn-round-sm btn-link withoutripple" bs-dropdown
                        data-template="home/dropdow" data-animation="mat-grow-top-right">
                    <i class="md md-person f20"></i>
                </button>
            </li>
        </ul>
    </div>
</nav>
