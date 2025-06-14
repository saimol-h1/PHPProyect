<html>

<head>
    <title>UTA</title>
    <link rel="stylesheet" href="<?php echo getUrl('css', 'style.css'); ?>">
    <link rel="stylesheet" type="text/css" href="https://www.jeasyui.com/easyui/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="https://www.jeasyui.com/easyui/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="https://www.jeasyui.com/easyui/themes/color.css">
    <link rel="stylesheet" type="text/css" href="https://www.jeasyui.com/easyui/demo/demo.css">
    <script type="text/javascript" src="https://www.jeasyui.com/easyui/jquery.min.js"></script>
    <script type="text/javascript" src="https://www.jeasyui.com/easyui/jquery.easyui.min.js"></script>
</head>

<body>
    <header>
        <img src="<?php echo getUrl('img', 'utabanner.svg'); ?>" width="90%" alt="banner">
    </header>
    <nav>
        <ul>
            <li><a href="<?php echo getUrl('root', 'index.php?action=inicio'); ?>">Inicio</a></li>
            <li><a href="<?php echo getUrl('root', 'index.php?action=nosotros'); ?>">Nosotros</a></li>
            <li><a href="<?php echo getUrl('root', 'index.php?action=servicios'); ?>">Servicios</a></li>
            <li><a href="<?php echo getUrl('root', 'index.php?action=contactanos'); ?>">Contactanos</a></li>
        </ul>
    </nav>
    <section>
        <?php
        // Include the MVC controller using configured paths
        requireFile('controller', 'controller.php');

        // Create an instance of the MvcController
        $mvc = new MvcController();
        $mvc->EnlacesPaginasController();
        ?>
    </section>
    <footer>
        Derechos Reservados @Cuarto SW
    </footer>
</body>

</html>