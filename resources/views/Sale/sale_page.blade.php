@extends('master')

@section('title','Shop Order Page')

@section('place')

<!--<div class="col-md-5 col-8 align-self-center">-->
<!--    <h3 class="text-themecolor m-b-0 m-t-0">Shop Order Page</h3>-->
<!--    <ol class="breadcrumb">-->
<!--        <li class="breadcrumb-item"><a href="{{route('index')}}">Back to Dashborad</a></li>-->
<!--        <li class="breadcrumb-item active">Shop Order Page</li>-->
<!--    </ol>-->
<!--</div>-->

@endsection

@section('content')

<div class="row page-titles">
    <div class="col-md-10 col-10 align-self-center">
        <h2 class="font-weight-bold">Shop Order</h2>
    </div>

    {{-- <div class="cold-md-2 pull-right">
        <a href="{{route('shop_order_sale', 0)}}" class="btn btn-outline-primary float-right">Take Away Order</a>
    </div> --}}
</div>

<div>
    <form action="{{route('soup_kitchen')}}" method="POST" id="soupkichen">
        @csrf
        <input type="hidden" id="kit_id" name="order_id">

    </form>
</div>

<div class="row">
    <div class="col-md-3">
        <label class="font-weight-bold">Filter By Floor</label>
        <select class="form-control mr-2" style="width: 100%" onchange="searchByFloor(this.value)" id="floor">
            <option >Select Floor</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
        </select>
    </div>

    <div class="col-md-3">
        <label class="font-weight-bold">Filter By Table Type</label>
        <select class="form-control mr-2" style="width: 100%" onchange="searchByTableType(this.value)" disabled id="table_type">
            <option value="0">Select Table Type</option>
            @foreach($table_types as $type)
                <option value="{{$type->id}}">{{$type->name}}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3">
    <label class="font-weight-bold">Choose shop or delivery</label>
    <div class="dropdown">
  <button class="btn btn-danger dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Shop
  </button>
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

    <a class="dropdown-item" href="{{route('delivery')}}">Delivery</a>

  </div>
