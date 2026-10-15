<?php
require_once 'includes.php';
if($_SERVER['REQUEST_METHOD']!=='POST'){header('Location: cart.php');exit;}
$cart=$_SESSION['cart']??[]; if(!$cart){flash('error','Your cart is empty.');header('Location: cart.php');exit;}
$name=trim($_POST['name']??'');$phone=trim($_POST['phone']??'');$address=trim($_POST['address']??'');$notes=trim($_POST['notes']??'');
if($name===''||$phone===''||$address===''){flash('error','Please complete all required checkout fields.');header('Location: cart.php');exit;}
try{
 $pdo->beginTransaction(); $items=[];$total=0;
 $stockStmt=$pdo->prepare("SELECT * FROM products WHERE product_id=? AND status='active' FOR UPDATE");
 foreach($cart as $id=>$qty){$qty=(int)$qty;if($qty<1)continue;$stockStmt->execute([(int)$id]);$p=$stockStmt->fetch();if(!$p||$p['stock']<$qty)throw new Exception(($p['name']??'A product').' does not have enough stock.');$sub=$p['price']*$qty;$total+=$sub;$items[]=['p'=>$p,'qty'=>$qty,'sub'=>$sub];}
 if(!$items)throw new Exception('Your cart is empty.');
 $stmt=$pdo->prepare("INSERT INTO customers(full_name,contact_number,address) VALUES(?,?,?)");$stmt->execute([$name,$phone,$address]);$customerId=$pdo->lastInsertId();
 do{$ref=order_reference();$chk=$pdo->prepare("SELECT COUNT(*) FROM orders WHERE order_reference=?");$chk->execute([$ref]);}while($chk->fetchColumn()>0);
 $stmt=$pdo->prepare("INSERT INTO orders(customer_id,order_reference,total_amount,order_notes,order_status) VALUES(?,?,?,?, 'Pending')");$stmt->execute([$customerId,$ref,$total,$notes]);$orderId=$pdo->lastInsertId();
 $oi=$pdo->prepare("INSERT INTO order_items(order_id,product_id,quantity,price,subtotal) VALUES(?,?,?,?,?)");$up=$pdo->prepare("UPDATE products SET stock=stock-? WHERE product_id=?");
 foreach($items as $i){$oi->execute([$orderId,$i['p']['product_id'],$i['qty'],$i['p']['price'],$i['sub']]);$up->execute([$i['qty'],$i['p']['product_id']]);}
 $pdo->commit();$_SESSION['cart']=[];$page_title='R5 Essence | Order Received';require 'header.php';
 echo '<main><section class="section products-section"><div class="success-box" style="margin:40px auto"><div class="success-icon">✓</div><p class="section-label">ORDER RECEIVED</p><h2>Thank You, <span>'.e($name).'</span></h2><p>Your order has been successfully recorded.</p><div class="order-reference">'.e($ref).'</div><p>Total: <strong style="color:#f5d27b">'.money($total).'</strong></p><br><a class="gold-btn" href="products.php">Continue Shopping</a></div></section></main>';require 'footer.php';
}catch(Throwable $e){if($pdo->inTransaction())$pdo->rollBack();flash('error',$e->getMessage());header('Location: cart.php');exit;}
