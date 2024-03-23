import {baseURL} from './inc-js/utils.inc.js';

//---------------------Load Header And Footer Into HTML Document---------------------//
$(function () {
  var header = $.Deferred();
  var footer = $.Deferred();
  $('#header').load(baseURL + "/partials/header.html", function () { header.resolve(); }); // load header
  $('#footer').load(baseURL + "/partials/footer.html", function () { footer.resolve(); }); // load footer

  $.when(header, footer).done(function () {
    //-------Check The Login Status of Current User-------//
    $.ajax({
      url: baseURL + "/backend/auth-php/status.auth.php",
      dataType: 'json',
      success: function (result) {
        if (result.isLoggedIn) {
          $('#login-false').addClass("visually-hidden");
          $('#login-true').removeClass("visually-hidden");
          $('#login-true > a').append("&nbsp;&nbsp" + result.name);
        } else {
          $('#login-false').removeClass("visually-hidden");
          $('#login-true').addClass("visually-hidden");
        }
      },
      error: function (xhr, status, error) {
        var err_msg = [
          `An error has occurred, most likely due to an attempt to execute`,
          `server-side scripts on a GitHub page, which is not permitted.`,
          `Please run this project on a PHP-supported server for seamless functionality.`,
          `\nThank you for your understanding and cooperation.\n\nIgnore:`
        ];
        console.error(err_msg.join(" "), error);
      }
    });

    //----Convert Root-Relative-Path To relative Links----//
    $('a[href^="/"], img[src^="/"]').each(function () {
      let filepath = $(this).attr('href');
      let imgpath = $(this).attr('src');
      // Update the href attribute with the relative path
      if (filepath) $(this).attr('href', baseURL + filepath);
      if (imgpath) $(this).attr('src', baseURL + imgpath)
    });
  });
});