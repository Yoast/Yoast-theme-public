function setStickyElements() {

	for (var i = 0; i < window.stickyEls.length; i++) {
		stickyEl= window.stickyEls[i];

		if(window.matchMedia) {

			// Sticky on desktop
			if (window.matchMedia("(min-width: 56em)").matches ) {
				if( stickyEl.hasAttribute("data-sticky-desktop") ) {
					setToSticky(stickyEl, i);
				} else {
					resetSticky(stickyEl, i);
				}
			} 
			
			//Sticky on "mobile"
			if (window.matchMedia("(max-width: 48em)").matches ) {
				if( stickyEl.hasAttribute("data-sticky-mobile") ) {
					setToSticky(stickyEl, i);
				} else {
					resetSticky(stickyEl, i);
				}
			} 
		}
	}
}

function setToSticky(stickyEl, i) {
	var translateY;

	if(stickyEl.getAttribute("data-sticky") == "pushup") {
		pushEl= document.querySelector('[data-push-sticky]');
		topPos= 0;
	} 

	if(stickyEl.getAttribute("data-sticky") != "stacked" && stickyEl.getAttribute("data-sticky") != "pushup" ) {
		topPos= 0;
	}

	// Stack against the navigation
	if(stickyEl.hasAttribute("data-sticky-stacked")) {
		//if (window.matchMedia("(max-width: 48em)").matches ) {
		//	prevPos= document.querySelector('.masthead').getBoundingClientRect();
		//	topPos= prevPos.bottom;
		//} else {
			prevPos= document.querySelector('.subnav').getBoundingClientRect();
			topPos= prevPos.bottom;
		//}
	}

	// The anchor position determines if the 
	// element is sticky or not.
	stickyAnchor= stickyEl.previousElementSibling; // [data-banner-sticky-anchor]

	if(stickyEl.hasAttribute('data-sticky-keep')) {
		stickyAnchor= false;
	}

	if(stickyAnchor) {
		elOffset= stickyAnchor.getBoundingClientRect();
		if( elOffset.top <= topPos) {
			stickyAnchor.style.height= getOuterHeight(stickyEl) + "px";
			stickyEl.classList.add('is-sticky');
			stickyEl.style.top= topPos + "px";

		} else {
			stickyEl.classList.remove('is-sticky');
			stickyEl.style.top= "";
			stickyAnchor.style.height= '0';
		}
	} 

	if(typeof pushEl != "undefined" && stickyEl.getAttribute("data-sticky") == "pushup") {
		pushOffset= pushEl.getBoundingClientRect();

		translateY = pushOffset.top-getOuterHeight(stickyEl);

		if( pushOffset.top < stickyEl.offsetHeight && translateY > 0 ) {

			stickyEl.style.webkitTransform= "translateY("+(translateY) + "px)";
			stickyEl.style.transform= "translateY("+(translateY) + "px)";
		}

	}

	return stickyEl;
}

function resetSticky(stickyEl) {
	stickyEl.classList.remove('is-sticky');
	stickyEl.style.top= "";

	stickyEl.previousElementSibling.style.height= '0'; //Sticky Anchor

	return stickyEl;
}
