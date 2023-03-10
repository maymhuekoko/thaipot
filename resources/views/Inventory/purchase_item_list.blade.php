@extends('master')

@section('title','Purchase Item List')

@section('place')

<div class="col-md-5 col-8 align-self-center">
    <h3 class="text-themecolor m-b-0 m-t-0">Branch</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('index')}}">Back to Dashborad</a></li>
        <li class="breadcrumb-item active">Purchase Item List</li>
    </ol>
</div>

@endsection

@section('content')

<div class="row page-titles">
    <div class="col-md-5 col-8 align-self-center">        
        <h2 class="font-weight-bold">Purchase Item List</h2>
    </div>
</div>


<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-body">
                <h4 class="card-title">Purchase Item List</h4>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Amount</th>
                                <th>Price</th>
                                <th>Stock Quantity</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $i=1;?>
                            @foreach($items as $item)
                            <tr>
                                <td>{{$i++}}</td>
                                <td>{{$item->name}}</td>
                                @foreach($categories as $category)
                                    @if($category->id == $item->pi_category_id)
                                        <td>{{$category->name}}</td>
                                    @endif
                                @endforeach
                                <td>{{$item->amount}} {{$item->unit}}</td>
                                <td>{{$item->price}}</td>
                                <td>{{$item->stock_quantity}}</td>
                                <td>
                                    <a href="#" class="btn btn-warning" data-toggle="modal" data-target="#edit_item{{$item->id}}"><i class="far fa-edit"></i>
                                    Edit</a>
                                </td>
                                
                                <div class="modal fade" id="edit_item{{$item->id}}" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                  <div class="modal-header">
                                    <h4 class="modal-title">Edit Category Form</h4>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                  </div>

                                    <div class="modal-body">
                                        <form class="form-material" method="post" action="{{route('purchase_item_update', $item->id)}}">
                                            @csrf
                                            <div class="form-group">    
                                                <label class="font-weight-bold">Name</label>
                                                <input type="text" name="name" class="form-control" value="{{$item->name}}"> 
                                            </div>
                                            <div class="form-group">    
                                                <label class="font-weight-bold">Choose Category</label>
                                                <select name="pi_category_id" class="form-control">
                                                @foreach($categories as $category)
                                                    <option value="{{$category->id}}" {{($category->id == $item->pi_category_id)? 'selected="selected"' : ''}}>{{$category->name}}</option>
                                                @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">Amount</label>
                                                <input type="text" name="amount" class="form-control" value="{{$item->amount}}">
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">Unit</label>
                                                <input type="text" name="unit" class="form-control" value="{{$item->unit}}">
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">Price</label>
                                                <input type="text" name="price" class="form-control" value="{{$item->price}}">
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">Stock Quantity</label>
                                                <input type="text" name="stock_quantity" class="form-control" value="{{$item->stock_quantity}}">
                                            </div>
                                            <input type="submit" name="btnsubmit" class="btnsubmit float-right btn btn-primary" value="Update">
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

    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-body">
                <h3 class="card-title">Purchase Item Create Form</h3>
                <form class="form-material" method="post" action="{{route('purchase_item_store')}}">
                    @csrf
                    
                    <div class="form-group">    
                        <label class="font-weight-bold">Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Enter Name" required>

                        @error('name')
                            <span class="invalid-feedback alert alert-danger" role="alert"  height="100">
                                {{ $message }}
                            </span>
                        @enderror 
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Choose Category</label>
                        <select name="category" class="form-control @error('category') is-invalid @enderror">
                                <option disabled>---Select Category---</option>
                            @foreach($categories as $category)
                                <option value="{{$category->id}}">{{$category->name}}</option>
                            @endforeach
                        </select>

                        @error('category')
                            <span class="invalid-feedback alert alert-danger" role="alert"  height="100">
                                {{ $message }}
                            </span>
                        @enderror 
                    </div>

                    <div class="form-group">    
                        <label class="font-weight-bold">Amount</label>
                        <input type="text" name="amount" class="form-control @error('amount') is-invalid @enderror" placeholder="Enter Amount" required>

                        @error('amount')
                            <span class="invalid-feedback alert alert-danger" role="alert"  height="100">
                                {{ $message }}
                            </span>
                        @enderror 
                    </div>

                    <div class="form-group">    
                        <label class="font-weight-bold">Unit</label>
                        <input type="text" name="unit" class="form-control @error('unit') is-invalid @enderror" placeholder="Enter Unit" required>

                        @error('unit')
                            <span class="invalid-feedback alert alert-danger" role="alert"  height="100">
                                {{ $message }}
                            </span>
                        @enderror 
                    </div>

                    <div class="form-group">    
                        <label class="font-weight-bold">Price</label>
                        <input type="text" name="price" class="form-control @error('price') is-invalid @enderror" placeholder="Enter Price" required>

                        @error('price')
                            <span class="invalid-feedback alert alert-danger" role="alert"  height="100">
                                {{ $message }}
                            </span>
                        @enderror 
                    </div>

                    <div class="form-group">    
                        <label class="font-weight-bold">Stock Quantity</label>
                        <input type="text" name="stock_quantity" class="form-control @error('stock_quantity') is-invalid @enderror" placeholder="Enter Stock Quantity" required>

                        @error('stock_quantity')
                            <span class="invalid-feedback alert alert-danger" role="alert"  height="100">
                                {{ $message }}
                            </span>
                        @enderror 
                    </div>

                    </div>
                    <input type="submit" name="btnsubmit" class="btnsubmit float-right btn btn-primary" value="Save Category">
                </form>
            </div>
        </div>
    </div>
</div>

@endsection