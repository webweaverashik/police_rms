"use strict";

var AllReportsList = function () {
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
                        { orderable: false, targets: 23 }, // Disable ordering on column Actions
                  ]
            });

            // Re-init functions on every table re-draw -- more info: https://datatables.net/reference/event/draw
            datatable.on('draw', function () {

            });
      }

      // Hook export buttons
      var exportButtons = () => {
            const documentTitle = 'সকল দাখিলকৃত প্রতিবেদন';

            var buttons = new $.fn.dataTable.Buttons(datatable, {
                  buttons: [
                        {
                              extend: 'copyHtml5',
                              className: 'buttons-copy',
                              title: documentTitle,
                              exportOptions: {
                                    columns: ':visible:not(.not-export)'
                              }
                        },
                        {
                              extend: 'excelHtml5',
                              className: 'buttons-excel',
                              title: documentTitle,
                              exportOptions: {
                                    columns: ':visible:not(.not-export)'
                              }
                        },
                        {
                              extend: 'csvHtml5',
                              className: 'buttons-csv',
                              title: documentTitle, exportOptions: {
                                    columns: ':visible:not(.not-export)'
                              }
                        },
                        {
                              extend: 'pdfHtml5',
                              className: 'buttons-pdf',
                              title: documentTitle,
                              exportOptions: {
                                    columns: ':visible:not(.not-export)',
                                    modifier: {
                                          page: 'all',
                                          search: 'applied'
                                    }
                              },
                              customize: function (doc) {
                                    // Set page margins [left, top, right, bottom]
                                    doc.pageMargins = [20, 20, 20, 40]; // reduce from default 40

                                    // Optional: Set font size globally
                                    doc.defaultStyle.fontSize = 10;

                                    // Optional: Set header or footer
                                    doc.footer = getPdfFooterWithPrintTime(); // your custom footer function
                              }
                        }

                  ]
            }).container().appendTo('#kt_hidden_export_buttons'); // or a hidden container

            // Hook dropdown export actions
            const exportItems = document.querySelectorAll('#kt_table_report_dropdown_menu [data-row-export]');
            exportItems.forEach(exportItem => {
                  exportItem.addEventListener('click', function (e) {
                        e.preventDefault();
                        const exportValue = this.getAttribute('data-row-export');
                        const target = document.querySelector('.buttons-' + exportValue);
                        if (target) {
                              target.click();
                        } else {
                              console.warn('Export button not found:', exportValue);
                        }
                  });
            });
      };


      // Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
      var handleSearch = function () {
            const filterSearch = document.querySelector('[data-all-reports-table-filter="search"]');
            filterSearch.addEventListener('keyup', function (e) {
                  datatable.search(e.target.value).draw();
            });
      }

      // Filter Datatable
      var handleFilter = function () {
            // Select filter options
            const filterForm = document.querySelector('[data-all-reports-table-filter="form"]');
            const filterButton = filterForm.querySelector('[data-all-reports-table-filter="filter"]');
            const resetButton = filterForm.querySelector('[data-all-reports-table-filter="reset"]');
            const selectOptions = filterForm.querySelectorAll('select');

            // Filter datatable on submit
            filterButton.addEventListener('click', function () {
                  var filterString = '';

                  // Get filter values
                  selectOptions.forEach((item, index) => {
                        if (item.value && item.value !== '') {
                              if (index !== 0) {
                                    filterString += ' ';
                              }

                              // Build filter value options
                              filterString += item.value;
                        }
                  });

                  // Filter datatable --- official docs reference: https://datatables.net/reference/api/search()
                  datatable.search(filterString).draw();
            });

            // Reset datatable
            resetButton.addEventListener('click', function () {
                  // Reset filter form
                  selectOptions.forEach((item, index) => {
                        // Reset Select2 dropdown --- official docs reference: https://select2.org/programmatic-control/add-select-clear-items
                        $(item).val(null).trigger('change');
                  });

                  // Filter datatable --- official docs reference: https://datatables.net/reference/api/search()
                  datatable.search('').draw();
            });
      }


      // Delete Report
      const handleDeletion = function () {
            document.addEventListener('click', function (e) {
                  const deleteBtn = e.target.closest('.delete-report');
                  if (!deleteBtn) return;

                  e.preventDefault();

                  let reportId = deleteBtn.getAttribute('data-report-id');
                  console.log('Report ID:', reportId);

                  let url = reportDeleteRoute.replace(':id', reportId);

                  Swal.fire({
                        title: 'আপনি কি নিশ্চিত?',
                        text: 'এই প্রতিবেদনটি মুছে ফেলা হবে।',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'মুছে ফেলুন',
                        cancelButtonText: 'বাতিল',
                  }).then((result) => {
                        if (result.isConfirmed) {
                              fetch(url, {
                                    method: "DELETE",
                                    headers: {
                                          "Content-Type": "application/json",
                                          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                                    },
                              })
                                    .then(response => response.json())
                                    .then(data => {
                                          if (data.success) {
                                                Swal.fire({
                                                      title: 'ধন্যবাদ!',
                                                      text: 'প্রতিবেদনটি সফলভাবে মুছে ফেলা হয়েছে।',
                                                      icon: 'success',
                                                      confirmButtonText: 'ঠিক আছে',
                                                }).then(() => {
                                                      location.reload();
                                                });
                                          } else {
                                                Swal.fire('Failed!', 'প্রতিবেদনটি মুছে ফেলা যায়নি।', 'error');
                                          }
                                    })
                                    .catch(error => {
                                          console.error("Fetch Error:", error);
                                          Swal.fire('Failed!', 'An error occurred. Please contact support.', 'error');
                                    });
                        }
                  });
            });
      };


      return {
            // Public functions
            init: function () {
                  table = document.getElementById('kt_all_reports_table');

                  if (!table) {
                        return;
                  }

                  initDatatable();
                  exportButtons();
                  handleSearch();
                  handleFilter();
                  handleDeletion();
            }
      }
}();

