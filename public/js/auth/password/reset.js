"use strict";

// Class Definition
var KTAuthNewPassword = function () {
      // Elements
      var form;
      var submitButton;
      var validator;
      var passwordMeter;

      var handleForm = function () {
            // Init form validation rules using FormValidation plugin
            validator = FormValidation.formValidation(
                  form,
                  {
                        fields: {
                              'password': {
                                    validators: {
                                          notEmpty: {
                                                message: 'পাসওয়ার্ড প্রয়োজন'
                                          },
                                          callback: {
                                                message: 'অনুগ্রহ করে আরও শক্তিশালী পাসওয়ার্ড লিখুন।',
                                                callback: function (input) {
                                                      if (input.value.length > 0) {
                                                            return validatePassword();
                                                      }
                                                }
                                          }
                                    }
                              },
                              'password_confirmation': {
                                    validators: {
                                          notEmpty: {
                                                message: 'পাসওয়ার্ড নিশ্চিতকরণ প্রয়োজন'
                                          },
                                          identical: {
                                                compare: function () {
                                                      return form.querySelector('[name="password"]').value;
                                                },
                                                message: 'পাসওয়ার্ড এবং এর নিশ্চিতকরণ মিলছে না।'
                                          }
                                    }
                              },
                        },
                        plugins: {
                              trigger: new FormValidation.plugins.Trigger({
                                    event: {
                                          password: false
                                    }
                              }),
                              bootstrap: new FormValidation.plugins.Bootstrap5({
                                    rowSelector: '.fv-row',
                                    eleInvalidClass: '',
                                    eleValidClass: ''
                              })
                        }
                  }
            );

            form.querySelector('input[name="password"]').addEventListener('input', function () {
                  if (this.value.length > 0) {
                        validator.updateFieldStatus('password', 'NotValidated');
                  }
            });
      };

      var handleSubmitAjax = function () {
            submitButton.addEventListener('click', function (e) {
                  e.preventDefault();

                  validator.revalidateField('password');

                  validator.validate().then(function (status) {
                        if (status === 'Valid') {
                              submitButton.setAttribute('data-kt-indicator', 'on');
                              submitButton.disabled = true;

                              axios.post(form.action, new FormData(form))
                                    .then(function (response) {
                                          // Success popup
                                          Swal.fire({
                                                icon: 'success',
                                                title: 'পাসওয়ার্ড রিসেট সফল হয়েছে।',
                                                text: response.data.message || 'আপনার পাসওয়ার্ড আপডেট করা হয়েছে। অনুগ্রহ করে সাইন ইন করুন।',
                                                buttonsStyling: false,
                                                confirmButtonText: 'আচ্ছা, ঠিক আছে!',
                                                customClass: {
                                                      confirmButton: 'btn btn-primary'
                                                }
                                          }).then(function (result) {
                                                if (result.isConfirmed) {
                                                      form.reset();
                                                      passwordMeter.reset();

                                                      var redirectUrl = form.getAttribute('data-kt-redirect-url');
                                                      if (redirectUrl) {
                                                            window.location.href = redirectUrl;
                                                      }
                                                }
                                          });
                                    })
                                    .catch(function (error) {
                                          let message = "কিছু সমস্যা হয়েছে, আবার চেষ্টা করুন।";

                                          if (error.response) {
                                                if (error.response.data && error.response.data.message) {
                                                      message = error.response.data.message;
                                                } else if (error.response.data && error.response.data.errors) {
                                                      // Laravel validation errors
                                                      const errors = Object.values(error.response.data.errors).flat();
                                                      message = errors.join('<br>');
                                                }
                                          }

                                          Swal.fire({
                                                icon: 'error',
                                                title: 'Error',
                                                html: message,
                                                buttonsStyling: false,
                                                confirmButtonText: 'আচ্ছা, ঠিক আছে!',
                                                customClass: {
                                                      confirmButton: 'btn btn-primary'
                                                }
                                          });
                                    })
                                    .then(function () {
                                          submitButton.removeAttribute('data-kt-indicator');
                                          submitButton.disabled = false;
                                    });
                        } else {
                              Swal.fire({
                                    icon: 'error',
                                    title: 'Validation Error',
                                    text: 'সাবমিট করার আগে ফর্মের ত্রুটিগুলি ঠিক করুন।',
                                    buttonsStyling: false,
                                    confirmButtonText: 'আচ্ছা, ঠিক আছে!',
                                    customClass: {
                                          confirmButton: 'btn btn-primary'
                                    }
                              });
                        }
                  });
            });
      };

      var validatePassword = function () {
            return passwordMeter.getScore() > 50;
      };

      return {
            // public functions
            init: function () {
                  form = document.querySelector('#kt_new_password_form');
                  submitButton = document.querySelector('#kt_new_password_submit');
                  passwordMeter = KTPasswordMeter.getInstance(form.querySelector('[data-kt-password-meter="true"]'));

                  handleForm();
                  handleSubmitAjax();
            }
      };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
      KTAuthNewPassword.init();
});
