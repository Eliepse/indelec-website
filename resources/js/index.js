"use strict";

import {checkScroll} from "./lib/scrollChecker";
import {unfakemail} from "./lib/fakemail";
import "./lib/caroussel";

window.addEventListener('scroll', checkScroll);
checkScroll();

document.querySelectorAll("a[fakemail]").forEach($link => unfakemail($link));