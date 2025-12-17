"use strict";

var KTUsersList = function () {
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
                  "autoWidth": false,  // Disable auto width
                  'columnDefs': [{ 
                        orderable: false, targets: [7, 8] 
                  }]
            });

            // Re-init functions on every table re-draw -- more info: https://datatables.net/reference/event/draw
            datatable.on('draw', function () {

            });
      }

      // Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
      var handleSearch = function () {
            const filterSearch = document.querySelector('[data-kt-user-table-filter="search"]');
            filterSearch.addEventListener('keyup', function (e) {
                  datatable.search(e.target.value).draw();
            });
      }

      // Delete users
      var handleDeletion = function () {
            document.querySelectorAll('.delete-user').forEach(item => {
                  item.addEventListener('click', function (e) {
                        e.preventDefault();

                        let userId = this.getAttribute('data-user-id');
                        console.log('User ID:', userId);

                        let url = routeDeleteUser.replace(':id', userId);  // Replace ':id' with actual user ID

                        Swal.fire({
                              title: 'আপনি কি নিশ্চিত ডিলিট করতে চান?',
                              text: "ডিলিট করার পর এই ইউজারের কোনো তথ্য থাকবে না। ",
                              icon: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'হ্যাঁ, ডিলিট করবো',
                              cancelButtonText: 'ক্যানসেল',
                        }).then((result) => {
                              if (result.isConfirmed) {
                                    fetch(url, {
                                          method: "DELETE",
                                          headers: {
                                                "Content-Type": "application/json",
                                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                                          },
                                    })
                                          .then(response => response.json())
                                          .then(data => {
                                                if (data.success) {
                                                      Swal.fire({
                                                            title: 'সফল!',
                                                            text: 'ইউজারটি সফলভাবে ডিলিট করা হয়েছে।',
                                                            icon: 'success',
                                                            confirmButtonText: 'ঠিক আছে।'
                                                      }).then(() => {
                                                            location.reload(); // Reload to reflect changes
                                                      });
                                                } else {
                                                      Swal.fire('ব্যর্থ!', 'ইউজার ডিলিট করা যায়নি।',
                                                            'error');
                                                }
                                          })
                                          .catch(error => {
                                                console.error("Fetch Error:", error);
                                                Swal.fire('ব্যর্থ!',
                                                      'একটি ত্রুটি হয়েছে। অনুগ্রহ করে সাপোর্টে যোগাযোগ করুন।',
                                                      'error');
                                          });
                              }
                        });
                  });
            });
      };

      // Toggle activation
      var handleToggleActivation = function () {
            const toggleInputs = document.querySelectorAll('.toggle-active');

            toggleInputs.forEach(input => {
                  input.addEventListener('change', function () {
                        const userId = this.value;
                        const isActive = this.checked ? 1 : 0;
                        const row = this.closest('tr'); // Get the parent <tr> element

                        console.log('User ID:', userId);

                        let url = routeToggleActive.replace(':id', userId);  // Replace ':id' with actual student ID


                        fetch(url, {
                              method: 'POST',
                              headers: {
                                    'Content-Type': 'application/json',
                                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                              },
                              body: JSON.stringify({
                                    user_id: userId,
                                    is_active: isActive
                              })
                        })
                              .then(response => {
                                    if (!response.ok) {
                                          throw new Error('Network response was not ok');
                                    }
                                    return response.json();
                              })
                              .then(data => {
                                    if (data.success) {
                                          toastr.success(data.message);
                                    } else {
                                          toastr.error(data.message);
                                    }
                              })
                              .catch(error => {
                                    console.error('Error:', error);
                                    toastr.error('Error occurred while toggling farm status');
                              });
                  });
            });
      };

      return {
            // Public functions  
            init: function () {
                  table = document.getElementById('kt_table_users');

                  if (!table) {
                        return;
                  }

                  initDatatable();
                  handleSearch();
                  handleDeletion();
                  handleToggleActivation();
            }
      }
}();

var KTUsersAddUser = function () {
      // Shared variables
      const element = document.getElementById('kt_modal_add_user');
      const form = element.querySelector('#kt_modal_add_user_form');
      const modal = new bootstrap.Modal(element);

      // Init add schedule modal
      var initAddUser = () => {

            // Cancel button handler
            const cancelButton = element.querySelector('[data-add-users-modal-action="cancel"]');
            cancelButton.addEventListener('click', e => {
                  e.preventDefault();

                  form.reset(); // Reset form			
                  modal.hide();
            });

            // Close button handler
            const closeButton = element.querySelector('[data-add-users-modal-action="close"]');
            closeButton.addEventListener('click', e => {
                  e.preventDefault();

                  form.reset(); // Reset form			
                  modal.hide();
            });
      }

      return {
            // Public functions
            init: function () {
                  initAddUser();
            }
      };
}();

var KTUsersEditUser = function () {
      // Shared variables
      const element = document.getElementById('kt_modal_edit_user');
      const form = element.querySelector('#kt_modal_edit_user_form');
      const modal = new bootstrap.Modal(element);

      // Init add schedule modal
      var initEditUser = () => {

            // Cancel button handler
            const cancelButton = element.querySelector('[data-edit-users-modal-action="cancel"]');
            cancelButton.addEventListener('click', e => {
                  e.preventDefault();

                  form.reset(); // Reset form			
                  modal.hide();
            });

            // Close button handler
            const closeButton = element.querySelector('[data-edit-users-modal-action="close"]');
            closeButton.addEventListener('click', e => {
                  e.preventDefault();

                  form.reset(); // Reset form			
                  modal.hide();
            });
      }

      return {
            // Public functions
            init: function () {
                  initEditUser();
            }
      };
}();

