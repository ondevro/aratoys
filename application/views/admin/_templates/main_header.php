<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
            <header class="main-header">
                <a href="<?php echo site_url('admin/dashboard'); ?>" class="logo">
                    <span class="logo-mini"><?php echo $title_mini; ?></span>
                    <span class="logo-lg"><?php echo $title; ?></span>
                </a>

                <nav class="navbar navbar-static-top" role="navigation">
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>

                    <div class="navbar-custom-menu">
                        <div class="pull-right">
                            <a href="<?php echo site_url('auth/logout/admin'); ?>" class="fa fa-lg fa-sign-out" style="margin:20px 20px 0;color:#FFF;font-size:14px"><span class="pull-left" style="font-family:Arial;padding-right:15px"><?php echo lang('header_sign_out'); ?></span></a>
                        </div>
                    </div>
                </nav>
            </header>
