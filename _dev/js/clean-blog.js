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

  $('#add-comment').click(function(e){
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
          popMessage('.alert-success', 'Commentaire enregistré avec succès.');
        }
      },
      error: function(xhr){

      }
    });
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

  $('.deletePost').click(function(e){
    e.preventDefault();
    var custom_url = $(this).attr('href');
    $.ajax({
      url: custom_url,
      success: function(data){
        if (data == 'echec') {
          popMessage('.alert-danger', 'Le post n\'a pas pu être supprimé.');
        } else {
          popMessage('.alert-success', 'Post supprimé avec succès.');
          setTimeout("window.location='/index.php?controller=Blog&action=renderHome'", 2000);
        }
      },
      error: function(xhr){
      }
    });
  });

  $('.deleteComment').click(function(e){
    e.preventDefault();
    var custom_url = $(this).attr('href');
    $.ajax({
      url: custom_url,
      success: function(data){
        if (data == 'echec') {
          popMessage('.alert-danger', 'Le commentaire n\'a pas pu être supprimé.');
        } else {
          popMessage('.alert-success', 'Commentaire supprimé avec succès.');
          setTimeout("window.location='/index.php?controller=Blog&action=renderHome'", 2000);
        }
      },
      error: function(xhr){
      }
    });
  });

  $('#login').click(function(e){
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
          popMessage('.alert-danger', 'Il manque un ou plusieurs champs.');
        } else if (data == 'not_existing'){
          popMessage('.alert-danger', 'Le couple e-mail/mot de passe est incorrect');
        } else if (data == 'not_confirmed'){
          popMessage('.alert-danger', 'Vous n\'avez pas confirmé votre compte.');
        } else {
          popMessage('.alert-success', 'Bienvenue '+data+' !');
          setTimeout("window.location='/index.php?controller=Blog&action=renderHome'", 2000);
        }
      },
      error: function(xhr){
      }
    });
  });

  $('#register').click(function(e){
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
          popMessage('.alert-danger', 'Il manque un ou plusieurs champs.');
        } else if (data == 'already_exists'){
          popMessage('.alert-danger', 'Cette adresse e-mail est déjà présente en base.');
        } else if (data == 'passwords_not_maching'){
          popMessage('.alert-danger', 'Les deux mots de passes ne sont pas identiques.');
        } else {
          popMessage('.alert-success', 'Vous êtes bien enregistré.');
          setTimeout("window.location='/index.php?controller=Blog&action=renderHome'", 2000);
        }
      },
      error: function(xhr){
      }
    });
  });


})(jQuery); // End of use strict

function popMessage(typeError, string){
  $(typeError).empty();
  $(typeError).append('<p>'+string+'</p>');
  $(typeError).fadeIn(function(){
    $(this).delay(5000).fadeOut();
  });
}
