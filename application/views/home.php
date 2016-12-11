<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Welcome to CodeIgniter</title>
    <base href="<?php echo base_url();?>" />

    <link rel="stylesheet" type="text/css" href="resources/css/main.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $extjs['css'];?>">
    <link rel="stylesheet" type="text/css" href="<?php echo $extjs['font-awesome'];?>">
    <script src="<?php echo $extjs['js'];?>"></script>
    <script src="<?php echo site_url('direct/api');?>"></script>

    <script>
        Ext.define("Myapp.Const", {
            singleton  : true,
            ajaxShow: false,
            loginSec: false,

            site: {
                name: 'Dana'
            }
        });
    </script>
    <script src="app.js"></script>
</head>
<body>
<div id="loading">
    <div class="cmsg">
        <div class="msg">
            Loading…
        </div>
        <div class="lpb">
            <div id="lpt" style="width: 10%;"></div>
        </div>
    </div>
</div>
<div class="flaeQ"></div>
</body>
</html>