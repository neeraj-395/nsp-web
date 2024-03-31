import {baseURL} from './inc-js/utils.inc.js';

/* RETRIEVE NOTE-META DATA TO SHOW INTO WEB PAGE */



//---------------------Load Notes Content into HTML Document---------------------//
$(function(){
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
})

function note_cards(meta) {
  return `
    <div class="container-sm col">
        <div class="card mb-3" style="max-width: 540px;">
            <div class="row g-0">
                <div class="col-md-4">
                    <img src="${meta.path}" class="img-fluid rounded-start">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title">${meta.title}</h5>
                        <p class="card-text">${meta.desc}</p>
                        <div class="d-flex justify-content-between align-items-center">
                          <div class="btn-group">
                            <a role="button" class="btn btn-sm btn-outline-secondary" 
                            href="${meta.vlink}" target="__blank">View</a>
                            <a role="button" class="btn btn-sm btn-outline-secondary" 
                            href="${meta.dlink}" download>Download</a>
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