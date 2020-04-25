"use strict";

import Axios from "axios";
import {checkScroll} from "./lib/scrollChecker";
import {unfakemail} from "./lib/fakemail";
import "./lib/caroussel";

window.addEventListener('scroll', checkScroll);
checkScroll();

document.querySelectorAll("a[fakemail]").forEach($link => unfakemail($link));

document.querySelector("form[action='/contact']")
	.addEventListener("submit", (event) => {
		const $form = event.target;
		const data = {};

		$form.querySelectorAll("input,textarea")
			.forEach(($el) => {
				console.debug($el.getAttribute("name") + ": " + $el.value);
				data[$el.getAttribute("name")] = $el.value;
			});

		Axios.post('/contact', data);
		event.preventDefault();
	});