var AssignMagistratesList = function () {

      let tagify = null;
      let currentReportId = null;

      const input = document.getElementById('magistrateTagify');
      const modalEl = document.getElementById('assignMagistrateModal');
      const saveBtn = document.getElementById('saveMagistrateAssignment');
      let bsModal = null;

      /* --------------------------------------------------
       * Template Functions - Define BEFORE using
       * -------------------------------------------------- */

      // ✅ Tag template function
      function tagTemplate(tagData) {
            return `
            <tag title="${tagData.name}"
                 contenteditable='false'
                 spellcheck='false'
                 tabIndex="-1"
                 class="tagify__tag"
                 value="${tagData.value}">
                <x title='' class='tagify__tag__removeBtn' role='button' aria-label='remove tag'></x>
                <div class="d-flex align-items-center gap-2">
                    <img src="${tagData.avatar}" 
                         onerror="this.src='${window.defaultAvatar || '/assets/img/dummy.png'}'"
                         width="28" height="28" 
                         class="rounded-circle"
                         style="object-fit: cover;">
                    <div>
                        <div class="fw-semibold" style="font-size: 13px;">${tagData.name}</div>
                        <small class="text-muted" style="font-size: 11px;">${tagData.designation}</small>
                    </div>
                </div>
            </tag>
        `;
      }

      // ✅ Dropdown item template function - MUST use this.getAttributes(tagData)
      function dropdownItemTemplate(tagData) {
            return `
            <div ${this.getAttributes(tagData)}
                 class='tagify__dropdown__item ${tagData.class ? tagData.class : ""}'
                 tabindex="0"
                 role="option">
                <div class="d-flex align-items-center gap-3">
                    <img src="${tagData.avatar}" 
                         onerror="this.src='${window.defaultAvatar || '/assets/img/dummy.png'}'"
                         width="36" height="36" 
                         class="rounded-circle"
                         style="object-fit: cover;">
                    <div>
                        <div class="fw-semibold">${tagData.name}</div>
                        <small class="text-muted">${tagData.designation}</small>
                    </div>
                </div>
            </div>
        `;
      }

      /* --------------------------------------------------
       * Init Tagify
       * -------------------------------------------------- */
      const initTagify = function () {
            tagify = new Tagify(input, {
                  tagTextProp: 'name',
                  enforceWhitelist: true,
                  skipInvalid: true,

                  // ✅ CRITICAL FIX: Properly format the hidden input value
                  originalInputValueFormat: valuesArr => valuesArr.map(item => item.value).join(','),

                  dropdown: {
                        enabled: 0,              // ✅ 0 = show dropdown on focus
                        maxItems: 20,
                        closeOnSelect: false,    // Keep open for multi-select
                        highlightFirst: true,
                        mapValueTo: 'name',      // ✅ CRITICAL: Map display value to name
                        searchKeys: ['name', 'designation'],
                        placeAbove: false,
                        appendTarget: document.body  // ✅ Important for modals
                  },

                  templates: {
                        tag: tagTemplate,
                        dropdownItem: dropdownItemTemplate
                  }
            });

            // Debug events (optional - remove in production)
            // tagify.on('dropdown:show', () => console.log('[Tagify] Dropdown shown'));
            // tagify.on('dropdown:select', (e) => console.log('[Tagify] Selected:', e.detail.data));
            // tagify.on('add', (e) => console.log('[Tagify] Tag added:', e.detail.data));
            // tagify.on('remove', (e) => console.log('[Tagify] Tag removed:', e.detail.data));
      };

      /* --------------------------------------------------
       * Click handler for assign button
       * -------------------------------------------------- */
      const initAssignClick = function () {
            document.addEventListener('click', function (e) {
                  const btn = e.target.closest('.assign-report');
                  if (!btn) return;

                  e.preventDefault();
                  currentReportId = btn.dataset.reportId;

                  console.log('[Assign] Loading magistrates for report:', currentReportId);

                  fetch(reportMagistratesRoute.replace(':id', currentReportId))
                        .then(res => {
                              if (!res.ok) throw new Error('Network response was not ok');
                              return res.json();
                        })
                        .then(data => {
                              console.log('[Assign] API response:', data);

                              // ✅ Build whitelist with proper structure
                              const whitelist = data.magistrates.map(m => ({
                                    value: String(m.id),  // Must be string
                                    name: m.name,
                                    designation: m.designation,
                                    avatar: m.avatar
                              }));

                              // ✅ Set whitelist
                              tagify.settings.whitelist = whitelist;
                              console.log('[Tagify] Whitelist set:', whitelist.length, 'items');

                              // ✅ Clear existing tags
                              tagify.removeAllTags();

                              // ✅ Pre-fill assigned magistrates
                              const assignedTags = whitelist.filter(m =>
                                    data.assigned.includes(parseInt(m.value))
                              );

                              if (assignedTags.length > 0) {
                                    tagify.addTags(assignedTags);
                                    console.log('[Tagify] Pre-filled:', assignedTags.length, 'tags');
                              }

                              // Show modal
                              if (!bsModal) {
                                    bsModal = new bootstrap.Modal(modalEl);
                              }
                              bsModal.show();
                        })
                        .catch(err => {
                              console.error('[Assign] Fetch failed:', err);
                              Swal.fire('Error', 'ম্যাজিস্ট্রেট লোড করতে সমস্যা হয়েছে।', 'error');
                        });
            });

            // ✅ Focus tagify and show dropdown when modal opens
            modalEl.addEventListener('shown.bs.modal', function () {
                  setTimeout(() => {
                        tagify.DOM.input.focus();
                        tagify.dropdown.show();
                        console.log('[Tagify] Modal opened, dropdown shown');
                  }, 150);
            });

            // Clean up when modal closes
            modalEl.addEventListener('hidden.bs.modal', function () {
                  tagify.dropdown.hide();
            });
      };

      /* --------------------------------------------------
       * Save handler
       * -------------------------------------------------- */
      const initSave = function () {
            saveBtn.addEventListener('click', function () {
                  // ✅ Get selected user IDs
                  const userIds = tagify.value.map(tag => tag.value);

                  console.log('[Save] Saving user_ids:', userIds);

                  fetch(reportAssignRoute.replace(':id', currentReportId), {
                        method: 'POST',
                        headers: {
                              'Content-Type': 'application/json',
                              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ user_ids: userIds })
                  })
                        .then(res => {
                              if (!res.ok) throw new Error('Network response was not ok');
                              return res.json();
                        })
                        .then(data => {
                              console.log('[Save] Response:', data);

                              if (data.success) {
                                    Swal.fire({
                                          icon: 'success',
                                          title: 'সফল!',
                                          text: data.message || 'প্রতিবেদনটিতে ম্যাজিস্ট্রেড এসাইন সফল হয়েছে।',
                                          confirmButtonText: 'ঠিক আছে'
                                    });
                                    bsModal.hide();
                              } else {
                                    Swal.fire('Error', data.message || 'কিছু সমস্যা হয়েছে।', 'error');
                              }
                        })
                        .catch(err => {
                              console.error('[Save] Error:', err);
                              Swal.fire('Error', 'সংরক্ষণ করতে সমস্যা হয়েছে।', 'error');
                        });
            });
      };

      /* --------------------------------------------------
       * Public init
       * -------------------------------------------------- */
      return {
            init: function () {
                  console.log('[AssignMagistratesList] Initializing...');
                  initTagify();
                  initAssignClick();
                  initSave();
                  console.log('[AssignMagistratesList] Ready!');
            }
      };
}();


/* --------------------------------------------------
 * DOM Ready
 * -------------------------------------------------- */
KTUtil.onDOMContentLoaded(function () {
      AllReportsList.init();
      AssignMagistratesList.init();
});