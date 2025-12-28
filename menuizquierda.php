<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">

        <?php if (($_SESSION['tipo'] == 0)) { ?>
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-menu-button-wide"></i><span>Administrar</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>

                <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="items.php">
                            <span>Items</span>
                        </a>
                    </li>
                    <li>
                        <a href="responsables.php">
                            <span>Responsables</span>
                        </a>
                    </li>
                    <li>
                        <a class="aMenu nav-link collapsed" style="background-color: transparent;color:white" data-bs-target="#coductores-nav" data-bs-toggle="collapse" href="#">
                            <span>Conductores</span><i class="bi bi-chevron-down ms-auto" style="font-size: medium;margin-right: 15px;"></i>
                        </a>
                    </li>
                    <ul id="coductores-nav" class="nav-content collapse ms-3">
                        <li>
                            <a href="conductores.php">
                                <span>Conductores Despacho</span>
                            </a>
                        </li>
                        <li>
                            <a href="conductoresRes.php">
                                <span>Conductores Res</span>
                            </a>
                        </li>
                        <li>
                            <a href="conductoresPollo.php">
                                <span>Conductores Pollo</span>
                            </a>
                        </li>
                    </ul>
                    <li>
                        <a href="placas.php">
                            <span>Placas Despacho</span>
                        </a>
                    </li>
                    <li>
                        <a href="plantabeneficio.php">
                            <span>Planta de Beneficio</span>
                        </a>
                    </li>
                    <li>
                        <a class="aMenu nav-link collapsed" style="background-color: transparent;color: white" data-bs-target="#proveedores-nav" data-bs-toggle="collapse" href="#">
                            <span>Proveedores</span><i class="bi bi-chevron-down ms-auto" style="font-size: medium;margin-right: 15px;"></i>
                        </a>
                    </li>
                    <ul id="proveedores-nav" class="nav-content collapse ms-3">
                        <li>
                            <a href="proveedorespollo.php">
                                <span>Proveedores Pollo</span>
                            </a>
                        </li>
                        <li>
                            <a href="proveedoresHielo.php">
                                <span>Proveedores Hielo</span>
                            </a>
                        </li>
                        <li>
                            <a href="proveedoresSalmuera.php">
                                <span>Proveedores Salmuera</span>
                            </a>
                        </li>
                        <li>
                            <a href="proveedoresEmpaque.php">
                                <span>Proveedores Empaque</span>
                            </a>
                        </li>
                        <li>
                            <a href="fabricantesEmpaque.php">
                                <span>Fabricantes Empaque</span>
                            </a>
                        </li>
                        <li>
                            <a href="proveedoresCerdo.php">
                                <span>Proveedores Cerdo</span>
                            </a>
                        </li>
                    </ul>
                    <li>
                        <a href="destinos.php">
                            <span>Destinos</span>
                        </a>
                    </li>
                    <li>
                        <a href="ciudades.php">
                            <span>Ciudades</span>
                        </a>
                    </li>
                    <li>
                        <a href="bolsas.php">
                            <span>Bolsas</span>
                        </a>
                    </li>
                    <li>
                        <a href="excepciones.php">
                            <span>Excepciones</span>
                        </a>
                    </li>
                </ul>
            </li>
        <?php } ?>

        <?php if ($_SESSION['tipo'] == 0 || $_SESSION['tipo'] == 3): ?>
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#forms-nav3" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-journal-text"></i><span>Programacion Canales</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>

                <ul id="forms-nav3" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="ProgramacionCanales.php">
                            <span>Programacion Canales</span>
                        </a>
                    </li>

                    <?php if ($_SESSION['tipo'] == 0): ?>
                        <li>
                            <a href="produccionDia.php">
                                <span>Produccion Dia</span>
                            </a>
                        </li>
                        <li>
                            <a href="year.php">
                                <span>Año</span>
                            </a>
                        </li>
                        <li>
                            <a href="semana.php">
                                <span>semana</span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </li>
        <?php endif; ?>

        <?php if (($_SESSION['tipo'] == 0) or ($_SESSION['tipo'] == 1 && $_SESSION["usuario"] != "1106781852")) { ?>
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#forms-nav1" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-journal-text"></i><span>Planta Desposte</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>

                <ul id="forms-nav1" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="recepcion.php">
                            <span>Recepción</span>
                        </a>
                    </li>
                    <li>
                        <a href="pesosRecepcion.php">
                            <span>Pesos Recepción</span>
                        </a>
                    </li>
                    <li>
                        <a href="despacho.php">
                            <span>Despacho</span>
                        </a>
                    </li>
                </ul>
            </li>
        <?php } elseif ($_SESSION['tipo'] == 2) { ?>

        <?php } ?>

        <?php if (($_SESSION['tipo'] == 0) or ($_SESSION['tipo'] == 1)) { ?>
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#forms-nav2" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-journal-text"></i><span>Planta Desprese</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>

                <ul id="forms-nav2" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <?php if ($_SESSION["usuario"] != "1106781852") : ?>
                        <li>
                            <a href="recepcionpollo.php">
                                <span>Recepcion Pollo</span>
                            </a>
                        </li>
                        <li>
                            <a href="desprese.php">
                                <span>Desprese</span>
                            </a>
                        </li>
                        <li>
                            <a href="despresado.php">
                                <span>Despresado</span>
                            </a>
                        </li>
                        
                        <?php if ($_SESSION["usuario"] == 0): ?>
                            <li>
                                <a href="salmuera.php">
                                    <span>Componentes Salmuera</span>
                                </a>
                            </li>
                        <?php endif ?>

                    <?php endif; ?>
                        <li>
                            <a href="despachopollo.php">
                                <span>Despacho Pollo</span>
                            </a>
                        </li>
                    <?php if ($_SESSION["usuario"] != "1106781852") : ?>
                        <li>
                            <a href="bitacoraLotes.php">
                                <span>Bitacora Lote</span>
                            </a>
                        </li>
                        <li>
                            <a href="datosLiquidacion.php">
                                <span>Datos Liquidación</span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </li>
        <?php } ?>

        <?php if (($_SESSION['tipo'] == 0) or ($_SESSION['tipo'] == 1)): ?>
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#PlantaPanificacion-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-journal-text"></i><span>Planta Panificadora</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>

                <ul id="PlantaPanificacion-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="despachoPanificacion.php">
                            <span>Despacho</span>
                        </a>
                    </li>
                </ul>
            </li>
        <?php endif; ?>
    </ul>
</aside>