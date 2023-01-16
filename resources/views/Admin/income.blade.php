@extends('master')

@section('title','Incomes List')

@section('place')

@section('place')

{{-- <div class="col-md-5 col-8 align-self-center">
    <h3 class="text-themecolor m-b-0 m-t-0">@lang('lang.expenses') @lang('lang.list')</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('index')}}">@lang('lang.dashboard')</a></li>
        <li class="breadcrumb-item active">@lang('lang.expenses') @lang('lang.list')</li>
    </ol>
</div> --}}

@endsection

@section('content')
        <div class="row">
            <div class="col-6">
                <a href="#" class="btn btn-info" data-toggle="modal" data-target="#add_incomes" >Add Income</a>
                <div class="modal fade" id="add_incomes" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title"> Create Incomes</h4>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                            </div>
                            <div class="modal-body" id="slimtest2">
                                <form action="{{route('store_income')}}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label">Income Type</label>
                                                <select class="form-control" onchange="showPeriod(this.value)" name="type">
                                                    <option value="">Select Income Type</option>
                                                    <option value="1">Fixed</option>
                                                    <option value="2">Variable</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Period</label>
                                                <select class="form-control" id="period" name="period">
                                                    <option value="">Select</option>
                                                    <option value="1">Daily</option>
                                                    <option value="2">Weekly</option>
                                                    <option value="3">Monthly</option>
                                                </select>
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Date</label>
                                                <input type="date" class="form-control" id="mdate" name="date">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Title</label>
                                                <input type="text" class="form-control" name="title">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Description</label>
                                                <input type="text" class="form-control" name="description">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Amount</label>
                                                <input type="number" class="form-control" name="amount">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Applied to Profit/Loss</label>
                                                <select class="form-control" name="profit_loss_flag">
                                                    <option value="">Select</option>
                                                    <option value="1">Yes</option>
                                                    <option value="2">No</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <div class="row">
                                            <div class="col-md-6 float-right">
                                                <div class="row">
                                                    <div class=" col-md-9">
                                                        <button type="submit" class="btn btn-success">Submit</button>
                                                        <button type="button" class="btn btn-inverse btn-dismiss" data-dismiss="modal">Cancel</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                   </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><br/>
        <div class="card">
            <div class="card-body">
                <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Income Type</th>
                                <th>Period</th> 
                                <th>Date</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            $i = 1;
                        ?>
                        @foreach($incomes as $income)
                        <tr>
                            <td>{{$i++}}</td>
                            @if($income->type == 1)
                            <td>Fixed</td>
                            @else
                            <td>Variable</td>
                            @endif
                            @if($income->period == 1)
                            <td>Daily</td>
                            @elseif($income->period == 2)
                            <td>Weekly</td>
                            @else
                            <td>Monthly</td>
                            @endif
                            @if($income->type == 1)
                            <td>ရက်စွဲမရှိပါ</td>
                            @else
                            <td>{{$income->date}}</td>
                            @endif
                            <td>{{$income->title}}</td>
                            <td>{{$income->description}}</td>
                            <td>{{$income->amount}}</td>
                            
                            <td class="text-center">
                                                    <div class="d-flex">
                                                        <a href="#" class="btn btn-sm btn-outline-warning" data-toggle="modal" data-target="#edit_income{{$income->id}}">
                                                            <i class="fas fa-edit"></i></a>

                                                        <a href="#" class="btn btn-sm btn-outline-danger" onclick="deleteIncome('{{$income->id}}')">
                                                            <i class="fas fa-trash-alt"></i></a>
                                                    </div>

                                                </td>
                            
                            <div class="modal fade" id="edit_income{{$income->id}}" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Update Incomes</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                            </div>
                            <div class="modal-body" id="slimtest2">
                                <form action="{{route('update_income',$income->id)}}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label">Income Type</label>
                                                <select class="form-control" onchange="showPeriod(this.value)" name="type">
                                                    <option value="">Select Income Type</option>
                                                    <option value="1" @if($income->type === 1) selected='selected' @endif>Fixed</option>
                                                    <option value="2" @if($income->type === 2) selected='selected' @endif>Variable</option>
                                                </select>
                                            </div>
                                        </div>                              
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Period</label>
                                                <select class="form-control" id="period" name="period">
                                                    <option value="">select</option>
                                                    <option value="1" @if($income->period === 1) selected='selected' @endif>Daily</option>
                                                    <option value="2" @if($income->period === 2) selected='selected' @endif>Weekly</option>
                                                    <option value="3" @if($income->period === 3) selected='selected' @endif>Monthly</option>
                                                </select>
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Date</label>
                                                <input type="date" class="form-control" id="mdate" name="date" value="{{$income->date}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Title</label>
                                                <input type="text" class="form-control" name="title" value="{{$income->title}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Description</label>
                                                <input type="text" class="form-control" name="description" value="{{$income->description}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Amount</label>
                                                <input type="number" class="form-control" name="amount" value="{{$income->amount}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Description</label>
                                                <select class="form-control" name="profit_loss_flag">
                                                    <option value="">Select</option>
                                                    <option value="1" @if($income->profit_loss_flag === 1) selected='selected' @endif>Yes</option>
                                                    <option value="2" @if($income->profit_loss_flag === 2) selected='selected' @endif>No</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <div class="row">
                                            <div class="col-md-6 float-right">
                                                <div class="row">
                                                    <div class=" col-md-9">
                                                        <button type="submit" class="btn btn-success">Submit</button>
                                                        <button type="button" class="btn btn-inverse btn-dismiss" data-dismiss="modal">Cancel</button>
                                                    </div>
                                                </div>
                                            </div> 
                                        </div>
                                   </div>
                                </form>       
                            </div>
                        </div>
                    </div>
                </div>
                            
                        </tr>
                        @endforeach
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
            </div>
        </div>
    </div>
</div>


@endsection

@section('js')

<script src="{{asset('assets/plugins/dropify/dist/js/dropify.min.js')}}"></script>

<script type="text/javascript">
    $('.dropify').dropify();
    // $('#mdate').bootstrapMaterialDatePicker({ weekStart: 0, time: false });
    $('#mdate').prop("disabled",true);
    $('#period').prop("disabled",true);
    function showPeriod(value){
        var show_options = value;
        //  alert(show_options);
        if( show_options == 1){
            $('#mdate').prop("disabled",true);
            $('#period').prop("disabled",false);
            }
        else{
            $('#mdate').prop("disabled",false);
            $('#period').prop("disabled",true);
        }
    }
    
    function deleteIncome(value) {
            var income_id = value;
            swal({
                    title: "Confirm",
                    icon: 'warning',
                    buttons: ["No", "Yes"]
                })
                .then((isConfirm) => {
                    if (isConfirm) {
                        $.ajax({
                            type: 'POST',
                            url: 'deleteIncome',
                            dataType: 'json',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "income_id": income_id,
                            },
                            success: function() {
                                window.location.href = "/Incomes";
                                swal({
                                    title: "Success!",
                                    text: "Successfully Deleted!",
                                    icon: "success",
                                });
                                setTimeout(function() {
                                    window.location.reload();
                                }, 1000);
                            },
                        });
                    }
                });
        }
</script>

@endsection