
function querySelector(element) {
	return document.querySelector(element)
}

function querySelectorAll(element) {
	return document.querySelectorAll(element)
}

let check = querySelector(".custom-check");

let toggleOpen = querySelector(".toggle-open");

let toggleClose = querySelector(".toggle-close");

let sidebar = querySelector(".sidebar");

let paymentAccount = querySelector("#payment_type");


function copyToClipBoard(e) {

	let copyInput = querySelector("#copyInput");

	copyInput.style.display = 'block'
	
	copyInput.select();

	 document.execCommand('copy');

	 e.innerHTML = "Copied"

	 copyInput.style.display = 'none'

	 setTimeout(() => e.innerHTML = "Copy Text", 1000)
}

paymentAccount?.addEventListener("change", function() {

	var btn = querySelector(".btn.proceed");
	
	if(this.value == "noblemerry_transfer"){

		querySelector(".modal-toggle").click()

		btn.style.display = "none"
	}else{
		btn.style.display = "inline-block"
	}
})


check?.addEventListener("change", function() {
	if(this.checked){
		querySelector(".header-right-list-wrapper").style.overflow = "unset"
	}else{
		querySelector(".header-right-list-wrapper").style.overflow = "hidden"
	}
})

toggleOpen?.addEventListener("click", e => {
	sidebar.classList.add("sidebar-show")
});

toggleClose?.addEventListener("click", e => {
	sidebar.classList.remove("sidebar-show")
});


/* TABS STARTS*/
function tabs() {
	let tabLists = window.innerWidth <= 992 ? querySelectorAll(".mobile-tab-lists") : querySelectorAll(".header-tab-lists");
	Array.from(tabLists)?.forEach(list => {
		list.addEventListener("click", e => {
			var current = e.target;
			var currentContent = querySelector(`#${current.dataset.tab}`); 
			performTab(current, window.innerWidth <= 992 ? "active" : "header-tab-lists-active" );
			performTab(currentContent, "profile-tabs-active")
		})
	})
}

tabs()

window.addEventListener("resize", tabs)

function performTab(current, element) {
	let activeTabLink = querySelector(`.${element}`);
		activeTabLink.classList.remove(element);
		current.classList.add(element);
}
/* TABS ENDS*/


/*DATATABLE STARTS*/
$('#table').dataTable({
	"aoColumnDefs": [
            { "bSortable": false, "aTargets": Array.from($("#table th")).map((_, ind) => ind)  }
        ],
})
/*DATATABLE ENDS*/





/*PREVENT FORM DOUBLE SUBMISSION START*/

	var form = document.querySelector("form");
	var clickCount = 0;
	form?.addEventListener('submit', function(e){
		clickCount++;
		if(clickCount > 1){
			e.preventDefault()
			form.querySelector("button").innerHTML = "Please wait...";
		}
		
	})
	
	/*PREVENT FORM DOUBLE SUBMISSION ENDS*/




/*SIDEBAR DROPDOWN UPDATES STARTS*/
var sideBarDropdownLinks = querySelectorAll(".sidebar-menu-link");
sideBarDropdownLinks.forEach(link => {
	link.addEventListener("click", function() {
		var dropMenuShow = querySelector(".dropdown-menus-show");
		var dropdownMenu = link.nextElementSibling;
		dropMenuShow != dropdownMenu && dropMenuShow?.classList.remove("dropdown-menus-show")
		dropdownMenu.classList.toggle("dropdown-menus-show")	
	})
})
/*SIDEBAR DROPDOWN UPDATES ENDS*/