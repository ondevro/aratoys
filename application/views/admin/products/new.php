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
                        <?php echo form_open(current_url(), array('class' => 'form-horizontal', 'id' => 'form-new_product')); ?>
                             <div class="box">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><?php echo form_button(array('type' => 'submit', 'class' => 'btn btn-block btn-primary btn-flat', 'style' => 'word-spacing:5px;letter-spacing:1px', 'content' => '<i class="fa fa-plus"> ADD PRODUCT</i>')); ?></h3>
                                </div>
                                <div class="box-body">
                                    <?php echo validation_errors('<div class="alert alert-warning"><i class="icon fa fa-warning"></i>', '</div>'); ?>

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
                                                <label>Stock status</label>
                                                <?php echo form_dropdown('stock_status', $stock_status, '', array('class' => 'btn btn-primary col-md-12 col-sm-12 col-xs-12')); ?>
                                            </div>   
                                            <div class="col-md-2 col-sm-6 col-xs-12">
                                                <label>Brand</label>
                                                <?php echo form_dropdown('brand', $brand_id, '', array('id' => 'brand', 'class' => 'btn btn-primary  col-md-12 col-sm-12 col-xs-12'));?>
                                            </div> 
                                            <div class="col-md-2 col-sm-6 col-xs-12" id="categories">
                                                <label>Categories</label>
                                                <div id="categories_list">
                                                <?php echo form_dropdown('categories', $category_id, '', array('id' => 'category', 'class' => 'btn btn-primary col-md-12 col-sm-12 col-xs-12'));?>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-sm-6 col-xs-12 hidden" id="subcategories">
                                                <label>Subcategory</label>
                                                <div id="subcategories_list">
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
                                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        <div class="col-md-6 col-md-offset-6 col-sm-12 col-xs-12">
                            <div class="upload-image-messages"></div>
                            <div class="col-md-6">
                                <?php echo form_open_multipart('/admin/products/do_upload', array('class' => 'upload-image-form')); ?>
                                    <input type="file" multiple="multiple" accept="image/*" id="fileUpload" class="form-control" name="uploadfile[]" size="20" /><br />
                                    <input type="submit" name="submit" value="Upload" class="btn btn-primary" />
                                </form>
                            </div>
                            <div id="image-holder"></div>
                        </div>
                </section>
            </div>