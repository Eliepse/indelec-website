class Caroussel {
	constructor($caroussel) {
		this.$this = $caroussel;
		this.$list = $caroussel.querySelector(".caroussel__list");
		this.caret = 0;
		this.length = this.$this.querySelectorAll(".caroussel__item").length;

		this.$this.querySelector(".caroussel__arrow--left").onclick = () => this.movePrevious();
		this.$this.querySelector(".caroussel__arrow--right").onclick = () => this.moveNext();
	}

	moveTo(index) {
		this.caret = Math.min(this.length - 1, Math.max(0, index));
		this.$list.style.left = -(this.$list.offsetWidth * this.caret) + "px";
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
}

export default (() => {
	document.querySelectorAll(".caroussel").forEach($el => new Caroussel($el));
})();