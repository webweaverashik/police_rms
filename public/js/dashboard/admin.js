"use strict";

/**
 * Dashboard Admin Module
 * Works with server-side data passed from Laravel Blade template
 */
var DashboardAdmin = (function () {
      // Chart instances
      var reportsChart = null;
      var programTypeChart = null;
      var politicalPartyChart = null;
      var zoneBarChart = null;

      // Current month offset for navigation
      var currentMonthOffset = 0;

      // Color palette (Metronic colors)
      var colors = {
            primary: '#3E97FF',
            success: '#50CD89',
            info: '#7239EA',
            warning: '#F6C000',
            danger: '#F1416C',
            dark: '#181C32',
            secondary: '#E4E6EF'
      };

      // Bengali digits mapping
      var bengaliDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];

      // Dashboard data - will be populated from server
      var dashboardData = {
            stats: {
                  totalReports: 0,
                  completedPrograms: 0,
                  pendingPrograms: 0,
                  totalRiskyPrograms: 0
            },
            zones: [],
            unions: [],
            users: [],
            programTypes: [],
            politicalParties: [],
            candidates: [],
            recentReports: [],
            monthlyReports: {
                  month: '',
                  year: '',
                  monthName: '',
                  categories: [],
                  data: []
            }
      };

      /**
       * Convert English number to Bengali number
       */
      var toBengaliNumber = function (num) {
            if (num === null || num === undefined) return '০';
            var numStr = num.toString();
            var bengaliStr = '';
            for (var i = 0; i < numStr.length; i++) {
                  var char = numStr[i];
                  if (char >= '0' && char <= '9') {
                        bengaliStr += bengaliDigits[parseInt(char)];
                  } else {
                        bengaliStr += char; // Keep non-digit characters (like commas, dots)
                  }
            }
            return bengaliStr;
      };

      /**
       * Load data from server (passed via global variable)
       */
      var loadServerData = function () {
            if (typeof dashboardServerData !== 'undefined' && dashboardServerData) {
                  dashboardData = Object.assign(dashboardData, dashboardServerData);
                  return true;
            }
            return false;
      };

      /**
       * Animate counter from 0 to target value
       */
      var animateCounter = function (elementId, targetValue) {
            var element = document.getElementById(elementId);
            if (!element) return;

            targetValue = parseInt(targetValue) || 0;
            var startValue = 0;
            var duration = 1500;
            var startTime = null;

            var animate = function (currentTime) {
                  if (!startTime) startTime = currentTime;
                  var progress = Math.min((currentTime - startTime) / duration, 1);
                  var value = Math.floor(progress * targetValue);
                  element.textContent = formatNumber(value);

                  if (progress < 1) {
                        requestAnimationFrame(animate);
                  } else {
                        element.textContent = formatNumber(targetValue);
                  }
            };

            requestAnimationFrame(animate);
      };

      /**
       * Format number with locale and convert to Bengali
       */
      var formatNumber = function (num) {
            if (typeof num !== 'number') num = parseInt(num) || 0;
            return toBengaliNumber(num.toLocaleString('en-IN'));
      };

      /**
       * Initialize stats counters
       */
      var initStats = function () {
            if (!dashboardData.stats) return;

            animateCounter('totalReports', dashboardData.stats.totalReports);
            animateCounter('completedPrograms', dashboardData.stats.completedPrograms);
            animateCounter('pendingPrograms', dashboardData.stats.pendingPrograms);
            animateCounter('totalRiskyPrograms', dashboardData.stats.totalRiskyPrograms);
      };

      /**
       * Update month label display
       */
      var updateMonthLabel = function () {
            var label = document.getElementById('currentMonthLabel');
            if (label && dashboardData.monthlyReports) {
                  label.textContent = dashboardData.monthlyReports.monthName + ' ' + dashboardData.monthlyReports.year;
            }
      };

      /**
       * Initialize Reports Trend Chart (Bar Chart for single month)
       */
      var initReportsChart = function () {
            var element = document.getElementById('reportsChart');
            if (!element) return;

            // Destroy existing chart
            if (reportsChart) {
                  reportsChart.destroy();
                  reportsChart = null;
            }

            var chartData = dashboardData.monthlyReports;
            if (!chartData || !chartData.categories || chartData.categories.length === 0) {
                  element.innerHTML = '<div class="d-flex align-items-center justify-content-center h-100 text-muted">কোন ডাটা নেই</div>';
                  return;
            }

            updateMonthLabel();

            var options = {
                  series: [{
                        name: 'রিপোর্ট',
                        data: chartData.data || []
                  }],
                  chart: {
                        type: 'bar',
                        height: 350,
                        fontFamily: 'inherit',
                        toolbar: {
                              show: false
                        }
                  },
                  colors: [colors.primary],
                  plotOptions: {
                        bar: {
                              borderRadius: 6,
                              columnWidth: '45%',
                              dataLabels: {
                                    position: 'top'
                              }
                        }
                  },
                  dataLabels: {
                        enabled: true,
                        formatter: function (val) {
                              return val > 0 ? toBengaliNumber(val) : '';
                        },
                        offsetY: -20,
                        style: {
                              fontSize: '11px',
                              fontFamily: 'inherit',
                              colors: ['#333']
                        }
                  },
                  xaxis: {
                        categories: chartData.categories,
                        labels: {
                              style: {
                                    fontFamily: 'inherit',
                                    fontSize: '11px'
                              }
                        },
                        axisBorder: {
                              show: false
                        }
                  },
                  yaxis: {
                        labels: {
                              formatter: function (val) {
                                    return val ? toBengaliNumber(val.toFixed(0)) : '০';
                              }
                        }
                  },
                  tooltip: {
                        y: {
                              formatter: function (val) {
                                    return toBengaliNumber(val) + ' টি রিপোর্ট';
                              }
                        }
                  },
                  grid: {
                        borderColor: '#f1f1f1',
                        strokeDashArray: 4
                  }
            };

            reportsChart = new ApexCharts(element, options);
            reportsChart.render();
      };

      /**
       * Navigate month (prev/next)
       */
      var navigateMonth = function (direction) {
            currentMonthOffset += direction;

            // Fetch new data for the month
            var url = dashboardData.monthlyReportsUrl || '/dashboard/monthly-reports';

            fetch(url + '?offset=' + currentMonthOffset, {
                  method: 'GET',
                  headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                  }
            })
                  .then(function (response) {
                        return response.json();
                  })
                  .then(function (data) {
                        dashboardData.monthlyReports = data;
                        initReportsChart();
                  })
                  .catch(function (error) {
                        console.error('Month navigation error:', error);
                  });
      };

      /**
       * Initialize Program Type Pie/Donut Chart
       */
      var initProgramTypeChart = function () {
            var element = document.getElementById('programTypeChart');
            if (!element) return;

            if (programTypeChart) {
                  programTypeChart.destroy();
                  programTypeChart = null;
            }

            // Filter out program types with 0 count
            var filteredTypes = (dashboardData.programTypes || []).filter(function (item) {
                  return (parseInt(item.count) || 0) > 0;
            });

            if (filteredTypes.length === 0) {
                  element.innerHTML = '<div class="d-flex align-items-center justify-content-center h-100 text-muted">কোন ডাটা নেই</div>';
                  return;
            }

            var labels = filteredTypes.map(function (item) { return item.name; });
            var series = filteredTypes.map(function (item) { return parseInt(item.count) || 0; });

            var options = {
                  series: series,
                  chart: {
                        type: 'donut',
                        height: 380,
                        fontFamily: 'inherit'
                  },
                  labels: labels,
                  colors: [colors.primary, colors.success, colors.warning, colors.info, colors.danger, colors.dark],
                  legend: {
                        show: false
                  },
                  plotOptions: {
                        pie: {
                              donut: {
                                    size: '60%',
                                    labels: {
                                          show: true,
                                          name: {
                                                show: true,
                                                fontSize: '14px',
                                                fontFamily: 'inherit'
                                          },
                                          value: {
                                                show: true,
                                                fontSize: '24px',
                                                fontWeight: 'bold',
                                                formatter: function (val) {
                                                      return toBengaliNumber(val) + ' টি';
                                                }
                                          },
                                          total: {
                                                show: true,
                                                label: 'মোট',
                                                fontSize: '14px',
                                                fontFamily: 'inherit',
                                                formatter: function (w) {
                                                      var total = w.globals.seriesTotals.reduce(function (a, b) {
                                                            return a + b;
                                                      }, 0);
                                                      return toBengaliNumber(total) + ' টি';
                                                }
                                          }
                                    }
                              }
                        }
                  },
                  dataLabels: {
                        enabled: true,
                        formatter: function (val, opts) {
                              return toBengaliNumber(val.toFixed(1)) + '%';
                        },
                        style: {
                              fontSize: '12px',
                              fontFamily: 'inherit'
                        },
                        dropShadow: {
                              enabled: false
                        }
                  },
                  tooltip: {
                        y: {
                              formatter: function (val) {
                                    return toBengaliNumber(val) + ' টি';
                              }
                        }
                  },
                  responsive: [{
                        breakpoint: 480,
                        options: {
                              chart: { height: 320 }
                        }
                  }]
            };

            programTypeChart = new ApexCharts(element, options);
            programTypeChart.render();
      };

      /**
       * Initialize Program Type Table
       */
      var initProgramTypeTable = function () {
            var tbody = document.getElementById('programTypeTableBody');
            if (!tbody) return;

            // Filter out program types with 0 count
            var filteredTypes = (dashboardData.programTypes || []).filter(function (item) {
                  return (parseInt(item.count) || 0) > 0;
            });

            if (filteredTypes.length === 0) {
                  tbody.innerHTML = '<tr><td colspan="3" class="text-center text-muted py-5">কোন ডাটা নেই</td></tr>';
                  return;
            }

            var totalCount = filteredTypes.reduce(function (sum, type) {
                  return sum + (parseInt(type.count) || 0);
            }, 0);

            var html = '';
            filteredTypes.forEach(function (type, index) {
                  var count = parseInt(type.count) || 0;
                  var percentage = totalCount > 0 ? ((count / totalCount) * 100).toFixed(1) : 0;
                  var badgeClass = getBadgeClass(index);
                  var color = getColorByIndex(index);

                  html += '<tr>';
                  html += '<td class="ps-4">';
                  html += '<div class="d-flex align-items-center">';
                  html += '<span class="bullet bullet-vertical h-40px me-3" style="background-color: ' + color + '"></span>';
                  html += '<span class="fw-semibold text-gray-800">' + type.name + '</span>';
                  html += '</div>';
                  html += '</td>';
                  html += '<td class="text-center">';
                  html += '<span class="fw-bold fs-5 text-gray-800">' + formatNumber(count) + '</span>';
                  html += '</td>';
                  html += '<td class="text-end pe-4">';
                  html += '<span class="badge badge-light-' + badgeClass + '">' + toBengaliNumber(percentage) + '%</span>';
                  html += '</td>';
                  html += '</tr>';
            });

            tbody.innerHTML = html;
      };

      /**
       * Initialize Political Party Horizontal Bar Chart (hide zero counts)
       */
      var initPoliticalPartyChart = function () {
            var element = document.getElementById('politicalPartyChart');
            if (!element) return;

            if (politicalPartyChart) {
                  politicalPartyChart.destroy();
                  politicalPartyChart = null;
            }

            // Filter out parties with zero reports
            var filteredParties = (dashboardData.politicalParties || []).filter(function (item) {
                  return (parseInt(item.count) || 0) > 0;
            });

            if (filteredParties.length === 0) {
                  element.innerHTML = '<div class="d-flex align-items-center justify-content-center h-100 text-muted">কোন ডাটা নেই</div>';
                  return;
            }

            var categories = filteredParties.map(function (item) { return item.name; });
            var data = filteredParties.map(function (item) { return parseInt(item.count) || 0; });
            var chartColors = filteredParties.map(function (item, index) {
                  return item.color || getColorByIndex(index);
            });

            var options = {
                  series: [{
                        name: 'রিপোর্ট',
                        data: data
                  }],
                  chart: {
                        type: 'bar',
                        height: 350,
                        fontFamily: 'inherit',
                        toolbar: {
                              show: false
                        }
                  },
                  plotOptions: {
                        bar: {
                              horizontal: true,
                              borderRadius: 4,
                              distributed: true,
                              dataLabels: {
                                    position: 'top'
                              }
                        }
                  },
                  colors: chartColors,
                  dataLabels: {
                        enabled: true,
                        formatter: function (val) {
                              return toBengaliNumber(val) + ' টি';
                        },
                        offsetX: 30,
                        style: {
                              fontSize: '12px',
                              fontFamily: 'inherit',
                              colors: ['#333']
                        }
                  },
                  xaxis: {
                        categories: categories,
                        labels: {
                              style: {
                                    fontFamily: 'inherit'
                              },
                              formatter: function (val) {
                                    return toBengaliNumber(val);
                              }
                        }
                  },
                  yaxis: {
                        labels: {
                              style: {
                                    fontFamily: 'inherit',
                                    fontSize: '13px'
                              }
                        }
                  },
                  legend: {
                        show: false
                  },
                  tooltip: {
                        y: {
                              formatter: function (val) {
                                    return toBengaliNumber(val) + ' টি রিপোর্ট';
                              }
                        }
                  },
                  grid: {
                        borderColor: '#f1f1f1'
                  }
            };

            politicalPartyChart = new ApexCharts(element, options);
            politicalPartyChart.render();
      };

      /**
       * Initialize Zone Vertical Bar Chart
       */
      var initZoneBarChart = function () {
            var element = document.getElementById('zoneBarChart');
            if (!element) return;

            if (zoneBarChart) {
                  zoneBarChart.destroy();
                  zoneBarChart = null;
            }

            if (!dashboardData.zones || dashboardData.zones.length === 0) {
                  element.innerHTML = '<div class="d-flex align-items-center justify-content-center h-100 text-muted">কোন ডাটা নেই</div>';
                  return;
            }

            var categories = dashboardData.zones.map(function (item) { return item.name; });
            var data = dashboardData.zones.map(function (item) { return parseInt(item.reports) || 0; });

            var options = {
                  series: [{
                        name: 'রিপোর্ট',
                        data: data
                  }],
                  chart: {
                        type: 'bar',
                        height: 350,
                        fontFamily: 'inherit',
                        toolbar: {
                              show: false
                        }
                  },
                  plotOptions: {
                        bar: {
                              borderRadius: 8,
                              columnWidth: '50%',
                              dataLabels: {
                                    position: 'top'
                              }
                        }
                  },
                  colors: [colors.primary],
                  dataLabels: {
                        enabled: true,
                        formatter: function (val) {
                              return toBengaliNumber(val);
                        },
                        offsetY: -20,
                        style: {
                              fontSize: '12px',
                              fontFamily: 'inherit',
                              colors: ['#333']
                        }
                  },
                  xaxis: {
                        categories: categories,
                        labels: {
                              style: {
                                    fontFamily: 'inherit',
                                    fontSize: '12px'
                              }
                        },
                        axisBorder: {
                              show: false
                        }
                  },
                  yaxis: {
                        labels: {
                              formatter: function (val) {
                                    return val ? toBengaliNumber(val.toFixed(0)) : '০';
                              }
                        }
                  },
                  grid: {
                        borderColor: '#f1f1f1',
                        strokeDashArray: 4
                  },
                  tooltip: {
                        y: {
                              formatter: function (val) {
                                    return toBengaliNumber(val) + ' টি রিপোর্ট';
                              }
                        }
                  }
            };

            zoneBarChart = new ApexCharts(element, options);
            zoneBarChart.render();
      };

      /**
       * Initialize Zone-wise Report Table
       */
      var initZoneTable = function () {
            var tbody = document.getElementById('zoneTableBody');
            if (!tbody) return;

            if (!dashboardData.zones || dashboardData.zones.length === 0) {
                  tbody.innerHTML = '<tr><td colspan="3" class="text-center text-muted py-5">কোন ডাটা নেই</td></tr>';
                  return;
            }

            var totalReports = dashboardData.zones.reduce(function (sum, zone) {
                  return sum + (parseInt(zone.reports) || 0);
            }, 0);

            var html = '';
            dashboardData.zones.forEach(function (zone, index) {
                  var reports = parseInt(zone.reports) || 0;
                  var percentage = totalReports > 0 ? ((reports / totalReports) * 100).toFixed(1) : 0;
                  var badgeClass = getBadgeClass(index);
                  var zoneColor = getColorByIndex(index);

                  html += '<tr>';
                  html += '<td class="ps-4">';
                  html += '<div class="d-flex align-items-center">';
                  html += '<div class="symbol symbol-40px me-3">';
                  html += '<span class="symbol-label" style="background-color: ' + zoneColor + '20; color: ' + zoneColor + '">';
                  html += zone.name.charAt(0);
                  html += '</span>';
                  html += '</div>';
                  html += '<span class="fw-semibold text-gray-800">' + zone.name + '</span>';
                  html += '</div>';
                  html += '</td>';
                  html += '<td class="text-center">';
                  html += '<span class="fw-bold fs-5 text-gray-800">' + formatNumber(reports) + '</span>';
                  html += '</td>';
                  html += '<td class="text-end pe-4">';
                  html += '<span class="badge badge-light-' + badgeClass + '">' + toBengaliNumber(percentage) + '%</span>';
                  html += '</td>';
                  html += '</tr>';
            });

            tbody.innerHTML = html;
      };

      /**
       * Initialize Union-wise Report Table
       */
      var initUnionTable = function () {
            var tbody = document.getElementById('unionTableBody');
            if (!tbody) return;

            if (!dashboardData.unions || dashboardData.unions.length === 0) {
                  tbody.innerHTML = '<tr><td colspan="3" class="text-center text-muted py-5">কোন ডাটা নেই</td></tr>';
                  return;
            }

            var totalReports = dashboardData.unions.reduce(function (sum, union) {
                  return sum + (parseInt(union.reports) || 0);
            }, 0);

            var html = '';
            dashboardData.unions.forEach(function (union, index) {
                  var reports = parseInt(union.reports) || 0;
                  var percentage = totalReports > 0 ? ((reports / totalReports) * 100).toFixed(1) : 0;
                  var badgeClass = getBadgeClass(index);
                  var unionColor = getColorByIndex(index);

                  html += '<tr>';
                  html += '<td class="ps-4">';
                  html += '<div class="d-flex align-items-center">';
                  html += '<div class="symbol symbol-40px me-3">';
                  html += '<span class="symbol-label" style="background-color: ' + unionColor + '20; color: ' + unionColor + '">';
                  html += union.name.charAt(0);
                  html += '</span>';
                  html += '</div>';
                  html += '<div>';
                  html += '<span class="fw-semibold text-gray-800 d-block">' + union.name + '</span>';
                  if (union.upazila) {
                        html += '<span class="text-muted fs-7">' + union.upazila + '</span>';
                  }
                  html += '</div>';
                  html += '</div>';
                  html += '</td>';
                  html += '<td class="text-center">';
                  html += '<span class="fw-bold fs-5 text-gray-800">' + formatNumber(reports) + '</span>';
                  html += '</td>';
                  html += '<td class="text-end pe-4">';
                  html += '<span class="badge badge-light-' + badgeClass + '">' + toBengaliNumber(percentage) + '%</span>';
                  html += '</td>';
                  html += '</tr>';
            });

            tbody.innerHTML = html;
      };

      /**
       * Initialize Top Users Table
       */
      var initUserTable = function () {
            var tbody = document.getElementById('userTableBody');
            if (!tbody) return;

            if (!dashboardData.users || dashboardData.users.length === 0) {
                  tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-5">কোন ডাটা নেই</td></tr>';
                  return;
            }

            var html = '';
            dashboardData.users.forEach(function (user, index) {
                  var rankClass = '';
                  var rankStyle = '';

                  if (index === 0) {
                        rankClass = 'ranking-1';
                  } else if (index === 1) {
                        rankClass = 'ranking-2';
                  } else if (index === 2) {
                        rankClass = 'ranking-3';
                  } else {
                        rankStyle = 'background-color: #f3f4f6; color: #6b7280;';
                  }

                  var userColor = getColorByIndex(index);
                  var designationBadge = getDesignationBadge(user.designation);

                  html += '<tr>';
                  html += '<td class="ps-4">';
                  html += '<span class="ranking-badge ' + rankClass + '" style="' + rankStyle + '">' + toBengaliNumber(index + 1) + '</span>';
                  html += '</td>';
                  html += '<td>';
                  html += '<div class="d-flex align-items-center">';
                  html += '<div class="symbol symbol-40px me-3">';
                  html += '<span class="symbol-label" style="background-color: ' + userColor + '20; color: ' + userColor + '">';
                  html += user.name.charAt(0);
                  html += '</span>';
                  html += '</div>';
                  html += '<span class="fw-semibold text-gray-800">' + user.name + '</span>';
                  html += '</div>';
                  html += '</td>';
                  html += '<td class="text-center">';
                  html += '<span class="badge badge-light-' + designationBadge + '">' + user.designation + '</span>';
                  html += '</td>';
                  html += '<td class="text-center">';
                  html += '<span class="fw-bold text-gray-800">' + formatNumber(user.reports || 0) + '</span>';
                  html += '</td>';
                  html += '<td class="text-end pe-4 text-muted fs-7">' + (user.lastReport || 'N/A') + '</td>';
                  html += '</tr>';
            });

            tbody.innerHTML = html;
      };

      /**
       * Initialize Candidate Table
       */
      var initCandidateTable = function () {
            var tbody = document.getElementById('candidateTableBody');
            if (!tbody) return;

            if (!dashboardData.candidates || dashboardData.candidates.length === 0) {
                  tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-5">কোন ডাটা নেই</td></tr>';
                  return;
            }

            var html = '';
            dashboardData.candidates.forEach(function (candidate, index) {
                  var color = getColorByIndex(index);

                  html += '<tr>';
                  html += '<td class="ps-4">';
                  html += '<div class="d-flex align-items-center">';
                  html += '<div class="symbol symbol-40px me-3">';
                  html += '<span class="symbol-label" style="background-color: ' + color + '20; color: ' + color + '">';
                  html += candidate.name.charAt(0);
                  html += '</span>';
                  html += '</div>';
                  html += '<span class="fw-semibold text-gray-800">' + candidate.name + '</span>';
                  html += '</div>';
                  html += '</td>';
                  html += '<td>';
                  html += '<span class="badge badge-light-primary">' + (candidate.party || 'স্বতন্ত্র') + '</span>';
                  html += '</td>';
                  html += '<td class="text-center">';
                  html += '<span class="fw-bold text-gray-800">' + formatNumber(candidate.programs || 0) + '</span>';
                  html += '</td>';
                  html += '<td class="text-end pe-4">';
                  html += '<span class="text-success fw-semibold">' + formatNumber(candidate.attendees || 0) + '</span>';
                  html += '</td>';
                  html += '</tr>';
            });

            tbody.innerHTML = html;
      };

      /**
       * Initialize Recent Reports List
       */
      var initRecentReports = function () {
            var container = document.getElementById('recentReports');
            if (!container) return;

            if (!dashboardData.recentReports || dashboardData.recentReports.length === 0) {
                  container.innerHTML = '<div class="text-center text-muted py-10">কোন সাম্প্রতিক রিপোর্ট নেই</div>';
                  return;
            }

            var html = '';
            dashboardData.recentReports.forEach(function (report, index) {
                  var statusBadge = getStatusBadge(report.status);
                  var color = getColorByIndex(index);

                  html += '<div class="d-flex align-items-center border-bottom border-gray-200 py-4 px-5">';
                  html += '<div class="symbol symbol-45px me-4">';
                  html += '<span class="symbol-label" style="background-color: ' + color + '20; color: ' + color + '">';
                  html += '<i class="bi bi-file-earmark-text fs-2"></i>';
                  html += '</span>';
                  html += '</div>';
                  html += '<div class="flex-grow-1">';
                  html += '<a href="reports/' + report.id + '" target="_blank" class="text-gray-800 fw-semibold text-hover-primary fs-6">' + (report.title || 'Untitled') + '</a>';
                  html += '<div class="text-muted fs-7 mt-1">';
                  html += '<span class="me-3"><i class="ki-outline ki-geolocation fs-7 me-1"></i>' + (report.zone || 'N/A') + '</span>';
                  html += '<span><i class="ki-outline ki-category fs-7 me-1"></i>' + (report.type || 'N/A') + '</span>';
                  html += '</div>';
                  html += '</div>';
                  html += '<div class="text-end">';
                  html += '<span class="badge ' + statusBadge + ' mb-1">' + (report.status || 'N/A') + '</span>';
                  html += '<div class="text-muted fs-7">' + (report.time || '') + '</div>';
                  html += '</div>';
                  html += '</div>';
            });

            container.innerHTML = html;
      };

      /**
       * Initialize month navigation buttons
       */
      var initMonthNavigation = function () {
            var prevBtn = document.getElementById('prevMonthBtn');
            var nextBtn = document.getElementById('nextMonthBtn');

            if (prevBtn) {
                  prevBtn.addEventListener('click', function () {
                        navigateMonth(-1);
                  });
            }

            if (nextBtn) {
                  nextBtn.addEventListener('click', function () {
                        navigateMonth(1);
                  });
            }
      };

      // ==================== Helper Functions ====================

      var getColorByIndex = function (index) {
            var colorArray = [colors.primary, colors.success, colors.info, colors.warning, colors.danger, colors.dark];
            return colorArray[index % colorArray.length];
      };

      var getProgressClass = function (index) {
            var classes = ['bg-primary', 'bg-success', 'bg-info', 'bg-warning', 'bg-danger'];
            return classes[index % classes.length];
      };

      var getBadgeClass = function (index) {
            var classes = ['primary', 'success', 'info', 'warning', 'danger'];
            return classes[index % classes.length];
      };

      var getDesignationBadge = function (designation) {
            var badges = {
                  'SuperAdmin': 'danger',
                  'Admin': 'success',
                  'Magistrate': 'info',
                  'Operator': 'warning',
                  'Viewer': 'secondary',
                  'ইউএনও': 'primary',
                  'অ্যাডমিন': 'success',
                  'ম্যাজিস্ট্রেট': 'info',
                  'অপারেটর': 'warning',
                  'ভিউয়ার': 'secondary'
            };
            return badges[designation] || 'secondary';
      };

      var getStatusBadge = function (status) {
            var badges = {
                  'সম্পন্ন': 'badge-light-success',
                  'completed': 'badge-light-success',
                  'চলমান': 'badge-light-primary',
                  'ongoing': 'badge-light-primary',
                  'in_progress': 'badge-light-primary',
                  'অপেক্ষমাণ': 'badge-light-warning',
                  'pending': 'badge-light-warning',
                  'বাতিল': 'badge-light-danger',
                  'cancelled': 'badge-light-danger'
            };
            return badges[status] || 'badge-light-secondary';
      };

      // ==================== Public Methods ====================

      return {
            /**
             * Initialize dashboard with server data
             */
            init: function () {
                  // Load data from server
                  loadServerData();

                  // Initialize all components
                  initStats();
                  initReportsChart();
                  initMonthNavigation();
                  initProgramTypeChart();
                  initProgramTypeTable();
                  initPoliticalPartyChart();
                  initZoneBarChart();
                  initZoneTable();
                  initUnionTable();
                  initUserTable();
                  initCandidateTable();
                  initRecentReports();
            },

            /**
             * Update dashboard with new data
             * @param {Object} newData - New dashboard data
             */
            updateData: function (newData) {
                  if (newData) {
                        dashboardData = Object.assign(dashboardData, newData);
                  }
                  this.refreshAll();
            },

            /**
             * Refresh all charts and tables
             */
            refreshAll: function () {
                  initStats();
                  initReportsChart();
                  initProgramTypeChart();
                  initProgramTypeTable();
                  initPoliticalPartyChart();
                  initZoneBarChart();
                  initZoneTable();
                  initUnionTable();
                  initUserTable();
                  initCandidateTable();
                  initRecentReports();
            },

            /**
             * Refresh only charts (destroy and recreate)
             */
            refreshCharts: function () {
                  initReportsChart();
                  initProgramTypeChart();
                  initPoliticalPartyChart();
                  initZoneBarChart();
            },

            /**
             * Navigate to previous/next month
             */
            navigateMonth: navigateMonth,

            /**
             * Fetch fresh data from server via AJAX
             * @param {string} url - API endpoint URL
             */
            fetchAndRefresh: function (url) {
                  var self = this;

                  fetch(url, {
                        method: 'GET',
                        headers: {
                              'Content-Type': 'application/json',
                              'X-Requested-With': 'XMLHttpRequest'
                        }
                  })
                        .then(function (response) {
                              return response.json();
                        })
                        .then(function (data) {
                              self.updateData(data);
                        })
                        .catch(function (error) {
                              console.error('Dashboard refresh error:', error);
                        });
            }
      };
})();

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', function () {
      DashboardAdmin.init();
});