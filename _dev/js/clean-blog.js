(function($) {
  "use strict"; // Start of use strict

  // Floating label headings for the contact form
  $("body").on("input propertychange", ".floating-label-form-group", function(e) {
    $(this).toggleClass("floating-label-form-group-with-value", !!$(e.target).val());
  }).on("focus", ".floating-label-form-group", function() {
    $(this).addClass("floating-label-form-group-with-focus");
  }).on("blur", ".floating-label-form-group", function() {
    $(this).removeClass("floating-label-form-group-with-focus");
  });

  // Show the navbar when the page is scrolled up
  var MQL = 1170;

  //primary navigation slide-in effect
  if ($(window).width() > MQL) {
    var headerHeight = $('#mainNav').height();
    $(window).on('scroll', {
        previousTop: 0
      },
      function() {
        var currentTop = $(window).scrollTop();
        //check if user is scrolling up
        if (currentTop < this.previousTop) {
          //if scrolling up...
          if (currentTop > 0 && $('#mainNav').hasClass('is-fixed')) {
            $('#mainNav').addClass('is-visible');
          } else {
            $('#mainNav').removeClass('is-visible is-fixed');
          }
        } else if (currentTop > this.previousTop) {
          //if scrolling down...
          $('#mainNav').removeClass('is-visible');
          if (currentTop > headerHeight && !$('#mainNav').hasClass('is-fixed')) $('#mainNav').addClass('is-fixed');
        }
        this.previousTop = currentTop;
      });
  }

  $('.show-comments').click(function(e){
    e.preventDefault();
    var parent = $(this).closest('.post-details');
    var hr = parent.next('hr');
    var comment_section = hr.next('.comment-section');
    if (comment_section.hasClass('visible')) {
      comment_section.removeClass('visible');
    } else {
      comment_section.addClass('visible');
    }
  });

  $('#add-post').click(function(e){
    e.preventDefault();
    var form = $(this).closest('form');
    var form_data = form.serialize()
    var custom_url = form.attr('action');
    $.ajax({
        type: 'POST',
        data: form_data,
        url: custom_url,
        success: function(data){
          if (data == 'not_complete') {
            popMessage('.alert-danger', 'Un ou plusieurs champs sont manquants.');
          } else {
            popMessage('.alert-success', 'Post enregistré avec succès.');
          }
        },
        error: function(xhr){

        }
    });
  });

})(jQuery); // End of use strict

function popMessage(typeError, string){
  $(typeError).append('<p>'+string+'</p>');
  $(typeError).fadeIn(function(){
   $(this).delay(5000).fadeOut();
  });
}
