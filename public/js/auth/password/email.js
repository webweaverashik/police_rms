"use strict";

// Class Definition
var KTAuthResetPassword = function () {
      // Elements
      var form;
      var submitButton;
      var validator;

      var handleForm = function (e) {
            // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
            validator = FormValidation.formValidation(
                  form,
                  {
                        fields: {
                              'email': {
                                    validators: {
                                          regexp: {
                                                regexp: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                                                message: 'এই ইমেইলটি সঠিক নয়।',
                                          },
                                          notEmpty: {
                                                message: 'ইমেইল এড্রেস প্রয়োজন'
                                          }
                                    }
                              }
                        },
                        plugins: {
                              trigger: new FormValidation.plugins.Trigger(),
                              bootstrap: new FormValidation.plugins.Bootstrap5({
                                    rowSelector: '.fv-row',
                                    eleInvalidClass: '',  // comment to enable invalid state icons
                                    eleValidClass: '' // comment to enable valid state icons
                              })
                        }
                  }
            );

      }

      var handleSubmitAjax = function () {
            form.addEventListener('submit', function (e) {
                  e.preventDefault(); // prevent default form submission (page reload)

                  validator.validate().then(function (status) {
                        if (status === 'Valid') {
                              submitButton.setAttribute('data-kt-indicator', 'on');
                              submitButton.disabled = true;

                              axios.post(form.getAttribute('action'), new FormData(form))
                                    .then(function (response) {
                                          form.reset();

                                          Swal.fire({
                                                text: "আমরা আপনার ইমেইলে একটি পাসওয়ার্ড রিসেট লিঙ্ক পাঠিয়েছি।",
                                                icon: "success",
                                                buttonsStyling: false,
                                                confirmButtonText: "আচ্ছা, ঠিক আছে!",
                                                customClass: {
                                                      confirmButton: "btn btn-primary"
                                                }
                                          }).then(function (result) {
                                                if (result.isConfirmed) {
                                                      var redirectUrl = form.getAttribute('data-kt-redirect-url');
                                                      if (redirectUrl) {
                                                            location.href = redirectUrl;
                                                      }
                                                }
                                          });
                                    })
                                    .catch(function (error) {
                                          let message = "দুঃখিত, মনে হচ্ছে কিছু ত্রুটি ধরা পড়েছে, অনুগ্রহ করে আবার চেষ্টা করুন।";

                                          if (error.response && error.response.data && error.response.data.message) {
                                                message = error.response.data.message;
                                          }

                                          Swal.fire({
                                                text: message,
                                                icon: "error",
                                                buttonsStyling: false,
                                                confirmButtonText: "আচ্ছা, ঠিক আছে!",
                                                customClass: {
                                                      confirmButton: "btn btn-primary"
                                                }
                                          });
                                    })

                                    .then(function () {
                                          submitButton.removeAttribute('data-kt-indicator');
                                          submitButton.disabled = false;
                                    });
                        } else {
                              toastr.options = {
                                    "closeButton": true,
                                    "debug": false,
                                    "newestOnTop": true,
                                    "progressBar": true,
                                    "positionClass": "toastr-top-right",
                                    "preventDuplicates": false,
                                    "onclick": null,
                                    "showDuration": "300",
                                    "hideDuration": "1000",
                                    "timeOut": "5000",
                                    "extendedTimeOut": "1000",
                                    "showEasing": "swing",
                                    "hideEasing": "linear",
                                    "showMethod": "fadeIn",
                                    "hideMethod": "fadeOut"
                              };

                              toastr.error("দয়া করে সঠিক ইমেইল এড্রেস প্রবেশ করুন এবং পুনরায় আবার চেষ্টা করুন।");
                        }
                  });
            });
      };


      var isValidUrl = function (url) {
            try {
                  new URL(url);
                  return true;
            } catch (e) {
                  return false;
            }
      }

      // Public Functions
      return {
            // public functions
            init: function () {
                  form = document.querySelector('#kt_password_reset_form');
                  submitButton = document.querySelector('#kt_password_reset_submit');

                  handleForm();

                  if (isValidUrl(form.getAttribute('action'))) {
                        handleSubmitAjax(); // use for ajax submit
                  } else {
                        // handleSubmitDemo(); // used for demo purposes only
                  }
            }
      };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
      KTAuthResetPassword.init();
});
