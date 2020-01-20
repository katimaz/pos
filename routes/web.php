<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::fallback(function(){
    return view('errors.404');
});

Auth::routes();

Route::get('/', 'AdminController@index');

Route::get('/{locale}', 'HomeController@locale');

Route::prefix('admin')->group(function () {
    Route::get('/dashboard', 'AdminController@index')->name('dashboard');

    Route::get('/category', 'CategoryController@index')->name('category')->middleware('can:hasCategoryAccess,App\User');
    Route::get('/category/add', 'CategoryController@add')->name('category.add');
    Route::post('/category/create', 'CategoryController@create')->name('category.create');
    Route::get('/category/edit/{id}', 'CategoryController@edit')->name('category.edit');
    Route::post('/category/update', 'CategoryController@update')->name('category.update');
    Route::post('/category/destroy', 'CategoryController@destroy')->name('category.destroy');

    Route::get('/customer', 'CustomerController@index')->name('customer')->middleware('can:hasCustomerAccess,App\User');
    Route::get('/customer/add', 'CustomerController@add')->name('customer.add');
    Route::post('/customer/create', 'CustomerController@create')->name('customer.create');
    Route::get('/customer/edit/{id}', 'CustomerController@edit')->name('customer.edit');
    Route::post('/customer/update', 'CustomerController@update')->name('customer.update');
    Route::post('/customer/destroy', 'CustomerController@destroy')->name('customer.destroy');

    Route::get('/product', 'ProductController@index')->name('product')->middleware('can:hasProductAccess,App\User');
    Route::get('/product/add', 'ProductController@add')->name('product.add');
    Route::post('/product/create', 'ProductController@create')->name('product.create');
    Route::get('/product/edit/{id}', 'ProductController@edit')->name('product.edit');
    Route::post('/product/update', 'ProductController@update')->name('product.update');
    Route::post('/product/destroy', 'ProductController@destroy')->name('product.destroy');
    Route::get('/product/category/{id}', 'ProductController@getProductByCategoryId')->name('product.getProductByCategoryId');

    Route::post('/product/addDetails', 'ProductController@addDetails')->name('product.addDetails');
    Route::get('/product/getDetails', 'ProductController@getDetails')->name('product.getDetails');
    Route::post('/product/editDetails', 'ProductController@editDetails')->name('product.editDetails');
    Route::post('/product/deleteDetails', 'ProductController@deleteDetails')->name('product.deleteDetails');
    Route::get('/product/getProductDetails', 'ProductController@getProductDetails')->name('product.getProductDetails');

    Route::get('/order', 'OrderController@index')->name('order')->middleware('can:hasOrderAccess,App\User');;
    Route::get('/order/add', 'OrderController@add')->name('order.add');
    Route::post('/order/create', 'OrderController@create')->name('order.create');
    Route::get('/order/edit/{id}', 'OrderController@edit')->name('order.edit');
    Route::post('/order/update', 'OrderController@update')->name('order.update');
    Route::post('/order/destroy', 'OrderController@destroy')->name('order.destroy');

    Route::get('/order/template/{id}', 'OrderController@template')->name('order.template');

    Route::get('/template', 'TemplateController@index')->name('template')->middleware('can:hasTemplateAccess,App\User');;
    Route::get('/template/add', 'TemplateController@add')->name('template.add');
    Route::post('/template/create', 'TemplateController@create')->name('template.create');
    Route::get('/template/edit/{id}', 'TemplateController@edit')->name('template.edit');
    Route::post('/template/update', 'TemplateController@update')->name('template.update');
    Route::post('/template/destroy', 'TemplateController@destroy')->name('template.destroy');

    Route::get('/export/{id}', 'ExcelController@index')->name('export');
    Route::get('/devicesales', 'ExcelController@exportDeviceSalesPage')->name('devicesales');
    Route::get('/sales', 'ExcelController@exportSalesPage')->name('sales');
    Route::get('/exportDeviceSaleList', 'ExcelController@exportDeviceSale')->name('exportDeviceSale');
    Route::get('/exportSaleList', 'ExcelController@exportSales')->name('exportSales');

    Route::get('/country', 'CountryController@index')->name('country')->middleware('can:hasCountryAccess,App\User');
    Route::get('/country/add', 'CountryController@add')->name('country.add');
    Route::post('/country/create', 'CountryController@create')->name('country.create');
    Route::get('/country/edit/{id}', 'CountryController@edit')->name('country.edit');
    Route::post('/country/update', 'CountryController@update')->name('country.update');
    Route::post('/country/destroy', 'CountryController@destroy')->name('country.destroy');

    Route::get('/currency', 'CurrencyController@index')->name('currency')->middleware('can:hasCurrencyAccess,App\User');
    Route::get('/currency/add', 'CurrencyController@add')->name('currency.add');
    Route::post('/currency/create', 'CurrencyController@create')->name('currency.create');
    Route::get('/currency/edit/{id}', 'CurrencyController@edit')->name('currency.edit');
    Route::post('/currency/update', 'CurrencyController@update')->name('currency.update');
    Route::post('/currency/destroy', 'CurrencyController@destroy')->name('currency.destroy');

    Route::get('/ordertype', 'OrderTypeController@index')->name('ordertype')->middleware('can:hasOrderTypeAccess,App\User');;
    Route::get('/ordertype/add', 'OrderTypeController@add')->name('ordertype.add');
    Route::post('/ordertype/create', 'OrderTypeController@create')->name('ordertype.create');
    Route::get('/ordertype/edit/{id}', 'OrderTypeController@edit')->name('ordertype.edit');
    Route::post('/ordertype/update', 'OrderTypeController@update')->name('ordertype.update');
    Route::post('/ordertype/destroy', 'OrderTypeController@destroy')->name('ordertype.destroy');

    Route::get('/user', 'UserController@index')->name('user')->middleware('can:hasUserAccess,App\User');;
    Route::get('/user/add', 'UserController@add')->name('user.add');
    Route::post('/user/create', 'UserController@create')->name('user.create');
    Route::get('/user/edit/{id}', 'UserController@edit')->name('user.edit');
    Route::post('/user/update', 'UserController@update')->name('user.update');
    Route::post('/user/destroy', 'UserController@destroy')->name('user.destroy');

    Route::get('/role', 'RoleController@index')->name('role')->middleware('can:hasRoleAccess,App\User');;
    Route::get('/role/add', 'RoleController@add')->name('role.add');
    Route::post('/role/create', 'RoleController@create')->name('role.create');
    Route::get('/role/edit/{id}', 'RoleController@edit')->name('role.edit');
    Route::post('/role/update', 'RoleController@update')->name('role.update');
    Route::post('/role/destroy', 'RoleController@destroy')->name('role.destroy');

    Route::get('getdata','AdminController@getdata')->name('getData');
    Route::get('getmonthdata','AdminController@getMonthdata')->name('getMonthData');

});