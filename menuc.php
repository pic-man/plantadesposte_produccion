<style>
        /* Estilos básicos para el encabezado y menú */
        .encabezado {
            color: white;
            padding: 10px 0;
        }

        .encabezado .logo img {
            max-height: 50px;
        }

        #menu-principal {
            display: flex;
            justify-content: flex-end;
        }

        #menu-principal ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        #menu-principal li {
            margin: 0 10px;
        }

        #menu-principal a {
            color: white;
            text-decoration: none;
        }

        /* Estilos para el menú en dispositivos móviles */
        #menu-toggle {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            #menu-principal {
                display: none;
                flex-direction: column;
                width: 100%;
            }

            #menu-principal.collapse {
                display: flex;
            }

            #menu-principal ul {
                flex-direction: column;
                width: 100%;
            }

            #menu-principal li {
                margin: 10px 0;
                text-align: center;
            }

            #menu-toggle {
                display: inline-block;
            }
        }
    </style>
</head>
<body>
    <section class="bienvenidos2">
        <header class="encabezado navbar-fixed-top" role="banner" id="encabezado">
            <div class="container">
                <a href="index.php" class="logo"><img src="images/logo-mercamio.png" alt="Logo del sitio"></a>
                <button id="menu-toggle" aria-controls="menu-principal" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    &#9776; <!-- Unicode character for a hamburger menu icon -->
                </button>
                <nav id="menu-principal" class="collapse">
                    <div style="position: relative;">
                        <ul>
                            <?php if(($_SESSION['tipo'] == 0)){ ?>   
                                <li><a href="recepcion.php">Recepcion</a></li>
                                <li><a href="items.php">Items</a></li>
                                <li><a href="responsables.php">Responsables</a></li>
                                <li><a href="conductores.php">Conductores</a></li>
                                <li><a href="placas.php">Placas</a></li>
                            <?php }?>     
                            <li><a href="guia.php">Despachos</a></li>
                            <li><a href="cerrarsesion.php">Salir</a></li>                
                        </ul>
                    </div>
                </nav>
            </div>    
        </header>    
    </section>

    <script>
        document.getElementById('menu-toggle').addEventListener('click', function () {
            var menu = document.getElementById('menu-principal');
            if (menu.classList.contains('collapse')) {
                menu.classList.remove('collapse');
                this.setAttribute('aria-expanded', 'true');
            } else {
                menu.classList.add('collapse');
                this.setAttribute('aria-expanded', 'false');
            }
        });
    </script>