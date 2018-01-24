<?php

namespace ElCaminas;
use \PDO;
use \ElCaminas\Producto;
class Carrito
{
    protected $connect;
    /** Sin parámetros. Sólo crea la variable de sesión
    */

    public function __construct()
    {
        global $connect;
        $this->connect = $connect;
        if (!isset($_SESSION['carrito'])){
            $_SESSION['carrito'] = array();
        }

    }
    public function getRedirect(){
      return urldecode($redirect= isset($_GET["redirect"]) ? $_GET["redirect"] : '/tiendaOOP/index.php');
    }

    public function addItem($id, $cantidad){
        $_SESSION['carrito'][$id] = $cantidad;
    }
    public function deleteItem($id){
      unset($_SESSION['carrito'][$id]);
    }
    public function empty(){
      unset($_SESSION['carrito']);
      self::__construct();
    }
    public function itemExists($id){
     return isset($_SESSION['carrito'][$id]);
 }
 public function getItemCount($id){
   if (!$this->itemExists($id))
     return 0;
   else
     return $_SESSION['carrito'][$id];
 }
 public function howMany(){
   return array_sum($_SESSION['carrito']);
 }
    public function createCarro(){
      $this->getRedirect();
      $carroicono= "<li><a href='./carro.php?redirect=jhug' style='color: red;'>El Carro</a></li>";

      return $carritoicono;
    }

    public function getTotal(){
      $totalX = 0;
      foreach($_SESSION['carrito'] as $key => $cantidad){
        $producto = new Producto($key);
        $totalX = $totalX + ($producto->getPrecioReal()*$cantidad);
      }

      return $totalX;
    }
    public function toHtml(){
      //NO USAR, de momento

      $this->getRedirect();

      $str = <<<heredoc
      <table class="table">
        <thead> <tr> <th>#</th> <th>Producto</th> <th>Cantidad</th> <th>Precio</th> <th>Total</th></tr> </thead>
        <tbody>
heredoc;
      if ($this->howMany() > 0){
        $i = 0;
        $totalX = 0;
        foreach($_SESSION['carrito'] as $key => $cantidad){
          $producto = new Producto($key);
          $i++;
          $subtotal = $producto->getPrecioReal() * $cantidad;
          $subtotalTexto = number_format($subtotal , 2, ',', ' ') ;
          $str .=  "<tr><th scope='row'>$i</th><td><a href='" .  $producto->getUrl() . "'>" . $producto->getNombre() . "</a>";
          $str .= "<a class='open-modal' title='Haga clic para ver el detalle del producto'        href='" .  $producto->getUrl() . "'>";
          $str .=     "<span style='color:#000' class='fa fa-external-link'></span>";
          $str .= "</a></td><td>$cantidad</td><td>" .  $producto->getPrecioReal() ." €</td><td>$subtotalTexto €</td><td><a href='?action=delete&cantidad=$cantidad&id=".$producto->getId()."'  class='fa fa-times' aria-hidden='true' onclick='return ConfirmarX()'/></td></tr>";
        }
      }
      $str .= <<<heredoc

        </tbody>
      </table>

heredoc;
$str .= "<h4 style='text-align: right; font-weight: bold;'>TOTAL CARRITO: ".$this->getTotal()." €</h4>
<a href='". $this->getRedirect() ."'><button type='button' class='btn btn-danger id='total'>SEGUIR COMPRANDO</button></a>
<a href='?action=empty' ><button onclick='return ConfirmarX()' type='button' class='btn btn-danger id='borrar'>CHECK OUT</button></a>";
$str .="
<script src='https://www.paypalobjects.com/api/checkout.js'></script>

<div id='paypal-button-container'></div>

<script>

    // Render the PayPal button

    paypal.Button.render({

        // Set your environment

        env: 'sandbox', // sandbox | production

        // Specify the style of the button

        style: {
            label: 'credit',
            size:  'medium', // small | medium | large | responsive
            shape: 'rect',  // pill | rect
        },

        // PayPal Client IDs - replace with your own
        // Create a PayPal app: https://developer.paypal.com/developer/applications/create

        client: {
            sandbox:    'AURtFahgo3cuV-8J35gOhzh0AhTk36fnkHRkuGs-ZBiDoRdzd4NGvRDFFvzkCqmoU3puoZ3FOyS2zkDX',
            production: '<insert production client id>'
        },

        // Wait for the PayPal button to be clicked

        payment: function(data, actions) {

            // Set up a payment and make credit the landing page

            return actions.payment.create({
                payment: {
                    transactions: [
                        {
                            amount: { total: '".$this->getTotal()."', currency: 'EUR' }
                        }
                    ]
                }
            });
        },

        // Wait for the payment to be authorized by the customer

        onAuthorize: function(data, actions) {
            return actions.payment.execute().then(function() {
                window.alert('Pago Completado!');
                document.location.href = 'gracias.php';
            });
        }

    }, '#paypal-button-container');

</script>
    ";
      return $str;
    }


}
?>
