var EasyCms = EasyCms || {};

$(function(){

    $(document).on('submit', '[data-ajax]', function(){
        var $this = $(this);
        var append = $this.data('append');
        var target = $this.data('target') || this;
        if($this.data('confirm') !== undefined) {
            if(! confirm('Are your sure?')) {
                return false;
            }
        }
        $('.fa-spin').show();
        $.post($this.data('ajax'), $this.serialize(), function(response) {
            if (append) {
                $(target).append(response.html);
                var $page = $this.find('[name="page"]');
                $page.val(parseInt($page.val()) + 1);
                if(!response.append) {
                    $(append).hide();
                }
            } else {
                $(target).html(response.html);
            }
            $('.fa-spin').hide();
        });
        return false;
    });

    $('[data-load]').each(function() {
        EasyCms.system.reload(this);
    });

    $(document).on('click', '[data-click]', function() {
        var callback = $(this).data('click').split('.');
        EasyCms[callback[0]][callback[1]](this);
    });

    $(document).on('change', '[data-change]', function() {
        var callback = $(this).data('change').split('.');
        EasyCms[callback[0]][callback[1]](this);
    });

    $(document).on('keyup', '[data-keyup]', function() {
        var callback = $(this).data('keyup').split('.');
        EasyCms[callback[0]][callback[1]](this);
    });

});

EasyCms.system = {
    toggleInput : function(el) {
        $($(el).data('target')).prop('disabled', !$(el).val());
    },
    reload: function(el) {
        var $this = $(el);
        $('.fa-spin').show();
        $.get($this.data('load'), function(response) {
            $this.html(response.html);
            $('.fa-spin').hide();
        });
    }
}