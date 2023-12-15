@extends('layouts/district')
@section('main')
<div class="card m-4">
    <div class="card-header">
        <h4>Add Sanction Progress</h4>
    </div>
    <div class="card-body">
        <div class="" >
            <div class="row my-2">
                <div class="col-md-4"><strong>District Name: {{$sanction->district}}</strong></div>
                <div class="col-md-4"><strong>Block Name: {{$sanction->block}}</strong></div>
                <div class="col-md-4"><strong>Gram Panchayat Name: {{$sanction->gp}}</strong></div>
            </div>
            <div class="row my-2">
                <div class="col-md-4"><strong>Sanction Amount: ₹{{$sanction->san_amount}}</strong></div>
                <div class="col-md-4"><strong>Financial Year: {{$sanction->financial_year}}</strong></div>
                <div class="col-md-4"><strong>Sanction Head: {{$sanction->sanction_head}}</strong></div>
            </div>
        </div>
        <div class="">
            <form>
                <div class="mb-3">
                  <label for="Progress Percentage" class="form-label">Percentage of Work Completed</label>
                  <input type="text" class="form-control" id="p_completed_per" name="completion_percentage">
                </div>
                <div class="mb-3">
                    <label for="Work Completed" class="form-label">Is Work Completed for which sanction is given?</label>
                    <select name="p_isComplete" id="isCompleted" class="form-control">
                        <option value="-1">--Select Option--</option>
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="Remarks" class="form-label">Remarks</label>
                    <textarea type="text" name="remarks" id="remarks" class="form-control">
                    </textarea>
                </div>
                <button type="submit" class="btn btn-primary">Add Progress</button>
              </form>

        </div>
        
    </div>
    
</div>
@endsection
