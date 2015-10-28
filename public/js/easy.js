$(function(){

    $(document).on('submit', '[data-ajax]', function(){
        var url = $(this).data('ajax');
        var data = $(this).serialize();
        var $this = $(this);
        $.post(url, data, function(response) {
            $this.html(response.message);
        });
        return false;
    });

});
