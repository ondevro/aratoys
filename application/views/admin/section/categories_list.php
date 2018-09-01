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
                                    <?php
                                    if(isset($category_status['added'])) {
                                        echo '<div class="alert alert-success"><i class="icon fa fa-check"></i>' . $category_status['added'] . '</div>';
                                    }

                                    if(isset($category_status['updated'])) {
                                        echo '<div class="alert alert-info"><i class="icon fa fa-info"></i>' . $category_status['updated'] . '</div>';
                                    }

                                    echo validation_errors('<div class="alert alert-warning"><i class="icon fa fa-warning"></i>', '</div>');

                                    echo form_open(current_url(), array('class' => 'form-horizontal', 'id' => 'form-new_product'));
                                    ?>
                                        <div class="form-group">
                                            <div class="col-md-7 col-sm-7 col-xs-12">
                                                <?php echo form_input($name); ?>
                                            </div>
                                            <div class="col-md-2 col-sm-2 col-xs-12">
                                                <?php echo form_dropdown('brand', $brand, $selected_brand['brand_id'], array('id' => 'brand', 'class' => 'btn btn-primary col-md-12 col-sm-12 col-xs-12'));?>
                                            </div>
                                            <div class="col-md-3 col-sm-3 col-xs-12" id="categories">
                                                <div id="categories_list">
                                                    <?php echo form_dropdown('categorie', $brand_categories, $selected_category['subcategory_id'], array('class' => 'btn btn-primary col-sm-12')); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <?php echo form_textarea($description); ?>
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
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box">
                                <div class="box-body">
                                    <table class="table table-striped table-hover">
<?php foreach($brands as $brand): ?>
                                        <thead>
                                            <tr>
                                                <td colspan="4" class="info text-center text-uppercase"><b><?php echo htmlspecialchars($brand['name'], ENT_QUOTES, 'UTF-8'); ?></b></td>
                                            </tr>
                                        </thead>
                                        <thead>
                                            <tr>
                                                <th class="col-md-1 col-sm-1 col-xs-1"></th>
                                                <th class="col-md-3 col-sm-3 col-xs-3">Name</th>
                                                <th class="col-md-7 col-sm-7 col-xs-7">Description</th>
                                                <th class="col-md-1 col-sm-1 col-xs-1"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
 <?php
    $i = 0;

    if(isset($categories[$brand['id']])) {
        foreach($categories[$brand['id']] as $category):
            $i++;
            $description = strlen($category['description']) > 140 ? substr($category['description'], 0, 140) . '...' : $category['description'];
?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td><b><?php echo htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8'); ?></b></td>
                                                <td><?php echo htmlspecialchars($description, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td class="text-right">
                                                    <span data-placement="top" data-toggle="tooltip" title="<?php echo lang('actions_edit'); ?>"><a href="<?php echo base_url() . 'admin/section/edit_category/' . $category['id']; ?>"><b class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-edit"></span></b></a></span>
                                                    <span data-placement="top" data-toggle="tooltip" title="<?php echo lang('actions_delete'); ?>"><a href="<?php echo base_url() . 'admin/section/delete_category/' . $category['id']; ?>"><b class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span></b></a></span>
                                                </td>
                                            </tr>
     <?php
             if(isset($subcategories[$category['id']])) {
                foreach($subcategories[$category['id']] as $subcategory):
                    $description = strlen($subcategory['description']) > 140 ? substr($subcategory['description'], 0, 140) . '...' : $subcategory['description'];
     ?>
                                                <tr class="success">
                                                    <td></td>
                                                    <td><b><?php echo htmlspecialchars($subcategory['name'], ENT_QUOTES, 'UTF-8'); ?></b></td>
                                                    <td><?php echo htmlspecialchars($description, ENT_QUOTES, 'UTF-8'); ?></td>
                                                    <td class="text-right">
                                                        <span data-placement="top" data-toggle="tooltip" title="<?php echo lang('actions_edit'); ?>"><a href="<?php echo base_url() . 'admin/section/edit_category/' . $subcategory['id']; ?>"><b class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-edit"></span></b></a></span>
                                                        <span data-placement="top" data-toggle="tooltip" title="<?php echo lang('actions_delete'); ?>"><a href="<?php echo base_url() . 'admin/section/delete_category/' . $subcategory['id']; ?>"><b class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span></b></a></span>
                                                    </td>
                                                </tr>
                <?php endforeach; } ?>
        <?php endforeach; } ?>
                                        </tbody>
<?php endforeach; ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
