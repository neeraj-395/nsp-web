import { baseURL, executePHP, spinner } from "../inc-js/utils.inc.js";

const socialBaseUrl = {
  twitter : 'https://twitter.com/',
  instagram : 'https://instagram.com/',
  github : 'https://github.com/',
  reddit : 'https://reddit.com/'
}; 

document.addEventListener('DOMContentLoaded', ()=>{
  const userForm = extractFormElements('user-data', 'userForm-btns');
  const socialForm = extractFormElements('user-social-set', 'socialForm-btns');

  /* FETCHING USER PROFILE DATA TO DISPLAY ON THE WEB */
  const phpPath = baseURL + "/backend/user-php/retrieve.upd.php";
  const img_desc = document.querySelector('#img-desc');
  const requestInit = { method: 'GET' };

  executePHP(phpPath, requestInit, (data)=>{
    img_desc.firstElementChild.innerText = data['name'];
    img_desc.lastElementChild.innerText = data['profession'];
    initUserDetail(userForm.form, data);
    initSocialURL(socialForm.form, data);
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
    /* INTIALIZE SPINNER */
    if(spinner) spinner.style.display = 'block';

    const phpPath = baseURL + form.getAttribute('php-action');
    const requestInit = {
      method: 'POST',
      body: formData
    };
    executePHP(phpPath, requestInit);
  }
  form.classList.add('was-validated');
}

function initSocialURL(socialForm, data) {
  Object.keys(socialBaseUrl).forEach(platform => {
    const url = socialBaseUrl[platform];
    const a = socialForm.querySelector(`a[name=${platform}]`);
    a.innerHTML = data[platform];
    a.setAttribute('href',url + data[platform]);
    a.setAttribute('target','_blank');
  });
}

function initUserDetail(userForm, data){
  userForm.querySelectorAll('a[name]').forEach(a=>{
    const name = a.getAttribute('name');
    a.innerHTML = data[name];
  });
}