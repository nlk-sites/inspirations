<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package pgb
 */
?><!DOCTYPE html>
<?php tha_html_before(); ?>
<html <?php language_attributes(); ?>>
<head>

    <?php tha_head_top(); ?>

    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php wp_title( '|', true, 'right' ); ?></title>

    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

    <?php tha_head_bottom(); ?>

    <?php 

    $pagecolor = inspirations_page_color();
    $cRGB = hex2rgb( $pagecolor );
    ?>

    <style type="text/css">
    article h3 {
      color: <?php echo $pagecolor; ?>;
    }
    .page-color {
      background-color: <?php echo $pagecolor; ?>;
    }
    article a, .sidebar a,
    article a:hover, .sidebar a:hover {
      color: <?php echo $pagecolor; ?>;
    }
    button, .btn {
      font-family: 'Source Sans Pro', 'Open Sans', arial, sans-serif;
      color:  #ffffff;
      font-size: 18px;
      font-weight: 700;
      line-height: 24px;
      text-transform: uppercase;
    }
    button.btn-primary, .btn.btn-primary {
      background-color: <?php echo $pagecolor; ?>;
      background-color: rgba(<?php echo implode(',', $cRGB); ?>, 1);
      border: <?php echo $pagecolor; ?>;
    }
    button.btn-primary:hover, .btn.btn-primary:hover {
      background-color: <?php echo $pagecolor; ?>;
      background-color: rgba(<?php echo implode(',', $cRGB); ?>,.8);
      border: <?php echo $pagecolor; ?>;
    }

    </style>

    <?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

    <?php tha_body_top(); ?>
    <?php tha_header_before(); ?>
    <?php do_action( 'before' ); ?>

    <?php pgb_block_navtop(); ?>

    <div id="page-content-wrapper" class="page-content-wrapper-left">

      <?php pgb_block_header(); //locate_template('block-header.php', true); ?>

      <?php tha_header_after();  ?>
      <?php tha_content_before(); ?>
      <div class="main-content">
        <div class="container">
          <div class="row">
