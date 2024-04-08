import { baseURL, executePHP, spinner } from './inc-js/utils.inc.js';

//---------------------Load Notes Content into HTML Document---------------------//
document.addEventListener('DOMContentLoaded', () => {
  const content = document.getElementById('content');
  const empMsg = document.getElementById('empMsg');
  const phpPath = baseURL + '/backend/retrieve.cont.php';
  const requestInit = { method: 'GET' };
  executePHP(phpPath, requestInit, (note_meta) => {
    handleNoteMeta(note_meta, content, empMsg);
  });

  const search = document.getElementById('search');
  if(!search) return;
  else search.addEventListener('submit',(event)=>{
    event.preventDefault();

    if(!search.checkValidity()){
      event.stopPropagation();
    } else {
      /* INTIALIZE SPINNER */
      if(spinner) spinner.style.display = 'block';

      const query = search.querySelector('input').value;
      const phpPath = baseURL + '/backend/retrieve.cont.php'
                    + '?query=' + query;
      executePHP(phpPath, requestInit, (note_meta)=>{
        handleNoteMeta(note_meta, content, empMsg);
      })
      .then(()=>{
        if(spinner) spinner.style.display = 'none';
      });
    }
  });
});

function note_cards(meta) {
  const uploadDate = new Date(meta.upload_date + 'T' + meta.upload_time);
  const timeAgoUpload = getTimeAgo(uploadDate);
  return `
    <div class="container-sm col">
      <div class="card mb-3" style="max-width: 540px;">
        <div class="row g-0">
          <div class="col-md-4">
              <img src="${baseURL + meta.cover_path}" 
                   class="img-fluid rounded-start">
          </div>
          <div class="col-md-8">
            <div class="card-body">
              <h5 class="card-title">${meta.title}</h5>
              <p class="card-text">${meta.description}</p>
              <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group">
                  <a role="button" class="btn btn-sm btn-outline-secondary" 
                  href="${meta.note_path}" target="__blank">View</a>
                  <a role="button" class="btn btn-sm btn-outline-secondary" 
                  href="${meta.note_path}" download>Download</a>
                </div>
                <small class="text-body-secondary">${timeAgoUpload}</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>`;
}

function getTimeAgo(uploadDate) {
  const currentDate = new Date();
  const timeDifference = currentDate.getTime() - uploadDate.getTime(); // Difference in milliseconds

  // Convert the difference to seconds
  const secondsDifference = Math.floor(timeDifference / 1000);

  if (secondsDifference < 60) {
    return secondsDifference + ' seconds ago';
  } else if (secondsDifference < 3600) {
    const minutes = Math.floor(secondsDifference / 60);
    return minutes === 1 ? '1 minute ago' : minutes + ' minutes ago';
  } else if (secondsDifference < 86400) {
    const hours = Math.floor(secondsDifference / 3600);
    return hours === 1 ? '1 hour ago' : hours + ' hours ago';
  } else {
    const days = Math.floor(secondsDifference / 86400);
    return days === 1 ? '1 day ago' : days + ' days ago';
  }
}

function handleNoteMeta(note_meta, content, empMsg){
  content.innerHTML = '';
  if (!note_meta.length){
    empMsg.removeAttribute('hidden');
    return;
  } else empMsg.setAttribute('hidden', '');
  note_meta.forEach(meta => {
    content.innerHTML += note_cards(meta);
  });
}