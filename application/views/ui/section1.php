<?php
/**
 * Created by PhpStorm.
 * User: Orlando
 * Date: 03/08/2016
 * Time: 16:48
 */


?>

<section id="content-4col-full" class="types-block no-sep" ng-show="toponebycat">
    <div class="container-fluid">
        <div class="row">

            <div ng-repeat="item in toponebycat" class="col-md-3 cover-bg" style="background-image: url(<?php echo base_url($frameworks_dir . '/frontend/images/gym-1.jpg'); ?>);">
                <div class="content">
                    <h3><b>{{item.title }}</b></h3>
                    <p class="desc-text" ng-bind-html="item.plot | limitTo: 150"></p>
                </div>
            </div>

        </div>
    </div>
</section>


