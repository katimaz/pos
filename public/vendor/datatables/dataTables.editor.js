/*! Datatables altEditor 1.0
 */

/**
 * @summary     altEditor
 * @description Lightweight editor for DataTables
 * @version     1.0
 * @file        dataTables.editor.lite.js
 * @author      kingkode (www.kingkode.com)
 * @contact     www.kingkode.com/contact
 * @copyright   Copyright 2016 Kingkode
 *
 * This source file is free software, available under the following license:
 *   MIT license - http://datatables.net/license/mit
 *
 * This source file is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 * or FITNESS FOR A PARTICULAR PURPOSE. See the license files for details.
 *
 * For details please refer to: http://www.kingkode.com
 */

(function( factory ){
    if ( typeof define === 'function' && define.amd ) {
        // AMD
        define( ['jquery', 'datatables.net'], function ( $ ) {
            return factory( $, window, document );
        } );
    }
    else if ( typeof exports === 'object' ) {
        // CommonJS
        module.exports = function (root, $) {
            if ( ! root ) {
                root = window;
            }

            if ( ! $ || ! $.fn.dataTable ) {
                $ = require('datatables.net')(root, $).$;
            }

            return factory( $, root, root.document );
        };
    }
    else {
        // Browser
        factory( jQuery, window, document );
    }
}(function( $, window, document, undefined ) {
   'use strict';
   var DataTable = $.fn.dataTable;

   var _instance = 0;
   var _timeout = 600;
   var _pathArray = window.location.pathname.split('/');
   var _id = _pathArray[_pathArray.length-1];

   /** 
    * altEditor provides modal editing of records for Datatables
    *
    * @class altEditor
    * @constructor
    * @param {object} oTD DataTables settings object
    * @param {object} oConfig Configuration object for altEditor
    */
   var altEditor = function( dt, opts )
   {
       if ( ! DataTable.versionCheck || ! DataTable.versionCheck( '1.10.8' ) ) {
           throw( "Warning: altEditor requires DataTables 1.10.8 or greater");
       }

       // User and defaults configuration object
       this.c = $.extend( true, {},
           DataTable.defaults.altEditor,
           altEditor.defaults,
           opts
       );

       /**
        * @namespace Settings object which contains customisable information for altEditor instance
        */
       this.s = {
           /** @type {DataTable.Api} DataTables' API instance */
           dt: new DataTable.Api( dt ),

           /** @type {String} Unique namespace for events attached to the document */
           namespace: '.altEditor'+(_instance++)
       };


       /**
        * @namespace Common and useful DOM elements for the class instance
        */
       this.dom = {
           /** @type {jQuery} altEditor handle */
           modal: $('<div class="dt-altEditor-handle"/>'),
       };


       /* Constructor logic */
       this._constructor();
   }



   $.extend( altEditor.prototype, {
       /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
        * Constructor
        * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

       /**
        * Initialise the RowReorder instance
        *
        * @private
        */
       _constructor: function ()
       {
           // console.log('altEditor Enabled')
           var that = this;
           var dt = this.s.dt;
           
           this._setup();

           dt.on( 'destroy.altEditor', function () {
               dt.off( '.altEditor' );
               $(dt.table().body()).off( that.s.namespace );
               $(document.body).off( that.s.namespace );
           } );
       },

       /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
        * Private methods
        * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

       /**
        * Setup dom and bind button actions
        *
        * @private
        */
       _setup: function()
       {
         // console.log('Setup');

         var that = this;
         var dt = this.s.dt;

         // add modal
         $('body').append('\
            <div class="modal fade" id="altEditor-modal" tabindex="-1" role="dialog">\
              <div class="modal-dialog">\
                <div class="modal-content">\
                  <div class="modal-header">\
                    <h4 class="modal-title"></h4>\
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>\
                  </div>\
                  <div class="modal-body">\
                    <p></p>\
                  </div>\
                  <div class="modal-footer">\
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>\
                    <button type="button" class="btn btn-primary">Save changes</button>\
                  </div>\
                </div>\
              </div>\
            </div>'
         );


         // add Edit Button
         if( this.s.dt.button('edit:name') )
         {
            this.s.dt.button('edit:name').action( function(e, dt, node, config) {
              var rows = dt.rows({
                selected: true
              }).count();

              that._openEditModal();
            });

            $(document).on('click', '#editRowBtn', function(e)
            {
              e.preventDefault();
              e.stopPropagation();
              that._editRowData();
            });

          }

         // add Delete Button
         if( this.s.dt.button('delete:name') )
         {
            this.s.dt.button('delete:name').action( function(e, dt, node, config) {
              var rows = dt.rows({
                selected: true
              }).count();

              that._openDeleteModal();
            });

            $(document).on('click', '#deleteRowBtn', function(e)
            {
              e.preventDefault();
              e.stopPropagation();
              that._deleteRow();
            });
         }

         // add Add Button
         if( this.s.dt.button('add:name') )
         {
            this.s.dt.button('add:name').action( function(e, dt, node, config) {
              var rows = dt.rows({
                selected: true
              }).count();

              that._openAddModal();
            });

            $(document).on('click', '#addRowBtn', function(e)
            {
              e.preventDefault();
              e.stopPropagation();
              that._addRowData();
            });
         }

       },

       /**
        * Emit an event on the DataTable for listeners
        *
        * @param  {string} name Event name
        * @param  {array} args Event arguments
        * @private
        */
       _emitEvent: function ( name, args )
       {
           this.s.dt.iterator( 'table', function ( ctx, i ) {
               $(ctx.nTable).triggerHandler( name+'.dt', args );
           } );
       },

       /**
        * Open Edit Modal for selected row
        * 
        * @private
        */
       _openEditModal: function ( )
       {
         var that = this;
         var dt = this.s.dt;

         var columnDefs = [];

         for( var i = 0; i < dt.context[0].aoColumns.length; i++ )
         {
            columnDefs.push({ title: dt.context[0].aoColumns[i].sTitle,
                id: dt.context[0].aoColumns[i].id
            })
         }

          var adata = dt.rows({
            selected: true
          });

          var data = "";

          data += "<form id='altEditor-form' name='altEditor-form' role='form'>";

          for (var j in columnDefs) {
              if(j==0){
                  data += "<div class='form-group row' style='display: none;'><div class='col-12'><input type='text'  id='" + columnDefs[j].id + "' name='" + columnDefs[j].id + "' placeholder='" + columnDefs[j].title + "' class='input-material form-control' value='" + adata.data()[0][j] + "'><label for='" + columnDefs[j].id + "' class='input-label'>" + columnDefs[j].title + ":</label></div></div>";
              }else{
                  data += "<div class='form-group row'><div class='col-12'><input type='text'  id='" + columnDefs[j].id + "' name='" + columnDefs[j].id + "' placeholder='" + columnDefs[j].title + "' class='input-material form-control' value='" + adata.data()[0][j] + "'><label for='" + columnDefs[j].id + "' class='input-label'>" + columnDefs[j].title + ":</label></div></div>";
              }
          }
          data += "</form>";


          $('#altEditor-modal').on('show.bs.modal', function() {
            $('#altEditor-modal').find('.modal-title').html('Edit Record');
            $('#altEditor-modal').find('.modal-body').html('' + data + '');
            $('#altEditor-modal').find('.modal-footer').html("<button type='button' data-content='remove' class='btn btn-default' data-dismiss='modal'>Close</button>\
               <button type='button' data-content='remove' class='btn btn-primary' id='editRowBtn'>Save Changes</button>");
          });

          $('#altEditor-modal').modal('show');
          $('#altEditor-modal input[0]').focus();

          $('#purchase_date').datepicker({
              format: 'yyyy-mm-dd'
          });
       },

       _editRowData: function()
       {
         var that = this;
         var dt = this.s.dt;

         var data = [];

           $('form[name="altEditor-form"] input').each(function( i ) {
            data.push($(this).val());
         });

           $.ajax({
               headers: {
                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               },
               url: "/admin/product/editDetails",
               type:"POST",
               data:{
                   data:data
               },
               success: function(result){
                   getProductDetails(_id);
                   $('#altEditor-modal .modal-body .alert').remove();

                   var message = '<div class="alert alert-success" role="alert">\
                    <strong>Success!</strong> This record has been updated.\
                    </div>';

                   $('#altEditor-modal .modal-body').append(message);

                   dt.row({ selected:true }).data(data);

                   setTimeout(function () {
                       $("#altEditor-modal").modal('toggle')
                   }, _timeout);
               }
           });
       },


       /**
        * Open Delete Modal for selected row
        *
        * @private
        */
       _openDeleteModal: function ()
       {
         var that = this;
         var dt = this.s.dt;

         var columnDefs = [];

         for( var i = 0; i < dt.context[0].aoColumns.length; i++ )
         {
            columnDefs.push({ title: dt.context[0].aoColumns[i].sTitle,
                id: dt.context[0].aoColumns[i].id
            })
         }
         // console.log(columnDefs);

           var adata = dt.rows({
            selected: true
          });

         var data = "";

          data += "<form id='altEditor-form' name='altEditor-form' role='form'>";
          for (var j in columnDefs) {
              if(j==0){
                  data += "<div class='form-group row' style='display: none'><div class='col-12'><input type='text'  id='" + columnDefs[j].id + "' name='" + columnDefs[j].id + "' placeholder='" + columnDefs[j].title + "' class='input-material form-control' value='" + adata.data()[0][j] + "'><label for='" + columnDefs[j].id + "' class='input-label'>" + columnDefs[j].title + ":</label></div></div>";
              }else{
                  data += "<div class='form-group row'><div class='col-12'><input type='text'  id='" + columnDefs[j].id + "' name='" + columnDefs[j].id + "' placeholder='" + columnDefs[j].title + "' class='input-material form-control' value='" + adata.data()[0][j] + "'><label for='" + columnDefs[j].id + "' class='input-label'>" + columnDefs[j].title + ":</label></div></div>";
              }

          }
          data += "</form>";

          $('#altEditor-modal').on('show.bs.modal', function() {
            $('#altEditor-modal').find('.modal-title').html('Delete Record');
            $('#altEditor-modal').find('.modal-body').html('' + data + '');
            $('#altEditor-modal').find('.modal-footer').html("<button type='button' data-content='remove' class='btn btn-default' data-dismiss='modal'>Close</button>\
               <button type='button' data-content='remove' class='btn btn-danger' id='deleteRowBtn'>Delete</button>");
          });

          $('#altEditor-modal').modal('show');
          $('#altEditor-modal input[0]').focus();

           $('#purchase_date').datepicker({
               format: 'yyyy-mm-dd'
           });

       },

       _deleteRow: function( )
       {
         var that = this;
         var dt = this.s.dt;

           var adata = dt.rows({
               selected: true
           });
           var product_id = adata.data()[0][0];

           $.ajax({
               headers: {
                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               },
               url: "/admin/product/deleteDetails",
               type:"POST",
               data:{
                   id:product_id
               },
               success: function(result){
                   getProductDetails(_id);

                   $('#altEditor-modal .modal-body .alert').remove();

                   var message = '<div class="alert alert-success" role="alert">\
                    <strong>Success!</strong> This record has been deleted.\
                    </div>';

                   $('#altEditor-modal .modal-body').append(message);

                   dt.row({ selected:true }).remove();

                   dt.draw();

                   setTimeout(function () {
                       $("#altEditor-modal").modal('toggle')
                   }, _timeout);
               }
           });
       },


       /**
        * Open Add Modal for selected row
        * 
        * @private
        */
       _openAddModal: function ( )
       {
         var that = this;
         var dt = this.s.dt;

         var columnDefs = [];

         for( var i = 0; i < dt.context[0].aoColumns.length; i++ )
         {
            columnDefs.push({
                title: dt.context[0].aoColumns[i].sTitle,
                id: dt.context[0].aoColumns[i].id
            })
         }
          var data = "";

          data += "<form id='altEditor-form' name='altEditor-form' role='form'>";

          for (var j in columnDefs) {
              if(j==0){
                  data += "<div class='form-group row' style='display: none'><div class='col-12'><input type='text'  id='" + columnDefs[j].id + "' name='" + columnDefs[j].id + "' placeholder='" + columnDefs[j].title + "' class='input-material form-control' value=''><label for='" + columnDefs[j].id + "' class='input-label'>" + columnDefs[j].title + ":</label></div></div>";
              }else{
                  data += "<div class='form-group row'><div class='col-12'><input type='text'  id='" + columnDefs[j].id + "' name='" + columnDefs[j].id + "' placeholder='" + columnDefs[j].title + "' class='input-material form-control' value=''><label for='" + columnDefs[j].id + "' class='input-label'>" + columnDefs[j].title + ":</label></div></div>";
              }
          }
          data += "</form>";

            $('#altEditor-modal').on('show.bs.modal', function() {
            $('#altEditor-modal').find('.modal-title').html('Add Record');
            $('#altEditor-modal').find('.modal-body').html('' + data + '');
            $('#altEditor-modal').find('.modal-footer').html("<button type='button' data-content='remove' class='btn btn-default' data-dismiss='modal'>Close</button>\
               <button type='button' data-content='remove' class='btn btn-primary' id='addRowBtn'>Add Record</button>");
          });

          $('#altEditor-modal').modal('show');
          $('#altEditor-modal input[0]').focus();

          $('#purchase_date').datepicker({
              format: 'yyyy-mm-dd'
          });

           $( "#purchase_price , #purchase_quantity" ).keypress(function(event) {
               return validateNumber(event);
           });

           $('#altEditor-form').validate({
               rules: {
                   purchase_date: {
                       required: true,
                   },
                   company_name: {
                       required: true,
                   },
                   purchase_price: {
                       required: true,
                   },
                   purchase_quantity: {
                       required: true,
                   },
               },errorPlacement: function(error, element) { }
           });
       },

       _addRowData: function()
       {
           if ($('#altEditor-form').valid()) {
               var that = this;
               var dt = this.s.dt;

               var data = [];

               $('form[name="altEditor-form"] input').each(function( i ) {
                   data.push( $(this).val() );
               });

               $.ajax({
                   headers: {
                       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                   },
                   url: "/admin/product/addDetails",
                   type:"post",
                   data:{
                       id:_id,
                       data:data
                   },
                   success: function(result){
                       data[0] = result['data'];
                       getProductDetails(_id);
                       $('#altEditor-modal .modal-body .alert').remove();

                       var message = '<div class="alert alert-success" role="alert">\
                <strong>Success!</strong> This record has been added.\
                </div>';

                       $('#altEditor-modal .modal-body').append(message);

                       dt.row.add(data).draw(false);

                       setTimeout(function () {
                           $("#altEditor-modal").modal('toggle')
                       }, _timeout);
                   }
               });
           }
       },

       _getExecutionLocationFolder: function() {
             var fileName = "dataTables.altEditor.js";
             var scriptList = $("script[src]");
             var jsFileObject = $.grep(scriptList, function(el) {

                  if(el.src.indexOf(fileName) !== -1 )
                  {
                     return el;
                  }
             });
             var jsFilePath = jsFileObject[0].src;
             var jsFileDirectory = jsFilePath.substring(0, jsFilePath.lastIndexOf("/") + 1);
             return jsFileDirectory;
         }
   } );



   /**
    * altEditor version
    * 
    * @static
    * @type      String
    */
   altEditor.version = '1.0';


   /**
    * altEditor defaults
    * 
    * @namespace
    */
   altEditor.defaults = {
       /** @type {Boolean} Ask user what they want to do, even for a single option */
       alwaysAsk: false,

       /** @type {string|null} What will trigger a focus */
       focus: null, // focus, click, hover

       /** @type {column-selector} Columns to provide auto fill for */
       columns: '', // all

       /** @type {boolean|null} Update the cells after a drag */
       update: null, // false is editor given, true otherwise

       /** @type {DataTable.Editor} Editor instance for automatic submission */
       editor: null
   };


   /**
    * Classes used by altEditor that are configurable
    * 
    * @namespace
    */
   altEditor.classes = {
       /** @type {String} Class used by the selection button */
       btn: 'btn'
   };


   // Attach a listener to the document which listens for DataTables initialisation
   // events so we can automatically initialise
   $(document).on( 'preInit.dt.altEditor', function (e, settings, json) {
       if ( e.namespace !== 'dt' ) {
           return;
       }

       var init = settings.oInit.altEditor;
       var defaults = DataTable.defaults.altEditor;

       if ( init || defaults ) {
           var opts = $.extend( {}, init, defaults );

           if ( init !== false ) {
               new altEditor( settings, opts  );
           }
       }
   } );


   // Alias for access
   DataTable.altEditor = altEditor;

   return altEditor;
}));