import { baseURL, executePHP, spinner } from "../inc-js/utils.inc.js";

var fileObject = null;
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
    formData.set('note-file', fileObject);
    
    validate(cover_img, ()=>{
      const imgType = formData.get('cover-img').type;
      return imgType !== 'image/png';
    });

    if(!fileObject){
      handleInvalidFile('Please select a PDF file!',
                        'Minimum allowed file size is 20MB.');
      event.stopPropagation();
    } else if(!form.checkValidity()){
      event.stopPropagation();
    } else {
      /* INTIALIZE SPINNER */
      if(spinner) spinner.style.display = 'block';

			const phpPath = baseURL + form.getAttribute('php-action');
			const requestInit = {
				method: 'POST',
				body: formData
			};
      formData.forEach((value,key)=>console.log(key + ' => ' + value));
			executePHP(phpPath, requestInit);
    }
    form.classList.add('was-validated');
  });
});

function fileValidity(event) {
  const file = event.dataTransfer ? event.dataTransfer.files : event.target.files;
  const _20MB = 20*1024*1024;
  if(file[0].type === 'application/pdf' && file[0].size < _20MB) {
    handleValidFile(file[0]);
  } else if(file[0].size > _20MB) {
    handleInvalidFile('Invalid File Size!',
                      'Minimum allowed file size is 20MB.');
  } else {
    handleInvalidFile('Invalid File Format!',
                      'Only pdf format is allowed');
  }
}

function validate(element, callBack) {
  if(callBack()){
    element.classList.remove('is-valid');
    element.classList.add('is-invalid'); // MAKE INVALID
  } else {
    element.classList.remove('is-invalid');
    element.classList.add('is-valid'); // MAKE VALID
  }
}

function handleInvalidFile(htext, stext) {
  droparea.icon.classList.remove('text-success');
  droparea.icon.classList.add('text-danger');
  droparea.elem.classList.remove('is-valid');
  droparea.elem.classList.add('is-invalid');
  droparea.htext.textContent = htext;
  droparea.stext.textContent = stext;
  fileObject = null;
}

function handleValidFile(file) {
  droparea.icon.classList.remove('text-danger');
  droparea.icon.classList.add('text-success');
  droparea.htext.textContent = 'File Selected!';
  droparea.stext.textContent = file.name;
  droparea.elem.classList.remove('is-invalid');
  droparea.elem.classList.add('is-valid');
  fileObject = file;
}