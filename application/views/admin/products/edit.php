<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<style>
/* Checkbox and Radio buttons */
.form-group-status input[type="checkbox"]{
    display: none;
}

.form-group-status input[type="checkbox"] + .btn-group > label{
    white-space: normal;
}

.form-group-status input[type="checkbox"] + .btn-group > label.btn-primary,frm-test-elm-110-1
.form-group-status input[type="radio"] + .btn-group > label.btn-primary{
    color: #fff;
    background-color: #428bca;
    border-color: #357ebd;
}
.form-group-status input[type="checkbox"] + .btn-group > label span:first-child{
    display: none;
}

.form-group-status input[type="checkbox"] + .btn-group > label span:first-child + span{
    display: inline-block;
}

.form-group-status input[type="checkbox"]:checked + .btn-group > label span:first-child{
    display: inline-block;
}

.form-group-status input[type="checkbox"]:checked + .btn-group > label span:first-child + span{
    display: none;  
}

.form-group-status input[type="checkbox"] + .btn-group > label span[class*="fa-"]{
    width: 15px;
    float: left;
    margin: 4px 0 2px -2px;
}

.form-group-status input[type="checkbox"] + .btn-group > label span.content{
    margin-left: 10px;
    font-weight: bold;
    letter-spacing: 2px;
}
.gallery .btn-group{
    position: absolute !important;
    left: 10px;
    top: 10px;
}
.gallery .btn-group label{
    padding: 3px 6px !important;
}
/* End::Checkbox and Radio buttons */
</style>
            <div class="content-wrapper">
                <section class="content-header">
                    <?php echo $pagetitle; ?>
                    <?php echo $breadcrumb; ?>
                </section>

                <section class="content">
                    <div class="row">
                        <div class="col-md-12">
                        <?php echo form_open(current_url(), array('class' => 'form-horizontal', 'id' => 'form-new_product')); ?>
                             <div class="box">
                                <div class="box-header with-border">
                                    <div class="form-group-status" style="display:inline-block;height:22px;margin:0">
                                        <div class="col-sm-12">
                                            <?php echo form_checkbox($active);?>
                                            <div class="btn-group">
                                                <label for="frm-test-elm-110-1" class="btn btn-primary">
                                                    <span class="fa fa-check-square-o fa-lg"></span>
                                                    <span class="fa fa-square-o fa-lg"></span>
                                                    <span class="content">ACTIVE</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="box-title"><?php echo form_button(array('type' => 'submit', 'class' => 'btn btn-block btn-primary btn-flat', 'content' => '<i class="fa fa-floppy-o"> SAVE</i>')); ?></div>
                                </div>
                                <div class="box-body">
                                    <?php

                                    if(isset($product_updated)) {
                                        echo '<div class="alert alert-info"><i class="icon fa fa-info"></i>' . $product_updated . '</div>';
                                    }

                                    echo validation_errors('<div class="alert alert-warning"><i class="icon fa fa-warning"></i>', '</div>'); ?>

                                    <div class="form-group">
                                        <div class="col-md-4 col-sm-6 col-xs-12">
                                            <label>Name</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-taxi"></i>
                                                </div>
                                                <?php echo form_input($name); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-6 col-xs-12">
                                            <label>Code</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-terminal"></i>
                                                </div>
                                                <?php echo form_input($code); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-6 col-xs-12">
                                            <label>Price</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-euro"></i>
                                                </div>
                                                <?php echo form_input($price); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-6 col-xs-12">
                                            <label>Sale price</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-euro"></i>
                                                </div>
                                                <?php echo form_input($price_rec); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-6 col-xs-12">
                                            <label>Stock</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-list-ol"></i>
                                                </div>
                                                <?php echo form_input($stock); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-2 col-sm-6 col-xs-12">
                                            <label>Status of stock</label>
                                            <?php echo form_dropdown('stock_status', $stock_status, $stock_status_selected, array('class' => 'btn btn-primary col-md-12 col-sm-12 col-xs-12')); ?>
                                        </div>
                                        <div class="col-md-2 col-sm-6 col-xs-12">
                                            <label>Brand</label>
                                            <?php echo form_dropdown('brand', $brand_id, $brand, array('id' => 'brand', 'class' => 'btn btn-primary col-md-12 col-sm-12 col-xs-12')); ?>
                                        </div>
                                        <div class="col-md-2 col-sm-6 col-xs-12" id="categories">
                                            <label>Categories</label>
                                            <div id="categories_list">
                                                <?php echo form_dropdown('categories', $category_id, $category, array('class' => 'btn btn-primary col-md-12 col-sm-12 col-xs-12')); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-6 col-xs-12 hidden" id="subcategories">
                                            <label>Subcategory</label>
                                            <div id="subcategories_list">
                                                <?php echo form_dropdown('subcategories', $subcategory_id, $subcategory, array('class' => 'btn btn-primary col-md-12 col-sm-12 col-xs-12')); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-6 col-sm-12 col-xs-12" data-placement="right" data-toggle="tooltip" data-original-title="Short description">
                                            <?php echo form_textarea($description_short); ?>
                                        </div>
                                        <div class="col-md-6 col-sm-12 col-xs-12" data-placement="left" data-toggle="tooltip" data-original-title="Long description">
                                            <?php echo form_textarea($description_long, lang('products_long_description', 'long_description')); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                     <div class="box">
                                        <div class="box-header with-border">
                                            <i class="fa fa-puzzle-piece "></i>
                                            <h3 class="box-title">Attributes</h3>
                                        </div>
                                        <div class="box-body" id="attributes">
                                            <?php foreach($product_attributes as $product_attribute) { ?>
                                            <div class="form-group">
                                                <div class="col-md-4 col-sm-4 col-xs-12">
                                                    <?php //echo form_dropdown('attributes', $attributes, $product_attribute['type_id'], array('class' => 'btn btn-primary col-md-12 col-sm-12 col-xs-12')); ?>
                                                    <input type="text" name="attributes[<?php echo $product_attribute['type']; ?>][]" value="<?php echo $product_attribute['type']; ?>" class="form-control" disabled="disabled" />
                                                </div>
                                                <div class="col-md-7 col-sm-7 col-xs-11">
                                                    <input type="text" name="attributes[<?php echo $attributes[$product_attribute['type_id']]; ?>][]" value="<?php echo $product_attribute['value']; ?>" placeholder="attribute value" class="form-control col-md-12 col-sm-12 col-xs-12 searchInput" />
                                                </div>
                                                <div class="col-md-1 col-sm-1 col-xs-1">
                                                    <i class="fa fa-minus-square bg-primary remove_attribute"></i>
                                                </div>
                                                <ul id="result"></ul>
                                            </div>
                                            <?php } ?>
                                            <div class="form-group">
                                                <div class="col-md-4 col-sm-4 col-xs-12">
                                                    <?php echo form_dropdown('new_attributes', $attributes, '', array('class' => 'attribute_type btn btn-primary col-md-12 col-sm-12 col-xs-12')); ?>
                                                </div>
                                                <div class="col-md-7 col-sm-7 col-xs-11">
                                                    <input type="text" name="" placeholder="attribute value" class="form-control col-md-12 col-sm-12 col-xs-12 searchInput" />
                                                </div>
                                                <ul id="result"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                     <div class="box">
                                        <div class="box-header with-border">
                                            <i class="fa fa-file-image-o"></i>
                                            <h3 class="box-title">Images</h3>
                                        </div>
                                        <div class="box-body gallery">
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                <?php
                                                    foreach($images as $image) {
                                                        echo '<div style="display:inline-block;position:relative;margin-right:10px" class="form-group-status">';
                                                        echo '<img src="' . $image['link'] . '" class="img-responsive img-thumbnail" role="button" style="height:130px;padding:4px;" />';
                                                        echo form_checkbox('images[]', $image['id'], TRUE, array('id' => 'image' . $image['id']));
                                                        echo '
                                                        <div class="btn-group" data-placement="top" data-toggle="tooltip" title="Active image">
                                                        <label for="image' . $image['id'] . '" class="btn btn-primary">
                                                        <span class="fa fa-check-square-o fa-lg"></span>
                                                        <span class="fa fa-square-o fa-lg"></span>';
                                                        echo '</label></div>';

                                                        echo '<a href="' . $image['link'] . '" target="_blank"><i class="fa fa-arrows-alt bg-primary" data-placement="top" data-toggle="tooltip" title="View image" style="position:absolute;top:10px;right:10px;font-size:20px;color:#FFF;padding:4px;border-radius:3px"></i></a>';
                                                        echo '</div>';
                                                    }
                                                ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php //echo form_close();?>
                        </form>
                        <div class="col-md-6 col-md-offset-6 col-sm-12 col-xs-12">
                            <div class="upload-image-messages"></div>
                                <?php echo form_open_multipart('/admin/products/do_upload', array('class' => 'upload-image-form'));?>
                                    <input type="file" multiple="multiple" accept="image/*" id="fileUpload" class="form-control" name="uploadfile[]" size="20" /><br />
                                    <input type="submit" name="submit" value="Upload" class="btn btn-primary" />
                                </form>
                            <div id="image-holder"></div>
                        </div>
                    </div>
                </section>
            </div>