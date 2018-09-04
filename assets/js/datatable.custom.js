var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {
	editor = new $.fn.dataTable.Editor( {
		"ajax": "../php/checkbox.php",
		"table": "#example1",
		"fields": [ {
				label:     "Active:",
				name:      "active",
				type:      "checkbox",
				separator: "|",
				options:   [
					{ label: '', value: 1 }
				]
			}, {
				label: "First name:",
				name:  "first_name"
			}, {
				label: "Last name:",
				name:  "last_name"
			}, {
				label: "Phone:",
				name:  "phone"
			}, {
				label: "City:",
				name:  "city"
			}, {
				label: "Zip:",
				name:  "zip"
			}
		]
	} );

	$('#example1').DataTable( {
		dom: "Bfrtip",
		ajax: "/panel/mail-ajax",
		columns: [
			{ data: "first_name" },
			{ data: "last_name" },
			{ data: "phone" },
			{ data: "city" },
			{ data: "zip" },
			{
				data:   "active",
				render: function ( data, type, row ) {
					if ( type === 'display' ) {
						return '<input type="checkbox" class="editor-active">';
					}
					return data;
				},
				className: "dt-body-center"
			}
		],
		select: {
			style: 'os',
			selector: 'td:not(:last-child)' // no row selection on last column
		},
		buttons: [
			{ extend: "create", editor: editor },
			{ extend: "edit",   editor: editor },
			{ extend: "remove", editor: editor }
		],
		rowCallback: function ( row, data ) {
			// Set the checked state of the checkbox in the table
			$('input.editor-active', row).prop( 'checked', data.active == 1 );
		}
	} );

	$('#example1').on( 'change', 'input.editor-active', function () {
		editor
			.edit( $(this).closest('tr'), false )
			.set( 'active', $(this).prop( 'checked' ) ? 1 : 0 )
			.submit();
	} );
} );


