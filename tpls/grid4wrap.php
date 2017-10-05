<?php
defined('_JEXEC') or die;

 if ($this->countModules('grid13 or grid14 or grid15 or grid16')) : ?>
  <div id="grid4wrap" class="clearfix">
    <div class="internal-container clearfix">
    <?php if ($this->countModules('grid13')) : ?>
      <div id="grid13">
        <jdoc:include type="modules" name="grid13" style="xhtml" />
      </div>
    <?php endif;?>

    <?php if ($this->countModules('grid14')) : ?>
      <div id="grid14">
        <jdoc:include type="modules" name="grid14" style="xhtml" />
      </div>
    <?php endif;?>

    <?php if ($this->countModules('grid15')) : ?>
      <div id="grid15">
        <jdoc:include type="modules" name="grid15" style="xhtml" />
      </div>
    <?php endif;?>

    <?php if ($this->countModules('grid16')) : ?>
      <div id="grid16">
        <jdoc:include type="modules" name="grid16" style="xhtml" />
      </div>
    <?php endif;?>
  </div>
  </div>
<?php endif; ?>