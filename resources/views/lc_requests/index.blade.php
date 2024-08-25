@extends('admin.app')

@section('content-header')
  <h1>Pending LC Opening Requests</h1>
@endsection

@section('content')

  <!-- Main row -->
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <x-auth-session-status class="mb-4 text-center" :status="session('status')" />


          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                  <label>Select Supplier*</label>
                  <select class="supplier form-control" id="supplier" name="supplier">
                  </select>
                  
              </div>
             
            </div>

            <div class="col-md-4">
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="inputFrom">Quantity From</label>
                  <input type="number" class="form-control" id="quantity_from" placeholder="Start Quantity" min="0">
                </div>
                <div class="form-group col-md-6">
                  <label for="inputTo">Quantity To</label>
                  <input type="number" class="form-control" id="quantity_to" placeholder="To Quantity" min="0">
                </div>
              </div>
              
            </div>
            
            <div class="col-md-4">
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="inputFrom">Value From</label>
                  <input type="number" class="form-control" id="value_from" placeholder="Value From" min="0" >
                </div>
                <div class="form-group col-md-6">
                  <label for="inputTo">Value To</label>
                  <input type="number" class="form-control" id="value_to" placeholder="Value To" min="0">
                </div>
              </div>
              
            </div>
          </div>

          <div class="row mb-4">
            <div class="col-md-4">
              <div class="form-group">
                <label>Date range:</label>

                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="far fa-calendar-alt"></i>
                    </span>
                  </div>
                  <input type="text" class="form-control float-right" id="date_range">
                </div>
                <!-- /.input group -->
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <button class="btn btn-warning btn-md">Search</button>
              </div>
            </div>
          </div>


          <table id="example1" class="table table-bordered table-striped">
            <thead>
            <tr>
              <th>ID</th>
              <th>Shipment Name</th>
              <th>Supplier Name</th>
              <th>Item Name</th>
              <th>Item Quantity</th>
              <th>Payment Terms</th>
              <th>Status</th>
              <th>Reason Code</th>
              <th>Priority</th>
              <th>Draft Required</th>
              <th>Amount</th>
              <th>Currency</th>
              <th>Amendment Added</th>
              <th>Bank Name</th>
              <th>Transmitted LC Number</th>
              {{-- <th>Created By</th>
              <th>Created At</th> --}}
              <th>Updated By</th>
              <th>Updated At</th>
              <th>Action</th>
            </tr>
            </thead>          
          </table>
        </div>
        <!-- /.card-body -->
      </div>
    </div>
  </div>
@endsection

@section('scripts')

<script src="{{asset("plugins/datatables/jquery.dataTables.min.js")}}"></script>
<script src="{{asset("plugins/datatables-bs4/js/dataTables.bootstrap4.min.js")}}"></script>
<script src="{{asset("plugins/datatables-responsive/js/dataTables.responsive.min.js")}}"></script>
<script src="{{asset("plugins/datatables-responsive/js/responsive.bootstrap4.min.js")}}"></script>
<script src="{{asset("plugins/datatables-buttons/js/dataTables.buttons.min.js")}}"></script>
<script src="{{asset("plugins/datatables-buttons/js/buttons.bootstrap4.min.js")}}"></script>
<script src="{{asset("plugins/jszip/jszip.min.js")}}"></script>

<script src="{{asset("plugins/datatables-buttons/js/buttons.html5.min.js")}}"></script>
<script src="{{asset("plugins/datatables-buttons/js/buttons.colVis.min.js")}}"></script>    
<script src="{{ asset('/plugins/toastr/toastr.min.js')}}"></script>
  <!-- Select2 -->
  <script src="{{asset("plugins/select2/js/select2.full.min.js")}}"></script>

  <script src="{{ asset('plugins/moment/moment.min.js')}}"></script>
  <script src="{{ asset('plugins/inputmask/jquery.inputmask.min.js') }}"></script>
  <!-- date-range-picker -->
  <script src="{{ asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
 <!-- Tempusdominus Bootstrap 4 -->
  <script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>


