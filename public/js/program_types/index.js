"use strict";

var AllProgramTypesList = function () {
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
            const filterSearch = document.querySelector('[data-program-types-table-filter="search"]');
            filterSearch.addEventListener('keyup', function (e) {
                  datatable.search(e.target.value).draw();
            });
      }

      return {
            // Public functions
            init: function () {
                  table = document.getElementById('kt_program_types_table');

                  if (!table) {
                        return;
                  }

                  initDatatable();
                  handleSearch();
            }
      }
}();

var KTAddProgramType = function () {
      // Shared variables
      const element = document.getElementById('kt_modal_add_program_type');

      // Early return if element doesn't exist
      if (!element) {
            console.error('Modal element not found');
            return {
                  init: function () { }
            };
      }

      const form = element.querySelector('#kt_modal_add_program_type_form');
      const modal = bootstrap.Modal.getOrCreateInstance(element);


      var initCloseModal = () => {
            // Reset Select2 inputs
            function resetSelect2Inputs() {
                  // 1) Reset Select2 value + UI + borders
                  $(form).find('select[data-control="select2"]').each(function () {
                        $(this).val(null).trigger('change');
                        $(this).next('.select2').find('.select2-selection')
                              .removeClass('is-valid is-invalid');
                  });

                  // 2) Remove Bootstrap validation classes from all fields
                  $(form).find('.is-valid, .is-invalid').removeClass('is-valid is-invalid');

                  // 3) Remove ALL FormValidation error messages
                  $(form).find('.fv-plugins-message-container').each(function () {
                        $(this).empty();  // Clear inner validation messages
                        // Optionally remove "enabled" class:
                        $(this).removeClass('fv-plugins-message-container--enabled');
                  });
            }

            // Cancel button handler
            const cancelButton = element.querySelector('[data-kt-add-program-type-modal-action="cancel"]');
            if (cancelButton) {
                  cancelButton.addEventListener('click', e => {
                        e.preventDefault();
                        if (form) form.reset();
                        resetSelect2Inputs()
                        modal.hide();
                  });
            }

            // Close button handler
            const closeButton = element.querySelector('[data-kt-add-program-type-modal-action="close"]');
            if (closeButton) {
                  closeButton.addEventListener('click', e => {
                        e.preventDefault();
                        if (form) form.reset();
                        resetSelect2Inputs()
                        modal.hide();
                  });
            }
      }

      // Form validation
      var initValidation = function () {
            if (!form) return;

            var validator = FormValidation.formValidation(
                  form,
                  {
                        fields: {
                              'type_name': {
                                    validators: {
                                          notEmpty: {
                                                message: 'প্রোগ্রামের ধরণের নাম প্রয়োজন'
                                          },
                                          stringLength: {
                                                max: 50,
                                                message: 'সর্বোচ্চ ৫০ অক্ষরের হতে পারবে'
                                          }
                                    }
                              },
                              'type_description': {
                                    validators: {
                                          stringLength: {
                                                max: 100,
                                                message: 'সর্বোচ্চ ১০০ অক্ষরের হতে পারবে'
                                          }
                                    }
                              },
                        },
                        plugins: {
                              trigger: new FormValidation.plugins.Trigger(),
                              bootstrap: new FormValidation.plugins.Bootstrap5({
                                    rowSelector: '.fv-row',
                                    eleInvalidClass: '',
                                    eleValidClass: ''
                              })
                        }
                  }
            );

            const submitButton = element.querySelector('[data-kt-add-program-type-modal-action="submit"]');

            if (submitButton && validator) {
                  submitButton.addEventListener('click', function (e) {
                        e.preventDefault(); // Prevent default button behavior

                        validator.validate().then(function (status) {
                              if (status === 'Valid') {
                                    // Show loading indicator
                                    submitButton.setAttribute('data-kt-indicator', 'on');
                                    submitButton.disabled = true;

                                    const formData = new FormData(form);
                                    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

                                    fetch(storeProgramTypeRoute, {
                                          method: "POST",
                                          body: formData,
                                          headers: {
                                                'Accept': 'application/json', // Explicitly ask for JSON
                                                'X-Requested-With': 'XMLHttpRequest'
                                          }
                                    })
                                          .then(async response => {
                                                const data = await response.json();

                                                if (!response.ok) {
                                                      const message = data.message || 'Something went wrong';
                                                      const errors = data.errors
                                                            ? [...new Set(Object.values(data.errors).flat())].join('<br>')
                                                            : '';
                                                      throw {
                                                            message: data.message || 'User creation failed',
                                                            response: new Response(JSON.stringify(data), {
                                                                  status: 422,
                                                                  headers: { 'Content-type': 'application/json' }
                                                            })
                                                      };

                                                }

                                                return data;
                                          })

                                          .then(data => {
                                                submitButton.removeAttribute('data-kt-indicator');
                                                submitButton.disabled = false;

                                                if (data.success) {
                                                      toastr.success(data.message || 'Teacher created successfully');
                                                      modal.hide();
                                                      setTimeout(() => {
                                                            window.location.reload();
                                                      }, 1500);
                                                } else {
                                                      toastr.error(data.message || 'Teacher creation failed');
                                                }
                                          })
                                          .catch(error => {
                                                submitButton.removeAttribute('data-kt-indicator');
                                                submitButton.disabled = false;
                                                toastr.error(error.message || 'Failed to create teacher');
                                                console.error('Error:', error);
                                          });

                              } else {
                                    toastr.warning('Please fill all fields correctly');
                              }
                        });
                  });
            }
      }

      return {
            init: function () {
                  initCloseModal();
                  initValidation();
            }
      };
}();


