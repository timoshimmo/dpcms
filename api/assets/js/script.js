
let toggleOpen = document.querySelector(".toggle-open");

let toggleClose = document.querySelector(".toggle-close");

let navMenu = document.querySelector(".navigation-menu");

let passwordToggle = document.querySelectorAll(".toggle-password");

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

passwordToggle?.forEach(eyeIcon => {
	eyeIcon.addEventListener("click", () => {
		var inputElement = eyeIcon.previousElementSibling;	
		if( inputElement.type == "password" ){

				 inputElement.type = "text"
				 eyeIcon.classList.remove("ri-eye-fill")
				 eyeIcon.classList.add("ri-eye-off-fill")

			}else{
				inputElement.type = "password"
				eyeIcon.classList.add("ri-eye-fill")
				eyeIcon.classList.remove("ri-eye-off-fill")

			}
	})
	
})

toggleOpen?.addEventListener("click", e => {
	navMenu.classList.add("navigation-show");
	document.body.style.overflowY = "hidden"
})


toggleClose?.addEventListener("click", e => {
	navMenu.classList.remove("navigation-show");
	document.body.style.overflowY = ""
})

var visionSwiper = new Swiper(".mySwiper", {
		        spaceBetween: 60,
		        breakpoints: {
		          480: {
		            slidesPerView: 2,
		            spaceBetween: 40,
		          },
		          768: {
		            slidesPerView: 2.5,
		            spaceBetween: 40,
		          },
		           1024: {
		            slidesPerView: 3,
		            spaceBetween: 40,
		          }
		        }
      	})

var teamSwiper = new Swiper(".teamSwiper", {
		        breakpoints: {
		          320: {
		            slidesPerView: 1.1,
		            spaceBetween: 30,
		          },
		          768: {
		            slidesPerView: 3.5,
		            spaceBetween: 40,
		          },
		           1024: {
		            slidesPerView: 4,
		            spaceBetween: 50,
		          }
		        }
  })


var howItWorksswiper = new Swiper(".howItWorksSwiper", {
        slidesPerView: 5,
        spaceBetween: 30,
        freeMode: true,
        breakpoints: {
		          320: {
		            slidesPerView: 1.6,
		            spaceBetween: 5,
		          },
		          768: {
		            slidesPerView: 4
		          },
		           1024: {
		            slidesPerView: 5
		          }
		        }
      })

var testimonialSwiper = new Swiper(".testimonialSwiper", {
					slidesPerView: 1,
		            spaceBetween: 30,
		            navigation: {
			          nextEl: ".next-pagination",
			          prevEl: ".prev-pagination"
			        }    
  });

let panelList = document?.querySelectorAll(".faqs-panel-top");

Array.from(panelList)?.forEach(panel => {
	panel.addEventListener("click", e => {
		panelBody = e.target.closest(".faqs-panel-top").nextElementSibling;

		panelBody.style.height = panelBody.style.height ? "" : panelBody.scrollHeight + "px"
	})
})

/**
   * Animation on scroll
   */
  window.addEventListener('load', () => {
    AOS.init({
      duration: 1000,
      easing: 'ease-in-out',
      once:true,
      mirror: false
    })
  });





let tabLists = document.querySelectorAll(".howItWorksSwiper .swiper-slide");

Array.from(tabLists)?.forEach(list => {
	list.addEventListener("click", e => {
		var current = e.target;
		var currentContent = document.querySelector(`#${current.dataset.id}`); 
		performTab(current, "tabs-active" );
		performTab(currentContent, "how-it-works-grid-list-active")
	})
})


function performTab(current, element) {
	let activeTabLink = document.querySelector(`.${element}`);
		activeTabLink.classList.remove(element);
		current.classList.add(element);
}
  
    
  
