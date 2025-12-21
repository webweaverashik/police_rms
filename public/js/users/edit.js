"use strict";

var KTEditUserForm = function () {

      const form = document.getElementById('kt_edit_user_form');
      const submitButton = document.getElementById('kt_edit_user_form_submit');

      // 🔹 Get user ID from Blade
      const userId = form.dataset.userId;

      const roleSelect = form.querySelector('select[name="role_id"]');
      const zoneWrapper = form.querySelector('select[name="zone_id"]').closest('.col-lg-4');
      const zoneSelect = form.querySelector('select[name="zone_id"]');

      const rolesRequiringZone = ['Viewer', 'Operator'];

      const roleBnToEn = {
            'সুপার এডমিন': 'SuperAdmin',
            'এডমিন': 'Admin',
            'পর্যবেক্ষক': 'Viewer',
            'ম্যাজিস্ট্রেট': 'Magistrate',
            'তৈরিকারি': 'Operator',
      };

      let validator = null;

      // ---------------------------
      // Role → Zone toggle
      // ---------------------------
      const initRoleZoneToggle = () => {
            $(roleSelect).on('change', function () {
                  const text = this.options[this.selectedIndex]?.text.trim();
                  const roleName = roleBnToEn[text] || text;

                  if (rolesRequiringZone.includes(roleName)) {
                        zoneWrapper.classList.remove('d-none');
                        zoneSelect.disabled = false;
                        validator.enableValidator('zone_id');
                  } else {
                        zoneWrapper.classList.add('d-none');
                        $(zoneSelect).val(null).trigger('change');
                        zoneSelect.disabled = true;
                        validator.disableValidator('zone_id');
                  }
            });

            // Trigger once on load
            $(roleSelect).trigger('change');
      };

      // ---------------------------
      // Validation + Submit
      // ---------------------------
      const initValidation = () => {
            validator = FormValidation.formValidation(form, {
                  fields: {
                        name: { validators: { notEmpty: { message: 'ইউজারের নাম লিখুন' } } },
                        bp_number: {
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
                        designation_id: { validators: { notEmpty: { message: 'পদবী সিলেক্ট করুন' } } },
                        role_id: { validators: { notEmpty: { message: 'ইউজারের রোল সিলেক্ট করুন' } } },
                        zone_id: {
                              enabled: false,
                              validators: { notEmpty: { message: 'থানা সিলেক্ট করুন' } }
                        },
                        email: {
                              validators: {
                                    notEmpty: { message: 'ইমেইল প্রয়োজন' },
                                    emailAddress: { message: 'সঠিক ইমেইল দিন' }
                              }
                        },
                        mobile_no: {
                              validators: {
                                    notEmpty: { message: 'মোবাইল নং লিখুন' },
                                    regexp: {
                                          regexp: /^01[3-9][0-9]{8}$/,
                                          message: 'সঠিক মোবাইল নাম্বার দিন'
                                    }
                              }
                        }
                  },
                  plugins: {
                        trigger: new FormValidation.plugins.Trigger(),
                        bootstrap: new FormValidation.plugins.Bootstrap5({
                              rowSelector: '.fv-row'
                        })
                  }
            });

            submitButton.addEventListener('click', function (e) {
                  e.preventDefault();

                  validator.validate().then(status => {
                        if (status !== 'Valid') {
                              toastr.warning('প্রয়োজনীয় তথ্য দিন');
                              return;
                        }

                        submitButton.setAttribute('data-kt-indicator', 'on');
                        submitButton.disabled = true;

                        const formData = new FormData(form);
                        formData.append('_method', 'PUT');
                        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

                        fetch(editUserRoute.replace(':id', userId), {
                              method: 'POST',
                              body: formData,
                              headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                              }
                        })
                              .then(res => res.json())
                              .then(data => {
                                    submitButton.removeAttribute('data-kt-indicator');
                                    submitButton.disabled = false;

                                    if (data.success) {
                                          toastr.success(data.message);
                                          setTimeout(() => window.location.href = data.redirect, 1200);
                                    } else {
                                          toastr.error(data.message);
                                    }
                              })
                              .catch(err => {
                                    submitButton.removeAttribute('data-kt-indicator');
                                    submitButton.disabled = false;
                                    toastr.error('আপডেট করতে সমস্যা হয়েছে');
                                    console.error(err);
                              });
                  });
            });
      };

      return {
            init: function () {
                  initValidation();
                  initRoleZoneToggle();
            }
      };
}();

KTUtil.onDOMContentLoaded(function () {
      KTEditUserForm.init();
});
