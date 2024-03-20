document.addEventListener("DOMContentLoaded", initApp);

document.addEventListener("DOMContentLoaded", ()=>{
});


function initApp () {
  const droparea = document.querySelector('.droparea');
  if(!droparea) return;
  const active = () => droparea.classList.add("green-border");
  const inactive = () => droparea.classList.remove("green-border");
  const prevents = (e) => e.preventDefault();

  ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(evtName => {
      droparea.addEventListener(evtName, prevents);
  });

  ['dragenter', 'dragover'].forEach(evtName => {
      droparea.addEventListener(evtName, active);
  });

  ['dragleave', 'drop'].forEach(evtName => {
      droparea.addEventListener(evtName, inactive);
  });

  droparea.addEventListener("drop", handleDrop);

}

const handleDrop = (e) => {
  const dt = e.dataTransfer;
  const files = dt.files;
  const fileArray = [...files];
  console.log(files); // FileList
  console.log(fileArray);
}

/*
const editBtn = document.querySelector('.edit-btn');
  editBtn.addEventListener("click",()=>{
    const userInputBlocks = document.querySelectorAll('.person .row > :nth-child(2)'); 
    userInputBlocks.forEach(inputBlock => {
      const name = inputBlock.getAttribute('name'); 
      const Data = inputBlock.textContent.trim();
      const input = `<input id="${name}" type="text" class="form-control" value="${Data}" required>`;
      inputBlock.innerHTML = input;
    });

    const newBtn = `<button id="changes" class="btn btn-primary px-4">Save Changes</button>`
    editBtn.parentElement.innerHTML= newBtn;

    document.getElementById('changes').addEventListener('click',()=>{
      const userInputBlocks = document.querySelectorAll('.person .row > :nth-child(2)');
      const inputData = {};
      userInputBlocks.forEach(inputBlock=>{
        let name = inputBlock.firstChild.getAttribute('name');
        let value = inputBlock.firstChild.getAttribute('value');
        inputData[name] = value;
      });
      console.log(inputData);
    });
  });
  */