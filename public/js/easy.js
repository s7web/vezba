var EasyCms = EasyCms || {};

$(function(){

    $(document).on('submit', '[data-ajax]', function(){
        var $this = $(this);
        var target = $this.data('target') || this;
        if($this.data('confirm') !== undefined) {
            if(! confirm('Are your sure?')) {
                return false;
            }
        }
        $('.fa-spin').show();
        $.post($this.data('ajax'), $this.serialize(), function(response) {
            $(target).html(response.html);
            $('.fa-spin').hide();
        });
        return false;
    });

    $('[data-load]').each(function() {
        var $this = $(this);
        $('.fa-spin').show();
        $.get($this.data('load'), function(response) {
            $this.html(response.html);
            $('.fa-spin').hide();
        });
    });

    $(document).on('click', '[data-click]', function() {
        var callback = $(this).data('click').split('.');
        EasyCms[callback[0]][callback[1]](this);
    });

});
