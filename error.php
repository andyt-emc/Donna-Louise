<?php
defined( '_JEXEC' ) or die;

// 404 page redirect
if (($this->error->getCode()) == '404') {
  header("HTTP/1.0 404 Not Found");
  echo file_get_contents(JURI::root().'error-404');
  exit;
}

// variables
$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$tpath = $this->baseurl.'/templates/'.$this->template;

?><!doctype html>

<html lang="<?php echo $this->language; ?>">

<head>
  <title><?php echo $this->error->getCode(); ?> - <?php echo $this->title; ?></title>
  <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" /> <!-- mobile viewport optimized -->
  <link rel="stylesheet" href="<?php echo $tpath; ?>/css/error.css?v=1">
</head>

<body>
  <div align="center">
    <div id="error">
      <h1>
        <?php echo htmlspecialchars($app->getCfg('sitename')); ?>
      </h1>
      <p>
        <?php
          echo $this->error->getCode().' - '.$this->error->getMessage();
          if (($this->error->getCode()) == '404') {
            echo '<br />';
            echo JText::_('JERROR_LAYOUT_REQUESTED_RESOURCE_WAS_NOT_FOUND');
          }
        ?>
      </p>
      <p>
        <?php echo JText::_('JERROR_LAYOUT_GO_TO_THE_HOME_PAGE'); ?>:
        <a href="<?php echo $this->baseurl; ?>/"><?php echo JText::_('JERROR_LAYOUT_HOME_PAGE'); ?></a>.
      </p>
      <?php // render module mod_search
        $module = new stdClass();
        $module->module = 'mod_search';
        echo JModuleHelper::renderModule($module);
      ?>
    </div>
  </div>
  <div align="center">
  <?php if ($this->debug) : ?>
          <?php echo $this->renderBacktrace(); ?>
          <?php // Check if there are more Exceptions and render their data as well ?>
          <?php if ($this->error->getPrevious()) : ?>
            <?php $loop = true; ?>
            <?php // Reference $this->_error here and in the loop as setError() assigns errors to this property and we need this for the backtrace to work correctly ?>
            <?php // Make the first assignment to setError() outside the loop so the loop does not skip Exceptions ?>
            <?php $this->setError($this->_error->getPrevious()); ?>
            <?php while ($loop === true) : ?>
              <p><strong><?php echo JText::_('JERROR_LAYOUT_PREVIOUS_ERROR'); ?></strong></p>
              <p><?php echo htmlspecialchars($this->_error->getMessage(), ENT_QUOTES, 'UTF-8'); ?></p>
              <?php echo $this->renderBacktrace(); ?>
              <?php $loop = $this->setError($this->_error->getPrevious()); ?>
            <?php endwhile; ?>
            <?php // Reset the main error object to the base error ?>
            <?php $this->setError($this->error); ?>
          <?php endif; ?>
      <?php endif; ?>
    </div>
</body>

</html>
