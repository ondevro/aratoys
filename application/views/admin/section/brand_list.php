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
                        <div class="col-md-8">
                            <div class="box">
                                <div class="box-body">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th class="col-sm-offset-1 col-sm-3">Name</th>
                                                <th class="col-sm-8">Description</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
<?php foreach ($brands as $brand):?>
                                            <tr>
                                                <td>
                                                    <?php echo htmlspecialchars($brand['name'], ENT_QUOTES, 'UTF-8'); ?><strong class="badge bg-light-blue" style="margin-left:10px"><?php echo $brand['count_brand']; ?></strong>
                                                    </td>
                                                <td>
                                                    <?php if($brand['description']) { echo htmlspecialchars(substr($brand['description'], 0, 100) . '...', ENT_QUOTES, 'UTF-8'); } ?>
                                                </td>
                                                <td>
                                                    <span data-placement="top" data-toggle="tooltip" title="<?php echo lang('actions_edit'); ?>"><a href="<?php echo base_url() . 'admin/section/edit_brand/' . $brand['id']; ?>"><b class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-edit"></span></b></a></span>
                                                    <span data-placement="top" data-toggle="tooltip" title="<?php echo lang('actions_delete'); ?>"><a href="<?php echo base_url() . 'admin/section/delete_brand/' . $brand['id']; ?>"><b class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span></b></a></span>
                                                </td>
                                            </tr>
<?php endforeach;?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="box">
                                <div class="box-body">
                                    <?php

                                    if(isset($brand_status['added'])) {
                                        echo '<div class="alert alert-success"><i class="icon fa fa-check"></i>' . $brand_status['added'] . '</div>';
                                    }

                                    if(isset($brand_status['updated'])) {
                                        echo '<div class="alert alert-info">' . $brand_status['updated'] . '</div>';
                                    }

                                    echo validation_errors('<div class="alert alert-warning"><i class="icon fa fa-warning"></i>', '</div>');

                                    echo form_open(current_url(), array('class' => 'form-horizontal', 'id' => 'form-new_product'));
                                    ?>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <?php echo form_input($name);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <?php echo form_textarea($description);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <div class="btn-group">
                                                    <?php echo form_button(array('type' => 'submit', 'class' => 'btn btn-primary btn-flat', 'content' => lang('actions_submit'))); ?>
                                                    <?php echo form_button(array('type' => 'reset', 'class' => 'btn btn-warning btn-flat', 'content' => lang('actions_reset'))); ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php echo form_close();?>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
