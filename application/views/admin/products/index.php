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
                                <div class="box-header with-border">
        <?php echo form_open('admin/products/index/', array('class' => 'form-horizontal', 'role' => 'form', 'method' => 'get')); ?>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="input-group">
                    <input id="search" name="search" class="form-control input-sm pull-right" type="text" placeholder="Search product name or code" value="<?php echo $search_term; ?>">
                    <div class="input-group-btn">
                        <button id="btnBuscar" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12 legend pull-right text-right">
                <span class="in_stoc">in stoc</span>
                <span class="stoc_minim">stoc minim</span>
                <span class="indisponibil">indisponibil</span>
                <span class="produs_inactiv">produs inactiv</span>
            </div>
        <?php echo form_close(); ?>
                                </div>
                                <div class="box-body">
                                    <table class="table table-striped table-hover table-products">
                                        <thead>
                                            <tr class="bg-primary text-uppercase">
                                                <th class="col-md-1 col-sm-2 col-xs-1"></th>
                                                <th class="col-md-4 col-sm-4 col-xs-4"><?php echo lang('products_name'); ?></th>
                                                <th class="col-md-1 col-sm-1 col-xs-1 text-center"><?php echo lang('products_code'); ?></th>
                                                <th class="col-md-1 col-sm-1 col-xs-1 text-center"><?php echo lang('products_price') . ' - ' . lang('products_price_rec'); ?></th>
                                                <th class="col-md-2 col-sm-2 hidden-xs text-center"><?php echo lang('products_brand'); ?></th>
                                                <th class="col-md-2 col-sm-2 hidden-xs text-center"><?php echo lang('products_category'); ?></th>
                                                <th class="col-md-1 col-sm-1 col-xs-1"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
<?php foreach ($products as $product):?>
                                            <tr class="<?php echo is_null($product['status']) ? 'bg-grey-hover' : ($product['stock_status'] == 'in stoc' ? 'bg-green-hover' : ($product['stock_status'] == 'stoc minim' ? 'bg-orange-hover' : 'bg-red-hover')); ?>">
                                                <td class="text-center v-md">
                                                    <?php echo '<img src="' . $product['images'] . '" alt="' . $product['name'] . '" class="img-thumbnail" style="width: 75px; height: 75px" />'; ?>
                                                    <div class="modal modal-danger fade" id="<?php echo $product['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModal">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
                                                                    <h4 class="modal-title" id="deleteModal">Delete notification</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                  Are you sure you want to delete <b><?php echo $product['name']; ?></b> ?
                                                                </div>
                                                                <div class="modal-footer">
                                                                  <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancel</button>
                                                                  <button type="button" class="btn btn-outline glyphicon glyphicon-remove" onclick="location.href='<?php echo base_url() . 'admin/products/delete_product/' . $product['id']; ?>';">Delete</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="v-md">
                                                    <h4><strong><?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?></strong></h4>
                                                    <p class="hidden-sm hidden-xs"><?php echo (strlen($product['description_long']) >= 115) ? substr($product['description_long'], 0, 115) . '..' : '<i>description not set</i>'; ?></p>
                                                </td>
                                                <td class="text-center v-md">
                                                    <h4><?php echo htmlspecialchars($product['code'], ENT_QUOTES, 'UTF-8'); ?></h4>
                                                    <p>[ <strong><?php echo $product['stock']; ?></strong> in stock ]</p>
                                                </td>
                                                <td class="text-center v-md">
                                                    <h4><strong><?php echo htmlspecialchars($product['price'], ENT_QUOTES, 'UTF-8' ); ?></strong> RON</h4>
                                                    <h4><strong><?php echo htmlspecialchars($product['price_rec'], ENT_QUOTES, 'UTF-8'); ?></strong> RON</h4>
                                                </td>
                                                <td class="hidden-xs  text-center v-md"><h4><?php echo htmlspecialchars($product['brand_name'], ENT_QUOTES, 'UTF-8'); ?></h4></td>
                                                <td class="hidden-xs text-center v-md"><h4><?php echo htmlspecialchars($product['category_name'], ENT_QUOTES, 'UTF-8'); ?></h4></td>
                                                <td class="text-right v-md">
                                                    <div data-placement="top" data-toggle="tooltip" title="<?php echo lang('actions_see'); ?>"><a href="<?php echo base_url() . 'admin/products/view_product/' . $product['id']; ?>"><b class="btn btn-success btn-xs"><span class="glyphicon glyphicon-eye-open"></span></b></a></div>
                                                    <div data-placement="top" data-toggle="tooltip" title="<?php echo lang('actions_edit'); ?>"><a href="<?php echo base_url() . 'admin/products/edit_product/' . $product['id']; ?>"><b class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-edit"></span></b></a></div>
                                                    <div data-placement="top" data-toggle="tooltip" title="<?php echo lang('actions_delete'); ?>"><b class="btn btn-danger btn-xs" data-toggle="modal" data-target="#<?php echo $product['id']; ?>"><span class="glyphicon glyphicon-remove"></span></b></div>
                                                </td>
                                            </tr>
<?php endforeach;?>
                                        </tbody>
                                        <thead>
                                            <tr class="bg-primary text-uppercase">
                                                <th class="col-md-1 col-sm-2 col-xs-1"></th>
                                                <th class="col-md-4 col-sm-4 col-xs-4"><?php echo lang('products_name'); ?></th>
                                                <th class="col-md-1 col-sm-1 col-xs-1 text-center"><?php echo lang('products_code'); ?></th>
                                                <th class="col-md-1 col-sm-1 col-xs-1 text-center"><?php echo lang('products_price') . ' - ' . lang('products_price_rec'); ?></th>
                                                <th class="col-md-2 col-sm-2 hidden-xs text-center"><?php echo lang('products_brand'); ?></th>
                                                <th class="col-md-2 col-sm-2 hidden-xs text-center"><?php echo lang('products_category'); ?></th>
                                                <th class="col-md-1 col-sm-1 col-xs-1"></th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                         </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <?php if(isset($pagination)) { echo $pagination; } ?>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
