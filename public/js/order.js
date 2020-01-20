$('#same_address_check').click(function() {
    if ($(this).is(':checked')) {
        $('#customer_delivery_address').val($('#customer_address').val());
        $('#customer_delivery_address').prop('readonly', true);
    }else{
        $('#customer_delivery_address').prop('readonly', false);
    }
});

$('#customer-select').on('changed.bs.select', function (e) {
    $('#customer_name').val($(this).find("option:selected").attr('customer_name'));
    $('#customer_phone').val($(this).find("option:selected").attr('customer_phone'));
    $('#customer_address').val($(this).find("option:selected").attr('customer_address'));
    $('#customer_delivery_address').val($(this).find("option:selected").attr('customer_delivery_address'));
    $('#customer_remark').val($(this).find("option:selected").attr('customer_remark'));
    $('#country-select').selectpicker('val',$(this).find("option:selected").attr('customer_country_name'));
});

$(document).on('change', '.category-select', function(e) {
    var category_id = $(this).find("option:selected").attr('category_id');
    var product_select =  $(this).closest('div.col-sm-5').next().find('select.product-select');
    getProductItems($selectProductOption,category_id,product_select)
});

$(document).on('change', '.sub-category-select', function(e) {
    var sub_category_id = $(this).find("option:selected").attr('category_id');
    var sub_product_select =  $(this).closest('div.col-sm-5').next().find('select.sub-product-select');
    getProductItems($selectProductOption,sub_category_id,sub_product_select)
});

function getProductItems(desc,category_id,product_select){
    $.ajax({
        url: "/admin/product/category/"+category_id,
        type: "GET",
        success: function(result){
            product_select.empty().append('<option value="" selected disabled hidden>'+desc+'</option>');
            $.each(result['result'], function( index, value ) {
                product_select.append('<option value="'+result['result'][index]['id']+'" product_id="'+result['result'][index]['id']+'" product_name="'+result['result'][index]['name']+'" product_model_no="'+result['result'][index]['model_no']+'" product_price="'+result['result'][index]['price']+'" data-subtext="'+result['result'][index]['name']+'">'+result['result'][index]['name']+'</option>');
            });

            // Refresh the selectpicker
            product_select.selectpicker("refresh");
        }});
}

$(document).on('change', '.product-select', function (e) {
    input_element = '';
    for (j = 0; j < 4; j++) {
        if (j == 0) {
            input_element = $(this).closest('div.form-group').next("div").find('input:text').first();
        } else {
            input_element = input_element.closest('div.col-sm-4').next("div").find('input').first();
        }

        input_element.val($(this).find("option:selected").attr(input_element.attr("name").substr(0, input_element.attr("name").length - 2)));
        if (j == 3) {
            $(this).closest('div.form-group').next("div").find("div.form-group").first().next("div").find("div.col-sm-6").next().find('input').first().val($(this).find("option:selected").attr(input_element.attr("name").substr(0, input_element.attr("name").length - 2)));
            $('#sum_total_price').text('$ ' + sum_price());
            $('#order_total_price').val(sum_price());
        }
    }
});

$(document).on('change', '.sub-product-select', function (e) {
    input_element = '';
    for (k = 0; k < 4; k++) {
        if (k == 0) {
            input_element = $(this).closest('div.form-group').next("div").find('input:text').first();
        } else if (k == 3) {
            input_element = input_element.closest('div.col-sm-3').next("div").find('input').first();
        } else {
            input_element = input_element.closest('div.col-sm-4').next("div").find('input').first();
        }

        input_element.val($(this).find("option:selected").attr(input_element.attr("name").substr(4, input_element.attr("name").length - 6)));
        if (k == 3) {
            input_element.val(0);
            //$(this).closest('div.form-group').next("div").find("div.form-group").first().next("div").find("div.col-5").next().find('input').first().val($(this).find("option:selected").attr(input_element.attr("name").substr(4, input_element.attr("name").length - 6)));//
            $(this).closest('div.form-group').next("div").find("div.form-group").first().next("div").find("div.col-5").next().find('input').first().val(0);
            $('#sum_total_price').text('$ ' + sum_price());
            $('#order_total_price').val(sum_price());
        }
    }
});

$(document).on('keypress keyup blur', ".product-price", function (event) {
//            $(this).val($(this).val().replace(/[^\d].+/, ""));
    if ((event.which < 45 || event.which > 57)) {
        event.preventDefault();
    }
    if ($(this).attr('id').indexOf('product_quantity') != "-1") {
        price = $(this).closest('div.form-group').prev().find('div').last().find('input').first().val();
        quantity = $(this).val().replace(/[^\d].+/, "")
        $(this).closest('div').next("div").find('input').first().val(price * quantity);
    }

    if ($(this).attr('id').indexOf('product_price') != "-1") {
        quantity = $(this).closest('div.form-group').next().find('div').find('input').first().val();
        price = $(this).val().replace(/[^\d].+/, "")
        $(this).closest('div.form-group').next().find('div').last().find('input').first().val(price * quantity);
    }
    $('#sum_total_price').text('$ ' + sum_price());
    $('#order_total_price').val(sum_price());
});

