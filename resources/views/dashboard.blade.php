@extends('admin.app')

@section('content-header')
<h1>Dashboard</h1>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                   <div class="row m-2">
                    <h3>LC Opening Requests</h3>
                   </div>
                   <div class="row">
                        <div class="col-lg-3 col-6">
                        <!-- small box -->
                            <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{$lc_count}}</h3>
                
                                <p>Requests Pending</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                            <a href="{{route('lc_request.index')}}" class="small-box-footer">IPAK</a>
                            </div>
                        </div>
                      <!-- ./col -->
                      <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-danger">
                          <div class="inner">
                            <h3>50</h3>
            
                            <p>Requests Pending</p>
                          </div>
                          <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                          </div>
                          <a href="" class="small-box-footer">CPAK</a>
                        </div>
                      </div>
                      <!-- ./col -->
                      <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-warning">
                          <div class="inner">
                            <h3>44</h3>
            
                            <p>Requests Pending</p>
                          </div>
                          <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                          </div>
                          <a href="{{route('lc_request.index')}}" class="small-box-footer">PETPAK</a>
                        </div>
                      </div>
                      <!-- ./col -->
                      <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-info">
                          <div class="inner">
                            <h3>50</h3>
            
                            <p>Requests Pending</p>
                          </div>
                          <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                          </div>
                          <a href="{{route('lc_request.index')}}" class="small-box-footer">GPAK</a>
                        </div>
                      </div>
                   </div> 
                   <div class="row">
                    <div class="col-lg-6">
                        <div class="card">
                          <div class="card-header border-0">
                            <div class="d-flex justify-content-between">
                              <h3 class="card-title">Monthly LC's Transmitted (All Companies)</h3>
                              {{-- <a href="javascript:void(0);">View Report</a> --}}
                            </div>
                          </div>
                          <div class="card-body">
                            {{-- <div class="d-flex">
                              <p class="d-flex flex-column">
                                <span class="text-bold text-lg">$18,230.00</span>
                                <span>Sales Over Time</span>
                              </p>
                              <p class="ml-auto d-flex flex-column text-right">
                                <span class="text-success">
                                  <i class="fas fa-arrow-up"></i> 33.1%
                                </span>
                                <span class="text-muted">Since last month</span>
                              </p>
                            </div> --}}
                            <!-- /.d-flex -->
            
                            <div class="position-relative mb-4">
                              <canvas id="sales-chart" height="200"></canvas>
                            </div>
            
                            {{-- <div class="d-flex flex-row justify-content-end">
                              <span class="mr-2">
                                <i class="fas fa-square text-primary"></i> This year
                              </span>
            
                              <span>
                                <i class="fas fa-square text-gray"></i> Last year
                              </span>
                            </div> --}}
                          </div>
                        </div>
                        <!-- /.card -->
            
                        {{-- <div class="card">
                          <div class="card-header border-0">
                            <h3 class="card-title">Online Store Overview</h3>
                            <div class="card-tools">
                              <a href="#" class="btn btn-sm btn-tool">
                                <i class="fas fa-download"></i>
                              </a>
                              <a href="#" class="btn btn-sm btn-tool">
                                <i class="fas fa-bars"></i>
                              </a>
                            </div>
                          </div>
                          <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
                              <p class="text-success text-xl">
                                <i class="ion ion-ios-refresh-empty"></i>
                              </p>
                              <p class="d-flex flex-column text-right">
                                <span class="font-weight-bold">
                                  <i class="ion ion-android-arrow-up text-success"></i> 12%
                                </span>
                                <span class="text-muted">CONVERSION RATE</span>
                              </p>
                            </div>
                            <!-- /.d-flex -->
                            <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
                              <p class="text-warning text-xl">
                                <i class="ion ion-ios-cart-outline"></i>
                              </p>
                              <p class="d-flex flex-column text-right">
                                <span class="font-weight-bold">
                                  <i class="ion ion-android-arrow-up text-warning"></i> 0.8%
                                </span>
                                <span class="text-muted">SALES RATE</span>
                              </p>
                            </div>
                            <!-- /.d-flex -->
                            <div class="d-flex justify-content-between align-items-center mb-0">
                              <p class="text-danger text-xl">
                                <i class="ion ion-ios-people-outline"></i>
                              </p>
                              <p class="d-flex flex-column text-right">
                                <span class="font-weight-bold">
                                  <i class="ion ion-android-arrow-down text-danger"></i> 1%
                                </span>
                                <span class="text-muted">REGISTRATION RATE</span>
                              </p>
                            </div>
                            <!-- /.d-flex -->
                          </div>
                        </div> --}}
                    </div>
                    <div class="col-lg-6">
                          <div class="card">
              <div class="card-header">
                <h3 class="card-title">All Companies Pending Requests</h3>

                {{-- <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div> --}}
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row">
                  <div class="col-md-8">
                    <div class="chart-responsive">
                      <canvas id="pieChart" height="150"></canvas>
                    </div>
                    <!-- ./chart-responsive -->
                  </div>
                  <!-- /.col -->
                  <div class="col-md-4">
                    <ul class="chart-legend clearfix">
                      <li><i class="far fa-circle text-danger"></i> CPAK</li>
                      <li><i class="far fa-circle text-success"></i> IPAK</li>
                      <li><i class="far fa-circle text-warning"></i> PETPACK</li>
                      {{-- <li><i class="far fa-circle text-info"></i> Safari</li> --}}
                      <li><i class="far fa-circle text-primary"></i> GPAK</li>
                      {{-- <li><i class="far fa-circle text-secondary"></i> Navigator</li> --}}
                    </ul>
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
              </div>
              <!-- /.card-body -->
              {{-- <div class="card-footer p-0">
                <ul class="nav nav-pills flex-column">
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      United States of America
                      <span class="float-right text-danger">
                        <i class="fas fa-arrow-down text-sm"></i>
                        12%</span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      India
                      <span class="float-right text-success">
                        <i class="fas fa-arrow-up text-sm"></i> 4%
                      </span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      China
                      <span class="float-right text-warning">
                        <i class="fas fa-arrow-left text-sm"></i> 0%
                      </span>
                    </a>
                  </li>
                </ul>
              </div> --}}
              <!-- /.footer -->
            </div>
                    </div>
                   </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                   <div class="row m-2">
                    <h3>LC Amendment Requests</h3>
                   </div>
                   <div class="row">
                        <div class="col-lg-3 col-6">
                        <!-- small box -->
                            <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{$amendment_lc_count}}</h3>
                
                                <p>Requests Pending</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                            <a href="{{route('amendment_request.index')}}" class="small-box-footer">IPAK</a>
                            </div>
                        </div>
                      <!-- ./col -->
                      <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-danger">
                          <div class="inner">
                            <h3>50</h3>
            
                            <p>Requests Pending</p>
                          </div>
                          <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                          </div>
                          <a href="{{route('amendment_request.index')}}" class="small-box-footer">CPAK</a>
                        </div>
                      </div>
                      <!-- ./col -->
                      <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-warning">
                          <div class="inner">
                            <h3>44</h3>
            
                            <p>Requests Pending</p>
                          </div>
                          <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                          </div>
                          <a href="{{route('amendment_request.index')}}" class="small-box-footer">PETPAK</a>
                        </div>
                      </div>
                      <!-- ./col -->
                      <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-info">
                          <div class="inner">
                            <h3>50</h3>
            
                            <p>Requests Pending</p>
                          </div>
                          <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                          </div>
                          <a href="{{route('amendment_request.index')}}" class="small-box-footer">GPAK</a>
                        </div>
                      </div>
                   </div> 
                   <div class="row">
                    <div class="col-lg-6">
                        <div class="card">
                          <div class="card-header border-0">
                            <div class="d-flex justify-content-between">
                              <h3 class="card-title">Monthly LC's Transmitted (All Companies)</h3>
                              {{-- <a href="javascript:void(0);">View Report</a> --}}
                            </div>
                          </div>
                          <div class="card-body">
                            {{-- <div class="d-flex">
                              <p class="d-flex flex-column">
                                <span class="text-bold text-lg">$18,230.00</span>
                                <span>Sales Over Time</span>
                              </p>
                              <p class="ml-auto d-flex flex-column text-right">
                                <span class="text-success">
                                  <i class="fas fa-arrow-up"></i> 33.1%
                                </span>
                                <span class="text-muted">Since last month</span>
                              </p>
                            </div> --}}
                            <!-- /.d-flex -->
            
                            <div class="position-relative mb-4">
                              <canvas id="sales-chart2" height="200"></canvas>
                            </div>
            
                            {{-- <div class="d-flex flex-row justify-content-end">
                              <span class="mr-2">
                                <i class="fas fa-square text-primary"></i> This year
                              </span>
            
                              <span>
                                <i class="fas fa-square text-gray"></i> Last year
                              </span>
                            </div> --}}
                          </div>
                        </div>
                        <!-- /.card -->
            
                        {{-- <div class="card">
                          <div class="card-header border-0">
                            <h3 class="card-title">Online Store Overview</h3>
                            <div class="card-tools">
                              <a href="#" class="btn btn-sm btn-tool">
                                <i class="fas fa-download"></i>
                              </a>
                              <a href="#" class="btn btn-sm btn-tool">
                                <i class="fas fa-bars"></i>
                              </a>
                            </div>
                          </div>
                          <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
                              <p class="text-success text-xl">
                                <i class="ion ion-ios-refresh-empty"></i>
                              </p>
                              <p class="d-flex flex-column text-right">
                                <span class="font-weight-bold">
                                  <i class="ion ion-android-arrow-up text-success"></i> 12%
                                </span>
                                <span class="text-muted">CONVERSION RATE</span>
                              </p>
                            </div>
                            <!-- /.d-flex -->
                            <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
                              <p class="text-warning text-xl">
                                <i class="ion ion-ios-cart-outline"></i>
                              </p>
                              <p class="d-flex flex-column text-right">
                                <span class="font-weight-bold">
                                  <i class="ion ion-android-arrow-up text-warning"></i> 0.8%
                                </span>
                                <span class="text-muted">SALES RATE</span>
                              </p>
                            </div>
                            <!-- /.d-flex -->
                            <div class="d-flex justify-content-between align-items-center mb-0">
                              <p class="text-danger text-xl">
                                <i class="ion ion-ios-people-outline"></i>
                              </p>
                              <p class="d-flex flex-column text-right">
                                <span class="font-weight-bold">
                                  <i class="ion ion-android-arrow-down text-danger"></i> 1%
                                </span>
                                <span class="text-muted">REGISTRATION RATE</span>
                              </p>
                            </div>
                            <!-- /.d-flex -->
                          </div>
                        </div> --}}
                    </div>
                    <div class="col-lg-6">
                          <div class="card">
              <div class="card-header">
                <h3 class="card-title">All Companies Pending Requests</h3>

                {{-- <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div> --}}
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row">
                  <div class="col-md-8">
                    <div class="chart-responsive">
                      <canvas id="pieChart2" height="150"></canvas>
                    </div>
                    <!-- ./chart-responsive -->
                  </div>
                  <!-- /.col -->
                  <div class="col-md-4">
                    <ul class="chart-legend clearfix">
                      <li><i class="far fa-circle text-danger"></i> CPAK</li>
                      <li><i class="far fa-circle text-success"></i> IPAK</li>
                      <li><i class="far fa-circle text-warning"></i> PETPACK</li>
                      {{-- <li><i class="far fa-circle text-info"></i> Safari</li> --}}
                      <li><i class="far fa-circle text-primary"></i> GPAK</li>
                      {{-- <li><i class="far fa-circle text-secondary"></i> Navigator</li> --}}
                    </ul>
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
              </div>
              <!-- /.card-body -->
              {{-- <div class="card-footer p-0">
                <ul class="nav nav-pills flex-column">
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      United States of America
                      <span class="float-right text-danger">
                        <i class="fas fa-arrow-down text-sm"></i>
                        12%</span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      India
                      <span class="float-right text-success">
                        <i class="fas fa-arrow-up text-sm"></i> 4%
                      </span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      China
                      <span class="float-right text-warning">
                        <i class="fas fa-arrow-left text-sm"></i> 0%
                      </span>
                    </a>
                  </li>
                </ul>
              </div> --}}
              <!-- /.footer -->
            </div>
                    </div>
                   </div>
            </div>
        </div>
    </div>
