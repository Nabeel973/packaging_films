@extends('admin.app')

@section('content-header')
  <h1>LC Logs</h1>
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
              <th>LC Request ID</th>
              <th>LC Status</th>
              <th>Reason</th>
              <th>Amendment Request ID</th>
              <th>Amendment Request Status</th>
              <th>Created By</th>
              <th>Created At</th>
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
    var id = {{ $id }} 
    var customTitle = 'Logs';
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
        url: "{{ route('lc_request.logs') }}",
        "type": "GET",
        data: function (d) {
          d.id = id; // Pass the id to the server as a query parameter
        },
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
          { data: "lc_request_id",searchable: true },
          { data: "status",searchable: true },
          { data: "reason",searchable: true },
          { data: "amendment_id",searchable: true },
          { data: "amendment_status",searchable: true },
          { data: "created_by",searchable: true },
          { data: "created_at",searchable: true },
          
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

  });
  </script>
@endsection
