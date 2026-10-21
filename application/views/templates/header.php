<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= isset($title) ? $title : 'Dashboard'; ?></title>
  
  <link rel="icon" type="image/png" href="<?= base_url('assets/frontend/img/favicon-192.png'); ?>" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="<?= base_url('assets/frontend/img/logo.png'); ?>" />
    <link rel="shortcut icon" href="<?= base_url('assets/frontend/img/logo.png'); ?>" />
    <link rel="apple-touch-icon" sizes="180x180" href="<?= base_url('assets/frontend/img/logo.png'); ?>" />
    <link rel="manifest" href="<?= base_url('assets/img/site.webmanifest'); ?>" />
  
  <link rel="stylesheet" href="<?= base_url('assets/css/styles.min.css'); ?>" />
  <script src="<?= base_url('assets/libs/jquery/dist/jquery.min.js'); ?>"></script>
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

</head>

<body>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
       data-sidebar-position="fixed" data-header-position="fixed">
