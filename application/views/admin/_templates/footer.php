<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

        </div>
        <script src="<?php echo base_url($frameworks_dir . '/jquery/jquery.min.js'); ?>"></script>

        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.js"></script>

        <script src="<?php echo base_url($frameworks_dir . '/bootstrap/js/bootstrap.min.js'); ?>"></script>
        <script src="<?php echo base_url($plugins_dir . '/slimscroll/slimscroll.min.js'); ?>"></script>
<?php if ($mobile == TRUE): ?>
        <script src="<?php echo base_url($plugins_dir . '/fastclick/fastclick.min.js'); ?>"></script>
<?php endif; ?>
<?php if ($this->router->fetch_class() == 'products' && $this->router->fetch_method() == 'new_productt'): ?>
    <script src="<?php echo base_url($plugins_dir . '/selectorjs/selectorjs.js'); ?>"></script>
<?php endif; ?>
<?php if ($this->router->fetch_class() == 'users' && ($this->router->fetch_method() == 'create' OR $this->router->fetch_method() == 'edit')): ?>
        <script src="<?php echo base_url($plugins_dir . '/pwstrength/pwstrength.min.js'); ?>"></script>
<?php endif; ?>
        <script src="<?php echo base_url($frameworks_dir . '/adminlte/js/adminlte.min.js'); ?>"></script>
        <script src="<?php echo base_url($frameworks_dir . '/domprojects/js/dp.min.js'); ?>"></script>
        <script type="text/javascript">
        $(function () {
            $("#brand").change(get_categories);

            function get_categories() {
                var brand_id = $('select[name=brand]').val();
                var $categories_list = $('#categories_list');

                $.ajax({
                        url: '<?php echo base_url() . 'admin/json/get_brand_categories'; ?>/' + brand_id,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data){
                            if(data.length > 0) {
                                $('#categories').removeClass('hidden').addClass('show');
                                $('#subcategories').removeClass('show').addClass('hidden');
                                categoriesList = '<div id="categories_list">';
                                categoriesList += '<select name="categories" class="btn btn-primary col-md-12 col-sm-12 col-xs-12">';
                                categoriesList += '<option value="">Category</option>';
                                $.each(data, function(key, value) {
                                    categoriesList += '<option value="' + value['id'] + '">' + value['name'] + '</option>';
                                });
                                categoriesList += '</select>';
                                categoriesList += '</div>';
                                $categories_list.replaceWith(categoriesList);
                            } else {
                                $('#categories').removeClass('show').addClass('hidden');
                            }
                        }
                });
            }

            $("#categories").change(get_subcategories);

            function get_subcategories() {
                var category_id = $('select[name=categories]').val();
                var $subcategories_list = $('#subcategories_list');

                $.ajax({
                        url: '<?php echo base_url() . 'admin/json/get_categories_subcategories'; ?>/' + category_id,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data){
                            if(data.length > 0) {
                                $('#subcategories').removeClass('hidden').addClass('show');
                                subcategoriesList = '<div id="subcategories_list">';
                                subcategoriesList += '<select name="subcategories" class="btn btn-primary col-md-12 col-sm-12 col-xs-12">';
                                subcategoriesList += '<option value="">Subcategory</option>';
                                $.each(data, function(key, value) {
                                    subcategoriesList += '<option value="' + value['id'] + '">' + value['name'] + '</option>';
                                });
                                subcategoriesList += '</select>';
                                subcategoriesList += '</div>';
                                $subcategories_list.replaceWith(subcategoriesList);
                            } else {
                                $('#subcategories').removeClass('show').addClass('hidden');
                            }
                        }
                });
            }


<?php /*$(document).on('click','.new_attribute',function(){
       var clone = $(this).closest('.form-group').clone(true);
       $("input:first-child", clone).val(null);
       $(".new_attribute:last-child", clone).removeClass('fa-plus-square new_attribute').addClass('fa-minus-square remove_attribute');
       clone.insertAfter( $(this).closest('.form-group'));
    });*/ ?>

    $(document).on('click','.remove_attribute',function(e){
        var o = $(e.target);
        var group = $(o.closest('.form-group'));
        group.remove();
    });



