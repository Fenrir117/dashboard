
  <!--Navbar -->
  <nav class="mb-1 navbar navbar-expand-lg navbar-dark lighten-1 custom-nav">
    <a class="navbar-brand" href="#">Navbar</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent-555" aria-controls="navbarSupportedContent-555" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent-555">

      <ul class="navbar-nav ml-auto nav-flex-icons">
        <li class="nav-item pt-1">
          <a class="nav-link waves-effect waves-light" href="/list-notif.php"><?php ?>
            <i class="fas fa-envelope"></i>
          </a>
        </li>
        <li class="nav-item username-connect"><?php if (isset($_SESSION['user'])) : ?>
            <strong><?php echo $_SESSION['user']['username']; ?></strong>

            <small>

              <br>

            </small>

          <?php endif ?></li>
        <li class="nav-item avatar dropdown">
          <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink-55" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <img src="assets/img/user_profile.png" class="rounded-circle z-depth-0">

          </a>
          <div class="dropdown-menu dropdown-menu-lg-right dropdown-secondary" aria-labelledby="navbarDropdownMenuLink-55">
            <a class="dropdown-item" href="#">Mon profil</a>
            <a href="index.php?logout='1'" style="color: red;">logout</a>
          </div>
        </li>
      </ul>
    </div>
  </nav>
  <!--/.Navbar -->