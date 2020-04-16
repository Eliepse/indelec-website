"use strict";

import "./lib/caroussel";

window.addEventListener('scroll', checkScroll);

function checkScroll() {
	if (window.scrollY > 50) {
		document.body.classList.add('scrolled');
	} else {
		document.body.classList.remove('scrolled');
	}
}

function unfakemail($link) {
	const html = $link.innerHTML;
	$link.href = "mailto:" + html.replace(/(<!--.*-->|<span[a-z=": ;]*>.*<\/span>)+/gi, "");
}

checkScroll();
document.querySelectorAll("a[fakemail]").forEach($link => unfakemail($link));