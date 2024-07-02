@extends('admin.app')

@section('content-header')
  <h1>Supply Management</h1>
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
              <th>Name</th>
              <th>Created At</th>
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

<script>

  $(document).ready(function() {
    var customTitle = 'Supplier Management';

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
        // {
        //   extend: 'pdf',
        //   title: customTitle,
        //   exportOptions: {
        //     columns: ':not(:last-child)' // Exclude the last column (Action button)
        //   }
        // },
        // 'colvis'
      ],
      "ajax": {
        "url": "{{ route('supplier.list') }}",
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
          { data: "name",searchable: true },
          { data: "created_at",searchable: true },
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
      "initComplete": function () {
        // Append buttons container after DataTables initialization
        this.api().buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
      }
    });
  
    $('#example1').on('click', '.edit-btn', function(e) {
      e.preventDefault();
      var supplierId = $(this).data('id');
      console.log(supplierId);
      // Perform edit operation based on userId
      // For example, redirect to user page
      var editUrl = "{{ route('supplier.edit', ':id') }}"; // Laravel route with a placeholder
        editUrl = editUrl.replace(':id', supplierId); // Replace placeholder with actual user ID
        window.location.href = editUrl; // Redirect to the edit page
    });
  
    // Handle delete button click event
    $('#example1').on('click', '.delete-btn', function(e) {
      e.preventDefault();
      var userId = $(this).data('id');
      // Perform delete operation based on userId
      // For example, show confirmation modal
      // $('#deleteUserModal').modal('show');
    });
  });
  </script>
@endsection
