"use strict";

// Class definition
var KTCreateUserForm = function () {
      // Elements
      const form = document.getElementById('kt_create_user_form');
      const roleSelect = form ? form.querySelector('select[name="role_id"]') : null;
      const zoneWrapper = form ? form.querySelector('select[name="zone_id"]').closest('.col-lg-4') : null;
      const zoneSelect = form ? form.querySelector('select[name="zone_id"]') : null;

      // Roles that require zone assignment
      const rolesRequiringZone = ['Viewer', 'Operator'];

      // Role name mapping (Bengali to English)
      const roleBnToEn = {
            'সুপার এডমিন': 'SuperAdmin',
            'এডমিন': 'Admin',
            'পর্যবেক্ষক': 'Viewer',
            'ম্যাজিস্ট্রেট': 'Magistrate',
            'তৈরিকারি': 'Operator',
      };

      // FormValidation instance
      var validator = null;

      // ---- Handle Role Change for Zone Visibility ----
      var initRoleZoneToggle = function () {
            if (!roleSelect || !zoneWrapper || !zoneSelect) return;

            // Using Select2 change event
            $(roleSelect).on('change', function () {
                  const selectedOption = this.options[this.selectedIndex];
                  const selectedText = selectedOption ? selectedOption.text.trim() : '';
                  const roleName = roleBnToEn[selectedText] || selectedText;

                  if (rolesRequiringZone.includes(roleName)) {
                        // Show zone assignment field
                        zoneWrapper.classList.remove('d-none');
                        zoneSelect.disabled = false;

                        // Re-initialize Select2 after enabling
                        $(zoneSelect).prop('disabled', false);

                        // Enable validation for zone_id
                        if (validator) {
                              validator.enableValidator('zone_id');
                        }
                  } else {
                        // Hide zone assignment field
                        zoneWrapper.classList.add('d-none');
                        zoneSelect.disabled = true;

                        // Clear and disable Select2
                        $(zoneSelect).val(null).trigger('change').prop('disabled', true);

                        // Clear validation state
                        $(zoneSelect).next('.select2').find('.select2-selection')
                              .removeClass('is-valid is-invalid');

                        // Clear error messages for zone_id
                        const zoneErrorContainer = zoneWrapper.querySelector('.fv-plugins-message-container');
                        if (zoneErrorContainer) {
                              zoneErrorContainer.innerHTML = '';
                        }

                        // Disable validation for zone_id
                        if (validator) {
                              validator.disableValidator('zone_id');
                        }
                  }
            });

            // Initialize on page load - check if role is already selected
            const initialOption = roleSelect.options[roleSelect.selectedIndex];
            if (initialOption && initialOption.value) {
                  $(roleSelect).trigger('change');
            }
      };

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
                  $(this).empty();
                  $(this).removeClass('fv-plugins-message-container--enabled');
            });

            // 4) Hide zone field on reset
            if (zoneWrapper && zoneSelect) {
                  zoneWrapper.classList.add('d-none');
                  $(zoneSelect).prop('disabled', true);
                  if (validator) {
                        validator.disableValidator('zone_id');
                  }
            }
      }

      const resetButton = document.getElementById('kt_create_user_form_reset');

      if (resetButton) {
            resetButton.addEventListener('click', e => {
                  resetSelect2Inputs();
            });
      }

      // Form validation
      var initValidation = function () {
            if (!form) return;

            validator = FormValidation.formValidation(
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
                              'bp_number': {
                                    validators: {
                                          notEmpty: {
                                                message: 'বিপি বা আইডি নাম্বার লিখুন'
                                          },
                                          regexp: {
                                                regexp: /^[0-9]+$/,
                                                message: 'বিপি নাম্বার শুধুমাত্র সংখ্যা হতে হবে।'
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
                                    enabled: false, // Disabled by default, enabled when Viewer/Operator selected
                                    validators: {
                                          notEmpty: {
                                                message: 'থানা সিলেক্ট করুন'
                                          }
                                    }
                              },
                              'email': {
                                    validators: {
                                          notEmpty: {
                                                message: 'লগিন করার জন্য ইউজারের ইমেইল প্রয়োজন'
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
                        e.preventDefault();

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
                                                'Accept': 'application/json',
                                                'X-Requested-With': 'XMLHttpRequest'
                                          }
                                    })
                                          .then(async response => {
                                                const data = await response.json();

                                                if (!response.ok) {
                                                      throw {
                                                            message: data.message || 'ইউজার তৈরি অসফল',
                                                            errors: data.errors || null
                                                      };
                                                }

                                                return data;
                                          })
                                          .then(data => {
                                                submitButton.removeAttribute('data-kt-indicator');
                                                submitButton.disabled = false;

                                                if (data.success) {
                                                      toastr.success(data.message || 'ইউজার সফলভাবে তৈরি হয়েছে।');
                                                      // Redirect to users index page
                                                      setTimeout(() => {
                                                            window.location.href = data.redirect || '/users';
                                                      }, 1200);
                                                } else {
                                                      toastr.error(data.message || 'ইউজার তৈরি করা যায়নি।');
                                                }
                                          })
                                          .catch(error => {
                                                submitButton.removeAttribute('data-kt-indicator');
                                                submitButton.disabled = false;

                                                // Handle validation errors from server
                                                if (error.errors) {
                                                      const errorMessages = [...new Set(Object.values(error.errors).flat())].join('<br>');
                                                      toastr.error(errorMessages || error.message);
                                                } else {
                                                      toastr.error(error.message || 'ইউজার তৈরি করতে সমস্যা হয়েছে');
                                                }
                                                console.error('Error:', error);
                                          });

                              } else {
                                    toastr.warning('অনুগ্রহ করে প্রয়োজনীয় সকল তথ্য দিন');
                              }
                        });
                  });
            }
      };

      // Public functions
      return {
            init: function () {
                  initValidation();
                  initRoleZoneToggle();
            }
      };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
      KTCreateUserForm.init();
});