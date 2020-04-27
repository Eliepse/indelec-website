"use strict";

import "lazysizes";
import {checkScroll} from "./lib/scrollChecker";
import "./lib/fakemail";
import "./lib/caroussel";

window.addEventListener('scroll', checkScroll);
checkScroll();