// BASE URL FOR FETCHING CONTENT
var baseURL = getBaseURL('/nsp-dbms-project'); // Define root folder name with forward slash (/) !importatant

//---------------------Load Header And Footer Into HTML Document---------------------//
$(function () {
  var header = $.Deferred();
  var footer = $.Deferred();
  $('#header').load(baseURL + "/partials/header.html", function () { header.resolve(); }); // load header
  $('#footer').load(baseURL + "/partials/footer.html", function () { footer.resolve(); }); // load footer

  $.when(header, footer).done(function () {
    //-------Check The Login Status of Current User-------//
    $.ajax({
      url: baseURL + "/backend/status.inc.php",
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
          `\nThank you for your understanding and cooperation.\n Ignore:`
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
  //---------------------Load Notes Content into HTML Document---------------------//
  if ($('#content')) {
    const meta = {
      title: "MATT RIDLEY",
      desc: "This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.",
      time: "3 mins ago",
      path: baseURL + "/assets/img/note-img.jpg",
      vlink : "#",
      dlink : "#"
    }

    // Add note cards along with there meta data
    for (let i = 0; i < 6; i++)
      $('#content').append(note_cards(meta));
  }
});


//___________________ Utils ___________________ //

function note_cards(meta) {
  return `
    <div class="container-sm col">
        <div class="card mb-3" style="max-width: 540px;">
            <div class="row g-0">
                <div class="col-md-4">
                    <img src="${meta.path}" class="img-fluid rounded-start" alt="...">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title">${meta.title}</h5>
                        <p class="card-text">${meta.desc}</p>
                        <div class="d-flex justify-content-between align-items-center">
                          <div class="btn-group">
                            <a role="button" class="btn btn-sm btn-outline-secondary" 
                            href="${meta.vlink}">View</a>
                            <a role="button" class="btn btn-sm btn-outline-secondary" 
                            href="${meta.dlink}">Download</a>
                          </div>
                          <small class="text-body-secondary">${meta.time}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    `;
}
function getBaseURL(rootFolderName) {
  if (window.location.pathname.includes(rootFolderName)) {
    return window.location.origin + rootFolderName;
  } else {
    return window.location.origin;
  }
}