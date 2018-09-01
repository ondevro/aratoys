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
                                    <table class="table table-striped table-hover">
                                        <tbody>
                                            <tr>
                                                <th class="col-sm-offset-1 col-md-2"><?php echo lang('products_name'); ?></th>
                                                <td><?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo lang('products_code'); ?></th>
                                                <td><?php echo htmlspecialchars($product['code'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo lang('products_price') . '/' . lang('products_price_rec'); ?></th>
                                                <td><?php echo htmlspecialchars($product['price'], ENT_QUOTES, 'UTF-8'); ?>/<?php echo htmlspecialchars($product['price_rec'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo lang('products_stock'); ?></th>
                                                <td><?php echo $product['stock_status']; ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo lang('products_stock'); ?></th>
                                                <td><?php echo $product['stock']; ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo lang('products_brand'); ?></th>
                                                <td><?php echo htmlspecialchars($product['brand']['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            </tr>
                                        <?php foreach ($attributes as $attribute) { ?>
                                            <tr>
                                                <th><?php echo htmlspecialchars($attribute['type'], ENT_QUOTES, 'UTF-8'); ?></th>
                                                <td><?php echo htmlspecialchars($attribute['value'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            </tr>
                                        <?php } ?>
                                            <tr>
                                                <th><?php echo lang('products_category'); ?></th>
                                                <td><?php echo htmlspecialchars($product['category']['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo lang('products_subcategory'); ?></th>
                                                <td><?php echo htmlspecialchars($product['subcategory']['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo lang('products_description_short'); ?></th>
                                                <td><?php echo htmlspecialchars($product['description_short'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo lang('products_description_long'); ?></th>
                                                <td><?php echo htmlspecialchars($product['description_long'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                <?php foreach($product['images'] as $image) {
                                                    echo '<img src="' . $image['link'] . '" width="200" />';
                                                } ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                         </div>
                    </div>
                </section>
            </div>
