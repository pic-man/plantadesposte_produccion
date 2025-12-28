<header id="header" class="header fixed-top d-flex align-items-center">
    <div class="d-flex align-items-center justify-content-between">
        <a href="inicio.php" class="logo d-flex align-items-center">
            <img src="assets/img/logo-mercamio.png" alt="">
        </a>
        <i class="bi bi-list toggle-sidebar-btn" style="color:#FFFFFF"></i>
    </div>

    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">
            <li class="nav-item dropdown pe-3">

                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                    <span class=" dropdown-toggle ps-2">Usuario</span>
                </a>

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li class="dropdown-header">
                        <h6><?php echo $_SESSION['nombres'];?></h6>
                        <span>
                            <?php 
                                if ($_SESSION['tipo'] == 0) {
                                    echo "ADMINISTRADOR";
                                } elseif ($_SESSION['tipo'] == 1) {
                                    echo "RESPONSABLE";
                                }
                            ?>
                        </span>
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <!-- <li>
                        <a class="dropdown-item d-flex align-items-center" href="#">
                            <i class="bi bi-person"></i>
                            <span>Perfil</span>
                        </a>
                    </li> -->

                    <?php if ($_SESSION["tipo"] == 0): ?> 
                        <li>
                            <a class="dropdown-item d-flex align-items-center">
                                <i class="bi bi-gear"></i>
                                <span>Registros de Cambios</span>
                                <div class="form-check form-switch float-end w-100 m-0 p-0 d-flex align-items-center justify-content-end" style="height:100%;">
                                    <input class="form-check-input float-end align-self-center m-0 p-0" onclick="cambiarRegistrosCambios()" <?= $_SESSION["registrosCambios"] == 1 ? "checked" : "" ?> type="checkbox" id="registrosCambios" role="switch">
                                </div>
                            </a>
                        </li>
                    <?php endif ?>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="cerrarsesion.php">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Cerrar Sesión</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</header>