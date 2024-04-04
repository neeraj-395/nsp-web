/* LOADING SPINNER */
export const spinner = document.getElementById('cover-spin');

// BASE URL FOR FETCHING CONTENT
export const baseURL = getBaseURL('/nsp-dbms-project'); // Define root folder name with forward slash (/) !importatant

export function executePHP(phpPath, requestInit, callBack) {
	fetch(phpPath, requestInit)
	.then(response => {
		if (!response.ok)
			throw new Error('Network response was not ok');
		return response.json();
	})
	.then(result => {
    switch(result.status){
			case 200: if(result.message) alert(result.message); /* GOOD RESPONSE */
				if(result.redirect) window.location.href = baseURL + result.redirect;
				else window.location.reload();
				break;
			case 300: callBack(result.data); /* DATA RESPONSE */
				break;
			case 500: if(result.message) alert(result.message); /* BAD RESPONSE */
        if(result.redirect) window.location.href = baseURL + result.redirect;
				else window.location.reload();
				break;
			default: alert(result);
				window.location.reload();
				break;
		}
	})
	.catch(error => {
		var err_msg = [
			`An error occurred while executing the PHP file. `,
			`Please ensure that you are not attempting to run this website on GitHub Pages.\n`,
			`We apologize for any inconvenience [' . ']\n`,
			`Error: ${error.message}`
		];
		console.error(error.message);
		alert(err_msg.join(''));
	})
	.finally(()=>{
		/* CLOSE THE SPINNER WINDOW*/
		if(document.readyState === 'complete') {
			console.log('spinner off');
			if(spinner) spinner.style.display = 'none';
		}
	});
}

function getBaseURL(rootFolderName) {
	if(window.location.pathname.includes(rootFolderName)){
	  return window.location.origin + rootFolderName;
	} else {
	  return window.location.origin;
	}
}