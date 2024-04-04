import { baseURL, executePHP, spinner } from "../inc-js/utils.inc.js";

document.addEventListener('DOMContentLoaded',()=>{
  const tupleContainer = document.getElementById('table-content');
  const empMsg = document.getElementById('empMsg');

  /* FETCH USER UPLOADED NOTE META-DATA */
  const phpPath = baseURL + '/backend/user-php/retrieve.unt.php';
  const requestInit = { method: 'GET' };
  
  executePHP(phpPath, requestInit, (tuples)=>{
    if(!tuples.length) return;
    else empMsg.setAttribute('hidden','');

    tuples.forEach(tuple => {
      tupleContainer.innerHTML += note_tuple(tuple);
    });

    const deleteBtns = tupleContainer.querySelectorAll('a[delete-note-id]');
    deleteBtns.forEach((button)=>{
      button.addEventListener('click', ()=>{
          /* OPEN SPINNER WINDOW */
			    if(spinner) spinner.style.display = 'block';
      
          const noteId = button.getAttribute('delete-note-id');
          const phpPath = baseURL + '/backend/user-php/delete.unt.php?note_id=' + noteId;
          executePHP(phpPath, requestInit);
      });
    });
  });

});

function note_tuple(meta) {
  return `
  <tr>
    <td>${meta.note_id}</td>
    <td>${meta.title}</td>
    <td>${meta.upload_date}</td>
    <td>${meta.upload_time}</td>
    <td class="text-end">
      <a href="${baseURL + meta.note_path}" target="__blank" class="btn btn-outline-info btn-rounded">
        <i class="bi bi-eye-fill fs-5"></i>
      </a>
      <a class="btn btn-outline-danger btn-rounded" delete-note-id="${meta.note_id}">
        <i class="bi bi-trash-fill fs-5"></i>
      </a>
    </td>
  </tr>`;
}
