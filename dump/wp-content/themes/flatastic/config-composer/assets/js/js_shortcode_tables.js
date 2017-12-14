(function ($) {

	"use strict";

	$.mad_modal = $.mad_modal || {};

	$.mad_modal.tableBuilder = function() {

		var methods = {
			init: function () {
				this.base();
				this.listeners();
			},
			base: function () {
				var base = this;

				base.colsEl = $("#columns");
				base.rowsEl = $("#rows");
				base.colsVal = base.colsEl.val();
				base.rowsVal = base.rowsEl.val();

				$('.wpb_el_type_table_number').last().after('<div class="vc_col-sm-12 vc_column wpb_el_type_table-holder" />');

				if (base.colsVal != '' && base.rowsVal != '') {
					base.html(base.colsVal, base.rowsVal);
				}
			},
			stringData: function (colsVal, rowsVal) {
				var counter = 0,
					stringData = [],
					data = split('||', $('.tables-hidden-data').val());

				for (var i = 0; i < rowsVal; i++) {
					stringData[i] = [];
					for (var j = 0; j < colsVal; j++) {
						if (data[counter] != undefined) {
							stringData[i][j] = data[counter];
						} else {
							stringData[i][j] = '&nbsp;';
						}
						counter ++;
					}
				}

				return stringData;
			},
			html: function (colsVal, rowsVal) {
				var table = '', i, j;

				for (i = 0; i < rowsVal; i++) {
					table += '<div class="mad-table-row">' + "\n";
					for (j = 0; j < colsVal; j++) {
						var value = this.stringData(colsVal, rowsVal)[i][j];
							table += '<div class="mad-table-cell"><input type="text" class="wpb_table_data" id="input_'+ i +''+ j +'" value="'+ value +'"></div>' + "\n";
					}
					table += '</div>' + "\n";
				}
				$('.wpb_el_type_table-holder').empty().append($('<div class="mad-admin-table" />').append(table));
			},
			listeners: function () {
				var base = this;

				base.colsEl.add(base.rowsEl).on('click keyup', function (e) {
					e.preventDefault();

					var colsVal = base.colsEl.val(),
						rowsVal = base.rowsEl.val();

					base.html(colsVal, rowsVal);
				});

				$('.vc_panel-body').on('keyup', '.wpb_table_data', function (e) {
					var rows = [];
					var colsVal = base.colsEl.val(),
						rowsVal = base.rowsEl.val();

					for (var i = 0; i < rowsVal; i++) {
						for (var j = 0; j < colsVal; j++) {
							rows.push($('#input_'+ i +'' +j).val());
						}
					}
					$('.tables-hidden-data').val(rows.join('||'));
				});
			}

		};

		methods.init();
	}

	$(function () {
		$.mad_modal.tableBuilder();
	});

})(jQuery);