<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

            <div class="content-wrapper">
                <section class="content-header">
                    <?php echo $pagetitle; ?>
                    <?php echo $breadcrumb; ?>
                </section>

                <section class="content">
                    <div class="row">
                        <div class="col-md-12">
                             <div class="box">

                                <div class="box-body">
                                   <?php echo $crud->output; ?>
                                </div>
                            </div>
                         </div>
                    </div>
                </section>
            </div>
