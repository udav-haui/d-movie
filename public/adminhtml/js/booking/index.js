$(document).ready(function () {
    'use strict';

    tableName = 'bookings';
    $.fn.dataTable.defaults.aoColumns = aoColumns;
    $.fn.dataTable.defaults.columnDefs = columnDefs;
    $.fn.dataTable.defaults.order = colOrder;

    /* Draw data table */
    dtable = serverSideDatatable(route('bookings.index'), invisibleCols);
});
