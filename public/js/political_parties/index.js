"use strict";

var AllPoliticalPartyList = function () {
      // Define shared variables
      var table;
      var datatable;

      // Private functions
      var initDatatable = function () {
            // Init datatable --- more info on datatables: https://datatables.net/manual/
            datatable = $(table).DataTable({
                  "info": true,
                  'order': [],
                  "lengthMenu": [10, 25, 50, 100],
                  "pageLength": 10,
                  "lengthChange": true,
                  "autoWidth": false, // Disable auto width
                  'columnDefs': [
                        { orderable: false, targets: 4 }, // Disable ordering on column Actions
                  ]
            });

            // Re-init functions on every table re-draw -- more info: https://datatables.net/reference/event/draw
            datatable.on('draw', function () {

            });
      }


      // Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
      var handleSearch = function () {
            const filterSearch = document.querySelector('[data-political-parties-table-filter="search"]');
            filterSearch.addEventListener('keyup', function (e) {
                  datatable.search(e.target.value).draw();
            });
      }

      return {
            // Public functions
            init: function () {
                  table = document.getElementById('kt_political_parties_table');

                  if (!table) {
                        return;
                  }

                  initDatatable();
                  handleSearch();
            }
      }
}();


/* --------------------------------------------------
 * DOM Ready
 * -------------------------------------------------- */
KTUtil.onDOMContentLoaded(function () {
      AllPoliticalPartyList.init();
});