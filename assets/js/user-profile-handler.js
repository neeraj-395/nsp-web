import { executePHP, baseURL } from './inc-js/utils.inc.js';

document.addEventListener('DOMContentLoaded', ()=>{
  const userForm = extractFormElements('user-data', 'userForm-btns');
  const socialForm = extractFormElements('user-social-set', 'socialForm-btns');

  /* FETCHING USER PROFILE DATA TO DISPLAY ON THE WEB */
  const phpPath = baseURL + "/backend/user-php/retrieve.upd.php";
  const img_desc = document.querySelector('#img-desc');
  const requestInit = { method: 'GET' };

  executePHP(phpPath, requestInit, (data)=>{
    data = JSON.parse(data);
    img_desc.firstElementChild.innerText = data['name'];
    img_desc.lastElementChild.innerText = data['profession'];
    userForm.form.querySelector(`a[name='name']`).innerText = data['name'];
    userForm.form.querySelector(`a[name='email']`).innerText = data['email'];
    userForm.form.querySelector(`a[name='contact']`).innerText = data['contact'];
    userForm.form.querySelector(`a[name='profession']`).innerText = data['profession'];
    socialForm.form.querySelector(`a[name='github']`).innerText = data['github'];
    socialForm.form.querySelector(`a[name='twitter']`).innerText = data['twitter'];
    socialForm.form.querySelector(`a[name='instagram']`).innerText = data['instagram'];
    socialForm.form.querySelector(`a[name='reddit']`).innerText = data['reddit'];
  });

  /* SETUP USER PROFILE UPDATE OPTIONS */
  [userForm, socialForm].forEach(formObj=>{
    formObj.editBtn.addEventListener('click', ()=>{
      setInputElements(formObj);
    });
    formObj.resetBtn.addEventListener('click',()=>{
      resetFormElements(formObj);
    });
    formObj.form.addEventListener('submit', (event)=>{
      formSubmit(formObj.form, formObj.formData, event);
    });
  });
});

function extractFormElements(formId, formBtnsContainerId){
  const form = document.getElementById(formId);
  const btns = document.getElementById(formBtnsContainerId);
  return {
    form: form,
    inputs: form.querySelectorAll('input'),
    editBtn: btns.querySelector('button[type=button]'),
    submitBtn: btns.querySelector('button[type=submit]'),
    resetBtn: btns.querySelector('button[type=reset]'),
    formData: new FormData()
  }
}

const setInputElements = (formObj)=>{
  formObj.inputs.forEach(input=>{
    input.setAttribute('value',input.nextElementSibling.innerText);
    input.removeAttribute('hidden');
    input.nextElementSibling.setAttribute('hidden','');
    formObj.editBtn.setAttribute('hidden','');
    formObj.submitBtn.removeAttribute('hidden');
    formObj.resetBtn.removeAttribute('hidden');
    input.addEventListener('change', ()=>{
      if(input.value.length)
        formObj.formData.set(input.getAttribute('name'), input.value);
    });
  });
  const InValLen = formObj.inputs[0].value.length;
  formObj.inputs[0].setSelectionRange(InValLen, InValLen);
  formObj.inputs[0].focus();
}

const resetFormElements = (formObj)=>{
  formObj.inputs.forEach(input=>{
    input.setAttribute('hidden','');
    input.nextElementSibling.removeAttribute('hidden');
    formObj.editBtn.removeAttribute('hidden');
    formObj.submitBtn.setAttribute('hidden','');
    formObj.resetBtn.setAttribute('hidden','');
  });
}

const formSubmit = (form, formData, event)=>{
  // Prevent Deafault form submission.
  event.preventDefault();

  if(!form.checkValidity()){
    event.stopPropagation();
  } else {
    const spinner = document.getElementById('cover-spin');
    if(spinner) spinner.style.display = 'block';

    const phpPath = baseURL + form.getAttribute('php-execute');
    const requestInit = {
      method: 'POST',
      body: formData
    };
    executePHP(phpPath, requestInit);
  }
  form.classList.add('was-validated');
}