var KTUsersEditPassword = function () {
      // Shared variables
      const element = document.getElementById('kt_modal_edit_password');
      const form = element.querySelector('#kt_modal_edit_password_form');
      const modal = new bootstrap.Modal(element);

      let userId = null;
      let validator = null; // Declare validator globally

      // Init add schedule modal
      var initEditPassword = () => {
            const passwordInput = document.getElementById('teacherPasswordNew');
            const strengthText = document.getElementById('password-strength-text');
            const strengthBar = document.getElementById('password-strength-bar');

            // Cancel button handler
            const cancelButton = element.querySelector('[data-kt-edit-password-modal-action="cancel"]');
            cancelButton.addEventListener('click', e => {
                  e.preventDefault();

                  form.reset(); // Reset form			
                  modal.hide();

                  // Reset strength meter
                  if (strengthText) strengthText.textContent = '';
                  if (strengthBar) {
                        strengthBar.className = 'progress-bar';
                        strengthBar.style.width = '0%';
                  }
            });

            // Close button handler
            const closeButton = element.querySelector('[data-kt-edit-password-modal-action="close"]');
            closeButton.addEventListener('click', e => {
                  e.preventDefault();

                  form.reset(); // Reset form			
                  modal.hide();

                  // Reset strength meter
                  if (strengthText) strengthText.textContent = '';
                  if (strengthBar) {
                        strengthBar.className = 'progress-bar';
                        strengthBar.style.width = '0%';
                  }
            });


            // AJAX loading password modal data
            document.addEventListener('click', function (e) {
                  // Handle password toggle
                  const toggleBtn = e.target.closest('.toggle-password');
                  if (toggleBtn) {
                        const inputId = toggleBtn.getAttribute('data-target');
                        const input = document.getElementById(inputId);
                        const icon = toggleBtn.querySelector('i');

                        if (input) {
                              const isPassword = input.type === 'password';
                              input.type = isPassword ? 'text' : 'password';

                              if (icon) {
                                    icon.classList.toggle('ki-eye');
                                    icon.classList.toggle('ki-eye-slash');
                              }
                        }
                        return; // Prevent falling through to next case
                  }

                  // Handle edit password modal button
                  const changePasswordBtn = e.target.closest('.change-password-btn');
                  if (changePasswordBtn) {
                        userId = changePasswordBtn.getAttribute('data-user-id');
                        console.log('User ID:', userId);

                        const userName = changePasswordBtn.getAttribute('data-user-name');

                        const userIdInput = document.getElementById('user_id_input');
                        const modalTitle = document.getElementById('kt_modal_edit_password_title');

                        if (userIdInput) userIdInput.value = userId;
                        if (modalTitle) modalTitle.textContent = `জনাব ${userName} এর পাসওয়ার্ড পরিবর্তন`;
                  }
            });

            // Live strength meter
            if (passwordInput) {
                  passwordInput.addEventListener('input', function () {
                        const value = passwordInput.value;
                        let score = 0;

                        if (value.length >= 8) score++;
                        if (/[A-Z]/.test(value)) score++;
                        if (/[a-z]/.test(value)) score++;
                        if (/\d/.test(value)) score++;
                        if (/[^A-Za-z0-9]/.test(value)) score++;

                        let strength = '';
                        let barColor = '';
                        let width = score * 20;

                        switch (score) {
                              case 0:
                              case 1:
                                    strength = 'Very Weak';
                                    barColor = 'bg-danger';
                                    break;
                              case 2:
                                    strength = 'Weak';
                                    barColor = 'bg-warning';
                                    break;
                              case 3:
                                    strength = 'Moderate';
                                    barColor = 'bg-info';
                                    break;
                              case 4:
                                    strength = 'Strong';
                                    barColor = 'bg-primary';
                                    break;
                              case 5:
                                    strength = 'Very Strong';
                                    barColor = 'bg-success';
                                    break;
                        }

                        strengthText.textContent = strength;
                        strengthBar.className = `progress-bar ${barColor}`;
                        strengthBar.style.width = `${width}%`;
                  });
            }
      }


      // Form validation
      var initFormValidation = function () {
            if (!form) return;

            validator = FormValidation.formValidation(
                  form,
                  {
                        fields: {
                              'new_password': {
                                    validators: {
                                          notEmpty: {
                                                message: 'Password is required'
                                          },
                                          stringLength: {
                                                min: 8,
                                                message: '* Must be at least 8 characters long'
                                          },
                                          regexp: {
                                                regexp: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\w\s]).{8,}$/,
                                                message: '* Must contain uppercase, lowercase, number, and special character'
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

            const submitButton = element.querySelector('[data-kt-edit-password-modal-action="submit"]');

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

                                    console.log('Updating password for User ID:', userId);
                                    fetch(`/users/${userId}/password`, {
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
                                                      toastr.success(data.message || 'Password updated successfully');
                                                      modal.hide();
                                                      setTimeout(() => {
                                                            window.location.reload();
                                                      }, 1500); // 1000ms = 1 second delay
                                                } else {
                                                      throw new Error(data.message || 'Password Update failed');
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
            // Public functions
            init: function () {
                  initEditPassword();
                  initFormValidation();
            }
      };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
      KTUsersList.init();
      KTUsersAddUser.init();
      KTUsersEditUser.init();
      KTUsersEditPassword.init();
});