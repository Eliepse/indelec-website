class Caroussel {
	constructor($caroussel) {
		this.$this = $caroussel;
		this.$list = $caroussel.querySelector(".caroussel__list");
		this.caret = 0;
		this.length = this.$this.querySelectorAll(".caroussel__item").length;

		this.autoplay = true;
		this.autoplayDelay = 3000;

		this.$this.querySelector(".caroussel__arrow--left").onclick = () => {
			this.stopAutoplay();
			this.movePrevious();
		};
		this.$this.querySelector(".caroussel__arrow--right").onclick = () => {
			this.stopAutoplay();
			this.moveNext();
		};

		if(this.autoplay) {
			this.startAutoplay();
		}
	}

	moveTo(index) {
		this.caret = Math.min(this.length - 1, Math.max(0, index));
		this.$list.style.left = -(this.$list.offsetWidth * this.caret) + "px";
		if (this.autoplay) {
			this.timer = setTimeout(() => {this.moveNext();}, this.autoplayDelay);
		}
	}

	moveNext() {
		if (this.caret >= this.length - 1) {
			this.moveTo(0);
			return;
		}
		this.moveTo(this.caret + 1);
	}

	movePrevious() {
		if (this.caret <= 0) {
			this.moveTo(this.length - 1);
			return;
		}
		this.moveTo(this.caret - 1);
	}

	startAutoplay() {
		this.autoplay = true;
		this.timer = setTimeout(() => {this.moveNext();}, this.autoplayDelay);
	}

	stopAutoplay() {
		this.autoplay = false;
		clearTimeout(this.timer);
	}
}

document.querySelectorAll(".caroussel").forEach($el => new Caroussel($el));