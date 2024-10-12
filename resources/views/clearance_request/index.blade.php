@extends('admin.app')

@section('content-header')
  <h1>Clearance Requests</h1>
@endsection

@section('content')

  <!-- Main row -->
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <x-auth-session-status class="mb-4 text-center" :status="session('status')" />
  
       
          <table id="example1" class="table table-bordered table-striped">
            <thead>
            <tr>
              <th>ID</th>
              <th>Shipment Name</th>
              <th>LC No</th>
              <th>Amount</th>
              <th>Currency</th>
              <th>BL NO</th>
              <th>Shipment Date</th>
              <th>Expected Arrival Date</th>
              <th>Actual Arrival Date</th>
              <th>Status</th>
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

    var customTitle = 'LC Amendment Requests';
    var table = $("#example1").DataTable({
      processing: true,
      serverSide: true,
     "buttons": [
        {
          extend: 'copy',
          title: customTitle,
          exportOptions: {
            columns: function(idx, data, node) {
          // Include only visible columns, except the last column
              return table.column(idx).visible() && idx !== (table.columns().count() - 1);
            }
          }
        },
        {
          extend: 'csv',
          title: customTitle,
          exportOptions: {
            columns: function(idx, data, node) {
          // Include only visible columns, except the last column
              return table.column(idx).visible() && idx !== (table.columns().count() - 1);
            }
          }
        },
        {
          extend: 'excel',
          title: customTitle,
          exportOptions: {
            columns: function(idx, data, node) {
          // Include only visible columns, except the last column
              return table.column(idx).visible() && idx !== (table.columns().count() - 1);
            }
          }
        },
        'colvis'
      ],
      "ajax": {
        "url": "{{ route('clearance_request.list') }}",
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
          { data: "cl_id" },
          { data: "shipment_name",searchable: true },
          { data: "lc_request_id",searchable: true },
          { data: "amount",searchable: true },
          { data: "currency",searchable: true },
          { data: "bill_number",searchable: true },
          { data: "shipment_date",searchable: true },
          { data: "expected_arrival_date",searchable: true },
          { data: "actual_arrival_date",searchable: true },
          { data: "status",searchable: true },
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
        order: [[5, 'desc']],  // Set default sort order on the "priority" column (index 8)
      "initComplete": function () {
        // Append buttons container after DataTables initialization
        this.api().buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
      }
    });


    $('#example1').on('click', '.edit-btn', function(e) {
      e.preventDefault();
      var clearnace_request = $(this).data('id');
      console.log('clearnace_request:',clearnace_request);
        if (clearnace_request) {
          var editUrl = "{{ route('clearance_request.edit', ':id') }}"; // Laravel route with a placeholder
          editUrl = editUrl.replace(':id', clearnace_request); // Replace placeholder with actual user ID
          window.location.href = editUrl; // Redirect to the edit page
        }
    });

      // // Handle priority button click event
      $('#example1').on('click', '.shipment_arrived', function(e) {
      e.preventDefault();
      var clearnace_request = $(this).data('id');
      console.log('clearnace_request:',clearnace_request);
      
      if (clearnace_request) {
            $.ajax({
                url: '{{ route("clearance_request.status_update") }}', // The API endpoint to hit
                type: 'POST',
                data: {
                  clearnace_request_id: clearnace_request,
                    _token: '{{ csrf_token() }}' // Include CSRF token
                },
                success: function(response) {
                    if (response.success) {
                        // Reload the table or the specific part of the page
                        table.ajax.reload(null, false);
                        toastr.success('Status Updated');
                    } else {
                        // Handle the error
                        toastr.error('Failed to update the status.');
                    }
                },
                error: function(xhr, status, error) {
                    // Handle AJAX error
                    toastr.error('An error occurred: ' + error);
                }
            });
          }
     
    });


    $('#example1').on('click', '.view-logs', function(e) {
      e.preventDefault();
      var clearnace_request = $(this).data('id');
      console.log('clearnace_request:',clearnace_request);
        if (clearnace_request) {
          var url = "{{ route('clearance_request.view_logs', ':id') }}"; // Laravel route with a placeholder
          url = url.replace(':id', clearnace_request); // Replace placeholder with actual user ID
          window.location.href = url; // Redirect to the edit page
        }
    });


  });
  </script>
@endsection
