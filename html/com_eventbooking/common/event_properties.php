<?php
/**
 * @package            Joomla
 * @subpackage         Event Booking
 * @author             Tuan Pham Ngoc
 * @copyright          Copyright (C) 2010 - 2017 Ossolution Team
 * @license            GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die;

/**
 * Layout variables
 * -----------------
 * @var   EventbookingTableEvent $item
 * @var   RADConfig              $config
 * @var   boolean                $showLocation
 * @var   stdClass               $location
 * @var   boolean                $isMultipleDate
 * @var   string                 $nullDate
 * @var   int                    $Itemid
 */

$timeFormat        = $config->event_time_format ? $config->event_time_format : 'g:i a';
?>
<ul class="eb-event-info">
	<?php
	if (!$isMultipleDate)
	{
	?>
		<li class="eb-event-info-date">
				<strong class="hidden"><?php echo JText::_('EB_EVENT_DATE') ?></strong>
			
				<?php
				if ($item->event_date == EB_TBC_DATE)
				{
					echo JText::_('EB_TBC');
				}
				else
				{
				?>
					<meta itemprop="startDate" content="<?php echo JFactory::getDate($item->event_date)->format("Y-m-d\TH:i"); ?>">
					<?php
					if (strpos($item->event_date, '00:00:00') !== false)
					{
						$dateFormat = $config->date_format;
					}
					else
					{
						$dateFormat = $config->event_date_format;
					}
					echo '<span class="eb-icon-cal">' . JFactory::getDate($item->event_date)->format("j") . '</span>';
					echo '<span>' . JHtml::_('date', $item->event_date, $dateFormat, null) . '</span>';
				}
				?>
		</li>

		<?php
			if (strpos($item->event_date, '00:00:00') === false)
			{
			?>
				<li class="eb-event-info-time">							
					<span class="eb-time"><?php echo JHtml::_('date', $item->event_date, $timeFormat, null) ?></span>
				</li>
			<?php
			}
			?>


		<?php
		// if ($item->event_end_date != $nullDate)
		// {
		// 	if (strpos($item->event_end_date, '00:00:00') !== false)
		// 	{
		// 		$dateFormat = $config->date_format;
		// 	}
		// 	else
		// 	{
		// 		$dateFormat = $config->event_date_format;
		// 	}
			?>
			<!-- <tr>
				<td>
					<strong><?php echo JText::_('EB_EVENT_END_DATE'); ?></strong>
				</td>
				<td>
					<meta itemprop="endDate"
						  content="<?php echo JFactory::getDate($item->event_end_date)->format("Y-m-d\TH:i"); ?>">
					<?php echo JHtml::_('date', $item->event_end_date, $dateFormat, null); ?>
				</td>
			</tr> -->
			<?php
		// }

		if ($item->registration_start_date != $nullDate)
		{
			if (strpos($item->registration_start_date, '00:00:00') !== false)
			{
				$dateFormat = $config->date_format;
			}
			else
			{
				$dateFormat = $config->event_date_format;
			}
			?>
			<li>
					<strong class="hidden"><?php echo JText::_('EB_REGISTRATION_START_DATE'); ?></strong>
				
					<span><?php echo JHtml::_('date', $item->registration_start_date, $dateFormat, null); ?></span>
			</li>
			<?php
		}

		if ($config->show_capacity == 1 || ($config->show_capacity == 2 && $item->event_capacity))
		{
		?>
			<li class="eb-event-info-capacity">
					<strong class="hidden"><?php echo JText::_('EB_CAPACITY'); ?></strong>
					<span>
					<?php
					if ($item->event_capacity)
					{
						echo $item->event_capacity;
					}
					else
					{
						echo JText::_('EB_UNLIMITED');
					}
					?>
						
					</span>
			</li>
		<?php
		}

		if ($config->show_registered && $item->registration_type != 3)
		{
		?>
			<li>
					<strong><?php echo JText::_('EB_REGISTERED'); ?></strong>
				
					<span><?php
					echo $item->total_registrants . ' ';

					if ($config->show_list_of_registrants && ($item->total_registrants > 0) && EventbookingHelperAcl::canViewRegistrantList())
					{
					?>
						<a href="index.php?option=com_eventbooking&view=registrantlist&id=<?php echo $item->id ?>&tmpl=component"
						   class="eb-colorbox-register-lists"><span
								class="view_list"><?php echo JText::_("EB_VIEW_LIST"); ?></span></a>
					<?php
					}
					?></span>
			</li>	
		<?php
		}

		if ($config->show_available_place && $item->event_capacity)
		{
		?>
			<li>
					<strong><?php echo JText::_('EB_AVAILABLE_PLACE'); ?></strong>				
					<span><?php echo $item->event_capacity - $item->total_registrants; ?></span>
			</li>	
		<?php
		}

		if ($nullDate != $item->cut_off_date)
		{
			if (strpos($item->cut_off_date, '00:00:00') !== false)
			{
				$dateFormat = $config->date_format;
			}
			else
			{
				$dateFormat = $config->event_date_format;
			}
			?>
			<li>
					<strong><?php echo JText::_('EB_CUT_OFF_DATE'); ?></strong>
				
					<span><?php echo JHtml::_('date', $item->cut_off_date, $dateFormat, null); ?></span>
			</li>
			<?php
		}
	}

	if ($item->individual_price > 0 || ($config->show_price_for_free_event))
	{
		$showPrice = true;
	}
	else
	{
		$showPrice = false;
	}

	if ($config->show_discounted_price && ($item->individual_price != $item->discounted_price))
	{
		if ($showPrice)
		{
		?>
			<li class="eb-event-price">
					<strong><?php echo JText::_('EB_ORIGINAL_PRICE'); ?></strong>
				
					<?php
					if ($item->individual_price > 0)
					{
						echo '<span>'.EventbookingHelper::formatCurrency($item->individual_price, $config, $item->currency_symbol).'</span>';
					}
					else
					{
						echo '<span class="eb_free">' . JText::_('EB_FREE') . '</span>';
					}
					?>
			</li>
			<li class="eb-event-price">
					<strong><?php echo JText::_('EB_DISCOUNTED_PRICE'); ?></strong>
				
					<?php
					if ($item->discounted_price > 0)
					{
						echo '<span>'.EventbookingHelper::formatCurrency($item->discounted_price, $config, $item->currency_symbol).'</span>';

						if ($item->early_bird_discount_amount > 0 && $item->early_bird_discount_date != $nullDate)
						{
							echo ' <em>' . JText::sprintf('EB_UNTIl_DATE', JHtml::_('date', $item->early_bird_discount_date, $config->date_format, null)) . '</em>';
						}
					}
					else
					{
						echo '<span class="eb_free">' . JText::_('EB_FREE') . '</span>';
					}
					?>
			</li>
		<?php
		}
	}
	else
	{
		if ($showPrice)
		{
		?>
			<li class="eb-event-price">
					<!-- <strong><?php echo JText::_('EB_INDIVIDUAL_PRICE'); ?></strong> -->
					<?php
					if ($item->price_text)
					{
						echo '<span>'.$item->price_text.'</span>';
					}
					elseif ($item->individual_price > 0)
					{
						echo '<span>'.EventbookingHelper::formatCurrency($item->individual_price, $config, $item->currency_symbol).'</span>';
					}
					else
					{
						echo '<span class="eb_free">' . JText::_('EB_FREE') . '</span>';
					}
					?>
			</li>
			<?php
		}
	}

	if ($item->fixed_group_price > 0)
	{
	?>
		<li class="eb-event-price">
				<!-- <strong><?php echo JText::_('EB_FIXED_GROUP_PRICE'); ?></strong> -->
				<span><?php
				echo EventbookingHelper::formatCurrency($item->fixed_group_price, $config, $item->currency_symbol);
				?></span>
		</li>
	<?php
	}

	if ($item->late_fee > 0)
	{
	?>
		<li class="eb-event-price">
				<?php echo JText::_('EB_LATE_FEE'); ?>
				<?php
					echo EventbookingHelper::formatCurrency($item->late_fee, $config, $item->currency_symbol);
					echo '<em>' . JText::sprintf('EB_FROM_DATE', JHtml::_('date', $item->late_fee_date, $config->date_format, null)) . '</em>';
				?>
		</li>
	<?php
	}

	if ($config->show_event_creator)
	{
	?>
		<li>
				<?php echo JText::_('EB_CREATED_BY'); ?>
				<a href="<?php echo JRoute::_('index.php?option=com_eventbooking&view=search&created_by=' . $item->created_by . '&Itemid=' . $Itemid); ?>"><?php echo $item->creator_name; ?></a>
			</li>
	<?php
	}

	if (isset($item->paramData))
	{
		foreach ($item->paramData as $param)
		{
			if ($param['value'])
			{
				$paramValue = $param['value'];

				// Make the link click-able
				if (filter_var($paramValue, FILTER_VALIDATE_URL))
				{
					$paramValue = '<a href="' . $paramValue . '" target="_blank">' . $paramValue . '<a/>';
				}
			?>
				<li>
						<strong><?php echo JText::_($param['title']); ?></strong>					
						<?php echo JText::_($paramValue); ?>
				</li>
			<?php
			}
		}
	}

	if ($showLocation && $location)
	{
		$width = (int) $config->map_width;

		if (!$width)
		{
			$width = 500;
		}

		$height = (int) $config->map_height;

		if (!$height)
		{
			$height = 450;
		}
		?>
		<li class="eb-event-info-location">
				<strong class="hidden"><?php echo JText::_('EB_LOCATION'); ?></strong>
				<?php
				if ($location->address)
				{
				?>
					<div style="display:none" itemprop="location" itemscope itemtype="http://schema.org/Place">
						<div itemprop="name"><?php echo $location->name; ?></div>
						<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
							<span itemprop="streetAddress"><?php echo $location->address; ?></span>
						</div>
					</div>
					<?php
					if ($location->image || EventbookingHelper::isValidMessage($location->description))
					{
					?>
						<a href="<?php echo JRoute::_('index.php?option=com_eventbooking&view=map&location_id=' . $item->location_id . '&Itemid='.$Itemid); ?>"
						   title="<?php echo $location->name; ?>"><?php echo $location->name; ?></a>
					<?php
					}
					else
					{
					?>
						<a href="<?php echo JRoute::_('index.php?option=com_eventbooking&view=map&location_id=' . $item->location_id . '&tmpl=component&format=html'); ?>"
						   class="eb-colorbox-map" title="<?php echo $location->name; ?>"><?php echo $location->name; ?></a>
					<?php
					}
				}
				else
				{
					echo $location->name;
				}
				?>
		</li>
		<?php
	}

	if ($item->attachment && !empty($config->show_attachment_in_frontend))
	{
	?>
	<li>
			<strong><?php echo JText::_('EB_ATTACHMENT'); ?></strong>
		
			<?php
			$attachments = explode('|', $item->attachment);
			$baseUri =  JUri::base(true);

			for ($i = 0, $n = count($attachments); $i < $n; $i++)
			{
				$attachment = $attachments[$i];

				if ($i > 0)
				{
					echo '<br />';
				}
				?>
					<a href="<?php echo $baseUri . '/media/com_eventbooking/' . $attachment; ?>" target="_blank"><?php echo $attachment; ?></a>
				<?php
			}
			?>
		
	<li>
	<?php
	}
	?>	
</ul>
