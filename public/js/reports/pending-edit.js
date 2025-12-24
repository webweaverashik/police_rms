"use strict";

// Class definition
var KTUpdateReportForm = function () {
      // Elements
      const form = document.getElementById('kt_update_report_form');


      const resetButton = document.getElementById('kt_update_report_form_reset');

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
                              'actual_attendee_count': {
                                    validators: {
                                          notEmpty: {
                                                message: 'মোট উপস্থিতির সংখ্যা লিখুন।'
                                          },
                                          greaterThan: {
                                                min: 10,
                                                message: 'ন্যূনতম ১০ জন সংখ্যা দেওয়া যাবে।'
                                          }
                                    }
                              },
                              'program_status': {
                                    validators: {
                                          notEmpty: {
                                                message: 'প্রোগ্রামের অবস্থা সিলেক্ট করুন।'
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

            const submitButton = document.getElementById('kt_update_report_form_submit');

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


      // Public functions
      return {
            // public functions
            init: function () {
                  initValidation();
            }
      };

}();


// On document ready
KTUtil.onDOMContentLoaded(function () {
      KTUpdateReportForm.init();
});