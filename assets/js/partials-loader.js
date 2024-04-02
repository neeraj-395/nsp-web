import {baseURL, executePHP} from './inc-js/utils.inc.js';

//---------------------Load Header And Footer Into HTML Document---------------------//
$(function () {
  var header = $.Deferred();
  var footer = $.Deferred();
  $('#header').load(baseURL + "/partials/header.html", ()=> header.resolve()); // load header
  $('#footer').load(baseURL + "/partials/footer.html", ()=> footer.resolve()); // load footer

  $.when(header, footer).done(function () {
    /* CONFIRM WHETHER THE CURRENT USER IS LOGGED IN */
    const phpPath = baseURL + "/backend/auth-php/status.auth.php";
    const requestInit = { method: 'GET' };

    executePHP(phpPath, requestInit, (User)=>{
      if (User.isLoggedIn) {
        $('#login-false').addClass("visually-hidden");
        $('#login-true').removeClass("visually-hidden");
        $('#login-true > a').append("&nbsp;&nbsp" + User.name);
      } else {
        $('#login-false').removeClass("visually-hidden");
        $('#login-true').addClass("visually-hidden");
      }
    });
    
    /* COMBINE BOTH HEADER AND FOOTER TO SERACH BOTH SECTIONS */
    const searchBlock = $('#header').add('#footer');

    /* CONVERT ROOT-RELATIVE-PATH TO RELATIVE LINKS */
    searchBlock.find('a[href^="/"], img[src^="/"]').each(function () {
      const filepath = $(this).attr('href');
      const imgpath = $(this).attr('src');
      if (filepath) $(this).attr('href', baseURL + filepath);
      else $(this).attr('src', baseURL + imgpath);
    });
  });
});