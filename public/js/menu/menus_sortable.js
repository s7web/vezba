"use strict";
(function($){

  /**
   * Nestable list for menu items init
   *
   * Current setup:
   *                - group: 1 Bounded for group 1, to be able to drag items between two lists in this case ( items -> trash )
   *                - maxDepth: 3 Max nesting levels is 3 currently
   */
  $('#menu_items_list').nestable({
    group: 1,
    maxDepth: 3
  });

  /**
   * Trash nestable init, this is place for removing items from menu items list
   */
  $('#trash_menu_items').nestable({
    group: 1,
    maxDepth: 1
  });

  /**
   * Used to add custom url to menu
   *
   * When Menu item name and url is provided, add data attributes to button below form with entered values
   */
  $('#custom_menu_item_name, #custom_menu_item_url').keyup(function(){
    $('#custom_transfer_box').attr('data-itemName', $('#custom_menu_item_name').val());
    $('#custom_transfer_box').attr('data-composedUrl', $('#custom_menu_item_url').val());
  });

  /**
   * Send request to backend to save menu structure
   */
  $('#force_save_menu').click(function(e){
    $.post($('#menu_items_list').attr('data-urlpost'), {items: JSON.stringify($('#menu_items_list').nestable('serialize'))}, function(){}, 'json');
  });

  /**
   * Get data values of transfer element and create new menu item
   */
  $('.item_transfer_to_menu').click( function(){
    var html = '<li class="dd-item" data-name="'+ $(this).attr('data-itemName') +'" data-url="'+ $(this).attr('data-composedUrl') +'"><div class="dd-handle">'+ $(this).attr('data-itemName') +'</div></li>';
    $('#menu_items_list_container').append(html);
    $('#custom_menu_item_name').val('');
    $('#custom_menu_item_url').val('');
  });
})(jQuery);
