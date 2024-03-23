// BASE URL FOR FETCHING CONTENT
export const baseURL = getBaseURL('/nsp-dbms-project'); // Define root folder name with forward slash (/) !importatant

function getBaseURL(rootFolderName) {
	if(window.location.pathname.includes(rootFolderName)){
	  return window.location.origin + rootFolderName;
	} else {
	  return window.location.origin;
	}
}

export const formSubmitHandler = (formData, phpPath)=>{
	fetch(phpPath, { method:'POST', body: formData })
	.then(response => {
		if (!response.ok)
			throw new Error('Network response was not ok');
		return response.json();
	})
	.then(result => {
    switch(result.status){
			case 200: if(result.message) alert(result.message);
				if(result.redirect) window.location.href = baseURL + result.redirect;
				else window.location.reload();
				break;
			case 500: if(result.message) alert(result.message); // Bad response
        if(result.redirect) window.location.href = baseURL + result.redirect;
				else window.location.reload();
				break;
			default: alert("Unexpected error occurred. Please try again later :-(");
				window.location.reload();
				break;
		}
	})
	.catch(error => {
		var err_msg = [
			`An error occurred while executing the PHP file. `,
			`Please ensure that you are not attempting to run this website on GitHub Pages.`,
			`\nWe apologize for any inconvenience :(`,
			`\nError: ${error.message}`
		];
		alert(err_msg.join(" "));
		window.location.reload();
	});
}

export const fileDropHandler = ()=>{
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

  droparea.addEventListener("drop", (e) => {
    const dt = e.dataTransfer;
    const files = dt.files;
    const fileArray = [...files];
    console.log(files); // FileList
    console.log(fileArray);
  });
}