</div>
<!-- /.row -->

<!-- Password Reset Modal -->
<div class="modal fade" id="reset-password-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header justify-content-center">
                <h4 class="modal-title">Reset Password</h4>
            </div>
            <div class="modal-body">
              <form id="quickForm">
                @csrf
                <div class="input-group mb-3 form-group">
                  <input type="password" class="form-control" placeholder="Password" name="password" id="password">
                  <div class="input-group-append">
                    <div class="input-group-text">
                      <span class="fas fa-lock"></span>
                    </div>
                  </div>
                </div>

                <div class="input-group mb-3 form-group">
                  <input type="password" class="form-control" placeholder="Confirm Password" name="password_confirmation">
                  <div class="input-group-append">
                    <div class="input-group-text">
                      <span class="fas fa-lock"></span>
                    </div>
                  </div>
                </div>

              </form>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="submit" class="btn btn-primary" onclick="submitResetPassword()">Reset Password</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<style>
    .inner-text {
        display: flex; /* Use flexbox for layout */
    }

    .left {
        flex: 80%; /* Set left paragraph to take 70% of container */
        margin: 0; /* Remove default margins */
    }

    .right {
        flex: 20%; /* Set right paragraph to take 30% of container */
        margin-left: 10px; /* Adjust spacing between paragraphs */
    }
