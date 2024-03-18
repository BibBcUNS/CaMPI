$('.gridview_click tbody tr').click(function (e) {
   var url_view = $(this).closest('.gridview_click').data('url_view');
   var id_keys = $(this).closest('tr').data('key');
   //if(e.target == this)
   if (typeof id_keys !== 'undefined') {
	   	location.href = url_view + '&id=' + id_keys;
   }
});