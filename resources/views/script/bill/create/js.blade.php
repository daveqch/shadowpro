<script>
    "use strict";
    var old_row_qty;
    let grand_total = 0;
    var item_array = [];
    var d = null;

    $('.dropify').dropify();

    var quill = new Quill('#input_description', {
        theme: 'snow'
    });
    var address = $("#description").val();
    quill.clipboard.dangerouslyPasteHTML(address);
    quill.root.blur();
    $('#input_description').on('keyup', function(){
        var input_description = quill.container.firstChild.innerHTML;
        $("#description").val(input_description);
    });
    $(".select2").select2();

    $('.js-example-data-ajax').on('select2:select', function (e) {
        var data = e.params.data;
        var discount = 0;
        var taxType = 0;
        var taxRate = 0;
        var taxTypeRate = 0
        var pr_tax_val = 0;
        var pr_tax_rate = 0;
        var showTaxTypeRate = 'No Tax';
        var order_subtotal = data.purchase_price;
        var order_net_purchase = data.purchase_price;
        if(data.tax_id != null) {
            taxType = data.tax.type;
            taxRate = data.tax.rate;
            if (taxRate !== null && taxRate != 0) {
                if (taxType == "inclusive") {
                    pr_tax_val = Number((((order_net_purchase) * parseFloat(taxRate)) / (100 + parseFloat(taxRate))), 4).toFixed(2);
                    pr_tax_rate = Number(taxRate).toFixed(2) + '%';
                    order_net_purchase -= pr_tax_val;
                } else if (taxType == "exclusive") {
                    pr_tax_val = Number((((order_net_purchase) * parseFloat(taxRate)) / 100), 4).toFixed(2);
                    pr_tax_rate = Number(taxRate).toFixed(2) + '%';
                } else {
                    pr_tax_val = parseFloat(taxRate);
                    pr_tax_rate = taxRate;
                }
            }
            // order_subtotal = Number(order_net_purchase) + Number(pr_tax_val);
            order_subtotal = Number(order_net_purchase).toFixed(2);

            taxTypeRate = taxType+"_"+taxRate;
            showTaxTypeRate = taxRate+"%"+" "+capitalizeFirstLetter(taxType);
        }


        $("#table-combo").append('<tr id="'+ data.id +'" class="table-info"><th scope="row"><input type="hidden" class="order_row_id" value="'+data.id+'" name="product[order_row_id][]"><input type="hidden" class="order_name" value="'+data.name+'" name="product[order_name][]">' + data.name + '</th><td><input type="number" step="any" class="form-control order_quantity" min="1" value="1" name="product[order_quantity][]"></td><td><input type="hidden" class="order_price" value="'+data.purchase_price+'" name="product[order_price][]"><span>'+data.purchase_price+'</span></td><td><input type="hidden" class="order_tax_type_rate" value="'+taxTypeRate+'" name="product[order_tax_type_rate][]"><input type="hidden" class="order_product_tax" value="'+pr_tax_val+'" name="product[order_product_tax][]"><span class="order_product_tax_text">'+showTaxTypeRate+'</span></td><td><input type="hidden" class="order_subtotal" value="'+order_subtotal+'" name="product[order_subtotal][]"><span class="order_subtotal_text">'+order_subtotal+'</span></td><td><a href="javascript:void(0)" class="btn btn-info btn-outline table-remove" data-toggle="modal" data-target="#myModal" title="Delete"><span class="mdi mdi-delete ambitious-padding-btn"></span></a></td></tr>')

        /*** Start Total Product ***/
        var tbProductTax = $("input[name='product[order_product_tax][]']").map(function(){return $(this).val();}).get();
        var tbTotalProductTax=0;
        for(var i in tbProductTax) {
            tbTotalProductTax += Number(tbProductTax[i]);
        }
        $('.total_tax').val(tbTotalProductTax.toFixed(2));
        /*** End Total Product ***/

        /*** Start Total ***/
        var tbSubTotal = $("input[name='product[order_subtotal][]']").map(function(){return $(this).val();}).get();
        var tbTotalSubTotal=0;
        for(var i in tbSubTotal) {
            tbTotalSubTotal += Number(tbSubTotal[i]);
        }
        $('.sub_total').val(tbTotalSubTotal.toFixed(2));
        /*** End Total ***/

        /*** Start Grand Total ***/
        let mydiscount = $('.total_discount').val();
        mydiscount = (!mydiscount.length || isNaN(mydiscount)) ? 0 : parseFloat(mydiscount);

        let mytax = $('.total_tax').val();
        mytax = (!mytax.length || isNaN(mytax)) ? 0 : parseFloat(mytax);

        grand_total = tbTotalSubTotal - mydiscount;
        grand_total += mytax;
        $('.grand_total').val(grand_total.toFixed(2));
        /*** End Grand Total ***/

        // push
        item_array.push(data.id);
        // blank
        $('.js-example-data-ajax').val(null).trigger('change');
        // array to string
        var b = item_array.toString();
        var c = b;
        // comma replace to underscore
        window.d = c.replace(/,/g, '_');
    });

    $(document).on("focus", '.order_quantity', function () {
        old_row_qty = $(this).val();
    }).on('change keyup', '.order_quantity', function () {
        var row = $(this).closest('tr');
        var pr_tax_val = 0;
        var prs_tax_val = 0;
        if (!Number($(this).val()) || parseFloat($(this).val()) < 0) {
            $(this).val(old_row_qty);
            Swal.fire({
                icon: 'warning',
                title: 'Warning !',
                text: 'Unexpected value provided!',
            });
            row.children().children('.order_quantity').val(old_row_qty);
            return;
        }
        var new_order_subtotal = 0;
        var new_qty = parseFloat($(this).val());
        var item_id = row.attr('id');
        var order_price = row.children().children('.order_price').val();
        var order_tax_type_rate = row.children().children('.order_tax_type_rate').val();
        var order_price_number = Number(order_price);
        var new_qty_number = Number(new_qty);
        if(order_tax_type_rate != '0'){
            var strData = order_tax_type_rate.split("_");
            var taxType = strData[0];
            var taxRate = strData[1];
            if (taxRate !== null && taxRate != 0) {
                if (taxType == "inclusive") {
                    pr_tax_val = Number((((order_price) * parseFloat(taxRate)) / (100 + parseFloat(taxRate))), 4).toFixed(2);
                    prs_tax_val = Number(pr_tax_val*new_qty_number).toFixed(2);
                    new_order_subtotal = new_qty_number * order_price_number-prs_tax_val;
                } else {
                    pr_tax_val = Number((((order_price) * parseFloat(taxRate)) / 100), 4).toFixed(2);
                    prs_tax_val = Number(pr_tax_val*new_qty_number).toFixed(2);
                    new_order_subtotal = new_qty_number * order_price_number;
                }
            }
        } else {
            new_order_subtotal = new_qty_number * order_price_number;
        }
        row.children().children('.order_subtotal').val(new_order_subtotal);
        row.children().children('.order_subtotal_text').text(new_order_subtotal);
        row.children().children('.order_product_tax').val(prs_tax_val);

        /*** Start Total Sub Total ***/
        var tbSubTotal = $("input[name='product[order_subtotal][]']").map(function(){return $(this).val();}).get();
        var tbTotalSubTotal=0;
        for(var i in tbSubTotal) {
            tbTotalSubTotal += Number(tbSubTotal[i]);
        }
        $('.sub_total').val(tbTotalSubTotal.toFixed(2));
        /*** End Total Sub Total***/

        /*** Start Total Product Tax***/
        var tbProductTax = $("input[name='product[order_product_tax][]']").map(function(){return $(this).val();}).get();
        var tbTotalProductTax=0;
        for(var i in tbProductTax) {
            tbTotalProductTax += Number(tbProductTax[i]);
        }
        $('.total_tax').val(tbTotalProductTax.toFixed(2));
        /*** End Total Product Tax***/

        /*** Start Grand Total ***/
        let mydiscount = $('.total_discount').val();
        mydiscount = (!mydiscount.length || isNaN(mydiscount)) ? 0 : parseFloat(mydiscount);

        let mytax = tbTotalProductTax.toFixed(2);
        mytax = (!mytax.length || isNaN(mytax)) ? 0 : parseFloat(mytax);

        grand_total = tbTotalSubTotal - mydiscount;
        grand_total += mytax;
        $('.grand_total').val(grand_total.toFixed(2));
        /*** End Grand Total ***/
    });

    // tr remove item
    $("#table-combo").on('click', '.table-remove', function () {
        var row = $(this).closest('tr').remove();
        window.item_array = [];
        var tbRowId = $("input[name='product[order_row_id][]']").map(function(){return $(this).val();}).get();
        window.item_array.push(tbRowId);
        var b = tbRowId.toString();
        var c = b;
        window.d = c.replace(/,/g, '_');
        /*** Start Total ***/
        var tbSubTotal = $("input[name='product[order_subtotal][]']").map(function(){return $(this).val();}).get();
        var tbTotalSubTotal=0;
        for(var i in tbSubTotal) {
            tbTotalSubTotal += Number(tbSubTotal[i]);
        }
        $('.sub_total').val(tbTotalSubTotal.toFixed(2));
        /*** End Total ***/
        /*** Start Total Product ***/
        var tbProductTax = $("input[name='product[order_product_tax][]']").map(function(){return $(this).val();}).get();
        var tbTotalProductTax=0;
        for(var i in tbProductTax) {
            tbTotalProductTax += Number(tbProductTax[i]);
        }
        $('.total_tax').val(tbTotalProductTax.toFixed(2));
        /*** End Total Product ***/
        /*** Start Grand Total ***/
        let mydiscount = $('.total_discount').val();
        mydiscount = (!mydiscount.length || isNaN(mydiscount)) ? 0 : parseFloat(mydiscount);

        let mytax = tbTotalProductTax.toFixed(2);
        mytax = (!mytax.length || isNaN(mytax)) ? 0 : parseFloat(mytax);

        grand_total = tbTotalSubTotal - mydiscount;
        grand_total += mytax;
        $('.grand_total').val(grand_total.toFixed(2));
        /*** End Grand Total ***/
    });

    function capitalizeFirstLetter(string){
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    $(document).on('change keyup', '.total_discount', function () {
        calculateDiscount();
    });

    function calculateDiscount() {
        let total_discount = $('.total_discount').val();
        calculateTax();
    }

    function calculateTax() {
        let discount = $('.total_discount').val();
        discount = (!discount.length || isNaN(discount)) ? 0 : parseFloat(discount);

        let tax = $('.total_tax').val();
        tax = (!tax.length || isNaN(tax)) ? 0 : parseFloat(tax);

        var tbSubTotal = $("input[name='product[order_subtotal][]']").map(function(){return $(this).val();}).get();
        var total=0;
        for(var i in tbSubTotal) {
            total += Number(tbSubTotal[i]);
        }

        total = parseFloat(total.toFixed(2));
        grand_total = total - discount;
        grand_total += tax;
        $('.grand_total').val(grand_total.toFixed(2));
    }
</script>
<script type="text/javascript" class="js-code-placeholder">

    $(".js-example-data-ajax").select2({
        ajax: {
            url: "/getBillItems",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term,
                    item_array: d,
                    page: params.page
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
                return {
                    results: data,
                    pagination: {
                        more: (params.page * 30) < data.total_count
                    }
                };
            },
            cache: true
        },
        placeholder: '@lang('Search Your Item')',
        minimumInputLength: 1,
        templateResult: formatRepo,
        templateSelection: formatRepoSelection
    });

    function formatRepo (repo) {
        if (repo.loading) {
            return repo.text;
        }
        var $container = $(
            "<div class='select2-result-repository clearfix'>" +
            "<div class='select2-result-repository__meta'>" +
            "<div class='select2-result-repository__title'></div>" +
            "</div>" +
            "</div>" +
            "</div>"
        );
        $container.find(".select2-result-repository__title").text(repo.name);
        return $container;
    }

    function formatRepoSelection (repo) {
        return repo.name || repo.sku;
    }

    $(".today-flatpickr").flatpickr({
        enableTime: false,
        defaultDate: "today"
    });

    $(".flatpickr").flatpickr({
        enableTime: false
    });

    $(document).ready(function(){
        $(window).scrollTop(0);
    });

    </script>
