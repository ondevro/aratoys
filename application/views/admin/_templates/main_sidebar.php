<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

            <aside class="main-sidebar">
                <section class="sidebar">
<?php if ($admin_prefs['user_panel'] == TRUE): ?>
                    <div class="user-panel">
                        <div class="pull-left image">
                            <img src="http://www.gravatar.com/avatar/<?php echo md5($user_login['email']); ?>?s=32" class="img-circle" alt="User Image">
                        </div>
                        <div class="pull-left info">
                            <p><?php echo $user_login['firstname'].$user_login['lastname'] ?></p>
                        </div>
                    </div>

<?php endif; ?>
<?php if ($admin_prefs['sidebar_form'] == TRUE): ?>
                    <form action="#" method="get" class="sidebar-form">
                        <div class="input-group">
                            <input type="text" name="q" class="form-control" placeholder="<?php echo lang('menu_search'); ?>...">
                            <span class="input-group-btn">
                                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
                            </span>
                        </div>
                    </form>

<?php endif; ?>
                    <ul class="sidebar-menu">
                        <li class="<?=active_link_controller('dashboard'); ?>">
                            <a href="<?php echo site_url('admin/dashboard'); ?>">
                                <i class="fa fa-dashboard"></i> <span><?php echo lang('menu_dashboard'); ?></span>
                            </a>
                        </li>
                        <li class="header text-uppercase"><?php echo lang('menu_products'); ?></li>
                        <li<?php if(active_link_controller('products') == 'active' && active_link_function('index') == 'active') { echo ' class="active"'; } ?>>
                            <a href="<?php echo site_url('admin/products'); ?>">
                                <i class="fa fa-list-ul"></i> <span><?php echo lang('menu_products_list'); ?></span>
                            </a>
                        </li>
                        <li class="<?=active_link_function('new_product'); ?>">
                            <a href="<?php echo site_url('admin/products/new_product'); ?>">
                                <i class="fa fa-plus"></i> <span><?php echo lang('menu_new_product'); ?></span>
                            </a>
                        </li>
                        <li class="header text-uppercase"><?php echo lang('menu_sections'); ?></li>
                        <li class="<?=active_link_function('brands'); ?>">
                            <a href="<?php echo site_url('admin/section/brands'); ?>">
                                <i class="fa fa-font-awesome"></i> <span><?php echo lang('menu_section_brands'); ?></span>
                            </a>
                        </li>
                        <li class="<?=active_link_function('categories'); ?>">
                            <a href="<?php echo site_url('admin/section/categories'); ?>">
                                <i class="fa fa-list-alt"></i> <span><?php echo lang('menu_section_categories'); ?></span>
                            </a>
                        </li>
                        <li class="<?=active_link_function('attributes'); ?>">
                            <a href="<?php echo site_url('admin/section/attributes'); ?>">
                                <i class="fa fa-puzzle-piece"></i> <span><?php echo lang('menu_section_attributes'); ?></span>
                            </a>
                        </li>

                        <li class="header text-uppercase"><?php echo lang('menu_import'); ?></li>
                        <li<?php if(active_link_controller('import') == 'active' && active_link_function('products') == 'active') { echo ' class="active"'; } ?>>
                            <a href="<?php echo site_url('admin/import/products'); ?>">
                                <i class="fa fa-table"></i> <span><?php echo lang('menu_import_products'); ?></span>
                            </a>
                        </li>
                        <li<?php if(active_link_controller('import') == 'active' && active_link_function('stock') == 'active') { echo ' class="active"'; } ?>>
                            <a href="<?php echo site_url('admin/import/stock'); ?>">
                                <i class="fa fa-list-ol"></i> <span><?php echo lang('menu_import_stock'); ?></span>
                            </a>
                        </li>

                        <li class="header text-uppercase"><?php echo lang('menu_export'); ?></li>
                        <li<?php if(active_link_controller('export') == 'active' && active_link_function('excel') == 'active') { echo ' class="active"'; } ?>>
                            <a href="<?php echo site_url('admin/export/excel'); ?>">
                                <i class="fa fa-file-excel-o"></i> <span><?php echo lang('menu_export_excel'); ?></span>
                            </a>
                        </li>

                        <li class="header text-uppercase"><?php echo lang('menu_administration'); ?></li>
                        <li class="<?=active_link_controller('users'); ?>">
                            <a href="<?php echo site_url('admin/users'); ?>">
                                <i class="fa fa-user"></i> <span><?php echo lang('menu_users'); ?></span>
                            </a>
                        </li>
                    </ul>
                </section>
            </aside>
