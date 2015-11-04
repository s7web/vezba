(function ($) {
  $('#add_menu_form').validate({
    focusInvalid: false,
    ignore: "",
    rules: {
      menu_name: {
        digits: false,
        minlength: 3,
        maxlength: 30,
        required: true
      }
    },

    invalidHandler: function (event, validator) {

    },

    errorPlacement: function (label, element) { // render error placement for each input type
      $('<span class="error"></span>').insertAfter(element).append(label);
      var parent = $(element).parent('.input-with-icon');
      parent.removeClass('success-control').addClass('error-control');
    },

    highlight: function (element) { // hightlight error inputs
      var parent = $(element).parent('.input-with-icon');
      parent.removeClass('success-control').addClass('error-control');
    },

    unhighlight: function (element) { // revert the change done by hightlight

    },

    success: function (label, element) {
      var parent = $(element).parent('.input-with-icon');
      parent.removeClass('error-control').addClass('success-control');
    },

    submitHandler: function (form) {
      form.submit();
    }
  });
})(jQuery);

