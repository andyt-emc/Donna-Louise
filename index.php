<?php defined( '_JEXEC' ) or die;

$template_path = JPATH_THEMES.'/'.$this->template;

include_once $template_path . '/logic.php';

?><!doctype html>

<html lang="<?php echo $this->language; ?>">

<head>
  <jdoc:include type="head" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
  <link rel="apple-touch-icon-precomposed" href="<?php echo $tpath; ?>/images/apple-touch-icon-57x57-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $tpath; ?>/images/apple-touch-icon-72x72-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo $tpath; ?>/images/apple-touch-icon-114x114-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo $tpath; ?>/images/apple-touch-icon-144x144-precomposed.png">
  <script src='https://www.google.com/recaptcha/api.js'></script>
  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB4QcSr-EO3bOzAFQrwfHHB-5CKnhJnbew&libraries=places"></script>
</head>

<body class="<?php echo (($menu->getActive() == $menu->getDefault()) ? ('front') : ('site')).' '.$active->alias.' '.htmlspecialchars($pageclass); ?>">
<div id="outerwrap">
<div id="innerwrap">
    <?php include_once $template_path .'/tpls/topwrap.php'; ?>

    <?php include_once $template_path .'/tpls/menu.php'; ?>

    <?php if ($this->countModules('banner')) : ?>
      <div id="bannerwrap" class="clearfix">
        <div class="internal-container clearfix">
          <div id="banner">
            <jdoc:include type="modules" name="banner" style="xhtml" />
          </div>
      </div>
    </div>
    <?php endif; ?>

    <?php if ($this->countModules('breadcrumb')) : ?>
      <div id="breadcrumbwrap" class="clearfix">
        <div class="internal-container clearfix">
          <div id="breadcrumb">
            <jdoc:include type="modules" name="breadcrumb" style="xhtml" />
          </div>
        </div>
      </div>
    <?php endif; ?>

    <?php include_once $template_path .'/tpls/grid1wrap.php'; ?>

    <?php include_once $template_path .'/tpls/mainwrap.php'; ?>

    <?php include_once $template_path .'/tpls/grid2wrap.php'; ?>

    <?php include_once $template_path .'/tpls/grid3wrap.php'; ?>

    <?php include_once $template_path .'/tpls/grid4wrap.php'; ?>

    <?php include_once $template_path .'/tpls/bottomwrap.php'; ?>


    <?php if ($this->countModules('footer')) : ?>
      <div id="footerwrap" class="clearfix">
        <div class="internal-container clearfix">
          <div id="footer">
            <jdoc:include type="modules" name="footer" style="xhtml" />
          </div>
        </div>
      </div>
    <?php endif; ?>

    <?php if ($this->countModules('debug')) : ?>
      <jdoc:include type="modules" name="debug" />
    <?php endif; ?>
</div>
</div>
<script src="https://use.fontawesome.com/4d00cb0560.js"></script>
<!--script src="templates/<?php echo $this->template; ?>/js/vendor/jquery.lazyload.min.js"></script-->
<script src="templates/<?php echo $this->template; ?>/js/scripts.min.js"></script>
</body>
</html>