</div>

    </div>


    <div class="card shadow mt-3">
        <div class="card-body" style="width:1280px;">
            <ul class="nav nav-pills m-t-30 m-b-30">

                <li class="nav-item">
                    <a href="#navpills-2" class="nav-link active" data-toggle="tab" aria-expanded="false">Graphical View</a>
                </li>

                <li class=" nav-item">
                    <a href="#navpills-1" class="nav-link" data-toggle="tab" aria-expanded="false">List View</a>
                </li>
            </ul>

            <div class="tab-content br-n pn">

                <div id="navpills-1" class="tab-pane ">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Table Number</th>
                                            <th>Floor</th>
                                            <th>Table Type</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php $i=1;?>
                                        @foreach($table_lists as $table)
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>{{$table->table_number}}</td>
                                            <td>{{$table->floor}}</td>
                                            <td>{{$table->table_type->name}}</td>
                                            <td><a href="{{route('shop_order_sale', $table->id)}}" class="btn btn-outline-primary">Go To Shop Order Page</a></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="navpills-2" class="tab-pane active">
                    <div class="row" id="table_list_2">
                        <div class="col-md-3">
                            <h3 class="text-center">6 Persons Table </h3>
                            <div class="card">
                                <div class="card-body">
                            <div class="row">
                                @foreach($table_lists as $table)
                                @if ($table->table_type_id == 1)
                                    <div class="col-md-10 ml-4">
                                        <div class="card"  style="border:1px solid lightblue;border-radius:10px;" onclick="showorder({{$table->id}},{{$table->status}})">
                                            <div class="card-body">
                                                <div class="d-flex flex-row">
                                                    @if ($table->status == 1)
                                                    <div class="">
                                                        <span class="badge badge-info">{{$table->table_number}}</span><br>
                                                        <span class="badge badge-info">Free</span><br>
                                                    </div>
                                                    <div class="ml-1">
                                                        <span class="badge badge-info">Start-00:00</span><br>
                                                        <span class="badge badge-info">End-00:00</span><br>
                                                    </div>
                                                    @elseif ($table->status == 2)
                                                    <div class="">
                                                        <span class="badge badge-secondary">{{$table->table_number}}</span><br>
                                                        <span class="badge badge-secondary">Pending</span><br>
                                                    </div>
                                                    <div class="ml-1">
                                                        <span class="badge badge-secondary">Start-{{$table->start_time}}</span><br>
                                                        <span class="badge badge-secondary">End-{{$table->end_time}}</span><br>
                                                    </div>
                                                    @endif

                                                </div>
                                            </div>
                                            <span class="badge badge-warning"><div></div></span>
                                        </div>
                                    </div>
                                @endif
                                @endforeach
                            </div>
                            </div>
                        </div>
                    </div>

                        <div class="col-md-5">
                            <h3 class="text-center">4 Persons Table Outside</h3>
                            <div class="card">
                                <div class="card-body">
                            <div class="row">
                                @foreach($table_lists as $table)
                                @if ($table->table_type_id == 2)
                                    <div class="col-md-5 ml-4">
                                        <div class="card"  style="border:1px solid lightblue;border-radius:10px;" onclick="showorder({{$table->id}},{{$table->status}})">
                                            <div class="card-body">
                                                <div class="d-flex flex-row">
                                                    @if ($table->status == 1)
                                                    <div class="">
                                                        <span class="badge badge-info">{{$table->table_number}}</span><br>
                                                        <span class="badge badge-info">Free</span><br>
                                                    </div>
                                                    <div class="ml-1">
                                                        <span class="badge badge-info">Start-00:00</span><br>
                                                        <span class="badge badge-info">End-00:00</span><br>
                                                    </div>
                                                    @elseif ($table->status == 2)
                                                    <div class="">
                                                        <span class="badge badge-secondary">{{$table->table_number}}</span><br>
                                                        <span class="badge badge-secondary">Pending</span><br>
                                                    </div>
                                                    <div class="ml-1">
                                                        <span class="badge badge-secondary">Start-{{$table->start_time}}</span><br>
                                                        <span class="badge badge-secondary">End-{{$table->end_time}}</span><br>
                                                    </div>
                                                    @endif

                                                </div>
                                            </div>
                                            <span class="badge badge-danger"><div></div></span>
                                        </div>
                                    </div>

                                @endif
                                @endforeach
                            </div>
                            </div>
                        </div>
                    </div>

                        <div class="col-md-4">
                            <h3 class="text-center">4 Persons Table Room</h3>
                            <div class="card">
                                <div class="card-body">
                            <div class="row">
                                @foreach($table_lists as $table)
                                @if ($table->table_type_id == 3)
                                    <div class="col-md-6">
                                        <div class="card"  style="border:1px solid lightblue;border-radius:10px;" onclick="showorder({{$table->id}},{{$table->status}})">
                                            <div class="card-body">
                                                <div class="d-flex flex-row">
                                                    @if ($table->status == 1)
                                                    <div class="">
                                                        <span class="badge badge-info">{{$table->table_number}}</span><br>
                                                        <span class="badge badge-info">Free</span><br>
                                                    </div>
                                                    <div class="ml-1">
                                                        <span class="badge badge-info">Start-00:00</span><br>
                                                        <span class="badge badge-info">End-00:00</span><br>
                                                    </div>
                                                    @elseif ($table->status == 2)
                                                    <div class="">
                                                        <span class="badge badge-secondary">{{$table->table_number}}</span><br>
                                                        <span class="badge badge-secondary">Pending</span><br>
                                                    </div>
                                                    <div class="ml-1">
                                                        <span class="badge badge-secondary">Start-{{$table->start_time}}</span><br>
                                                        <span class="badge badge-secondary">End-{{$table->end_time}}</span><br>
                                                    </div>
                                                    @endif


                                                </div>
                                            </div>
                                            <span class="badge badge-success"><div></div></span>
                                        </div>
                                    </div>
                                @endif
                                @endforeach
                            </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" role="dialog" id="ordermodal">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Table Register Form</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class=" offset-md-2 col-md-4">
                        <label class="font-weight-bold" for="timechk">
                          Start Time
                        </label>
                    </div>
                    <div class="form-group col-md-4">
                        <input class="form-control" type="time" placeholder="0" id="time">
                    </div>
                    <div class="form-check offset-md-2 col-md-4">
                        <input class="form-check-input" type="checkbox" value="" id="adultchk" onclick="showdis()">
                        <label class="form-check-label" for="adultchk">
                          Adult
                        </label>
                    </div>
                    <div class="form-group col-md-4">
                        <input class="form-control" type="text" placeholder="0" value="0" id="adult" disabled>
                    </div>
                    <div class="form-check offset-md-2 col-md-4">
                        <input class="form-check-input" type="checkbox" value="" id="childchk" onclick="showdis()">
                        <label class="form-check-label" for="childchk">
                          Children
                        </label>
                    </div>
                    <div class="form-group col-md-4">
                        <input class="form-control" type="text" placeholder="0" value="0" id="child" disabled>
                    </div><div class="form-check offset-md-2 col-md-4">
                        <input class="form-check-input" type="checkbox" value="" id="kidchk" onclick="showdis()">
                        <label class="form-check-label" for="kidchk">
                          Kid
                        </label>
                    </div>
                    <div class="form-group col-md-4">
                        <input class="form-control" type="text" placeholder="0" value="0" id="kid" disabled>
                    </div>
                    <div class="form-check offset-md-2 col-md-4">
                        <input class="form-check-input" type="checkbox" value="" id="potchk" onclick="showdis()">
                        <label class="form-check-label" for="potchk">
                          Extra Pot
                        </label>
                    </div>
                    <div class="form-group col-md-4">
                        <input class="form-control" type="text" value="0" id="pot" disabled>
                    </div>
                    <div class="form-check offset-md-2 col-md-4">
                        <input class="form-check-input" type="checkbox" value="" id="bdchk" onclick="showdis()">
                        <label class="form-check-label" for="bdchk">
                          Birthday Person
                        </label>
                    </div>
                    <div class="form-group col-md-4">
                        <input class="form-control" type="text"  value="0" id="bd" disabled>
                    </div>
                    <div class="form-check offset-md-3 col-md-3">
                        <input class="form-check-input" type="radio" value="" name="selectsoup" id="simchk" onclick="simplechg()">
                        <label class="form-check-label" for="simchk">
                          Simple
                        </label>
                    </div>
                    <div class="form-check col-md-4">
                        <input class="form-check-input" type="radio" value=""  name="selectsoup" id="mixchk" onclick="mixchg()">
                        <label class="form-check-label" for="mixchk">
                          Mix
                        </label>
                    </div>
                    <div id="soupchk" class="mt-3">
                    <div class="form-check offset-md-10">
                        <input class="form-check-input" type="checkbox" value="" id="mchk">
                        <label class="form-check-label" for="mchk">
                          Tonyan
                        </label>
                    </div>
                    <div class="form-check offset-md-10">
                        <input class="form-check-input" type="checkbox" value="" id="mchk1">
                        <label class="form-check-label" for="mchk1">
                          Marlar
                        </label>
                    </div>
                    <div class="form-check offset-md-10">
                        <input class="form-check-input" type="checkbox" value="" id="mchk2">
                        <label class="form-check-label" for="mchk2">
                          Soup
                        </label>
                    </div>
                    <div class="form-check offset-md-10">
                        <input class="form-check-input" type="checkbox" value="" id="mchk3">
                        <label class="form-check-label" for="mchk3">
                          Others
                        </label>
                    </div>
                    </div>
                    <div id="soupradio"  class="mt-3">
                        <div class="form-check offset-md-10">
                            <input class="form-check-input" type="radio" value="" name="sradio" id="simrchk">
                            <label class="form-check-label" for="simrchk">
                              Tonyan
                            </label>
                        </div>
                        <div class="form-check offset-md-10">
                            <input class="form-check-input" type="radio" value="" name="sradio" id="simrchk1">
                            <label class="form-check-label" for="simrchk1">
                              Marlar
                            </label>
                        </div>
                        <div class="form-check offset-md-10">
                            <input class="form-check-input" type="radio" value="" name="sradio" id="simrchk2">
                            <label class="form-check-label" for="simrchk2">
                              Soup
                            </label>
                        </div>
                        <div class="form-check offset-md-10">
                            <input class="form-check-input" type="radio" value="" name="sradio" id="simrchk3">
                            <label class="form-check-label" for="simrchk3">
                              Others
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class=" offset-md-2 col-md-4">
                        <label class="font-weight-bold" for="remark">
                          Remark
                        </label>
                    </div>
                    <div class="form-group col-md-4">
                        <input class="form-control" type="text" placeholder="Enter Remark" id="soupremark">
                    </div>
                </div>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" onclick="showscancode()">Save</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>

        </div>
      </div>

      <div class="modal" tabindex="-1" role="dialog" id="scanshow">
        <div class="modal-dialog" role="document" style="max-width: 300px;">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">QR Code</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                {{-- <input type="text" value="" id="scanId"> --}}

              <p>Scan Here.</p>
              <div  id="scanid">
                <div class="text-center printableArea">
                    {!! QrCode::size(150)->generate('Welcome to ThaiPot!') !!}
                  </div>
              </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="print">Print</button>
                <button type="button" class="btn btn-secondary" id="closescan">Close</button>
            </div>
          </div>
        </div>
      </div>
      <input type="hidden" id="tableid">
