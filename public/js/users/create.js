"use strict";

// Class definition
var KTCreateUserForm = function () {
      // Elements
      const form = document.getElementById('kt_create_user_form');

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

      const resetButton = document.getElementById('kt_create_user_form_reset');

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
                              'name': {
                                    validators: {
                                          notEmpty: {
                                                message: 'ইউজারের নাম লিখুন'
                                          }
                                    }
                              },
                              'designation_id': {
                                    validators: {
                                          notEmpty: {
                                                message: 'পদবী সিলেক্ট করুন'
                                          }
                                    }
                              },
                              'role_id': {
                                    validators: {
                                          notEmpty: {
                                                message: 'ইউজারের রোল সিলেক্ট করুন'
                                          }
                                    }
                              },
                              'zone_id': {
                                    validators: {
                                          notEmpty: {
                                                message: 'থানা সিলেক্ট করুন'
                                          }
                                    }
                              },
                              'zone_id': {
                                    validators: {
                                          notEmpty: {
                                                message: 'থানা সিলেক্ট করুন'
                                          }
                                    }
                              },
                              'email': {
                                    validators: {
                                          notEmpty: {
                                                message: 'লগিন করার জন্য ইউজারের ইমেইল প্রয়োজন'
                                          },
                                          emailAddress: {
                                                message: 'অনুগ্রহ করে সঠিক ইমেইল দিন',
                                          },
                                    }
                              },
                              'mobile_no': {
                                    validators: {
                                          notEmpty: {
                                                message: 'মোবাইল নং লিখুন'
                                          },
                                          regexp: {
                                                regexp: /^01[3-9][0-9](?!\b(\d)\1{7}\b)\d{7}$/,
                                                message: 'একটি সঠিক বাংলাদেশি মোবাইল নাম্বার লিখুন'
                                          },
                                          stringLength: {
                                                min: 11,
                                                max: 11,
                                                message: 'মোবাইল নাম্বার ১১ ডিজিটের হবে।'
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

            const submitButton = document.getElementById('kt_create_user_form_submit');

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

                                    fetch(storeUserRoute, {
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
      KTCreateUserForm.init();
});