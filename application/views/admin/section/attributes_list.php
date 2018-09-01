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
                                                <th class="col-md-1 col-sm-1 col-xs-1"></th>
                                                <th class="col-md-9 col-sm-10 col-xs-8">Name</th>
                                                <th class="col-md-2 col-sm-1 col-xs-3"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
<?php $i = 1; foreach ($attributes as $attribute): ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td><?php echo htmlspecialchars($attribute['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td class="text-right">
                                                    <span data-placement="top" data-toggle="tooltip" title="<?php echo lang('actions_edit'); ?>"><a href="<?php echo base_url() . 'admin/section/edit_attribute/' . $attribute['id']; ?>"><b class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-edit"></span></b></a></span>
                                                    <span data-placement="top" data-toggle="tooltip" title="<?php echo lang('actions_delete'); ?>"><a href="<?php echo base_url() . 'admin/section/delete_attribute/' . $attribute['id']; ?>"><b class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span></b></a></span>
                                                </td>
                                            </tr>
<?php $i++; endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="box">
                                <div class="box-body">
                                    <?php echo form_open(current_url(), array('class' => 'form-horizontal', 'id' => 'form-new_attribute_type')); ?>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <?php echo form_input($name);?>
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
