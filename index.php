<?php


require 'user_login.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Smart Wallet | Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-900 to-slate-700">

<div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">

  <!-- HEADER -->
  <div class="text-center mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Smart Wallet</h1>
    <p class="text-sm text-gray-500">Manage your money smarter</p>
  </div>

  <!-- ALERTS -->
  <?php if (isset($_GET['error'])): ?>
    <div class="mb-4 text-sm text-red-600 bg-red-100 p-3 rounded">
      <?php
        if ($_GET['error'] === 'login') echo 'Invalid email or password';
        if ($_GET['error'] === 'otp_invalid') echo 'Invalid OTP code';
        if ($_GET['error'] === 'otp_missing') echo 'OTP required';
      ?>
    </div>
  <?php endif; ?>

  <?php if (isset($_GET['success'])): ?>
    <div class="mb-4 text-sm text-green-600 bg-green-100 p-3 rounded">
      <?php
        if ($_GET['success'] === 'signup') echo 'Account created successfully. Please login.';
      ?>
    </div>
  <?php endif; ?>

  <!-- TABS -->
  <div class="flex mb-6 border-b">
    <button id="loginTab" class="w-1/2 py-2 font-semibold text-slate-800 border-b-2 border-slate-800">
      Login
    </button>
    <button id="registerTab" class="w-1/2 py-2 text-gray-400">
      Register
    </button>
  </div>

  <!-- LOGIN FORM -->
  <form id="loginForm" action="user_login.php" method="POST" class="space-y-4">

    <div>
      <label class="text-sm font-medium">Email</label>
      <input type="email" name="emailL" required
        class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring focus:ring-slate-300">
    </div>

    <div>
      <label class="text-sm font-medium">Password</label>
      <input type="password" name="passwordL" required
        class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring focus:ring-slate-300">
    </div>

    <button
      class="w-full bg-slate-900 text-white py-3 rounded-lg hover:bg-slate-800 transition">
      Login
    </button>
  </form>

  <!-- REGISTER FORM -->
  <form id="registerForm" action="user_login.php" method="POST" class="space-y-4 hidden">

    <div>
      <label class="text-sm font-medium">Username</label>
      <input type="text" name="username" required
        class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring focus:ring-slate-300">
    </div>

    <div>
      <label class="text-sm font-medium">Email</label>
      <input type="email" name="email" required
        class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring focus:ring-slate-300">
    </div>

    <div>
      <label class="text-sm font-medium">Password</label>
      <input type="password" name="password" required
        class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring focus:ring-slate-300">
    </div>

    <button
      class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition">
      Create Account
    </button>
  </form>

</div>

<!-- JS TOGGLE -->
<script>
const loginTab = document.getElementById('loginTab');
const registerTab = document.getElementById('registerTab');
const loginForm = document.getElementById('loginForm');
const registerForm = document.getElementById('registerForm');

loginTab.onclick = () => {
  loginForm.classList.remove('hidden');
  registerForm.classList.add('hidden');
  loginTab.classList.add('border-slate-800','text-slate-800');
  registerTab.classList.remove('border-slate-800','text-slate-800');
};

registerTab.onclick = () => {
  registerForm.classList.remove('hidden');
  loginForm.classList.add('hidden');
  registerTab.classList.add('border-slate-800','text-slate-800');
  loginTab.classList.remove('border-slate-800','text-slate-800');
};
</script>

</body>
</html>
