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
                        <div class="col-md-7">
                            <div class="box">
                                <div class="box-body">
                                <?php
                                    if($status_imported_products['products_imported'] > 0) {
                                        echo '<div class="alert alert-success"><i class="icon fa fa-check"></i><strong>' . $status_imported_products['products_imported'] . '</strong> product(s) successfully imported</div>';
                                    }

                                    if(isset($status_imported_products['products_updated'])) {
                                        echo '<div class="alert alert-info"><i class="icon fa fa-info"></i><strong>' . $status_imported_products['products_updated'] . '</strong> product(s) updated</div>';
                                    }

                                    if(isset($status_imported_products['products_invalid'])) {
                                        echo '<div class="alert alert-warning"><i class="icon fa fa-warning"></i><strong>' . $status_imported_products['products_invalid'] . '</strong> product(s) data are invalid or empty rows</div>';
                                    }
                                ?>
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Name</th>
                                                <th>Size</th>
                                                <th>Date</th>
                                                <th>Hour</th>
                                            </tr>
                                        </thead>
                                        <tbody>
<?php $i = 0; foreach ($files as $file): $i++; ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td><?php echo htmlspecialchars($file['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo round($file['size'] / 1024000, 2); ?> MB</td>
                                                <td><?php echo date('d F', $file['date']); ?></td>
                                                <td><?php echo date('H:i', $file['date']); ?></td>
                                                <td>
                                                    <div class="modal modal-danger fade" id="<?php echo $i; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModal">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
                                                                    <h4 class="modal-title" id="deleteModal">Delete notification</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                  Are you sure you want to delete <b><?php echo $file['name']; ?></b> ?
                                                                </div>
                                                                <div class="modal-footer">
                                                                  <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancel</button>
                                                                  <button type="button" class="btn btn-outline glyphicon glyphicon-remove" onclick="location.href='<?php echo 'delete_file/products/' . $file['name']; ?>';">Delete</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <span data-placement="top" data-toggle="tooltip" title="Import"><a href="<?php echo "import_products/" . $file['name']; ?>"><b class="btn btn-primary btn-xs"><span class="fa fa-arrow-circle-o-up"></span></b></a></span>
                                                    <span data-placement="top" data-toggle="tooltip" title="Delete"><b class="btn btn-danger btn-xs" data-toggle="modal" data-target="#<?php echo $i; ?>"><span class="fa fa-minus-circle"></span></b></span>
                                                </td>
                                            </tr>
<?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 pull-right">
                            <div class="box">
                                <div class="box-body">
                                    <form method="post" enctype="multipart/form-data">
                                        <p><input type="file" name="file" accept=".xls,.xlsx" /></p>
                                        <?php echo form_error('file','<p class="help-block">','</p>'); ?>
                                        <p><input type="submit" name="uploadFile" value="UPLOAD" /></p>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
