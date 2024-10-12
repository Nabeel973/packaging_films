@extends('admin.app')

@section('content-header')
    <h1>Edit Shipment Clearance Request</h1>
@endsection

@section('content')
    <!-- Main row -->
    <div class="card">
        <div class="card-body">
            <x-auth-session-status class="mb-4 text-center" :status="session('status')" />
            <form id="quickForm" method="post" action="{{ route('clearance_request.update',$id) }}" enctype="multipart/form-data">
                @csrf
                <div class="row mb-2">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="shipmentName">BL NO*</label>
                            <input type="text" name="bill_number" class="form-control" id="bill_number"
                                placeholder="Enter BL No" value="{{$clearnace_request->bill_number}}" {{ $disable ? 'disabled' : '' }}>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Select Shipment Type*</label>
                            <select class="shipment_type form-control" id="shipment_type_id" name="shipment_type_id"  {{ $disable ? 'disabled' : '' }}>
                            </select>

                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="itemName">Duties and Taxes (Estd)*</label>
                            <input type="number" name="tax" class="form-control " id="tax"
                                placeholder="Enter Duties and Taxes (Estd)" value="{{$clearnace_request->tax}}"  {{ $disable ? 'disabled' : '' }}>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="itemQuantity">Tracking Number</label>
                            <input type="text" name="tracking_number" class="form-control" id="tracking_number"
                                placeholder="Enter Tracking Number" value="{{$clearnace_request->tracking_number}}"  {{ $disable ? 'disabled' : '' }}>
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Shipment Date:</label>
                            <div class="input-group shipment_date" id="shipmentdatepicker" data-target-input="nearest">
                                <input type="text" class="form-control shipment-datetimepicker-input"
                                    data-target="#shipmentdatepicker" id="shipment_date" name="shipment_date" value="{{$clearnace_request->shipment_date}}"  {{ $disable ? 'disabled' : '' }}>
                                <div class="input-group-append" data-target="#shipmentdatepicker"
                                    data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Shipment Arrival Date:</label>
                            <div class="input-group shipment_arrival_date" id="shipmentarrivaldatepicker"
                                data-target-input="nearest">
                                <input type="text" class="form-control arrival-datetimepicker-input"
                                    data-target="#shipmentarrivaldatepicker" id="shipment_arrival_date"
                                    name="shipment_arrival_date" value="{{$clearnace_request->expected_arrival_date}}"  {{ $disable ? 'disabled' : '' }}>
                                <div class="input-group-append" data-target="#shipmentarrivaldatepicker"
                                    data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="otherDocuments">Shipping Document*</label>
                            <input type="file" class="form-control" id="document"
                                name="document"  {{ $disable ? 'disabled' : '' }}>
                            @if ($clearnace_request->document)
                                <a href="{{ asset('storage/'.$clearnace_request->document) }}" class="btn btn-success mt-2" download>
                                    <i class="fas fa-download"></i> Download
                                </a>
                            @endif
                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="otherDocuments">picture</label>
                            <input type="file" class="form-control" id="picture"
                                name="picture"  {{ $disable ? 'disabled' : '' }}>
                                @if ($clearnace_request->picture)
                                    <a href="{{ asset('storage/'.$clearnace_request->picture) }}" class="btn btn-success mt-2" download>
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                @endif
                        </div>

                    </div>
                </div>   
                @if(!$disable)
                    <div class="row justify-content-center mt-2">
                        <button type="submit" class="btn btn-warning btn-lg mx-2" id="submit-button"  >
                        <i class="fas fa-save pr-2"> </i> Update Shipment Planning
                        </button>
                    </div>
                @endif   
            </form>

        </div>

    </div>
    </div>
@endsection

@section('scripts')
    <!-- jquery-validation -->
    <script src="{{ asset('plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>

    <!-- InputMask -->
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/inputmask/jquery.inputmask.min.js') }}"></script>
    <!-- date-range-picker -->
    <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <script>
        $(document).ready(function() {

            var shipment_types = {!! json_encode($shipment_types) !!};
    
            $(".shipment_type").select2({
                placeholder: "Select Shipment Type",
                data: shipment_types.map(function(shipment_type) {
                    return {
                        id: shipment_type.id,
                        text: shipment_type.name
                    };
                }),
                width: '100%',
                dropdownAutoWidth: true,

            });

            $(".shipment_type").val({{$clearnace_request->shipment_type_id}}).trigger('change');


            $.validator.setDefaults({
                submitHandler: function(form) {
                    $('#submit-button').prop('disabled', true);
                    // $('#loader').show();
                    form.submit(); // Submit the form
                }
            });


            $('#quickForm').validate({
                rules: {
                    bill_number: {
                        required: true,
                    },
                    shipment_type_id: {
                        required: true,
                    },
                    tax: {
                        required: true,
                    },
                    document: {
                        extension: "pdf|doc|docx|png|jpg|jpeg",
                    },
                    picture: {
                        extension: "pdf|doc|docx|png|jpg|jpeg",
                    },
                },
                messages: {
                    bill_number: {
                        required: "Please enter BL NO",
                    },
                    shipment_type_id: {
                        required: "Please select a Shipment Type ",
                    },
                    tax: {
                        required: "Please enter Duties and Taxes (Estd)",
                    },
                    document: {
                        required: "Please upload Shipping Document",
                    },

                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                    $('#submit-button').prop('disabled', false);
                    // $('#loader').hide();
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });

            $('#quickForm').on('invalid-form.validate', function() {
                $('#submit-button').prop('disabled', false);
                // $('#loader').hide();
            });

            $('#shipmentdatepicker').datetimepicker({
                format: 'YYYY-MM-DD' // Use the correct format for your date
            });


            $('#shipmentarrivaldatepicker').datetimepicker({
                format: 'YYYY-MM-DD' // Use the correct format for your date
            });




        });
    </script>
@endsection
