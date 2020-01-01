<html moznomarginboxes mozdisallowselectionprint>
<head>
    <title>Cetak Nota</title>
    <?php
    echo '<style type="text/css">';
    $minify = new \App\Libraries\Minify();
    echo $minify->css('public/css/cetak.css');
    echo '</style>';
    ?>
</head>
<body>
    <div class="content" id="root"></div>
    <?php
    echo '<script type="text/javascript">';
    echo 'var baseURL = "' . base_url() . '",';
    echo 'siteURL = "' . base_url('index.php') . '",';
    echo 'content = ' . $footerJs . ';';
    echo '</script>';
    echo $internalJs;
    ?>
</body>
</html>