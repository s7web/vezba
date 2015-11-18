var EasyCms = EasyCms || {};

$(function(){

    $(document).on('submit', '[data-ajax]', function(){
        var $this = $(this);
        $.post($this.data('ajax'), $this.serialize(), function(response) {
            $($this.data('target')).html(response.html);
        });
        return false;
    });

    $('[data-load]').each(function() {
        var $this = $(this);
        $.get($this.data('load'), function(response) {
            $this.html(response.html);
        });
    });

    $(document).on('click', '[data-click]', function() {
        var callback = $(this).data('click').split('.');
        EasyCms[callback[0]][callback[1]](this);
    });

});
