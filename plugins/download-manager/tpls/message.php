<?php
if (!defined('ABSPATH')) die();
?><!DOCTYPE html>
<html>
<head>
    <title><?php echo $title; ?></title>
    <link href='<?php echo plugins_url("/download-manager/assets/bootstrap/css/bootstrap.css"); ?>' rel='stylesheet' />
    <link href='<?php echo plugins_url("/download-manager/assets/font-awesome/css/font-awesome.min.css"); ?>' rel='stylesheet' />
    <link href="https://fonts.googleapis.com/css?family=Abel|Gudea" rel="stylesheet">
    <style>
        *{
            font-family: 'Gudea', sans-serif;
            text-align: center;
            letter-spacing: 1px;
        }
        body{
            overflow: hidden;
            padding: 0;
            margin: 0;
        }
        .w3eden{
            display: table;
            position: absolute;
            height: 100%;
            width: 100%;
            overflow: hidden;
        }
        .middle{
            display: table-cell;
            vertical-align: middle;
            overflow: hidden;
        }
        body.danger i.fa{
            color: #ff435b;
        }
        body.danger h3{
            color: #ff435b;
            font-family: 'Abel', sans-serif;
            letter-spacing: 0px;
        }
        .w3eden .panel{
            padding: 30px;
            display: inline-block;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.08) !important;
            border-radius: 15px !important;
            margin: 0;
            width: 400px;
            max-width: 80%;
        }
        .w3eden .panel.panel-danger{
            border: 1px solid rgba(255, 67, 91, 0.7) !important;
        }
    </style>
</head>
<body class="<?php echo $type; ?>">
<div class='w3eden'>
<div class='middle'>
<div class="panel panel-<?php echo $type; ?>">
    <?php if($type == 'danger') echo "<img style='width: 100px' src='data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/PjxzdmcgZGF0YS1uYW1lPSJMaXZlbGxvIDEiIGlkPSJMaXZlbGxvXzEiIHZpZXdCb3g9IjAgMCAxNTEuNTcgMTUxLjU3IiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjx0aXRsZS8+PGNpcmNsZSBjeD0iNzUuNzgiIGN5PSI3NS43OCIgcj0iNzIuMjgiIHN0eWxlPSJmaWxsOiNkYTIyNDQ7c3Ryb2tlOiNmMmYyZjI7c3Ryb2tlLWxpbmVjYXA6cm91bmQ7c3Ryb2tlLWxpbmVqb2luOnJvdW5kO3N0cm9rZS13aWR0aDo3cHgiLz48bGluZSBzdHlsZT0iZmlsbDpub25lO3N0cm9rZTojZjJmMmYyO3N0cm9rZS1saW5lY2FwOnJvdW5kO3N0cm9rZS1saW5lam9pbjpyb3VuZDtzdHJva2Utd2lkdGg6NXB4IiB4MT0iMzUuMiIgeDI9IjExNi4zNyIgeTE9IjEwNC4zNyIgeTI9IjEwNC4zNyIvPjxsaW5lIHN0eWxlPSJmaWxsOm5vbmU7c3Ryb2tlOiNmMmYyZjI7c3Ryb2tlLWxpbmVjYXA6cm91bmQ7c3Ryb2tlLWxpbmVqb2luOnJvdW5kO3N0cm9rZS13aWR0aDo1cHgiIHgxPSIxMTYuMzciIHgyPSI3NS43OCIgeTE9IjEwNC4zNyIgeTI9IjM1LjIiLz48bGluZSBzdHlsZT0iZmlsbDpub25lO3N0cm9rZTojZjJmMmYyO3N0cm9rZS1saW5lY2FwOnJvdW5kO3N0cm9rZS1saW5lam9pbjpyb3VuZDtzdHJva2Utd2lkdGg6NXB4IiB4MT0iMzUuMiIgeDI9Ijc1Ljc4IiB5MT0iMTA0LjM3IiB5Mj0iMzUuMiIvPjxsaW5lIHN0eWxlPSJmaWxsOm5vbmU7c3Ryb2tlOiNmMmYyZjI7c3Ryb2tlLWxpbmVjYXA6cm91bmQ7c3Ryb2tlLWxpbmVqb2luOnJvdW5kO3N0cm9rZS13aWR0aDo0cHgiIHgxPSI3NS43OCIgeDI9Ijc1Ljc4IiB5MT0iNTcuMzIiIHkyPSI4My4xOCIvPjxjaXJjbGUgY3g9Ijc1Ljc4IiBjeT0iOTEuNjMiIHI9IjIuNjIiIHN0eWxlPSJmaWxsOiNmY2ZiZjIiLz48L3N2Zz4=' />"; ?>
    <br/>
    <h3><?php echo $title; ?></h3>
        <?php echo $message; ?>
</div>
</div>
</div>
</body>
</html>