$('select.attribute_type').on('change',function(){
    var current = $(this).closest('.form-group');
    var clone = current.clone(true);
    var optionSelected = $("option:selected", this).text();
    current.find('input').attr('name', 'attributes['+optionSelected+'][]');

    $("input:first-child", clone).val(null);

    $('<div class="col-md-1 col-sm-1 col-xs-1"><i class="fa fa-minus-square bg-primary remove_attribute"></i></div>').appendTo(current);
    clone.insertAfter($(this).closest('.form-group'));
    $(this).replaceWith('<input type="text" name="attributes['+optionSelected+'][]" value="'+optionSelected+'" class="form-control" disabled="disabled" />');
});


            function previewImages() {

              if (this.files) $.each(this.files, readAndPreview);

              function readAndPreview(i, file) {
                
                if (!/\.(jpe?g|png|gif)$/i.test(file.name)){
                  return alert(file.name +" is not an image");
                }

                $('.temp-img').remove();
                var reader = new FileReader();

                $(reader).on("load", function() {
                  ($('.gallery .form-group .col-sm-12')).append($('<div class="form-group-status" style="display:inline-block;position:relative;margin-right:10px"><img src="' + this.result + '" style="width:170px; max-height:200px;" class="img-responsive img-thumbnail temp-img" /></div>'));
                });

                reader.readAsDataURL(file);
                
              }

            }

            $('#fileUpload').change(previewImages);


            var options = {
                beforeSend: function(){
                    $(".upload-image-messages").html('<div class="btn btn-lg btn-warning"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate span6 offset3"></span> <strong>Loading..</strong></div>');
                },
                complete: function(response){
                    $(".upload-image-messages").html(response.responseText);
                }
            };

            $(".upload-image-form").ajaxForm(options);
        });


        $(this).ready( function() {

var selector = 'input.searchInput';
$(document).on('keyup.autocomplete', selector, function() {
    var vType = $(this).closest('div.form-group').find('input.form-control').val();
    var vInput = this.value;
    $(this).autocomplete({
        source: function(request, response){
            $.get("<?php echo base_url(); ?>admin/json/get_attribute_values/" + vType + "/" + vInput, {
                term:request.term
                }, function(data){
                response($.map(data, function(item) {
                    return {
                        label: item.label,
                        value: item.label
                    }
                }))
            }, "json");
        },
        minLength: 1,
        dataType: "json",
        cache: false
    });

});
        });
        </script>

        <style>
            .glyphicon-refresh-animate {
                -animation: spin .7s infinite linear;
                -webkit-animation: spin2 .7s infinite linear;
            }

            @-webkit-keyframes spin2 {
                from { -webkit-transform: rotate(0deg);}
                to { -webkit-transform: rotate(360deg);}
            }

            @keyframes spin {
                from { transform: scale(1) rotate(0deg);}
                to { transform: scale(1) rotate(360deg);}
            }

            table.table-products tr.bg-green-hover h4, table.table-products tr.bg-orange-hover h4, table.table-products tr.bg-red-hover h4, table.table-products tr.bg-grey-hover h4 {
                margin: 0 0 5px !important
            }
            table.table-products tr.bg-green-hover p, table.table-products tr.bg-orange-hover p, table.table-products tr.bg-red-hover p, table.table-products tr.bg-grey-hover p {
                margin: 0 !important
            }
            table.table-products td.v-md {
                vertical-align: middle;
            }
            table.table-products td.text-right div {
                margin: 0 5px 3px !important
            }
            table.table-products tr {
                color: #FFF;
                transition: background-color 0.2s ease;
                border: 2px solid rgba(137, 137, 137, 0.1);
                border-left: 0;
                border-right: 0;
                box-shadow: inset 0px 0px 2px 0px rgba(0,0,0,0.75);
            }
            table.table-products tr:hover {
                box-shadow: inset 0px 0px 5px 0px rgba(0,0,0,0.75);
            }
            table.table-products h4 {
                overflow: hidden;
            }
            table.table-products tr.bg-green-hover {
                background: rgba(87, 168, 130, 0.6);
            }
            table.table-products tr.bg-orange-hover {
                background: rgba(168, 78, 0, 0.6);
            }
            table.table-products tr.bg-red-hover {
                background: rgba(144, 26, 12, 0.6);
            }
            table.table-products tr.bg-grey-hover {
                background: rgba(162, 162, 162, 0.6);
            }
            table.table-products tr.bg-green-hover:hover {
                background: rgba(87, 168, 130, 0.8);
            }
            table.table-products tr.bg-orange-hover:hover {
                background: rgba(168, 78, 0, 0.7);
            }
            table.table-products tr.bg-red-hover:hover {
                background: rgba(144, 26, 12, 0.7);
            }
            table.table-products tr.bg-grey-hover:hover {
                background: rgba(162, 162, 162, 0.7);
            }

            div.legend {
                text-transform: uppercase;
            }
            div.legend span:before {
                content: '';
                display: inline-block;
                width: 20px;
                height: 20px;
                margin: 0 10px;
                vertical-align: top;
            }
            div.legend span.in_stoc:before {
                background: rgba(87, 168, 130, 0.6);
                border: 1px solid rgba(87, 168, 130, 0.7);
            }
            div.legend span.stoc_minim:before {
                background: rgba(168, 78, 0, 0.6);
                border: 1px solid rgba(168, 78, 0, 0.7);
            }
            div.legend span.indisponibil:before {
                background: rgba(144, 26, 12, 0.6);
                border: 1px solid rgba(144, 26, 12, 0.7);
            }
            div.legend span.produs_inactiv:before {
                background: rgba(162, 162, 162, 0.6);
                border: 1px solid rgba(162, 162, 162, 0.7);
            }


            /* Autocomplete
            ----------------------------------*/
            .ui-autocomplete { position: absolute; cursor: default; z-index: 101 !important;}   
            .ui-autocomplete-loading { background: white url('http://jquery-ui.googlecode.com/svn/tags/1.8.2/themes/flick/images/ui-anim_basic_16x16.gif') right center no-repeat; }*/
  
            /* workarounds */
            * html .ui-autocomplete { width:1px; } /* without this, the menu expands to 100% in IE6 */
  
            /* Menu
            ----------------------------------*/
            .ui-menu {
                list-style:none;
                padding: 2px;
                margin: 0;
                display:block;
            }
            .ui-menu .ui-menu {
                margin-top: -3px;
            }
            .ui-menu .ui-menu-item {
                margin:0;
                padding: 0;
                zoom: 1;
                float: left;
                clear: left;
                width: 100%;
                font-size:80%;
            }
            .ui-menu .ui-menu-item a {
                text-decoration:none;
                display:block;
                padding:.2em .4em;
                line-height:1.5;
                zoom:1;
            }
            .ui-menu .ui-menu-item a.ui-state-hover,
            .ui-menu .ui-menu-item a.ui-state-active {
                font-weight: normal;
                margin: -1px;
            }
        </style>
    </body>
</html>