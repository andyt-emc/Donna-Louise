<?php
defined('_JEXEC') or die;

 if ($this->countModules('grid9 or grid10 or grid11 or grid12')) : ?>
  <div id="grid3wrap" class="clearfix">
    <div class="internal-container clearfix">
    <?php if ($this->countModules('grid9')) : ?>
      <div id="grid9">
        <jdoc:include type="modules" name="grid9" style="xhtml" />
      </div>
    <?php endif;?>

    <?php if ($this->countModules('grid10')) : ?>
      <div id="grid10">
        <jdoc:include type="modules" name="grid10" style="xhtml" />
      </div>
    <?php endif;?>

    <?php if ($this->countModules('grid11')) : ?>
      <div id="grid11">
        <jdoc:include type="modules" name="grid11" style="xhtml" />
      </div>
    <?php endif;?>

    <?php if ($this->countModules('grid12')) : ?>
      <div id="grid12">
        <jdoc:include type="modules" name="grid12" style="xhtml" />
      </div>
    <?php endif;?>
  </div>
  </div>
<?php endif; ?>