@extends('layouts.app')

@section('content')
<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="/public/images/Prince and Princes logo/6.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <title>Term Profile</title>

    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />


    <!-- Bootstrap core CSS     -->
    <link href="/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Animation library for notifications   -->
    <link href="/css/animate.min.css" rel="stylesheet"/>

    <!--  Light Bootstrap Table core CSS    -->
    <link href="/css/light-bootstrap-dashboard.css?v=1.4.0" rel="stylesheet"/>


    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <link href="/css/demo.css" rel="stylesheet" />
    
    <!--     Fonts and icons     -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    <link href="/css/pe-icon-7-stroke.css" rel="stylesheet" />
    
    <style>
        .box{
            border: 0px solid #888888;
            box-shadow: 5px 5px 8px 5px #888888;
        }
    </style>
</head>

<body>
    <div class="wrapper">

        <!-- SIDEBAR -->
        <div class="sidebar" data-color="none" data-image="/images/lol.png">
            <div class="sidebar-wrapper">
                <div class="logo">
                    <a href="{{ route('dashboard') }}" class="simple-text">
                        Prince & Princess
                    </a>
                </div>

                <ul class="nav">
                    <li>
                        <a href="{{ route('dashboard') }}">
                            <i class="pe-7s-note"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('terms') }}">
                            <i class="pe-7s-graph"></i>
                            <p>Terms</p>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('inventory') }}">
                            <i class="pe-7s-drawer"></i>
                            <p>Inventories</p>
                        </a>
                    </li>
                    <li  class="active">
                        <a href="{{ route('stockins') }}">
                            <i class="pe-7s-download"></i>
                            <p>Stock Ins</p>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('stockouts') }}">
                            <i class="pe-7s-upload"></i>
                            <p>Stock Outs</p>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('suppliers') }}">
                            <i class="pe-7s-box1"></i>
                            <p>Suppliers</p>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('usrmgmt') }}">
                            <i class="pe-7s-users"></i>
                            <p>Users</p>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('logs') }}">
                            <i class="pe-7s-note2"></i>
                            <p>Logs</p>
                        </a>
                    </li>
                </ul>
            </div>
        </div>


        <div class="main-panel bgd">

            <!-- NAVBAR -->
            <nav class="navbar navbar-default">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
                           <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="#">Logs</a>
                   </div>
                    <div class="collapse navbar-collapse">
                        <ul class="nav navbar-nav navbar-right">
                            <li>
                                <a href="/html/user.html">
                                        {{$curr_usr->fname}} {{$curr_usr->mname}} {{$curr_usr->lname}}  
                                        <!-- Full Name of currently logged in user -->
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                                    Logout
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                            <li class="separator hidden-lg"></li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- CONTENT -->
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <!-- TABLE OF USERS -->
                        <div class="col-md-12">      
                            <div class="card box">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="header">
                                            <h4 class="title">Stock Ins</h4> 
                                        </div> 
                                    </div>

                                    <form method="GET" action="{{ route('searchUsers') }}">
                                        <div class="col-md-4" style="margin-top:10px">
                                            <input type="text" name="titlesearch" class="form-control search" placeholder="Search . . ." value="{{ old('titlesearch') }}">
                                        </div>
                                    
                                        <div class="col-md-2" style="margin-top:10px">
                                            <button style="height: 40px;"; class="btn btn-success pe-7s-search"></button>
                                        </div>
                                    </form>
                                </div>

                                <div class="content table-responsive table-full-width">
                                    <table id="users-table" class="table table-hover table-striped" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                @if(count($stockins)>0)
                                                <th>#</th>
                                                <th>Stock In Date</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $x=0; ?>
                                            @forelse($stockins as $stockin)
                                                <tr>    
                                                    <td>{{$x+=1}}</td>
                                                    <td>{{$stockin->si_date}}</td>
                                                    <td> 
                                                        <button data-target="#viewSI" data-toggle="modal" data-id='{{$stockin->si_si_id}}' class="viewSI-btn btn btn-primary btn-fill">
                                                            View
                                                        </button>
                                                    </td><!-- 
                                                    <td>
                                                        <button data-target="#removeSI" data-toggle="modal" class="delSI-btn btn btn-danger btn-fill">
                                                            Remove
                                                        </button>
                                                    </td> -->
                                                </tr>
                                            @empty
                                                <h3 style="text-align: center"> No stock ins stored. </h3>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <div style="margin-left: 1%"> 
                                    {{$stockins->links()}} 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" role="dialog" id="viewSI">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <center><h4 class="modal-title">Stock In Details</h4></center>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row form-group">
                            <div class="col-md-4">    
                                <label for="sel1">Handler</label>
                                <p id="handler"> <span></span> </p>      
                            </div>
                            <div class="col-md-4">    
                                <label>From</label>
                                <p id="from"> <span></span> </p> 
                            </div>
                            <div class="col-md-4">    
                                <label>Date Received</label>
                                <p id="date"> <span></span> </p> 
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-2"> <label>#</label></div>    
                            <div class="col-md-4"> <label>Item Name</label></div>
                            <div class="col-md-4"> <label>Supplier</label></div>
                            <div class="col-md-2"> <label>Quantity</label></div>
                        </div>
                        <div class="add-here"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-bg btn-fill btn-primary pull-left" data-target="#printSI" data-toggle="modal" data-dismiss="modal">Print</button>
                <button type="button" class="btn  btn-default pull-right" data-dismiss="modal">Close</button>
            </div>
        </div>
      </div>
    </div>
    <!--PRINTING ITEMS MODAL-->    
    <div class="modal fade" role="dialog" id="printSI" >
        <div class="modal-dialog">
          <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <center>
                        <h4 class="modal-title"> Stock Ins </h4>
                    </center>
                </div>

                <form method="POST" id="form-SI" action="/printSI/">
                    {{csrf_field()}}
                    <div class="modal-body">
                        <div id="view-edit-content" class="row">
                            <div class="col-md-12">                                     
                                <div class="row form-group">                       
                                    <div class=""> 
                                        <div class="col-md-12">   
                                            You are about to generate a pdf of this particular stock-in. Do you want to proceed?
                                        </div>
                                    </div>
                                </div> 
                            </div> 
                        </div>                       
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-bg btn-default" data-dismiss="modal">Cancel
                        </button>

                        <button type="submit" id="gen-si" class="btn btn-bg btn-success btn-fill">
                        Generate PDF
                        </button> 
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

    <!--   Core JS Files   -->
    <script src="/js/jquery.3.2.1.min.js" type="text/javascript"></script>
    <!--<script src="/js/bootstrap.min.js" type="text/javascript"></script>-->

    <!--  Charts Plugin -->
    <script src="/js/chartist.min.js"></script>

    <!--  Notifications Plugin    -->
    <script src="/js/bootstrap-notify.js"></script>
     
    <!-- Light Bootstrap Table Core javascript and methods for Demo purpose -->
    <script src="/js/light-bootstrap-dashboard.js?v=1.4.0"></script>
    
    <script>
        $(document).ready(function(){ 
            $('.viewSI-btn').click(function() {
                var id = $(this).data('id');
                $('.add-here').empty();
                $.ajax({
                        url: "/getSI",
                        type: "GET",
                        data: {'id' : id},
                        success: function(response){
                            console.log(response);
                            $('#handler span').html(response[0].fname +" " +response[0].mname +". " 
                                +response[0].lname);
                            $('#date span').html(response[0].si_date);    

                             

                            for (i = 0; i < response.length; i++) {
                                if (response[i].si_term_id != null)
                                    $('#from span').html("Term");
                                else  $('#from span').html("Supplier"); 

                                $('.add-here').append(
                                    "<div class='row form-group'>" +
                                        "<div class='col-md-2'> <p> <span> " +(i + 1) +" </span> </p>  </div>"+
                                        "<div class='col-md-4'> <p> <span> " +response[i].inventory_name +" </span> </p>  </div>"+
                                        "<div class='col-md-4'> <p> <span> " +response[i].supplier_name +"</span> </p> </div>"+
                                        "<div class='col-md-2'> <p> <span> " +response[i].si_qty +"</span> </p> </div>"+
                                    "</div>"
                                );
                            }
                        },
                        error: function(data){
                            console.log(data);
                        }
                    });
                
            });
        });
    </script>

    <script>
        $(document).on('click', '.viewSI-btn', function(){  
           var id = $(this).data('id');

           $("#form-SI").attr("action", "/printSI/" +id);
        });
    </script>
@endsection

