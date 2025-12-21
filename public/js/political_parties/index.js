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

var KTAddPoliticalParty = function () {
      // Shared variables
      const element = document.getElementById('kt_modal_add_political_party');

      // Early return if element doesn't exist
      if (!element) {
            console.error('Modal element not found');
            return {
                  init: function () { }
            };
      }

      const form = element.querySelector('#kt_modal_add_political_party_form');
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
            const cancelButton = element.querySelector('[data-kt-add-political-party-modal-action="cancel"]');
            if (cancelButton) {
                  cancelButton.addEventListener('click', e => {
                        e.preventDefault();
                        if (form) form.reset();
                        resetSelect2Inputs()
                        modal.hide();
                  });
            }

            // Close button handler
            const closeButton = element.querySelector('[data-kt-add-political-party-modal-action="close"]');
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
                              'party_name': {
                                    validators: {
                                          notEmpty: {
                                                message: 'পার্টি নাম প্রয়োজন'
                                          },
                                          stringLength: {
                                                max: 50,
                                                message: 'সর্বোচ্চ ৫০ অক্ষরের হতে পারবে'
                                          }
                                    }
                              },
                              'party_head': {
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

            const submitButton = element.querySelector('[data-kt-add-political-party-modal-action="submit"]');

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

                                    fetch(storePoliticalPartyRoute, {
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
                                                      toastr.success(data.message || 'Party created successfully');
                                                      modal.hide();
                                                      setTimeout(() => {
                                                            window.location.reload();
                                                      }, 1500);
                                                } else {
                                                      toastr.error(data.message || 'Party creation failed');
                                                }
                                          })
                                          .catch(error => {
                                                submitButton.removeAttribute('data-kt-indicator');
                                                submitButton.disabled = false;
                                                toastr.error(error.message || 'Failed to create Party');
                                                console.error('Error:', error);
                                          });

                              } else {
                                    toastr.warning('অনুগ্রহ করে প্রয়োজনীয় সকল তথ্য দিন');
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

var KTEditPoliticalParty = function () {
      // Shared variables
      const element = document.getElementById('kt_modal_edit_political_party');
      const form = element.querySelector('#kt_modal_edit_political_party_form');
      const modal = new bootstrap.Modal(element);

      let politicalPartyId = null;
      let validator = null; // Declare validator globally


      // Init Edit User Modal
      const initEditProgramType = () => {
            document.addEventListener('click', function (e) {
                  const editBtn = e.target.closest("[data-bs-target='#kt_modal_edit_political_party']");
                  if (!editBtn) return;

                  e.preventDefault();

                  politicalPartyId = editBtn.getAttribute("data-party-id");
                  console.log('Program ID:', politicalPartyId);

                  if (!politicalPartyId) return;

                  if (form) form.reset();

                  // AJAX data fetch
                  fetch(`/ajax/political-party/${politicalPartyId}`)
                        .then(response => {
                              if (!response.ok) throw new Error('Network response was not ok');
                              return response.json();
                        })
                        .then(data => {
                              if (data.success && data.data) {
                                    const political_party = data.data;

                                    const titleEl = document.getElementById("kt_modal_edit_political_party_title");
                                    if (titleEl) {
                                          titleEl.textContent = `${political_party.name} আপডেট করুন`;
                                    }

                                    document.querySelector("input[name='party_name_edit']").value = political_party.name;
                                    document.querySelector("input[name='party_head_edit']").value = political_party.party_head;

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
            const cancelButton = element.querySelector('[kt_modal_edit_political_party="cancel"]');
            const closeButton = element.querySelector('[kt_modal_edit_political_party="close"]');
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
                              'party_name_edit': {
                                    validators: {
                                          notEmpty: {
                                                message: 'পার্টির নাম প্রয়োজন'
                                          },
                                          stringLength: {
                                                max: 50,
                                                message: 'সর্বোচ্চ ৫০ অক্ষরের হতে পারবে'
                                          }
                                    }
                              },
                              'party_head_edit': {
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

            const submitButton = element.querySelector('[kt_modal_edit_political_party="submit"]');

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

                                    console.log(politicalPartyId);
                                    fetch(`/political-parties/${politicalPartyId}`, {
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
                                                      toastr.success(data.message || 'Party updated successfully');
                                                      modal.hide();
                                                      setTimeout(() => {
                                                            window.location.reload();
                                                      }, 1500); // 1000ms = 1 second delay
                                                } else {
                                                      throw new Error(data.message || 'Party Update failed');
                                                }
                                          })
                                          .catch(error => {
                                                submitButton.removeAttribute('data-kt-indicator');
                                                submitButton.disabled = false;
                                                toastr.error(error.message || 'Failed to update party');
                                                console.error('Error:', error);
                                          });
                              } else {
                                    toastr.warning('অনুগ্রহ করে প্রয়োজনীয় সকল তথ্য দিন');
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
      AllPoliticalPartyList.init();
      KTAddPoliticalParty.init();
      KTEditPoliticalParty.init();
});