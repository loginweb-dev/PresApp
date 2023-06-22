@extends('voyager::master')

@section('page_title', 'Reportes')
@section('page_header')
    <div class="container-fluid">
      <h2 class="">
        <i class="icon voyager-helm"></i>
        Reportes de pagos, gastos y movimiento de capítal
      </h2>
    </div>
@stop

@section('content')

<div class="page-content browse container-fluid">

  <div class="row">
    <div class="col-sm-5">
      <div class="panel panel-bordered">
          <div class="panel-body">
            <h3>Movimiento de capital</h3>
            <div class="input-group">
                <input type="date" class="form-control">
                <span class="input-group-btn">
                  <button class="btn btn-dark" type="button">Ir</button>
                </span>
            </div>
            <h4>Totales: </h4>
            <table class="table table-bordered" >
                <tr>
                  <td>Cantidad de prestamos: </td>
                  <td><input type="number" class="form-control" value="0"></td>
                </tr>
                <tr>
                  <td>Monto en Bs: </td>
                  <td><input type="number" class="form-control" value="0"></td>
                </tr>
            </table>
          </div>
      </div>
    </div>

    <div class="col-sm-7">
      <div class="panel panel-bordered">
          <div class="panel-body">
            <canvas id="myChart3"></canvas>
          </div>
      </div>
    </div>
  </div>

  <div class="row">
      <div class="col-sm-5">
        <div class="panel panel-bordered">
            <div class="panel-body">
              <h3>Pagos del mes</h3>
              <div class="input-group">
                  <input type="date" class="form-control">
                  <span class="input-group-btn">
                    <button class="btn btn-dark" type="button">Ir</button>
                  </span>
              </div>
              <h4>Totales: </h4>
              <table class="table table-bordered" >
                  <tr>
                    <td>Cantidad de pagos: </td>
                    <td><input type="number" class="form-control" value="0"></td>
                  </tr>
                  <tr>
                    <td>Monto en Bs: </td>
                    <td><input type="number" class="form-control" value="0"></td>
                  </tr>
              </table>
            </div>
        </div>
      </div>

      <div class="col-sm-7">
        <div class="panel panel-bordered">
            <div class="panel-body">
              <canvas id="myChart"></canvas>
            </div>
        </div>
      </div>

  </div>

  <div class="row">
    <div class="col-sm-5">
      <div class="panel panel-bordered">
          <div class="panel-body">
            <h3>Gastos del mes</h3>
            <div class="input-group">
                <input type="date" class="form-control">
                <span class="input-group-btn">
                  <button class="btn btn-dark" type="button">Ir</button>
                </span>
            </div>
            <h4>Totales: </h4>
            <table class="table table-bordered" >
                <tr>
                  <td>Cantidad de gastos: </td>
                  <td><input type="number" class="form-control" value="0"></td>
                </tr>
                <tr>
                  <td>Monto en Bs: </td>
                  <td><input type="number" class="form-control" value="0"></td>
                </tr>
            </table>
          </div>
      </div>
    </div>

    <div class="col-sm-7">
      <div class="panel panel-bordered">
          <div class="panel-body">
            <canvas id="myChart2"></canvas>
          </div>
      </div>
    </div>
  </div>
  
</div>
@endsection

@section('javascript')

@endsection