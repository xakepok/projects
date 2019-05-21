'use strict';

jQuery(document).ready(function() {
    jQuery('#bigtable').DataTable( {
        scrollY:        610,
        scrollX:        true,
        scrollCollapse: true,
        paging:         false,
        columnDefs: [
            { width: '20%', targets: 0 }
        ],
        fixedColumns:   {
            leftColumns: 3
        }
    } );
});