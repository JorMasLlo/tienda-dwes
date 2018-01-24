<?php
  session_start();
  $title = "Plantas el Caminàs -> Gracias";

  require './include/ElCaminas/Carrito.php';
  use ElCaminas\Carrito;

  $carrito = new Carrito();

  $carrito->empty();

  include("./include/header2.php");
?>
<div class="container">
  <div class="row">
    <div class="col-md-12 ">
      <div class="row">
        <div class="jumbotron">
          <h1>Gracias</h1>
          <p> Gracias por realizar su compra con nosotros</p>
          <p><a class="btn btn-primary btn-lg" href="/tiendaOOP/" role="button">Continuar</a></p>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
include("./include/footer2.php");
?>
