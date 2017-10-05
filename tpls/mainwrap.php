<?php
defined('_JEXEC') or die;
?>
<?php
$menu = & JSite::getMenu();
if($menu->getActive() !== $menu->getDefault()) :
?>
  <div id="mainwrap" class="clearfix">
    <div class="internal-container clearfix">
      <?php if ($this->countModules('left')) : ?>
        <div id="left">
          <jdoc:include type="modules" name="left" style="xhtml" />
        </div>
      <?php endif;?>

      <div id="main">
        <?php if ($this->countModules('above')) : ?>
          <div id="above">
            <jdoc:include type="modules" name="above" style="xhtml" />
          </div>
        <?php endif; ?>

        <?php if ($this->countModules('main')) : ?>
          <jdoc:include type="modules" name="main" style="xhtml" />
        <?php endif; ?>
        <jdoc:include type="component" />

        <?php if ($this->countModules('below')) : ?>
          <div id="below">
            <jdoc:include type="modules" name="below" style="xhtml" />
          </div>
        <?php endif; ?>
      </div>

      <?php if ($this->countModules('right')) : ?>
        <div id="right">
          <jdoc:include type="modules" name="right" style="xhtml" />
        </div>
      <?php endif;?>
    </div>
  </div>
<?php endif;?>
