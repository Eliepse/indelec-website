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
	$link.href = "mailto:" + html.replace(/(<!--.*-->\s?|<span[a-z=": ;]*>.*<\/span>)+/gmi, "");
}

checkScroll();
document.querySelectorAll("a[fakemail]").forEach($link => unfakemail($link));