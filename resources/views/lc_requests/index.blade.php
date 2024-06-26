@extends('admin.app')

@section('content-header')
  <h1>LC Enquiry</h1>
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
              <th>Supplier Name</th>
              <th>Item Name</th>
              <th>Item Quantity</th>
              <th>Payment Terms</th>
              <th>Status</th>
              <th>Reason Code</th>
              <th>priority</th>
              <th>Draft Required</th>
              <th>Created By</th>
              <th>Created At</th>
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
<script src="{{asset("plugins/pdfmake/pdfmake.min.js")}}"></script>
<script src="{{asset("plugins/pdfmake/vfs_fonts.js")}}"></script>
<script src="{{asset("plugins/datatables-buttons/js/buttons.html5.min.js")}}"></script>
{{-- <script src="{{asset("plugins/datatables-buttons/js/buttons.print.min.js")}}"></script> --}}
<script src="{{asset("plugins/datatables-buttons/js/buttons.colVis.min.js")}}"></script>    
<script src="../../plugins/toastr/toastr.min.js"></script>
<script>

  $(document).ready(function() {
    var customTitle = 'Supplier Management';
    var table = $("#example1").DataTable({
  
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
        {
          extend: 'pdf',
          title: customTitle,
          exportOptions: {
            columns: ':not(:last-child)' // Exclude the last column (Action button)
          }
        },
        'colvis'
      ],
      "ajax": {
        "url": "{{ route('lc_request.list') }}",
        "type": "GET",
        "dataSrc": ""
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
          { data: "created_by",searchable: true },
          { data: "created_at",searchable: true },
          { data: "updated_by",searchable: true },
          { data: "updated_at",searchable: true },
          { 
            data: null,
            render: function(data, type, row) {
              return '<div class="btn-group">' +
                        '<button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                          'Actions' +
                        '</button>' +
                        '<div class="dropdown-menu">' +
                          '<a class="dropdown-item edit-btn" href="#" data-id="' + data.id + '">Edit</a>' +
                          '<a class="dropdown-item set-priority-high" href="#" data-id="' + data.id + '">Set priority High</a>' +
                        
                        '</div>' +
                      '</div>';
            }
          }
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
  
    // Handle delete button click event
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

      }
    });
  });
  </script>
@endsection