$(document).on('keypress keyup blur', ".total-price", function (event) {
//            $(this).val($(this).val().replace(/[^\d].+/, ""));
    if ((event.which < 45 || event.which > 57)) {
        event.preventDefault();
    }

    $('#sum_total_price').text('$ ' + sum_price());
    $('#order_total_price').val(sum_price());
});

$(document).on('click', '.minus_sub_product', function (e) {
    $(this).closest('div.form-group').parent().next().remove();
    $(this).closest('div.form-group').parent().next().remove();
    $(this).attr('value', parseInt($(this).attr('value')) - 1);
    if ($(this).attr('value') == 0) {
        $(this).hide();
    }else if(typeof $(this).closest('div.form-group').parent().next().find('div.col-sm-2').attr('class') === "undefined"){
        $(this).hide();
    }
    $('#sum_total_price').text('$ ' + sum_price());
    $('#order_total_price').val(sum_price());
});

$(document).on('click', '.minus_product', function (e) {
    var countSubProduct = $(this).closest('div.form-group').next('div').find('div.col-sm-2').find('#minus_sub_product').attr('value');

    while(countSubProduct != 0 && countSubProduct > 0 ){
        $(this).closest('div.form-group').next('div').next('div').remove();
        $(this).closest('div.form-group').next('div').next('div').remove();
        countSubProduct--;
    }
    $(this).closest('div.form-group').next('div').remove();
    $(this).closest('div.form-group').remove();

    if($('.minus_product').length == 1){
        $('#minus_product').hide();
    }

    $('#sum_total_price').text('$ ' + sum_price());
    $('#order_total_price').val(sum_price());
});

$(document).on('click', '.insert_sub_product', function (e) {
    $(this).next('a').show();
    $(this).next('a').attr('value', parseInt($(this).next('a').attr('value')) + 1);

    var add_sub_product = $('#add_sub_product').clone().attr('id', "add_sub_product" + x);
    add_sub_product.removeClass('non-display');

    var product_id = $(this).closest('div.form-group').prev().prev().find('div').first().find('input').last().val()

    add_sub_product.find('#ui_sub_product_id').attr('value', product_id);
    add_sub_product.find('#sub_product_name').next().attr('for', 'sub_product_name' + x);
    add_sub_product.find('#sub_product_name').attr('id', 'sub_product_name' + x);

    add_sub_product.find('#sub_product_model_no').next().attr('for', 'sub_product_model_no' + x);
    add_sub_product.find('#sub_product_model_no').attr('id', 'sub_product_model_no' + x);

    add_sub_product.find('#sub_product_price').next().attr('for', 'sub_product_price' + x);
    add_sub_product.find('#sub_product_price').attr('id', 'sub_product_price' + x);

    add_sub_product.find('#sub_product_id').next().attr('for', 'sub_producty_id' + x);
    add_sub_product.find('#sub_product_id').attr('id', 'sub_product_id' + x);

    add_sub_product.find('#sub_product_quantity').next().attr('for', 'sub_product_quantity' + x);
    add_sub_product.find('#sub_product_quantity').attr('id', 'sub_product_quantity' + x);

    add_sub_product.find('#sub_product_total_price').next().attr('for', 'sub_product_total_price' + x);
    add_sub_product.find('#sub_product_total_price').attr('id', 'sub_product_total_price' + x);

    add_sub_product.find('#sub_product_serial_no').next().attr('for', 'sub_product_serial_no' + x);
    add_sub_product.find('#sub_product_serial_no').attr('id', 'sub_product_serial_no' + x);

    add_sub_product.find('#sub_product_remark').next().attr('for', 'sub_product_remark' + x);
    add_sub_product.find('#sub_product_remark').attr('id', 'sub_product_remark' + x);
    x++;

    $(this).closest('div.add_product').after(add_sub_product);

    var ProductClone = $("#product-select").clone().attr('id', 'sub-product-select').addClass('sub-product-select').removeClass('product-select').prop('required', true);
    var CategoryClone = $("#category-select").clone().attr('id', 'sub-category-select').addClass('sub-category-select').removeClass('category-select').prop('required', true);
    ProductClone = ProductClone.empty().append('<option value="" selected disabled hidden>'+$selectProductOption+'</option>');

    var formDiv = document.createElement('div');
    formDiv.className = 'form-group row';
    var innerDiv = document.createElement('div');
    innerDiv.className = 'col-sm-2 mb-3 mb-sm-0';
    formDiv.appendChild(innerDiv);

    var secondInnerDiv = document.createElement('div');
    secondInnerDiv.className = 'col-sm-5 mb-3 mb-sm-0';
    formDiv.appendChild(secondInnerDiv);

    var thirdInnerDiv = document.createElement('div');
    thirdInnerDiv.className = 'col-sm-5 mb-3 mb-sm-0';
    formDiv.appendChild(thirdInnerDiv);

    $(this).closest('div.add_product').after(formDiv);
    $(this).closest('div.add_product').next('div').find('.col-sm-5').first().append(CategoryClone);
    $(this).closest('div.add_product').next('div').find('.col-sm-5').last().append(ProductClone);

    CategoryClone.val('');
    ProductClone.val('');

    $('.sub-product-select').selectpicker("render");
    $('.sub-category-select').selectpicker("render");
});