var KTEditProgramType = function () {
      // Shared variables
      const element = document.getElementById('kt_modal_edit_program_type');
      const form = element.querySelector('#kt_modal_edit_program_type_form');
      const modal = new bootstrap.Modal(element);

      let programTypeId = null;
      let validator = null; // Declare validator globally


      // Init Edit User Modal
      const initEditProgramType = () => {
            document.addEventListener('click', function (e) {
                  const editBtn = e.target.closest("[data-bs-target='#kt_modal_edit_program_type']");
                  if (!editBtn) return;

                  e.preventDefault();

                  programTypeId = editBtn.getAttribute("data-program-type-id");
                  console.log('Program ID:', programTypeId);

                  if (!programTypeId) return;

                  if (form) form.reset();

                  // AJAX data fetch
                  fetch(`/program-types/${programTypeId}`)
                        .then(response => {
                              if (!response.ok) throw new Error('Network response was not ok');
                              return response.json();
                        })
                        .then(data => {
                              if (data.success && data.data) {
                                    const program_type = data.data;

                                    const titleEl = document.getElementById("kt_modal_edit_program_type_title");
                                    if (titleEl) {
                                          titleEl.textContent = `${program_type.name} আপডেট করুন`;
                                    }

                                    document.querySelector("input[name='type_name_edit']").value = program_type.name;
                                    document.querySelector("input[name='type_description_edit']").value = program_type.description;

                                    modal.show();
                              } else {
                                    throw new Error(data.message || 'Invalid response data');
                              }
                        })
                        .catch(error => {
                              console.error("Error:", error);
                              toastr.error(error.message || "Failed to load user details");
                        });
            });

            // Cancel and close buttons
            const cancelButton = element.querySelector('[data-edit-program-type-modal-action="cancel"]');
            const closeButton = element.querySelector('[data-edit-program-type-modal-action="close"]');
            [cancelButton, closeButton].forEach(btn => {
                  if (btn) {
                        btn.addEventListener('click', e => {
                              e.preventDefault();
                              form.reset();
                              modal.hide();
                        });
                  }
            });
      };


      // Form validation
      var initEditFormValidation = function () {
            if (!form) return;

            validator = FormValidation.formValidation(
                  form,
                  {
                        fields: {
                              'type_name_edit': {
                                    validators: {
                                          notEmpty: {
                                                message: 'প্রোগ্রামের ধরণের নাম প্রয়োজন'
                                          },
                                          stringLength: {
                                                max: 50,
                                                message: 'সর্বোচ্চ ৫০ অক্ষরের হতে পারবে'
                                          }
                                    }
                              },
                              'type_description_edit': {
                                    validators: {
                                          stringLength: {
                                                max: 100,
                                                message: 'সর্বোচ্চ ১০০ অক্ষরের হতে পারবে'
                                          }
                                    }
                              },
                        },
                        plugins: {
                              trigger: new FormValidation.plugins.Trigger(),
                              bootstrap: new FormValidation.plugins.Bootstrap5({
                                    rowSelector: '.fv-row',
                                    eleInvalidClass: '',
                                    eleValidClass: ''
                              })
                        }
                  }
            );

            const submitButton = element.querySelector('[data-edit-program-type-modal-action="submit"]');

            if (submitButton && validator) {
                  submitButton.addEventListener('click', function (e) {
                        e.preventDefault(); // Prevent default button behavior

                        validator.validate().then(function (status) {
                              if (status === 'Valid') {
                                    // Show loading indicator
                                    submitButton.setAttribute('data-kt-indicator', 'on');
                                    submitButton.disabled = true;

                                    const formData = new FormData(form);
                                    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                                    formData.append('_method', 'PUT');

                                    console.log(programTypeId);
                                    fetch(`/program-types/${programTypeId}`, {
                                          method: 'POST',
                                          body: formData,
                                          headers: {
                                                'Accept': 'application/json',
                                                'X-Requested-With': 'XMLHttpRequest'
                                          }
                                    })
                                          .then(response => {
                                                if (!response.ok) {
                                                      return response.json().then(errorData => {
                                                            // Show error from Laravel if available
                                                            throw new Error(errorData.message || 'Network response was not ok');
                                                      });
                                                }
                                                return response.json();
                                          })
                                          .then(data => {
                                                submitButton.removeAttribute('data-kt-indicator');
                                                submitButton.disabled = false;

                                                if (data.success) {
                                                      toastr.success(data.message || 'Teacher updated successfully');
                                                      modal.hide();
                                                      setTimeout(() => {
                                                            window.location.reload();
                                                      }, 1500); // 1000ms = 1 second delay
                                                } else {
                                                      throw new Error(data.message || 'Teacher Update failed');
                                                }
                                          })
                                          .catch(error => {
                                                submitButton.removeAttribute('data-kt-indicator');
                                                submitButton.disabled = false;
                                                toastr.error(error.message || 'Failed to update user');
                                                console.error('Error:', error);
                                          });
                              } else {
                                    toastr.warning('Please fill all required fields');
                              }
                        });
                  });
            }
      };

      return {
            init: function () {
                  initEditProgramType();
                  initEditFormValidation();
            }
      };
}();


/* --------------------------------------------------
 * DOM Ready
 * -------------------------------------------------- */
KTUtil.onDOMContentLoaded(function () {
      AllProgramTypesList.init();
      KTAddProgramType.init();
      KTEditProgramType.init();
});