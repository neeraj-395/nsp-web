import { baseURL, formSubmitHandler } from './inc-js/utils.inc.js';

document.addEventListener('DOMContentLoaded', ()=>{

  let submitBtnFlag = false; 
  const form = document.getElementById('user-details');
  const user_data = form.querySelectorAll('a[user-data]');
  const resetBtn = form.querySelector('button[type=reset]');
  const formBtnContainer =  resetBtn.parentElement.parentElement;

  const unHideSubBtn = ()=>{
    formBtnContainer.removeAttribute('hidden');
    formBtnContainer.previousElementSibling.removeAttribute('hidden');
    submitBtnFlag = true;
  }

  user_data.forEach(a=>{
    a.addEventListener('click', ()=>{
      a.setAttribute('hidden',''); // hide a element
      a.previousElementSibling.setAttribute('required','');
      a.previousElementSibling.removeAttribute('hidden'); // unhide input element
      a.previousElementSibling.focus();
      if(!submitBtnFlag) unHideSubBtn(); 
    });
  });

  resetBtn.addEventListener('click', ()=>{
    user_data.forEach(a=>{
      a.removeAttribute('hidden'); // unhide a element
      a.previousElementSibling.removeAttribute('required','');
      a.previousElementSibling.setAttribute('hidden',''); // hide input element
    });
    formBtnContainer.setAttribute('hidden','');
    formBtnContainer.previousElementSibling.setAttribute('hidden','');
    submitBtnFlag = false;
  });

  form.addEventListener('submit', (event)=>{
    event.preventDefault();

    if(!form.checkValidity()){
      event.stopPropagation();
    } else {
      const phpPath = baseURL + form.getAttribute('php-execute');
      console.log('hello');
      formSubmitHandler(new FormData(form), phpPath);
    }
    form.classList.add('was-validated');
  });
});