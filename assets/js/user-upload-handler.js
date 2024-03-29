import { executePHP, baseURL } from "./inc-js/utils.inc.js";

var note_file = null;
const droparea = {
  elem : null,
  icon : null,
  htext : null,
  stext : null,
  input : null
}

document.addEventListener('DOMContentLoaded', ()=>{
  const form = document.getElementById('upload-form');
  droparea.elem = document.querySelector('.droparea');
  droparea.icon = droparea.elem.querySelector('i');
  droparea.htext = droparea.elem.querySelector('h6');
  droparea.stext = droparea.elem.querySelector('small');
  droparea.input = droparea.elem.querySelector('input');

  droparea.elem.addEventListener('click',()=>{
    droparea.input.click();
  });

  ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(evtName => {
    droparea.elem.addEventListener(evtName, (e)=> e.preventDefault());
  });

  droparea.input.addEventListener('change', fileValidity);
  droparea.elem.addEventListener('drop', fileValidity);

  
  form.addEventListener('submit', (event)=>{
    event.preventDefault();

    const cover_img = document.getElementById('cover-img');
    const formData = new FormData(form);
    
    validate(cover_img, ()=>{
      const imgType = formData.get('cover-img').type;
      return imgType !== 'image/png';
    });

    validate(droparea.elem, ()=>{
      return note_file === null;
    });

    if(!form.checkValidity() || !note_file){
      event.stopPropagation();
    } else {
      const spinner = document.getElementById('cover-spin');
			if(spinner) spinner.style.display = 'block';

      formData.set('note-file', note_file);

			const phpPath = baseURL + form.getAttribute('php-execute');
			const requestInit = {
				method: 'POST',
				body:formData
			};
			executePHP(phpPath, requestInit);
    }
    form.classList.add('was-validated');
  });
});

function fileValidity(event) {
  const file = event.dataTransfer ? event.dataTransfer.files : event.target.files;
  if(file[0].type === 'application/pdf') {
    droparea.icon.classList.remove('text-danger');
    droparea.icon.classList.add('text-success');
    droparea.htext.textContent = 'File Selected!';
    droparea.stext.textContent = file[0].name;
    droparea.elem.classList.remove('is-invalid');
    droparea.elem.classList.add('is-valid');
    note_file = file[0];
  } else {
    droparea.icon.classList.remove('text-success');
    droparea.icon.classList.add('text-danger');
    droparea.htext.textContent = 'Invalid File Format!';
    droparea.stext.textContent = 'Only pdf format is allowed';
    droparea.elem.classList.remove('is-valid');
    droparea.elem.classList.add('is-invalid');
    note_file = null;
  }
}

const validate = function(element, callBack){
  if(callBack()){
    element.classList.remove('is-valid');
    element.classList.add('is-invalid'); // MAKE INVALID
  } else {
    element.classList.remove('is-invalid');
    element.classList.add('is-valid'); // MAKE VALID
  }
}