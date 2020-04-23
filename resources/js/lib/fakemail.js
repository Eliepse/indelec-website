export const unfakemail = $link => {
	$link.href = "mailto:" + $link.innerHTML.replace(/(<!--.*-->\s?|<span[a-z=": ;]*>.*<\/span>)+/gmi, "");
};