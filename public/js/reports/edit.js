"use strict";

// Class definition
var KTEditReportForm = function () {
      // Elements
      const form = document.getElementById('kt_edit_report_form');

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

      const resetButton = document.getElementById('kt_edit_report_form_reset');

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
                                                message: 'সংসদীয় আসন তথ্য প্রয়োজন'
                                          }
                                    }
                              },
                              'upazila_id': {
                                    validators: {
                                          notEmpty: {
                                                message: 'উপজেলার তথ্য প্রয়োজন'
                                          }
                                    }
                              },
                              'union_id': {
                                    validators: {
                                          notEmpty: {
                                                message: 'ইউনিয়ন তথ্য প্রয়োজন'
                                          },
                                    }
                              },
                              'zone_id': {
                                    validators: {
                                          notEmpty: {
                                                message: 'থানা / জোনের তথ্য প্রয়োজন'
                                          },
                                    }
                              },
                              'political_party_id': {
                                    validators: {
                                          notEmpty: {
                                                message: 'রাজনৈতিক দলের নাম প্রয়োজন'
                                          },
                                    }
                              },
                              'candidate_name': {
                                    validators: {
                                          notEmpty: {
                                                message: 'প্রার্থীর নাম প্রয়োজন।'
                                          },
                                    }
                              },
                              // 'program_date': {
                              //       validators: {
                              //             notEmpty: {
                              //                   message: 'তারিখ উল্লেখ করুন'
                              //             },
                              //       }
                              // },
                              // 'program_time': {
                              //       validators: {
                              //             notEmpty: {
                              //                   message: 'সময় উল্লেখ করুন'
                              //             },
                              //       }
                              // },
                              // 'location_name': {
                              //       validators: {
                              //             notEmpty: {
                              //                   message: 'প্রোগ্রামারের স্থান উল্লেখ করুন'
                              //             },
                              //       }
                              // },
                              'tentative_attendee_count': {
                                    validators: {
                                          greaterThan: {
                                                min: 10,
                                                message: 'ন্যূনতম ১০ জন সংখ্যা দেওয়া যাবে নতুবা ফাঁকা রাখুন।'
                                          }
                                    }
                              },
                              'program_type_id': {
                                    validators: {
                                          notEmpty: {
                                                message: 'প্রোগ্রামের ধরণ বাছাই করুন।'
                                          },
                                    }
                              },
                              'program_status': {
                                    validators: {
                                          notEmpty: {
                                                message: 'প্রোগ্রামের অবস্থা জানানো প্রয়োজন।'
                                          },
                                    }
                              },
                              'program_title': {
                                    validators: {
                                          notEmpty: {
                                                message: 'প্রোগ্রামের বিষয় লিখুন।'
                                          },
                                    }
                              },
                              // 'program_description': {
                              //       validators: {
                              //             notEmpty: {
                              //                   message: 'প্রোগ্রামের বিস্তারিত লিখুন।'
                              //             },
                              //       }
                              // },
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

            const submitButton = document.getElementById('kt_edit_report_form_submit');

            if (submitButton && validator) {
                  submitButton.addEventListener('click', function (e) {
                        e.preventDefault();

                        validator.validate().then(function (status) {
                              if (status === 'Valid') {

                                    submitButton.setAttribute('data-kt-indicator', 'on');
                                    submitButton.disabled = true;

                                    const formData = new FormData(form);
                                    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                                    formData.append('_method', 'PUT'); // ✅ update method

                                    fetch(updateReportRoute, {
                                          method: "POST", // Laravel handles PUT via spoofing
                                          body: formData,
                                          headers: {
                                                'Accept': 'application/json',
                                                'X-Requested-With': 'XMLHttpRequest'
                                          }
                                    })
                                          .then(async response => {
                                                const data = await response.json();

                                                if (!response.ok) {
                                                      throw new Error(data.message || 'প্রতিবেদন আপডেট ব্যর্থ');
                                                }

                                                return data;
                                          })
                                          .then(data => {
                                                submitButton.removeAttribute('data-kt-indicator');
                                                submitButton.disabled = false;

                                                if (data.success) {
                                                      toastr.success(data.message || 'প্রতিবেদন সফলভাবে আপডেট হয়েছে');
                                                      setTimeout(() => {
                                                            window.location.href = data.redirect || '/reports';
                                                      }, 1200);
                                                } else {
                                                      toastr.error(data.message || 'আপডেট করা যায়নি');
                                                }
                                          })
                                          .catch(error => {
                                                submitButton.removeAttribute('data-kt-indicator');
                                                submitButton.disabled = false;
                                                toastr.error(error.message || 'Something went wrong');
                                                console.error(error);
                                          });

                              } else {
                                    toastr.warning('অনুগ্রহ করে প্রয়োজনীয় সকল তথ্য দিন');
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
                                    toastr.warning('এই উপজেলার জন্য কোনো ইউনিয়ন পাওয়া যায়নি');
                              }

                              unionSelect.trigger('change');
                        })
                        .catch(error => {
                              console.error(error);
                              toastr.error('ইউনিয়ন লোড করা যায়নি');
                        });
            });
      }


      // ================================
      // Load Political Parties by Seat
      // ================================
      function initSeatWiseParties() {
            const partySelect = $('select[name="political_party_id"]');
            const candidateInput = $('input[name="candidate_name"]');

            function getSelectedSeatId() {
                  const seat = $('input[name="parliament_seat_id"]:checked');
                  return seat.length ? seat.val() : null;
            }

            $('input[name="parliament_seat_id"]').on('change', function () {
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
                              toastr.error('রাজনৈতিক দল লোড করা যায়নি');
                        });
            });
      }



      // =====================================
      // Load Candidate by Seat + Party
      // =====================================
      function initCandidateBySeatAndParty() {
            const partySelect = $('select[name="political_party_id"]');
            const candidateInput = $('input[name="candidate_name"]');

            function getSelectedSeatId() {
                  const seat = $('input[name="parliament_seat_id"]:checked');
                  return seat.length ? seat.val() : null;
            }

            partySelect.on('change', function () {
                  const seatId = getSelectedSeatId();
                  const partyId = $(this).val();

                  candidateInput.val('');

                  if (!seatId || !partyId) {
                        candidateInput.prop('disabled', true); // 🔒 still disabled
                        return;
                  }

                  // ✅ Enable candidate input once party is selected
                  candidateInput.prop('disabled', false);

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
                              if (data.success && data.candidate_name) {
                                    candidateInput.val(data.candidate_name);
                              }
                        })
                        .catch(err => {
                              console.error(err);
                              toastr.error('প্রার্থীর তথ্য লোড করা যায়নি');
                        });
            });
      }


      // Public functions
      return {
            // public functions
            init: function () {
                  initValidation();
                  initUnionByUpazila();

                  initSeatWiseParties();
                  initCandidateBySeatAndParty();
            }
      };

}();


// On document ready
KTUtil.onDOMContentLoaded(function () {
      KTEditReportForm.init();
});