$("#add").click(function () {
    $('.minus_product').show();
    var add_product = $('#add_new_product').clone().attr('id', "add_new_product" + i);
    add_product.removeClass('non-display');

    add_product.find('#minus_sub_product').hide();
    add_product.find('#ui_product_id').attr('value', i + 1);

    add_product.find('#product_name').val('');
    add_product.find('#product_name').next().attr('for', 'product_name' + i);
    add_product.find('#product_name').attr('id', 'product_name' + i);

    add_product.find('#product_model_no').val('');
    add_product.find('#product_model_no').next().attr('for', 'product_model_no' + i);
    add_product.find('#product_model_no').attr('id', 'product_model_no' + i);

    add_product.find('#product_price').val('');
    add_product.find('#product_price').next().attr('for', 'product_price' + i);
    add_product.find('#product_price').attr('id', 'product_price' + i);

    add_product.find('#product_id').val('');
    add_product.find('#product_id').next().attr('for', 'product_id' + i);
    add_product.find('#product_id').attr('id', 'product_id' + i);

    add_product.find('#product_quantity').val('1');
    add_product.find('#product_quantity').next().attr('for', 'product_quantity' + i);
    add_product.find('#product_quantity').attr('id', 'product_quantity' + i);


    add_product.find('#product_total_price').val('');
    add_product.find('#product_total_price').next().attr('for', 'product_total_price' + i);
    add_product.find('#product_total_price').attr('id', 'product_total_price' + i);

    add_product.find('#product_serial_no').val('');
    add_product.find('#product_serial_no').next().attr('for', 'product_serial_no' + i);
    add_product.find('#product_serial_no').attr('id', 'product_serial_no' + i);

    add_product.find('#product_remark').val('');
    add_product.find('#product_remark').next().attr('for', 'product_remark' + i);
    add_product.find('#product_remark').attr('id', 'product_remark' + i);

    add_product.find('#order_productsid').attr('value', '');

    add_product.find('#minus_sub_product').attr('value','0');

    add_product.insertAfter('#add');

    var MinusProduct = $("#minus_product").clone();
    var CategoryClone = $("#category-select").clone();
    var ProductClone = $("#product-select").clone();
    ProductClone = ProductClone.empty().append('<option value="" selected disabled hidden>'+$selectProductOption+'</option>');

    var formDiv = document.createElement('div');
    formDiv.className = 'form-group row';
    var innerDiv1 = document.createElement('div');
    innerDiv1.className = 'col-sm-5 mb-3 mb-sm-0';
    formDiv.appendChild(innerDiv1);

    var innerDiv2 = document.createElement('div');
    innerDiv2.className = 'col-sm-5 mb-3 mb-sm-0';
    formDiv.appendChild(innerDiv2);

    var innerDiv3 = document.createElement('div');
    innerDiv3.className = 'col-sm-2 mb-3 mb-sm-0';
    formDiv.appendChild(innerDiv3);

    $('#add').after(formDiv);
    $('#add').next('div').find('.col-sm-5').first().append(CategoryClone);
    $('#add').next('div').find('.col-sm-5').last().append(ProductClone);
    $('#add').next('div').find('.col-sm-2').first().append(MinusProduct);

    CategoryClone.val('');
    ProductClone.val('');
    $('.category-select').selectpicker("render");
    $('.product-select').selectpicker("render");

    i = i + 1;
});

$('#currency-select').on('changed.bs.select', function (e) {
    $('#currency_ratio').val($(this).find("option:selected").attr('currency_ratio'));
});

$('#reset_customer').click(function() {
    $("#customer_id").val('');
    $("#customer-select").val('default');
    $("#customer-select").selectpicker("refresh");
});

$('#invoice_date').datepicker({
    format: 'yyyy-mm-dd'
});

if (!order_date) {
    $('#invoice_date').datepicker('setDate', new Date());
}

$( "#invoice_number" ).keypress(function(event) {
    return validateNumber(event);
});

$("#invoice_date").keypress(function(event) {
    return false;
});

function sum_price() {
    var sum = 0;
    $('.total-price').each(function () {
        sum += Number($(this).val());
    });
    return sum.toFixed(2);
}

function validateNumber(event) {
    var key = window.event ? event.keyCode : event.which;
    if (event.keyCode === 8 || event.keyCode === 46) {
        return true;
    } else if ( key < 48 || key > 57 ) {
        return false;
    } else {
        return true;
    }
}

function currencyFormat(n, currency,decimal) {
    return currency + n.toFixed(decimal ).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
}