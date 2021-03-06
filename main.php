<?php
ob_start();
session_start();
include("include/config.php");
include("include/defs.php");

if(!isset($_SESSION['USER_ID']))
   {
        header("Location: login.php");
        exit;
   }

   /* confi cabezera de pagina */
   $link = "";
   $nombre_titulo = "SHL - Principal";
   /////////////////////////////////

   $MSG="";

if (isset($_POST['form_actions'])) {

    $arrVal = array("Stat" => $_POST['actions']);

    UpdateRec("services", "Id = ".$_POST['id_service'], $arrVal);

    $MSG = '<div class="alert alert-success bg-success"><a href="#" class="close" style="color:white;" data-dismiss="alert">&times;</a><strong style="color:white;">Servicio Modificado...!</strong></div>';

}

if (isset($_POST['messaje'])) {

    $registro = array("Id_service"=>$_POST['id_service'],
                      "Id_user"=>$_POST['id_user'],
                      "Messaje"=>$_POST['messaje_user'],
                      "Date_register"=>date("Y-m-d H:i:s"),
                      "Stat"=>2,
                      "Id_register"=>$_SESSION['USER_ID']);

    $id_rec = InsertRec("messajes", $registro);

    $MSG = '<div class="alert alert-success bg-success"><a href="#" class="close" style="color:white;" data-dismiss="alert">&times;</a><strong style="color:white;">Mensaje Enviado...!</strong></div>';

}

   ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>Gruas SHL</title>
  <!-- Bootstrap core CSS-->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom fonts for this template-->
  <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <!-- Page level plugin CSS-->
  <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
  <!-- Custom styles for this template-->
  <link href="css/sb-admin.css" rel="stylesheet">
</head>

<body class="fixed-nav sticky-footer bg-warning" id="page-top">
  <!-- Navigation-->
  <?php include("menu.php"); ?>
  <div class="content-wrapper">
    <div class="container-fluid">
      <div class="card mb-3">
        <?php echo $MSG; ?>
        <div class="card-header">
          <i class="fa fa-table"></i> Servicios Activos / Pendientes</div>
        <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>Id</th>
              <th>Servicio</th>
              <th>Cliente</th>
              <th>Operador</th>
              <th>Fecha de Inicio</th>
              <th>Estado</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th>Id</th>
              <th>Servicio</th>
              <th>Cliente</th>
              <th>Operador</th>
              <th>Fecha de Inicio</th>
              <th>Estado</th>
              <th>Acciones</th>
            </tr>
          </tfoot>
          <tbody>
            <?php $register_services = GetRecords("SELECT
                                                    services.Id,
                                                    services.Date_service,
                                                    services.Name,
                                                    services.Id_customer,
                                                    services.Id_operator,
                                                    (SELECT CONCAT(Name, ' ', Last_name) FROM users WHERE Id = services.Id_customer) AS customer,
                                                    (SELECT CONCAT(Name, ' ', Last_name) FROM users WHERE Id = services.Id_operator) AS operator,
                                                    master_stat.Name_stat
                                                   FROM
                                                   services INNER JOIN master_stat ON services.Stat = master_stat.Id
                                                   WHERE
                                                   services.Stat IN(2,3)"); ?>
            <?php foreach ($register_services as $key => $value) { ?>
            <tr>
              <td><?php echo $value["Id"]; ?></td>
              <td><?php echo $value["Name"]; ?></td>
              <td><?php echo $value["customer"]; ?></td>
              <td><?php echo $value["operator"]; ?></td>
              <td><?php echo $value["Date_service"]; ?></td>
              <td><?php echo $value["Name_stat"]; ?></td>
              <td>
                <a href="services_detail.php?id_service=<?php echo $value["Id"]; ?>" title="Detalle" class="btn btn-sm btn-icon btn-primary"><i class="fa fa-bar-chart"></i></a>
                <a href="" title="Enviar mensaje al cliente" data-toggle="modal" data-target="#messaje_customer<?php echo $value["Id"]; ?>" class="btn btn-sm btn-icon btn-success"><i class="fa fa-telegram"></i></a>
                <a href="" title="Enviar mensaje al operador" data-toggle="modal" data-target="#messaje_oprator<?php echo $value["Id"]; ?>" class="btn btn-sm btn-icon btn-warning"><i class="fa fa-telegram"></i></a>
                <a href="" title="Acciones" data-toggle="modal" data-target="#actions<?php echo $value["Id"]; ?>" class="btn btn-sm btn-icon btn-danger"><i class="fa fa-bars"></i></a>
              </td>
            </tr>
          <?php /* Modal mensaje cliente */ ?>
          <div class="modal fade" id="messaje_customer<?php echo $value["Id"]; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <form class="" action="" method="post">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Enviar mensaje al Cliente</h5>
                  <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                  </button>
                </div>
                <div class="modal-body">
                  <label for="exampleInputEmail1">Mensaje</label>
                  <textarea class="form-control" name="messaje_user" rows="8" cols="80"></textarea>
                </div>
                <div class="modal-footer">
                  <input type="hidden" name="id_service" value="<?php echo $value["Id"]; ?>">
                  <input type="hidden" name="id_user" value="<?php echo $value['Id_customer']; ?>">
                  <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                  <button class="btn btn-primary" name="messaje">Enviar</button>
                </div>
              </div>
              </form>
            </div>
          </div>
          <?php /* Modal mensaje Operador */ ?>
          <div class="modal fade" id="messaje_oprator<?php echo $value["Id"]; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <form class="" action="" method="post">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Enviar mensaje al Operador</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <label for="exampleInputEmail1">Mensaje</label>
                    <textarea class="form-control" name="messaje_user" rows="8" cols="80"></textarea>
                  </div>
                  <div class="modal-footer">
                    <input type="hidden" name="id_service" value="<?php echo $value["Id"]; ?>">
                    <input type="hidden" name="id_user" value="<?php echo $value['Id_operator']; ?>">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary" name="messaje">Enviar</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <?php /* Modal mensaje acciones */ ?>
          <div class="modal fade" id="actions<?php echo $value["Id"]; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <form class="" action="" method="post">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Seleccione una Opcion</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <label for="exampleInputEmail1">Seleccionar</label>
                    <select class="form-control" name="actions">
                      <option value="">Seleccionar</option>
                      <?php $get_recors_action = GetRecords("SELECT * FROM master_stat WHERE Id IN(3,4,5,6)"); ?>
                      <?php foreach ($get_recors_action as $key => $value_action) { ?>
                      <option value="<?php echo $value_action['Id']; ?>"><?php echo $value_action['Name_stat']; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                  <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary" name="form_actions">Guardar</button>
                    <input type="hidden" name="id_service" value="<?php echo $value["Id"]; ?>">
                  </div>
                </form>
              </div>
            </div>
          </div>

          <?php } ?>

          </tbody>
        </table>
        </div>
      </div>
      <div class="card-footer small text-muted">Gruas SHL Todos los Derechos Reservados</div>
    </div>
    </div>
    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Page level plugin JavaScript-->
    <script src="vendor/chart.js/Chart.min.js"></script>
    <script src="vendor/datatables/jquery.dataTables.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin.min.js"></script>
    <!-- Custom scripts for this page-->
    <script src="js/sb-admin-datatables.min.js"></script>
    <script src="js/sb-admin-charts.min.js"></script>
  </div>
</body>

</html>
