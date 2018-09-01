<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

            <div class="content-wrapper">
                <section class="content-header">
                    <?php echo $pagetitle; ?>
                    <?php echo $breadcrumb; ?>
                </section>

                <section class="content">
                    <div class="callout callout-info" style="margin-bottom: 50px">
                      <h4><i class="icon fa fa-info"></i> Reminder!</h4>
                      <p>
                        The feed cache will auto-generate only once each day after the request was sent to access data products.
                      </p>
                      <p>
                      	After changing the stock quantity, the product stock status will be updated automatically; this feature is also available at importing stock.
                      </p>
                    </div>

                    <div class="callout callout-warning" style="margin-bottom: 50px">
                      <h4><i class="icon fa fa-warning"></i> Tip!</h4>
                      <p>
                        Once the products are importing from excel, existing products code from the database will be overwritten with the data from the file selected and the rest will be inserted.
                      </p>
                      <p>
                        Importing stock will overwrite existing stock quantity, and will update ONLY for products that are already in the database.
                      </p>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-aqua"><i class="fa fa-list-ul"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Products</span>
                                    <span class="info-box-number"><?php echo $count_products; ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-yellow"><i class="fa fa-font-awesome"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Brands</span>
                                    <span class="info-box-number"><?php echo $count_brands; ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-orange"><i class="fa fa-list-alt"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Categories & SubCategories</span>
                                    <span class="info-box-number"><?php echo $count_categories; ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-red"><i class="fa fa-puzzle-piece"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Attributes</span>
                                    <span class="info-box-number"><?php echo $count_attributes; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-sm-12 col-xs-12">
                          <div class="info-box bg-aqua">
                            <span class="info-box-icon"><i class="fa fa-line-chart"></i></span>
                            <div class="info-box-content">
                              <span class="info-box-number">STATUS OF PRODUCTS ON STOCK</span>
                              <span class="info-box-text">
                                <?php if($count_products > 0 && $count_products_in_stock > 0) {
                                    echo '<b>' . $count_products_in_stock . '</b> of <b>' . $count_products . '</b> products are <b>available in stock (' . $products_in_stock_percent . '%)</b>';
                                  } else {
                                    echo '<b>NOT ENOUGH DATE IN DATABASE</b>';
                                  }
                                ?>
                              </span>
                              <div class="progress">
                                <div class="progress-bar"<?php if(isset($products_in_stock_percent)) { echo ' style="width: ' . $products_in_stock_percent . '%"'; } ?>></div>
                              </div>
                              <span class="progress-description" style="font-style: italic">
                                <?php
                                  if($stock_pieces > 0) {
                                    echo '<b>' . $stock_pieces . '</b> stock pieces are in stock</b>';
                                  } else {
                                    echo '<b>NO STOCK AVAILABLE IN DATABASE</b>';
                                  }
                                ?>
                              </span>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-green"><i class="fa fa-user"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Users</span>
                                    <span class="info-box-number"><?php echo $count_users; ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-purple"><i class="fa fa-users"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Groups</span>
                                    <span class="info-box-number"><?php echo $count_groups; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-sm-12 col-xs-12">
                          <div class="info-box bg-blue">
                            <span class="info-box-icon"><i class="fa fa-rss"></i></span>
                            <div class="info-box-content">
                              <span class="info-box-number">XML V1</span>
                              <span class="info-box-text">Update <b><?php echo $xml_v1_generated_date; ?></b></span>
                              <div class="progress">
                                <div class="progress-bar"></div>
                              </div>
                              <span class="progress-description">
                              <span><a href="<?php echo base_url(); ?>v1/xml" target="_blank" style="text-decoration: underline; color: #FFF !important; font-weight: bold; letter-spacing: 3px; word-spacing: 5px">XML V1 LINK</a></span>
                              </span>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-3 col-sm-12 col-xs-12">
                          <div class="info-box bg-blue">
                            <span class="info-box-icon"><i class="fa fa-rss"></i></span>
                            <div class="info-box-content">
                              <span class="info-box-number">XML V2</span>
                              <span class="info-box-text">Update <b><?php echo $xml_v2_generated_date; ?></b></span>
                              <div class="progress">
                                <div class="progress-bar"></div>
                              </div>
                              <span class="progress-description">
                              <span><a href="<?php echo base_url(); ?>v2/xml" target="_blank" style="text-decoration: underline; color: #FFF !important; font-weight: bold; letter-spacing: 3px; word-spacing: 5px">XML V2 LINK</a></span>
                              </span>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-3 col-sm-12 col-xs-12">
                          <div class="info-box bg-light-blue">
                            <span class="info-box-icon"><i class="fa fa-rss"></i></span>
                            <div class="info-box-content">
                              <span class="info-box-number">JSON V1</span>
                              <span class="info-box-text">Update <b><?php echo $json_v1_generated_date; ?></b></span>
                              <div class="progress">
                                <div class="progress-bar"></div>
                              </div>
                              <span class="progress-description">
                              <span><a href="<?php echo base_url(); ?>v1/json" target="_blank" style="text-decoration: underline; color: #FFF !important; font-weight: bold; letter-spacing: 3px; word-spacing: 5px">JSON V1 LINK</a></span>
                              </span>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-3 col-sm-12 col-xs-12">
                          <div class="info-box bg-light-blue">
                            <span class="info-box-icon"><i class="fa fa-rss"></i></span>
                            <div class="info-box-content">
                              <span class="info-box-number">JSON V2</span>
                              <span class="info-box-text">Update <b><?php echo $json_v2_generated_date; ?></b></span>
                              <div class="progress">
                                <div class="progress-bar"></div>
                              </div>
                              <span class="progress-description">
                              <span><a href="<?php echo base_url(); ?>v2/json" target="_blank" style="text-decoration: underline; color: #FFF !important; font-weight: bold; letter-spacing: 3px; word-spacing: 5px">JSON V2 LINK</a></span>
                              </span>
                            </div>
                          </div>
                        </div>
                    </div>  
                </section>
            </div>
