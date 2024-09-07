<div class="row">
    <div class="col-md-4">
      <div class="form-group">
          <label>Select Supplier</label>
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
    {{-- <div class="col-md-4">
      <div class="form-group">
          <label>Select Company</label>
          <select class="company_id form-control" id="company_id" name="company_id">
          </select>
          
      </div>
     
    </div> --}}
    <div class="col-md-4">
      <div class="form-group">
          <label>Select Payment Terms</label>
          <select class="payment_id form-control" id="payment_id" name="payment_id">
          </select>
          
      </div>
     
    </div>
    <div class="col-md-2 mt-4">
      <div class="form-group">
        {{-- <button class="btn btn-warning btn-lg rounded-pill p-4">Search</button> --}}
        {{-- <button type="button" class="btn btn-block bg-gradient-warning rounded-pill">Search</button> --}}
        <button type="button" class="btn btn-warning btn-block" id="search"><i class="fa fa-search"></i> Search</button>
      </div>
    </div>
  </div>
