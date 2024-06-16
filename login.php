<?php include('../tk/template/siswa/header.php') ?>
  <div class="login">
    <div class="container">
      <?php
        session_start();
      if(isset($_SESSION['pesan_regisB'])) {?>
        <div class="alert alert-success">
          <?= $_SESSION['pesan_regisB'] ?>
        </div>
      <?php } 
      if(isset($_SESSION['login_error'])) {?>
          <div class="alert alert-danger">
          <?= $_SESSION['login_error'] ?>
        </div>
      <?php } 
      if(isset($_SESSION['logout'])) {?>
          <div class="alert alert-danger">
          <?= $_SESSION['logout'] ?>
        </div>
      <?php } 
        session_destroy();
      ?>
     
     <form class="user" action="core/login_control.php"method="POST">
        <h1>Login</h1>
        <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan Username...">
        <div >
        </div>
        <input type="password" class="form-control" name="password" id="exampleInputPassword1" placeholder="password">
        <div class="mb-1">
        </div>
        <button type="submit"  value="login" name="btn_login" class="btn btn-primary btn-submit">Login</button>
        <div class="mb-1">
        </div>
        <div class="mb-1">
          <a class="btn btn-primary btn-submit" href="pendaftaran.php">Pendaftaran Mahasiswa</a>
        </div>
      </form> 
    </div>
  </div>
  <?php include('../tk/template/siswa/footer.php') ?>