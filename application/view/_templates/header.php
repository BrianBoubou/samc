<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>SAMC - Organisation Platform</title>

  <link rel="icon" href="<?php echo URL ?>img/icon.png">

  <!-- Styles -->
  <link href="<?php echo URL; ?>css/bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo URL; ?>css/chosen.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="<?php echo URL; ?>css/tooltipster.bundle.min.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo URL; ?>css/tooltip-themes/shadow.css" />
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.18/sl-1.2.6/datatables.min.css"/>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
  <link href="<?php echo URL; ?>css/datepicker.min.css" rel="stylesheet" type="text/css">


    <!-- JS -->

    <!-- CSS -->
    <style>
        thead {
            padding-right: 20px;
            background: #373a3c !important;
            color: white;
            opacity: 0.8;
        }
        .page-link {
            background: #373a3c !important;
            opacity: 0.8 !important;
            color: white !important;
            cursor: pointer !important;
        }
    </style>
</head>
<body style="background: whitesmoke !important;">
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary" style="background: #252728 !important;">
      <div class="container">
          <a class="navbar-brand" href="<?php echo URL; ?>">
              <img src="https://campus.samsung.fr/images/logo_samsung_campus.png" alt="logo_samsung">
          </a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                  aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
              <ul class="navbar-nav">
                  <?php if(isset($auth) && $auth['check'] && $auth['isAdmin']) { ?>
                  <li class="nav-item dropdown">
                      <a href="#" class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown"
                          aria-haspopup="true" aria-expanded="false">
                          <i class="fas fa-users"></i> Etudiants
                      </a>
                      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                          <a href="<?php echo URL; ?>students" class="dropdown-item">
                                  <i class="fas fa-list"></i> Voir tous les etudiants
                          </a>
                          <a href="<?php echo URL; ?>students/add" class="dropdown-item">
                              <i class="fas fa-plus"></i> Ajouter un etudiant
                          </a>
                          <a href="<?php echo URL; ?>students/addBulk" class="dropdown-item">
                              <i class="fas fa-user-plus"></i> Ajouter plusieurs etudiants
                          </a>
                      </div>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" href="<?php echo URL; ?>logs"><i class="fas fa-history"></i> Logs</a>
                  </li>
                  <?php } ?>
                  <?php if (!isset($auth)) { ?>
                      <li class="nav-item"><a href="<?php echo URL; ?>home/login" class="nav-link">Connexion</a></li>
                  <?php } else { ?>
                      <li class="nav-item dropdown">
                          <a href="#" class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown"
                             aria-haspopup="true" aria-expanded="false">
                              <?php echo $auth['name']; ?>
                          </a>
                          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                              <a href="<?php echo URL; ?>home/logout" class="dropdown-item">
                                  Deconnexion
                              </a>

                              <form id="logout-form" action="<?php echo URL; ?>logout" method="POST"
                                    style="display: none;">
                              </form>
                          </div>
                      </li>
                  <?php } ?>
              </ul>
          </div>

      </div>
  </nav>
