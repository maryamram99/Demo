"use strict";
jQuery(document).ready(function()
{
	jQuery('.wcmc_currency_selector').select2(
		{ 
			width: 'resolve',
			minimumResultsForSearch: -1,
			containerCssClass: 'wcmca_select2_container',
			templateResult: wcmc_selectwoo_template_result_format,
			templateSelection: wcmc_selectwoo_template_selection_format,
			escapeMarkup: function(m) { return m; }
		});	
	jQuery(document).on('change','.wcmc_currency_selector', wcmc_currency_selection);
	
	//Needed to refresh the minicart component after a currency has been selected
	jQuery( document.body ).trigger( 'wc_fragment_refresh' );	
});
function wcmc_selectwoo_template_result_format(state) 
{
    var originalOption = state.element; 
	return state.text.replace("%js_flag", '<img class="'+wcmc_currency_selector.prefix+'flag currency-flag currency-flag-'+jQuery(originalOption).data('flag')+' currency-flag-sm"/>');
}
function wcmc_selectwoo_template_selection_format(state) 
{
    var originalOption = state.element; 
	return state.text.replace("%js_flag", '<img class="'+wcmc_currency_selector.prefix+'flag currency-flag currency-flag-'+jQuery(originalOption).data('flag')+' currency-flag-sm"/>');
	//to return no flag: state.text.replace("%js_flag", ' ');
}

function wcmc_currency_selection(event)
{
	event.preventDefault();
	
	const id = jQuery(event.currentTarget).data('id');
	const value = jQuery(event.currentTarget).val();
	
	//UI
	jQuery(event.currentTarget).attr('disabled', true);
	
	let location = wcmc_remove_param('currency', window.location.href)
	var url = new URL(location);
	url.searchParams.append('currency', value);
	window.location = url;
	
	return;
	
	//ajax: no longer used
	var formData = new FormData();
	formData.append('action', 'wcmc_set_customer_currency');	
	formData.append('currency_code', value); 			
	jQuery.ajax({
			url: wcmc_currency_selector.ajaxurl, 
			type: 'POST',
			data: formData,
			async: true,
			success: function (data) 
			{
				const location = wcmc_remove_param('currency',window.location.href);
				jQuery("#wcmc_loader_"+id).fadeOut();
				//location.reload(true);
				window.location = location;
			},
			error: function (data) 
			{
				
			},
			cache: false,
			contentType: false,
			processData: false
		}); 		
	
	
	return false;
}
function wcmc_insert_param(key, value)
{
    key = encodeURI(key); value = encodeURI(value);

    var kvp = document.location.search.substr(1).split('&');

    var i=kvp.length; var x; while(i--) 
    {
        x = kvp[i].split('=');

        if (x[0]==key)
        {
            x[1] = value;
            kvp[i] = x.join('=');
            break;
        }
    }

    if(i<0) {kvp[kvp.length] = [key,value].join('=');}

    //this will reload the page, it's likely better to store this until finished
    //document.location.search = kvp.join('&'); 
	return kvp.join('&');;
}
function wcmc_remove_param(key, sourceURL) 
{
    var rtn = sourceURL.split("?")[0],
        param,
        params_arr = [],
        queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";
    if (queryString !== "") {
        params_arr = queryString.split("&");
        for (var i = params_arr.length - 1; i >= 0; i -= 1) {
            param = params_arr[i].split("=")[0];
            if (param === key) {
                params_arr.splice(i, 1);
            }
        }
        rtn = rtn + "?" + params_arr.join("&");
    }
    return rtn;
}