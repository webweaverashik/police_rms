"use strict";

// Class definition
var KTCreateReportForm = function () {
      // Elements
      const form = document.getElementById('kt_create_report_form');

      // ---- Reset Select2 inputs ----
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

      const resetButton = document.getElementById('kt_create_report_form_reset');

      if (resetButton) {
            resetButton.addEventListener('click', e => {
                  resetSelect2Inputs()
            });
      }
      // --------------------

      // Form validation
      var initValidation = function () {
            if (!form) return;

            var validator = FormValidation.formValidation(
                  form,
                  {
                        fields: {
                              'parliament_seat_id': {
                                    validators: {
                                          notEmpty: {
                                                message: 'সংসদীয় আসন সিলেক্ট করুন'
                                          }
                                    }
                              },
                              'upazila_id': {
                                    validators: {
                                          notEmpty: {
                                                message: 'উপজেলার সিলেক্ট করুন'
                                          }
                                    }
                              },
                              'union_id': {
                                    validators: {
                                          notEmpty: {
                                                message: 'ইউনিয়ন সিলেক্ট করুন'
                                          },
                                    }
                              },
                              'zone_id': {
                                    validators: {
                                          notEmpty: {
                                                message: 'থানার সিলেক্ট করুন'
                                          },
                                    }
                              },
                              'political_party_id': {
                                    validators: {
                                          notEmpty: {
                                                message: 'রাজনৈতিক দল সিলেক্ট করুন'
                                          },
                                    }
                              },
                              'candidate_name': {
                                    validators: {
                                          notEmpty: {
                                                message: 'প্রার্থীর নাম প্রয়োজন।'
                                          },
                                    }
                              },
                              'tentative_attendee_count': {
                                    validators: {
                                          greaterThan: {
                                                min: 10,
                                                message: 'ন্যূনতম ১০ জন সংখ্যা দেওয়া যাবে নতুবা ফাঁকা রাখুন।'
                                          }
                                    }
                              },
                              'program_type_id': {
                                    validators: {
                                          notEmpty: {
                                                message: 'প্রোগ্রামের ধরণ সিলেক্ট করুন'
                                          },
                                    }
                              },
                              'program_status': {
                                    validators: {
                                          notEmpty: {
                                                message: 'প্রোগ্রামের অবস্থা জানানো প্রয়োজন।'
                                          },
                                    }
                              },
                              'program_title': {
                                    validators: {
                                          notEmpty: {
                                                message: 'প্রোগ্রামের বিষয় লিখুন।'
                                          },
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

            const submitButton = document.getElementById('kt_create_report_form_submit');

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

                                    fetch(storeReportRoute, {
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
                                                            message: data.message || 'প্রতিবেদন এন্ট্রি অসফল',
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
                                                      toastr.success(data.message || 'প্রতিবেদনটি সফলভাবে দাখিল হয়েছে।');
                                                      // ✅ Redirect to reports page
                                                      setTimeout(() => {
                                                            window.location.href = data.redirect || '/reports';
                                                      }, 1200);
                                                } else {
                                                      toastr.error(data.message || 'প্রতিবেদনটি তৈরি করা যায়নি।');
                                                }
                                          })
                                          .catch(error => {
                                                submitButton.removeAttribute('data-kt-indicator');
                                                submitButton.disabled = false;
                                                toastr.error(error.message || 'Failed to create report');
                                                console.error('Error:', error);
                                          });

                              } else {
                                    toastr.warning('অনুগ্রহ করে প্রয়োজনীয় সকল তথ্য দিন');
                              }
                        });
                  });
            }
      }

      // Initalizing flatpickr
      $("#program_date_picker").flatpickr({
            enableTime: false,
            dateFormat: "d-m-Y",
      });

      $("#program_time_picker").flatpickr({
            noCalendar: true,
            enableTime: true,
            dateFormat: "h:i K",
      });

      // ===================================
      // Load Upazilas by Parliament Seat
      // ===================================
      function initUpazilasBySeat() {
            const seatSelect = $('select[name="parliament_seat_id"]');
            const upazilaSelect = $('select[name="upazila_id"]');
            const zoneSelect = $('select[name="zone_id"]');

            if (!seatSelect.length || !upazilaSelect.length) return;

            // ✅ Use jQuery .on('change') for Select2 compatibility
            seatSelect.on('change', function () {
                  const seatId = $(this).val();

                  // Reset upazila and zone fields
                  upazilaSelect
                        .empty()
                        .append('<option></option>')
                        .prop('disabled', true)
                        .trigger('change');

                  if (zoneSelect.length) {
                        zoneSelect
                              .empty()
                              .append('<option></option>')
                              .prop('disabled', true)
                              .trigger('change');
                  }

                  if (!seatId) return;

                  // Build URL with query parameter
                  const url = `${fetchUpazilasBySeatRoute}?parliament_seat_id=${seatId}`;

                  fetch(url, {
                        headers: {
                              'Accept': 'application/json',
                              'X-Requested-With': 'XMLHttpRequest'
                        }
                  })
                        .then(response => response.json())
                        .then(upazilas => {
                              if (Array.isArray(upazilas) && upazilas.length > 0) {
                                    upazilas.forEach(upazila => {
                                          upazilaSelect.append(
                                                `<option value="${upazila.id}">${upazila.name}</option>`
                                          );
                                    });
                                    upazilaSelect.prop('disabled', false).trigger('change');
                              } else {
                                    toastr.warning('এই সংসদীয় আসনের জন্য কোনো উপজেলা পাওয়া যায়নি');
                              }
                        })
                        .catch(error => {
                              console.error('Error fetching upazilas:', error);
                              toastr.error('উপজেলা লোড করা যায়নি');
                        });
            });
      }

      // =======================
      // Load Zones by Upazila
      // =======================
      function initZonesByUpazila() {
            const upazilaSelect = $('select[name="upazila_id"]');
            const zoneSelect = $('select[name="zone_id"]');

            if (!upazilaSelect.length || !zoneSelect.length) return;

            // ✅ Use jQuery .on('change') for Select2 compatibility
            upazilaSelect.on('change', function () {
                  const upazilaId = $(this).val();

                  // Reset zone field
                  zoneSelect
                        .empty()
                        .append('<option></option>')
                        .prop('disabled', true)
                        .trigger('change');

                  if (!upazilaId) return;

                  // Build URL with query parameter
                  const url = `${fetchZonesByUpazilaRoute}?upazila_id=${upazilaId}`;

                  fetch(url, {
                        headers: {
                              'Accept': 'application/json',
                              'X-Requested-With': 'XMLHttpRequest'
                        }
                  })
                        .then(response => response.json())
                        .then(zones => {
                              if (Array.isArray(zones) && zones.length > 0) {
                                    zones.forEach(zone => {
                                          zoneSelect.append(
                                                `<option value="${zone.id}">${zone.name}</option>`
                                          );
                                    });
                                    zoneSelect.prop('disabled', false).trigger('change');
                              } else {
                                    toastr.warning('এই উপজেলার জন্য কোনো থানা পাওয়া যায়নি');
                              }
                        })
                        .catch(error => {
                              console.error('Error fetching zones:', error);
                              toastr.error('থানা লোড করা যায়নি');
                        });
            });
      }

      // =======================
      // Load Unions by Upazila
      // =======================
      function initUnionByUpazila() {
            const upazilaSelect = $('select[name="upazila_id"]');
            const unionSelect = $('select[name="union_id"]');

            upazilaSelect.on('change', function () {
                  const upazilaId = $(this).val();

                  // Reset union field
                  unionSelect
                        .empty()
                        .append('<option></option>')
                        .prop('disabled', true)
                        .trigger('change');

                  if (!upazilaId) {
                        return;
                  }

                  // Build URL
                  const url = fetchUnionRoute.replace(':upazila_id', upazilaId);

                  // Optional loading state
                  unionSelect.prop('disabled', true);

                  fetch(url, {
                        headers: {
                              'Accept': 'application/json',
                              'X-Requested-With': 'XMLHttpRequest'
                        }
                  })
                        .then(response => response.json())
                        .then(unions => {
                              if (Array.isArray(unions) && unions.length > 0) {
                                    unions.forEach(union => {
                                          unionSelect.append(
                                                `<option value="${union.id}">${union.name}</option>`
                                          );
                                    });
                                    unionSelect.prop('disabled', false);
                              } else {
                                    toastr.warning('এই উপজেলার জন্য কোনো ইউনিয়ন পাওয়া যায়নি');
                              }

                              unionSelect.trigger('change');
                        })
                        .catch(error => {
                              console.error(error);
                              toastr.error('ইউনিয়ন লোড করা যায়নি');
                        });
            });
      }

      // ================================
      // Load Political Parties by Seat
      // ================================
      function initSeatWiseParties() {
            const seatSelect = $('select[name="parliament_seat_id"]');
            const partySelect = $('select[name="political_party_id"]');
            const candidateInput = $('input[name="candidate_name"]');

            function getSelectedSeatId() {
                  return seatSelect.val() || null;
            }

            seatSelect.on('change', function () {
                  const seatId = getSelectedSeatId();

                  // Reset
                  partySelect
                        .empty()
                        .append('<option></option>')
                        .prop('disabled', true)
                        .trigger('change');

                  candidateInput
                        .val('')
                        .prop('disabled', true); // 🔒 keep disabled

                  if (!seatId) return;

                  fetch(`${fetchSeatPartiesRoute}?parliament_seat_id=${seatId}`, {
                        headers: {
                              'Accept': 'application/json',
                              'X-Requested-With': 'XMLHttpRequest'
                        }
                  })
                        .then(res => res.json())
                        .then(data => {
                              if (data.success && Array.isArray(data.parties)) {
                                    data.parties.forEach(party => {
                                          partySelect.append(
                                                `<option value="${party.id}">${party.name}</option>`
                                          );
                                    });
                                    // ✅ Enable party select AFTER seat chosen
                                    partySelect.prop('disabled', false);
                              }
                              partySelect.trigger('change');
                        })
                        .catch(err => {
                              console.error(err);
                              toastr.error('রাজনৈতিক দল লোড করা যায়নি');
                        });
            });
      }

      // =====================================
      // Load Candidate by Seat + Party
      // =====================================
      function initCandidateBySeatAndParty() {
            const seatSelect = $('select[name="parliament_seat_id"]');
            const partySelect = $('select[name="political_party_id"]');
            const candidateSelect = $('select[name="candidate_name"]');

            function getSelectedSeatId() {
                  return seatSelect.val() || null;
            }

            partySelect.on('change', function () {
                  const seatId = getSelectedSeatId();
                  const partyId = $(this).val();

                  // Reset candidate select
                  candidateSelect
                        .empty()
                        .append('<option></option>')
                        .prop('disabled', true)
                        .trigger('change');

                  if (!seatId || !partyId) {
                        return;
                  }

                  fetch(
                        `${fetchCandidateRoute}?parliament_seat_id=${seatId}&political_party_id=${partyId}`,
                        {
                              headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                              }
                        }
                  )
                        .then(res => res.json())
                        .then(data => {
                              if (data.success && Array.isArray(data.candidates) && data.candidates.length > 0) {
                                    // Loop through all candidates and add them as options
                                    data.candidates.forEach(candidate => {
                                          candidateSelect.append(
                                                `<option value="${candidate.candidate_name}">${candidate.candidate_name}</option>`
                                          );
                                    });
                                    candidateSelect.prop('disabled', false).trigger('change');

                                    // Auto-select if only one candidate
                                    if (data.candidates.length === 1) {
                                          candidateSelect.val(data.candidates[0].candidate_name).trigger('change');
                                    }
                              } else {
                                    // Enable select even if no candidates found
                                    candidateSelect.prop('disabled', false).trigger('change');
                                    if (partyId) {
                                          toastr.info('এই দলের জন্য কোনো প্রার্থী পাওয়া যায়নি');
                                    }
                              }
                        })
                        .catch(err => {
                              console.error(err);
                              toastr.error('প্রার্থীর তথ্য লোড করা যায়নি');
                        });
            });
      }

      // =====================================
      // Add Program Type - Mini Popup
      // =====================================
      function initAddProgramType() {
            const wrapper = $('#programTypeWrapper');
            const popup = $('#programTypePopup');
            const toggleBtn = $('#toggleProgramTypePopup');
            const input = $('#newProgramTypeName');
            const saveBtn = $('#saveProgramTypeBtn');
            const cancelBtn = $('#cancelProgramTypeBtn');
            const errorDiv = $('#programTypeError');
            const select = $('select[name="program_type_id"]');

            if (!wrapper.length) return;

            // Toggle popup
            toggleBtn.on('click', function (e) {
                  e.stopPropagation();
                  popup.toggleClass('show');
                  if (popup.hasClass('show')) {
                        input.focus();
                  }
            });

            // Cancel / Close
            cancelBtn.on('click', function () {
                  closePopup();
            });

            // Close popup helper
            function closePopup() {
                  popup.removeClass('show');
                  input.val('').removeClass('is-invalid');
                  errorDiv.text('').hide();
            }

            // Close on outside click
            $(document).on('click', function (e) {
                  if (!wrapper.is(e.target) && wrapper.has(e.target).length === 0) {
                        closePopup();
                  }
            });

            // Clear error on input
            input.on('input', function () {
                  $(this).removeClass('is-invalid');
                  errorDiv.text('').hide();
            });

            // Handle Enter & Escape keys
            input.on('keydown', function (e) {
                  if (e.key === 'Enter') {
                        e.preventDefault();
                        saveBtn.click();
                  } else if (e.key === 'Escape') {
                        closePopup();
                  }
            });

            // Save new program type
            saveBtn.on('click', function () {
                  const name = input.val().trim();

                  if (!name) {
                        input.addClass('is-invalid');
                        errorDiv.text('নাম প্রয়োজন').show();
                        return;
                  }

                  // Show loading
                  const originalHtml = saveBtn.html();
                  saveBtn.html('<span class="spinner-border spinner-border-sm"></span>').prop('disabled', true);

                  // AJAX request
                  $.ajax({
                        url: storeProgramTypeRoute,
                        method: 'POST',
                        data: {
                              name: name,
                              _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (data) {
                              // Add new option and select it
                              const newOption = new Option(data.program_type.name, data.program_type.id, true, true);
                              select.append(newOption).trigger('change');

                              closePopup();
                              toastr.success(data.message || 'সফলভাবে যোগ হয়েছে');
                        },
                        error: function (xhr) {
                              if (xhr.status === 422 && xhr.responseJSON?.errors?.name) {
                                    input.addClass('is-invalid');
                                    errorDiv.text(xhr.responseJSON.errors.name[0]).show();
                              } else {
                                    toastr.error('যোগ করা যায়নি');
                              }
                        },
                        complete: function () {
                              saveBtn.html(originalHtml).prop('disabled', false);
                        }
                  });
            });
      }

      // Public functions
      return {
            // public functions
            init: function () {
                  initValidation();
                  initUpazilasBySeat();
                  initZonesByUpazila();
                  initUnionByUpazila();

                  initSeatWiseParties();
                  initCandidateBySeatAndParty();

                  initAddProgramType();
            }
      };

}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
      KTCreateReportForm.init();
});