</style>
@endsection

@section('scripts')
<!-- Ensure the correct path to jquery-validation plugin -->
<script src="{{ asset('plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('plugins/jquery-validation/additional-methods.min.js') }}"></script>
<script src="plugins/chart.js/Chart.min.js"></script>
<script src="../../plugins/toastr/toastr.min.js"></script>

<script>
  $(document).ready(function() {
      // Check session value and show modal if needed
      @if(session('change_password') == 0)
          $('#reset-password-modal').modal({
              backdrop: 'static',
              keyboard: false
          });
          $('#reset-password-modal').modal('show');
      @endif

      // jQuery validation for the form
      $('#quickForm').validate({
          rules: {
              password: {
                  required: true,
                  minlength: 8
              },
              password_confirmation: {
                  required: true,
                  minlength: 8,
                  equalTo: "#password"
              }
          },
          messages: {
              password: {
                  required: "Please provide a password",
                  minlength: "Your password must be at least 8 characters long"
              },
              password_confirmation: {
                  required: "Please confirm your password",
                  minlength: "Your password must be at least 8 characters long",
                  equalTo: "Passwords do not match"
              }
          },
          errorElement: 'span',
          errorPlacement: function (error, element) {
              error.addClass('invalid-feedback');
              element.closest('.form-group').append(error);
          },
          highlight: function (element, errorClass, validClass) {
              $(element).addClass('is-invalid');
          },
          unhighlight: function (element, errorClass, validClass) {
              $(element).removeClass('is-invalid');
          }
      });

    var ticksStyle = {
        fontColor: '#495057',
        fontStyle: 'bold'
    }

    var mode = 'index'
    var intersect = true
    var $salesChart = $('#sales-chart')
    // eslint-disable-next-line no-unused-vars
    var salesChart = new Chart($salesChart, {
        type: 'bar',
        data: {
        labels: ['JAN','FEB','MAR','APR','MAY','JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
        datasets: [
            {
            backgroundColor: '#ffc107',
            borderColor: '#ffc107',
            data: [1000, 2000, 3000, 2500, 2700, 2500, 3000]
            },
            // {
            //   backgroundColor: '#ced4da',
            //   borderColor: '#ced4da',
            //   data: [700, 1700, 2700, 2000, 1800, 1500, 2000]
            // }
        ]
        },
        options: {
        maintainAspectRatio: false,
        tooltips: {
            mode: mode,
            intersect: intersect
        },
        hover: {
            mode: mode,
            intersect: intersect
        },
        legend: {
            display: false
        },
        scales: {
            yAxes: [{
            // display: false,
            gridLines: {
                display: true,
                lineWidth: '4px',
                color: 'rgba(0, 0, 0, .2)',
                zeroLineColor: 'transparent'
            },
            ticks: $.extend({
                beginAtZero: true,

                // Include a dollar sign in the ticks
                callback: function (value) {
                if (value >= 1000) {
                    value /= 1000
                    value += 'k'
                }

                //   return '$' + value
                return value
                }
            }, ticksStyle)
            }],
            xAxes: [{
            display: true,
            gridLines: {
                display: false
            },
            ticks: ticksStyle
            }]
        }
        }
    })

    // - PIE CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
    var pieData = {
        labels: [
        'CPAK',
        'IPAK',
        'PETPACK',
        'GPAK',
        ],
        datasets: [
        {
            data: [700, 500, 400, 600],
            backgroundColor: ['#f56954', '#00a65a', '#f39c12', '#00c0ef']
        }
        ]
    }
    var pieOptions = {
        legend: {
        display: false
        }
    }
    // Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    // eslint-disable-next-line no-unused-vars
    var pieChart = new Chart(pieChartCanvas, {
        type: 'doughnut',
        data: pieData,
        options: pieOptions
    })

    //amendment request

    var ticksStyle2 = {
        fontColor2: '#495057',
        fontStyle2: 'bold'
    }

    var mode2 = 'index'
    var intersect2 = true
    var $salesChart2 = $('#sales-chart2')
    // eslint-disable-next-line no-unused-vars
    var salesChart2 = new Chart($salesChart2, {
        type: 'bar',
        data: {
        labels: ['JAN','FEB','MAR','APR','MAY','JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
        datasets: [
            {
            backgroundColor: '#ffc107',
            borderColor: '#ffc107',
            data: [1000, 2000, 3000, 2500, 2700, 2500, 3000]
            },
            // {
            //   backgroundColor: '#ced4da',
            //   borderColor: '#ced4da',
            //   data: [700, 1700, 2700, 2000, 1800, 1500, 2000]
            // }
        ]
        },
        options: {
        maintainAspectRatio: false,
        tooltips: {
            mode: mode2,
            intersect: intersect2
        },
        hover: {
            mode: mode2,
            intersect: intersect2
        },
        legend: {
            display: false
        },
        scales: {
            yAxes: [{
            // display: false,
            gridLines: {
                display: true,
                lineWidth: '4px',
                color: 'rgba(0, 0, 0, .2)',
                zeroLineColor: 'transparent'
            },
            ticks: $.extend({
                beginAtZero: true,

                // Include a dollar sign in the ticks
                callback: function (value) {
                if (value >= 1000) {
                    value /= 1000
                    value += 'k'
                }

                //   return '$' + value
                return value
                }
            }, ticksStyle)
            }],
            xAxes: [{
            display: true,
            gridLines: {
                display: false
            },
            ticks: ticksStyle
            }]
        }
        }
    })

    // - PIE CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var pieChartCanvas2 = $('#pieChart2').get(0).getContext('2d')
    var pieData2 = {
        labels: [
        'CPAK',
        'IPAK',
        'PETPACK',
        'GPAK',
        ],
        datasets: [
        {
            data: [700, 500, 400, 600],
            backgroundColor: ['#f56954', '#00a65a', '#f39c12', '#00c0ef']
        }
        ]
    }
    var pieOptions2 = {
        legend: {
        display: false
        }
    }
    // Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    // eslint-disable-next-line no-unused-vars
    var pieChart2 = new Chart(pieChartCanvas2, {
        type: 'doughnut',
        data: pieData2,
        options: pieOptions2
    })
});

  function submitResetPassword() {
      if ($('#quickForm').valid()) {  // Check if the form is valid
          const formData = $('#quickForm').serialize();

          $.ajax({
              url: '{{ route('user_password.reset') }}', // Adjust the route to your password reset endpoint
              type: 'POST',
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              data: formData,
              success: function(response) {
                  if (response.success) {
                      $('#reset-password-modal').modal('hide');
                     
                      toastr.success('Password Reset Successfully')
                      // Optionally, refresh the page or redirect
                  } else {
                      //$('#password-error').text(response.message).removeClass('d-none');
                      toastr.error(response.message);
                  }
              },
              error: function(xhr) {
                  let errorMessage = 'An error occurred. Please try again.';
                  if (xhr.responseJSON && xhr.responseJSON.message) {
                      errorMessage = xhr.responseJSON.message;
                  }
                  toastr.error(errorMessage);
              }
          });
      }
  }
</script>

@endsection

