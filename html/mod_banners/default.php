<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_banners
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JLoader::register('BannerHelper', JPATH_ROOT . '/components/com_banners/helpers/banner.php');
$baseurl = JUri::base();
?>


<?php foreach ($list as $item) : ?>
	<?php $imageurl = $item->params->get('imageurl'); ?>
	<?php $alt = $item->params->get('alt'); ?>
	<?php $alt = $alt ?: $item->name; ?>

	<?php // Just display the image if no link specified ?>
		<img src="<?php echo $baseurl . $imageurl;?>" alt="<?php echo $alt;?>"/>
	 <?php echo $item->description ?>
	 
<?php endforeach; ?>
