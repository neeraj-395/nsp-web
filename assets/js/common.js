// BASE URL FOR FETCHING CONTENT
var baseURL = window.location.protocol + "//" + window.location.host;

//---------------------Load Header And Footer Into HTML Document---------------------//
$(function(){
  $('#symbol').load(baseURL + "/assets/img/symbols.svg"); // load essentials symbols
  $('#header').load(baseURL + "/partials/header.html", function() {
    //-------Check The Login Status of Current User-------//
    $.ajax({
      url: baseURL + "/backend/status.inc.php",
      dataType: 'json',
      success: function(result) {
        if (result.isLoggedIn) {
          var div = $('#status');
          div.find('a').eq(0).attr('href', '#').text('Profile');
          div.find('a').eq(1).attr('href', baseURL + "/backend/logout.inc.php").text('Log Out');
        }
      },
      error: function(xhr, status, error) {
        console.error('There was a problem with the fetch operation:', error);
      }
    });
    
    //----Convert Root-Relative-Path To Absolute Links----//
    $('a[href^="/"], img[src^="/"]').each(function() {
      var filepath = $(this).attr('href');
      var imgpath = $(this).attr('src');
      // Update the href attribute with the relative path
      if(filepath) $(this).attr('href', baseURL + filepath);
      if(imgpath) $(this).attr('src', baseURL + imgpath)
    });
  }); // load header
  //-----------------------------------------------------//
  $('#footer').load(baseURL + "/partials/footer.html"); // load footer
});

document.addEventListener('DOMContentLoaded', ()=>{
//---------------------Load Notes Content into HTML Document---------------------//

    // Add note cards along with thier meta data
    if(container = document.getElementById('recent-notes')) {
        const meta = {
            title : "Card title",
            desc : "This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.",
            upload_time : "Last updated 3 mins ago",
            path : baseURL + "/assets/img/note-img.jpg"
        }

        for (let i = 0; i < 6; i++)
            container.innerHTML += recent_notes(meta.title,meta.desc,meta.upload_time,meta.path);

    }
    // Add note cards along with thier meta data
    if(container = document.getElementById('content'))  {
        const meta = {
            desc : "This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.",
            upload_time : "9 min",
            vlink : "#", // view link
            dlink : "#" // download link
        }

        for (let i = 0; i < 9; i++)
          container.innerHTML += search_notes(meta.desc,meta.upload_time,meta.vlink,meta.dlink);
    }
});


//___________________ Utils ___________________ //

function recent_notes(title, desc, time, img_link) {
    return `
    <div class="container-sm col">
        <div class="card mb-3" style="max-width: 540px;">
            <div class="row g-0">
                <div class="col-md-4">
                    <img src="${img_link}" class="img-fluid rounded-start" alt="...">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title">${title}</h5>
                        <p class="card-text">${desc}</p>
                        <p class="card-text"><small class="text-body-secondary">${time}</small></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    `;
}

function search_notes(note_desc, time, view_link, download_link) {
    return `
    <div class="col">
        <div class="card shadow-sm">
          <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text></svg>
            <div class="card-body">
              <p class="card-text">${note_desc}</p>
              <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group">
                  <a role="button" class="btn btn-sm btn-outline-secondary" href="${view_link}">View</a>
                  <a role="button" class="btn btn-sm btn-outline-secondary" href="${download_link}">Download</a>
                </div>
                <small class="text-body-secondary">${time}</small>
              </div>
            </div>
          </div>
        </div>
    </div>
    `;
}