<?php require_once __DIR__ . '/includes.php'; ?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title><?= e($page_title ?? 'R5 Essence') ?></title><link rel="stylesheet" href="style.css"></head><body>
<header class="navbar"><a href="index.php" class="brand"><img src="assets/logo.jpg" alt="R5 Essence logo"><span>R5 ESSENCE</span></a>
<button class="menu-toggle" aria-label="Open navigation">☰</button><nav class="nav-links">
<a href="index.php">Home</a><a href="about.php">About Us</a><a href="offer.php">Offer</a><a href="products.php">Products</a><a href="contact.php">Contacts</a><button class="cart-nav" id="cartNavBtn">Cart <span id="cartCount"><?= cart_count() ?></span></button>
</nav></header>
<script>document.querySelector('.menu-toggle')?.addEventListener('click',()=>document.querySelector('.nav-links').classList.toggle('open'));document.querySelectorAll('.nav-links a').forEach(a=>a.addEventListener('click',()=>document.querySelector('.nav-links').classList.remove('open')));document.getElementById('cartNavBtn')?.addEventListener('click',()=>location.href='cart.php');</script>