</div>
@endsection

@section('js')
<script src="{{asset('js/jquery.PrintArea.js')}}" type="text/JavaScript"></script>

<script>

    $(document).ready(function() {

        $('#soupradio').hide();
        $('#soupchk').hide();

        $("#print").click(function() {
            $('#scanshow').modal('hide');
            // window.print();
            var mode = 'iframe'; //popup
            var close = mode == "popup";
            var options = {
                mode: mode,
                popClose: close
            };
            $("div.printableArea").printArea(options);

            setTimeout(function(){
                $('#soupkichen').submit();
            }, 2500);
        });

        $('#closescan').click(function() {
            $('#soupkichen').submit();
        })
    });

    function simplechg(){
        $('#soupradio').show();
        $('#soupchk').hide();
    }

    function mixchg(){
        $('#soupradio').hide();
        $('#soupchk').show();
    }

   function showorder(id,status){
    if(status == 1){
        $('#ordermodal').modal('show');
        $('#tableid').val(id);
    }else{
        swal({
                title: "Warning!",
                text : "This table is not free!",
                icon : "warning",
            });
    }

   }

   function showscancode(){
        $('#ordermodal').modal('hide');
    if(document.getElementById('simchk').checked == true){
        if(document.getElementById('simrchk').checked == true){
            var soup = 'Tonyan';
        }
        if(document.getElementById('simrchk1').checked == true){
            var soup = 'Marlar';
        }
        if(document.getElementById('simrchk2').checked == true){
            var soup = 'Soup';
        }
        if(document.getElementById('simrchk3').checked == true){
            var soup = 'Others';
        }
    }
    if(document.getElementById('mixchk').checked == true){
        var soup ='';
        if(document.getElementById('mchk').checked == true){
            soup += 'Tonyan,';
        }
        if(document.getElementById('mchk1').checked == true){
            soup += 'Marlar,';
        }
        if(document.getElementById('mchk2').checked == true){
            soup += 'Soup,';
        }
        if(document.getElementById('mchk3').checked == true){
            soup += 'Others,';
        }
    }

    var adult_qty = $('#adult').val();
    var child_qty = $('#child').val();
    var kid_qty = $('#kid').val();
    var extrapot_qty = $('#pot').val();
    var birth_qty = $('#bd').val();
    var start_time = $('#time').val();
    var table_id = $('#tableid').val();
    var remark = $('#soupremark').val();
    $('#scanId').val(table_id);
    $.ajax({

    type:'POST',

    url:'/StoreThaiOrder',

    data:{
    "_token":"{{csrf_token()}}",
    "adult_qty":adult_qty,
    "child_qty" : child_qty ,
    "kid_qty" : kid_qty,
    "extrapot_qty" : extrapot_qty,
    "birth_qty" : birth_qty,
    "start_time" : start_time,
    "table_id" : table_id,
    "soup_name" : soup,
    'remark' : remark,
    },

    success:function(data){
        console.log('success');
        $('#kit_id').val(data.id);

    }
})


    $('#scanshow').modal('show');
   }

   function showdis(){
    if($('#adultchk').is(':checked')){
        $('#adult').removeAttr('disabled');
    }else{
        $('#adult').attr('disabled','disabled');
        $('#adult').val(0);
    }
    if($('#childchk').is(':checked')){
        $('#child').removeAttr('disabled');
    }else{
        $('#child').attr('disabled','disabled');
        $('#child').val(0);
    }
    if($('#kidchk').is(':checked')){
        $('#kid').removeAttr('disabled');
    }else{
        $('#kid').attr('disabled','disabled');
        $('#kid').val(0);
    }
    if($('#potchk').is(':checked')){
        $('#pot').removeAttr('disabled');
    }else{
        $('#pot').attr('disabled','disabled');
        $('#pot').val(0);
    }
    if($('#bdchk').is(':checked')){
        $('#bd').removeAttr('disabled');
    }else{
        $('#bd').attr('disabled','disabled');
        $('#bd').val(0);
    }
   }

</script>
@endsection
