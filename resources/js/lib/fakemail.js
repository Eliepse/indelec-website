export const unfake = ($el) => $el.innerHTML.replace(/(<!--.*-->\s?|<span[a-z=": ;]*>.*<\/span>)+/gmi, "");

document.querySelectorAll("a[scrambledMail]").forEach($link => $link.href = "mailto:" + unfake($link));