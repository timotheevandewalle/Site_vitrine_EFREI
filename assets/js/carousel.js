class Carousel {
    constructor(container) {
        this.container = container;
        this.track = container.querySelector('.carousel-track');
        this.slides = Array.from(this.track.children);
        this.nextBtn = container.querySelector('.next');
        this.prevBtn = container.querySelector('.prev');
        this.dotsContainer = container.querySelector('.carousel-dots');
        this.currentIndex = 0;
        this.slideWidth = this.slides[0].getBoundingClientRect().width;

        this.createDots();
        this.setSlidePosition();
        this.updateDots();

        this.nextBtn.addEventListener('click', () => this.moveToNext());
        this.prevBtn.addEventListener('click', () => this.moveToPrev());
        window.addEventListener('resize', () => {
            this.slideWidth = this.slides[0].getBoundingClientRect().width;
            this.setSlidePosition();
            this.moveToSlide(this.currentIndex);
        });
    }

    setSlidePosition() {
        this.slides.forEach((slide, index) => {
            slide.style.left = this.slideWidth * index + 'px';
        });
    }

    createDots() {
        this.slides.forEach((_, i) => {
            const dot = document.createElement('button');
            dot.classList.add('dot');
            dot.addEventListener('click', () => this.moveToSlide(i));
            this.dotsContainer.appendChild(dot);
        });
    }

    updateDots() {
        const dots = this.dotsContainer.children;
        Array.from(dots).forEach((dot, i) => {
            dot.classList.toggle('active', i === this.currentIndex);
        });
    }

    moveToSlide(index) {
        this.track.style.transform = `translateX(-${this.slideWidth * index}px)`;
        this.currentIndex = index;
        this.updateDots();
    }

    moveToNext() {
        if (this.currentIndex < this.slides.length - 1) {
            this.moveToSlide(this.currentIndex + 1);
        } else {
            this.moveToSlide(0);
        }
    }

    moveToPrev() {
        if (this.currentIndex > 0) {
            this.moveToSlide(this.currentIndex - 1);
        } else {
            this.moveToSlide(this.slides.length - 1);
        }
    }
}

// Initialiser le carrousel s'il existe
const carouselContainer = document.querySelector('.carousel-container');
if (carouselContainer) {
    new Carousel(carouselContainer);
}