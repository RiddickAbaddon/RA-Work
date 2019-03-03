$('a[href*="#"]')
	.not('[href="#"]')
	.not('[href="#0"]')
	.click(function(event) {
	if (
		location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') 
		&& 
		location.hostname == this.hostname
	) {
		var link = $(this).attr('href');
		var element = document.querySelector(link);
		ScrollbarCustom.scrollIntoView(element, {
			offsetTop: 30,
			onlyScrollIfNeeded: false,
		});
	}
});