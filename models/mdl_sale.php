<?php
require '../config/init.php';

if (!isset($_SESSION['user_id'])) {
   header("Location: ../auth/login.php");
   exit;
}

header('Content-Type: application/json');

$response = [
   'success' => false,
   'message' => ''
];

$data = json_decode(file_get_contents("php://input"), true);

$items = $data['items'] ?? [];
$discount = floatval($data['discount'] ?? 0);
$payment_method = $data['payment_method'] ?? 'cash';

if (empty($items)) {
   echo json_encode([
      'success' => false,
      'message' => "Tidak ada item"
   ]);
   exit;
}

try {

   $db->beginTransaction();

   /* ===============================
   HITUNG TOTAL
================================ */

   $total = 0;

   foreach ($items as $it) {

      $total += $it['price'] * $it['qty'];
   }

   $grand_total = $total - $discount;


   /* ===============================
   DATA STORE & CASHIER
================================ */
   if (!isset($_SESSION['store_id'])) {
      throw new Exception("Store tidak ditemukan pada session");
   }
   $store_id = $_SESSION['store_id'];
   $cashier_id = $_SESSION['user_id'] ?? 1;
   $member_id = null;


   /* ===============================
   GENERATE INVOICE
================================ */

   $invoice = "INV" . date("YmdHis");


   /* ===============================
   INSERT SALES
================================ */

   $db->execute("
INSERT INTO sales
(invoice_no,store_id,cashier_id,member_id,total,discount,grand_total,payment_method)
VALUES
(:invoice,:store,:cashier,:member,:total,:discount,:grand,:method)
", [
      ':invoice' => $invoice,
      ':store' => $store_id,
      ':cashier' => $cashier_id,
      ':member' => $member_id,
      ':total' => $total,
      ':discount' => $discount,
      ':grand' => $grand_total,
      ':method' => $payment_method
   ]);

   $sale_id = $db->lastInsertId();


   /* ===============================
   INSERT SALE ITEMS
================================ */

   foreach ($items as $it) {

      $product_id = $it['product_id'];
      $qty = $it['qty'];
      $price = $it['price'];
      $subtotal = $qty * $price;

      $db->execute("
INSERT INTO sale_items
(sale_id,product_id,qty,price,subtotal)
VALUES
(:sale,:product,:qty,:price,:subtotal)
", [
         ':sale' => $sale_id,
         ':product' => $product_id,
         ':qty' => $qty,
         ':price' => $price,
         ':subtotal' => $subtotal
      ]);


      /* ===============================
   UPDATE STOCK STORE
================================ */
      $cek = $db->single("
SELECT qty
FROM stocks
WHERE product_id = :product
AND location_type='store'
AND location_id=:store
", [
         ':product' => $product_id,
         ':store' => $store_id
      ]);

      if (!$cek || $cek['qty'] < $qty) {
         throw new Exception("Stok tidak cukup");
      } else {
         $db->execute("
UPDATE stocks
SET qty = qty - :qty
WHERE product_id = :product
AND location_type = 'store'
AND location_id = :store
AND qty >= :qty
", [
            ':qty' => $qty,
            ':product' => $product_id,
            ':store' => $store_id
         ]);
      }
      /* ===============================
   INSERT STOCK MOVEMENT
================================ */

      $db->execute("
INSERT INTO stock_movements
(product_id,from_type,from_id,qty,movement_type,reference_id)
VALUES
(:product,'store',:store,:qty,'sale',:ref)
", [
         ':product' => $product_id,
         ':store' => $store_id,
         ':qty' => $qty,
         ':ref' => $sale_id
      ]);
   }

   $db->commit();

   $response['success'] = true;
   $response['message'] = "Transaksi berhasil";
   $response['invoice'] = $invoice;
   $response['sale_id'] = $sale_id;
} catch (Exception $e) {

   $db->rollBack();

   $response['message'] = $e->getMessage();
}

echo json_encode($response);
