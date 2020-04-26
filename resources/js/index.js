"use strict";

import yall from "yall-js";
import {checkScroll} from "./lib/scrollChecker";
import {unfakemail} from "./lib/fakemail";
import "./lib/caroussel";

window.addEventListener('scroll', checkScroll);
checkScroll();

document.querySelectorAll("a[fakemail]").forEach($link => unfakemail($link));

document.addEventListener("DOMContentLoaded", function() {
	yall({}); // Todo: Add lazy-loading for inline background-images
});