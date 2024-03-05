function note_cards(note_desc, time, view_link, download_link){
    return `
    <div class="col">
    <div class="card shadow-sm">
      <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text></svg>
        <div class="card-body">
          <p class="card-text">`+ note_desc +`</p>
          <div class="d-flex justify-content-between align-items-center">
            <div class="btn-group">
              <a role="button" class="btn btn-sm btn-outline-secondary" href="`+ view_link +`">View</a>
              <a role="button" class="btn btn-sm btn-outline-secondary" href="`+ download_link +`">Download</a>
            </div>
            <small class="text-body-secondary">`+ time +`</small>
          </div>
        </div>
      </div>
    </div>`;
}

container = document.getElementById('cards');
let desc =  "This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.";
let time = "9 min"
let vlink = "#";
let dlink = "#";

for (let i = 0; i < 9; i++) {
    let card = note_cards(desc,time,vlink,dlink);
    container.innerHTML += card;
}