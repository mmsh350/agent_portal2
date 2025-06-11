

(function($) {
  'use strict';
  $(function() {
    if ($("#sales-status-chart").length) {
      var pieChartCanvas = $("#sales-status-chart").get(0).getContext("2d");
      // pieChartCanvas.width = "150px"; // Set the width to your desired value
      // pieChartCanvas.height = "150px"; // Set the height to your desired value
      var pieChart = new Chart(pieChartCanvas, {

        type: 'pie',
        data: {
          datasets: [{
            data: [25, 13, 12, 50],
            backgroundColor: [
              '#fc7242',
              '#4d13d1',
              '#998eac',
              '#5e2572'
            ],
            borderColor: [
              '#fc7242',
              '#4d13d1',
              '#998eac',
              '#5e2572'
            ],
          }],
      
          // These labels appear in the legend and in the tooltips when hovering different arcs
          labels: [
            'Automobiles',
            'Machinery',
            'Home decor items',
            'Chemicals'
          ]
        },
        options: {
          responsive: false,
          maintainAspectRatio: true,
          animation: {
            animateScale: true,
            animateRotate: true
          },
          plugins: {
            legend: {
              display: false
            },
            position: 'bottom'
          },
          // legendCallback: function(chart) { 
          //   var text = [];
          //   text.push('<ul class="legend'+ chart.id +'">');
          //   for (var i = 0; i < chart.data.datasets[0].data.length; i++) {
          //     text.push('<li><span class="legend-dots" style="background-color:' + chart.data.datasets[0].backgroundColor[i] + '"></span>');
          //     if (chart.data.labels[i]) {
          //       text.push(chart.data.labels[i]);
          //     }
          //     text.push('</li>');
          //   }
          //   text.push('</ul>');
          //   return text.join("");
          // }
        }
      });
      // document.getElementById('sales-status-chart-legend').innerHTML = pieChart.generateLegend();
      var legendHtml = pieChart.data.labels.map((label, index) => {
        return `<li><span class="legend-dots" style="background-color:${pieChart.data.datasets[0].backgroundColor[index]}"></span>${label}</li>`;
      }).join("");
      
      document.getElementById('sales-status-chart-legend').innerHTML = `<ul>${legendHtml}</ul>`;
    }

    if ($('#sales-chart').length) {
      var lineChartCanvas = $("#sales-chart").get(0).getContext("2d");
      var lineChart = new Chart(lineChartCanvas, {
        type: 'line',
        data: {
           labels: ["2013", "2014", "2014", "2015", "2016", "2017", "2018", "2019"],
           datasets: [
            {
              label: 'Support',
              data: [2500, 8030, 3050, 3300, 4510, 7800, 5500],
              borderColor: ['#5e2572'],
              borderWidth: 3,
              fill: false,
              pointBackgroundColor: '#ffffff',
              pointBorderColor: '#fc7242'

            }
           ]
        },
        options: {
          scales: {
            y: {
              gridLines: {
                drawBorder: false,
                borderDash: [3, 3]
              },
              ticks: {
                display: false,
                stepSize: 2000,
                min: 0
              },
            },
            x: {
              display: false,
              gridLines: {
                display: false,
                drawBorder: false,
                color: '#fffffff'
              },
              ticks: {
                display: false,
              }
            }
          },
          plugins: {
            legend: {
              display: false,
            }
          },
          elements: {
            line: {
              tension: 0.2,
            },
            point: {
              radius: 4
            }
          },
          stepsize: 1
        }
      })
    }
    
     if ($('#sales-chart-dark').length) {
      var lineChartCanvas = $("#sales-chart-dark").get(0).getContext("2d");
      var lineChart = new Chart(lineChartCanvas, {
        type: 'line',
        data: {
             labels: ["2013", "2014", "2014", "2015", "2016", "2017", "2018", "2019"],   
             datasets: [
                {
                  label: 'Support',
                  data: [2500, 8030, 3050, 3300, 4510, 7800, 5500],
                  borderColor: ['#5e2572'],
                  borderWidth: 3,
                  fill: false,
                  pointBackgroundColor: "#ffffff",
                  pointBorderColor: "#fc7242"
                }
             ]
        },
        options: {
          scales: {
            y: {
              gridLines: {
                drawBorder: false,
                borderDash: [3, 3],
                color: '#312d4e'
              },
              ticks: {
                display: false,
                stepSize: 2000,
                min: 0
              }
            },
            x: {
              display: false,
              gridLines: {
                display: false,
                drawBorder: false,
                colors: '#ffffff'
              },
              ticks: {
                display: false
              }
            }
          },
          plugins: {
            legend: {
              display: false,
            }
          },
          elements: {
            line: {
              tension: 0.2
            },
            point: {
              radius: 4
            }
          },
          stepsize: 1
        }
      })
    }
    
    if ($("#statistics-chart").length) {
      

      var salesChartCanvas = $("#statistics-chart").get(0).getContext("2d");
      var salesChart = new Chart(salesChartCanvas, {
        type: 'line',
        data: {
          labels: ["IA", "RI", "NY", "CO", "MI", "FL", "IL", "PA", "LA", "NJ", "CA", "TX", "LA", "PQ", "RF", "JG"],
          datasets: [
            {
              data: [22, 23, 28, 20, 27, 20, 20, 24, 10, 35, 20, 25, 20, 24, 22, 27],
              backgroundColor: [
                '#fc7242'
              ],
              borderColor: [
                '#fc7242'
              ],
              borderWidth: 1,
              fill: 'origin',
              label: "purchases"
            },
            {
              data: [50, 40, 48, 70, 50, 63, 63, 42, 42, 51, 35, 35, 35, 40, 61, 35],
              backgroundColor: [
                '#5e2572'
              ],
              borderColor: [
                '#5e2572'
              ],
              borderWidth: 1,
              fill: 'origin',
              label: "services"
            },
            {
              data: [95, 75, 90, 105, 95, 95, 95, 100, 75, 95, 90, 105, 90, 75, 110, 85],
              backgroundColor: [
                '#dfe3e9'
              ],
              borderColor: [
                '#dfe3e9'
              ],
              borderWidth: 1,
              fill: 'origin',
              label: "services"
            }          
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: true,
          plugins: {
            legend: {
              display: false
            },
            filler: {
              propagate: false
            },
            tooltip: {
              enabled: true
            }
          },
          elements: {
            line: {
              tension: 0
            },
            point: {
              radius: 0
            }
          },
          scales: {
            x: {
              display: false,
              ticks: {
                display: false,
              },
              girdLines: {
                display: false,
                drawBorder: false,
                color: 'transparent',
                zeroLineColor: '#eeeee'
              }
            },
            y: {
              display: false,
              ticks: {
                display: false,
                autoSkip: false,
                maxRotation: 0,
                stepSize: 20,
                min :0,
                max: 110
              },
              gridLines: {
                drawBorder: false
              }
            }
          }
        }
      })

      // var salesChart = new Chart(salesChartCanvas, {
      //   type: 'line',
      //   data: areaData,
      //   options: areaOptions
      // });
    }
    if ($("#statistics-chart-dark").length) {
      
      var salesChartCanvas = $($('#statistics-chart-dark').get(0).getContext("2d"));
      var salesChart = new Chart(salesChartCanvas, {
        type: 'line',
        data: {
          labels: ["IA", "RI", "NY", "CO", "MI", "FL", "IL", "PA", "LA", "NJ", "CA", "TX", "LA", "PQ", "RF", "JG"],
          datasets: [
            {
              data: [22, 23, 28, 20, 27, 20, 20, 24, 10, 35, 20, 25, 20, 24, 22, 27],
              backgroundColor: ['#fc7242'],
              borderColor: ['#fc7242'],
              borderWidth: 1,
              fill: 'origin',
              label: 'purchases'
            },
            {
            data: [50, 40, 48, 70, 50, 63, 63, 42, 42, 51, 35, 35, 35, 40, 61, 35],
            backgroundColor: [
              '#5e2572'
            ],
            borderColor: [
              '#5e2572'
            ],
            borderWidth: 1,
            fill: 'origin',
            label: "services"
          },
          {
            data: [95, 75, 90, 105, 95, 95, 95, 100, 75, 95, 90, 105, 90, 75, 110, 85],
            backgroundColor: [
              '#312d4e'
            ],
            borderColor: [
              '#312d4e'
            ],
            borderWidth: 1,
            fill: 'origin',
            label: "services"
          }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: true,
          plugins: {
            filler: {
              propagate: false,
            },
            legend: {
              display: false
            },
            tooltip: {
              display: false
            }
          },
          elements: {
            line: {
              tension: 0
            },
            point: {
              radius: 0
            }
          }
        },
        scales: {
          x: {
            display: false,
            ticks: {
              display: false
            },
            gridLines: {
              display: false,
              drawBorder: false,
              color: 'transparent',
              zeroLineColor: '#eeeeee'
            }
          },
          y: {
            display: false,
            ticks: {
              display: false,
              autoSkip: false,
              maxRotation: 0,
              stepSize: 20,
              min: 0,
              max: 110
            },
            gridLines: {
              drawBorder: false
            }
          }
        },
      })
    }

    if ($("#orders-chart").length) {
      var currentChartCanvas = $("#orders-chart").get(0).getContext("2d");
      var currentChart = new Chart(currentChartCanvas, {
        type: 'bar',
        data: {
          labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
          datasets: [{
              label: 'Chrome',
              data: [530, 600, 490, 420, 800, 590],
              backgroundColor: '#5e2572'
            },
            {
              label: 'Safari',
              data: [970, 1110, 700, 620, 1020, 1200],
              backgroundColor: '#f4e8f8'
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: true,
          layout: {
            padding: {
              left: 0,
              right: 0,
              top: 20,
              bottom: 0
            }
          },
          scales: {
            y: {
              display: false,
              gridLines: {
                drawBorder: false,
              },
              ticks: {
                stepSize: 250,
                fontColor: "#686868"
              }
            },
            x: {
              stacked: true,
              ticks: {
                beginAtZero: true,
                fontColor: "#686868"
              },
              gridLines: {
                display: false,
                drawBorder: false
              },
            },
          },
          barPercentage: 0.4,
          plugins: {
            legend: {
              display: false
            },
          },
          elements: {
            point: {
              radius: 0
            }
          },
          // legendCallback: function(chart) { 
          //   var text = [];
          //   text.push('<ul class="legend'+ chart.id +'">');
          //   for (var i = 0; i < chart.data.datasets.length; i++) {
          //     text.push('<li><span class="legend-dots" style="background-color:' + chart.data.datasets[i].backgroundColor + '"></span>');
          //     if (chart.data.datasets[i].label) {
          //       text.push(chart.data.datasets[i].label);
          //     }
          //     text.push('</li>');
          //   }
          //   text.push('</ul>');
          //   return text.join("");
          // }
        }
      });
      var legendHtml = '';
      for (var i = 0; i < currentChart.data.datasets.length; i++) {
        legendHtml += `<li><span class="legend-dots" style="background-color:${currentChart.data.datasets[i].backgroundColor}"></span>${currentChart.data.datasets[i].label}</li>`;
      }
      document.getElementById('orders-chart-legend').innerHTML = `<ul>${legendHtml}</ul>`;
    }

    if ($("#orders-chart-dark").length) {
      var currentChartCanvas = $("#orders-chart-dark").get(0).getContext("2d");
      var currentChart = new Chart(currentChartCanvas, {
        type: 'bar',
        data: {
          labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
          datasets: [{
              label: 'Chrome',
              data: [530, 600, 490, 420, 800, 590],
              backgroundColor: '#5e2572'
            },
            {
              label: 'Safari',
              data: [970, 1110, 700, 620, 1020, 1200],
              backgroundColor: '#312d4e'
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: true,
          layout: {
            padding: {
              left: 0,
              right: 0,
              top: 20,
              bottom: 0
            }
          },
          scales: {
            y: {
              display: false,
              gridLines: {
                drawBorder: false,
              },
              ticks: {
                stepSize: 250,
                fontColor: "#686868"
              }
            },
            x: {
              stacked: true,
              ticks: {
                beginAtZero: true,
                fontColor: "#686868"
              },
              gridLines: {
                display: false,
                drawBorder: false
              },
            }
          },
          barPercentage: 0.4,
          plugins: {
            legend: {
              display: false
            },
          },
          elements: {
            point: {
              radius: 0
            }
          },
          // legendCallback: function(chart) { 
          //   var text = [];
          //   text.push('<ul class="legend'+ chart.id +'">');
          //   for (var i = 0; i < chart.data.datasets.length; i++) {
          //     text.push('<li><span class="legend-dots" style="background-color:' + chart.data.datasets[i].backgroundColor + '"></span>');
          //     if (chart.data.datasets[i].label) {
          //       text.push(chart.data.datasets[i].label);
          //     }
          //     text.push('</li>');
          //   }
          //   text.push('</ul>');
          //   return text.join("");
          // }
        }
      });
      var legendHtml = '';
      for (var i = 0; i < currentChart.data.datasets.length; i++) {
        legendHtml += `<li><span class="legend-dots" style="background-color:${currentChart.data.datasets[i].backgroundColor}"></span>${currentChart.data.datasets[i].label}</li>`;
      }
      document.getElementById('orders-chart-legend').innerHTML = `<ul>${legendHtml}</ul>`;
    }
  });
})(jQuery);

if ($("#inline-datepicker-example").length) {
  $('#inline-datepicker-example').datepicker({
    enableOnReadonly: true,
    todayHighlight: true,
    templates: {
      leftArrow: '<i class="mdi mdi-chevron-left"></i>',
      rightArrow: '<i class="mdi mdi-chevron-right"></i>'
    }
  });
}