"use strict";
jQuery(document).ready(function()
{
	jQuery(document).on('click', '#wcmc_update_rates', wcmc_update_rates);
	jQuery(document).on('click', '.wcmc_toggle', wcmc_manage_base_currency_click);
	jQuery(document).on('submit', '#wcmc_exchange_rates_form', wcmc_on_submit);
	
	
	
	 jQuery(".wcmc_sortable").sortable({
				handle: ".wcmc_sort_button",
				cancel: '',
				placeholder: "wcmc_sort_placeholder"
			});
	
	jQuery(".wcmc_select2").selectWoo(
	{
		width: 'resolve',
		minimumResultsForSearch: -1
	});
});
function wcmc_on_submit(event)
{
	//No base currency is selected
	if(jQuery('.wcmc_toggle:checked').data('id') == undefined)
	{
		alert(wcmc.select_base_currency_message);
		event.preventDefault();
		return false;
	}
}
function wcmc_manage_base_currency_click(event)
{
	
	  const checkedState = jQuery(this).prop('checked');
	  const id = jQuery(this).data('id');
	  
	  //rates are resetted, so the user is forced to refresh
	  jQuery('.wcmc_rate_input').val("");
	  jQuery('#wcmc_'+id+'_input').val(1); 
	 
	  jQuery('.wcmc_toggle').each(function () 
	  {
		  jQuery(this).prop('checked', false);
	  });
	  jQuery(this).prop('checked', checkedState);
	   
	  //triggering automatic update 
	  setTimeout(function(){ wcmc_update_rates(event); }, 300);
}
function wcmc_update_rates(event)
{
	event.preventDefault();
	
	//No base currency is selected
	if(jQuery('.wcmc_toggle:checked').data('id') == undefined)
	{
		wcmc_rate_update_status_message(wcmc.select_base_currency_message, "#e74c3c");
		return;
	}
	//UI
	jQuery('#wcmc_loader').fadeIn();
	jQuery('#wcmc_update_rates').attr('disabled', true);
	jQuery('.wcmc_toggle').attr('disabled', true);
	jQuery('#wcmc_rates_table').animate({'opacity': 0.5});
	wcmc_rate_update_status_message(wcmc.updating_message, "#27ae60");
	
	var formData = new FormData();
	formData.append('action', 'wcmc_load_currency_rates');	
	formData.append('base_currency', jQuery('.wcmc_toggle:checked').data('id')); 	
	
	jQuery.ajax({
			url: ajaxurl,
			type: 'POST',
			data: formData,
			async: true,
			success: function (data) 
			{
				//UI
				jQuery('#wcmc_loader').fadeOut();
				jQuery('#wcmc_update_rates').removeAttr('disabled');
				jQuery('.wcmc_toggle').removeAttr('disabled');
				jQuery('#wcmc_rates_table').animate({'opacity': 1});
				
				if(data == "error")
				{
					//UI
					wcmc_rate_update_status_message(wcmc.update_error_message, "#e74c3c");
				}
				else
				{
					const result = JSON.parse(data);
					//console.log(result)
					for (var key in result)
					{
						if (result.hasOwnProperty(key)) 
						{
							 jQuery('#wcmc_'+key+'_input').val(result[key].rate);
						}
					}
					//UI
					wcmc_rate_update_status_message(wcmc.update_ok_message, "#27ae60");
				}
			},
			error: function (data) 
			{
				//UI
				wcmc_rate_update_status_message(wcmc.update_error_message, "#e74c3c");
			},
			cache: false,
			contentType: false,
			processData: false
		}); 			
		
	return false;
}

function wcmc_rate_update_status_message(message, color)
{
	jQuery('#wcmc_rate_update_status').html(message);					
	jQuery('#wcmc_rate_update_status').css({'color': color});
}