<script>

  $(document).ready(function() {

      //Date range picker
      $('#reservation').daterangepicker();



    var supplier_names = {!! json_encode($supplier_names) !!};

    $(".supplier").select2({
        placeholder: "Select Supplier",
        data: supplier_names.map(function(supplier) {
            return { id: supplier.id, text: supplier.name };
        }),
        width: '100%',
        dropdownAutoWidth: true,
        //allowClear: true // Add this line to allow clearing the selection
      });

      $(".supplier").val('').trigger('change');

    var customTitle = 'Pending LC Enquiry';
    var table = $("#example1").DataTable({
      processing: true,
      serverSide: true,
     "buttons": [
        {
          extend: 'copy',
          title: customTitle,
          exportOptions: {
            columns: ':not(:last-child)' // Exclude the last column (Action button)
          }
        },
        {
          extend: 'csv',
          title: customTitle,
          exportOptions: {
            columns: ':not(:last-child)' // Exclude the last column (Action button)
          }
        },
        {
          extend: 'excel',
          title: customTitle,
          exportOptions: {
            columns: ':not(:last-child)' // Exclude the last column (Action button)
          }
        },

        'colvis'
      ],
      "ajax": {
        "url": "{{ route('lc_request.pending.list') }}",
        "type": "GET",
        dataSrc: function(json) {
          if (Array.isArray(json.data)) {
            return json.data;
          } else {
            return [];
          }
        }
      },
      columns: [
          { data: "id" },
          { data: "shipment_name",searchable: true },
          { data: "supplier_name",searchable: true },
          { data: "item_name",searchable: true },
          { data: "quantity",searchable: true },
          { data: "payment_terms",searchable: true },
          { data: "status",searchable: true },
          { data: "reason_code",searchable: true },
          { data: "priority",searchable: true },
          { data: "draft_required",searchable: true },
          { data: "amount",searchable: true },
          { data: "currency_name",searchable: true },
          { data: "amendment_request_count",searchable: true },
          { data: "bank_name",searchable: true },
          { data: "lc_number",searchable: true },
          // { data: "created_by",searchable: true },
          // { data: "created_at",searchable: true },
          { data: "updated_by",searchable: true },
          { data: "updated_at",searchable: true },
          { data: "action", orderable: false, searchable: false }
          
        ],
        paging: true,
        lengthChange: true,
      
      "lengthMenu": [10, 25, 50, 100], // Specify available options for records per page
      "pageLength": 10, // Set default number of records per page
        searching: true,
        ordering: true,
        info: true,
        responsive: true, 
        autoWidth: false,
        order: [[8, 'asc']],  // Set default sort order on the "priority" column (index 8)
      "initComplete": function () {
        // Append buttons container after DataTables initialization
        this.api().buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
      }
    });

  
  
    $('#example1').on('click', '.edit-btn', function(e) {
      e.preventDefault();
      var lc_request_id = $(this).data('id');
      console.log(lc_request_id);
      // Perform edit operation based on userId
      // For example, redirect to user page
      var editUrl = "{{ route('lc_request.edit', ':id') }}"; // Laravel route with a placeholder
        editUrl = editUrl.replace(':id', lc_request_id); // Replace placeholder with actual user ID
        window.location.href = editUrl; // Redirect to the edit page
    });
  
    // // Handle priority button click event
    $('#example1').on('click', '.set-priority-high', function(e) {
      e.preventDefault();
      var lc_request_id = $(this).data('id');
      if (lc_request_id) {
            $.ajax({
                url: '{{ route("lc_request.set-priority") }}', // The API endpoint to hit
                type: 'POST',
                data: {
                    lc_request_id: lc_request_id,
                    _token: '{{ csrf_token() }}' // Include CSRF token
                },
                success: function(response) {
                    if (response.success) {
                        // Reload the table or the specific part of the page
                        table.ajax.reload(null, false);
                        toastr.success('Priority Updated');
                    } else {
                        // Handle the error
                        toastr.error('Failed to set the priority.');
                    }
                },
                error: function(xhr, status, error) {
                    // Handle AJAX error
                    toastr.error('An error occurred: ' + error);
                }
            });
          }
      else{
        //
      }
    });

    $('#example1').on('click', '.amendment-request', function(e) {
      e.preventDefault();
      var lc_request_id = $(this).data('id');
        if (lc_request_id) {
          var editUrl = "{{ route('amendment_request.add', ':id') }}"; // Laravel route with a placeholder
          editUrl = editUrl.replace(':id', lc_request_id); // Replace placeholder with actual user ID
          window.location.href = editUrl; // Redirect to the edit page
        }
    });

    $('#example1').on('click', '.view-logs', function(e) {
      e.preventDefault();
      var lc_request_id = $(this).data('id');
        if (lc_request_id) {
          var editUrl = "{{ route('lc_request.logs_view', ':id') }}"; // Laravel route with a placeholder
          editUrl = editUrl.replace(':id', lc_request_id); // Replace placeholder with actual user ID
          window.location.href = editUrl; // Redirect to the edit page
        }
    });


  });
  </script>
@endsection
