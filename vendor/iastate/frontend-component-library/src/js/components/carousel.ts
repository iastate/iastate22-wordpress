import Flickity from "flickity";
import "flickity-imagesloaded";
import _debounce from "lodash.debounce";

const mobileMQ = window.matchMedia("(max-width: 992px)");

export class Carousel {
  private element: HTMLElement;
  private elementNavNext: HTMLElement;
  private elementNavPrevious: HTMLElement;
  private elementNavDots: HTMLElement;
  private carousel: Flickity;

  constructor(element: HTMLElement) {
    if (!!element && !!element.querySelector(".iastate22-carousel")) {
      this.element = element;
      this.elementNavNext = this.element.querySelector(".carousel__nav-next") as HTMLElement;
      this.elementNavPrevious = this.element.querySelector(".carousel__nav-previous") as HTMLElement;
      this.carousel = new Flickity(this.element.querySelector(".iastate22-carousel"), {
        contain: true,
        imagesLoaded: true,
        wrapAround: true,
        lazyLoad: 2,
        pageDots: false,
        prevNextButtons: false,
        adaptiveHeight: true,
      });
      this.createNavDots();
      this.addCarouselEventlisteners();
      this.handleNavDotWrapping();
    }
  }

  private addCarouselEventlisteners() {
    const navDots = this.elementNavDots.querySelectorAll("button") as NodeListOf<HTMLButtonElement>;
    // onClick
    this.elementNavNext.addEventListener("click", (event) => {
      this.carousel.next();
    });

    this.elementNavPrevious.addEventListener("click", (event) => {
      this.carousel.previous();
    });

    this.carousel.on("change", (index) => {
      const activeDot = this.elementNavDots.querySelector(".is-selected") as HTMLButtonElement;
      activeDot?.classList.remove("is-selected");
      navDots[index]?.classList.add("is-selected");
    });

    this.elementNavDots.addEventListener("click", (event) => {
      const target = event.target as HTMLElement;
      const parentLi = target.closest("li");
      const index = Array.from(this.elementNavDots.children).indexOf(parentLi);

      this.carousel.select(index);
    });
  }

  private createNavDots() {
    const ul = document.createElement("UL") as HTMLUListElement;
    ul.className = "carousel__nav-dots";
    for (let i = 0; i < this.carousel.cells.length; i++) {
      const li = document.createElement("LI") as HTMLLIElement;
      const button = document.createElement("BUTTON") as HTMLButtonElement;
      button.classList.add("dot");
      const label = document.createElement("SPAN") as HTMLElement;
      if (i === 0) {
        button.classList.add("is-selected");
      }
      label.className = "visible-for-screen-readers";
      label.textContent = `Navigate to slide ${i + 1}`;
      button.appendChild(label);
      li.appendChild(button);
      ul.appendChild(li);
    }
    this.elementNavDots = ul;
    this.element.appendChild(this.elementNavDots);
  }

  private handleNavDotWrapping() {
    const resize = () => {
      const navDotsScrollHeight = this.elementNavDots.scrollHeight;
      const navDotsPaddingTop = parseInt(
        window.getComputedStyle(this.elementNavDots, null).getPropertyValue("padding-top")
      );
      const navDotsActualHeight = navDotsScrollHeight - navDotsPaddingTop;
      const carouselContent = document.querySelector(".carousel__slide-content") as HTMLElement;
      if (this.elementNavDots) {
        if (mobileMQ.matches) {
          carouselContent.style.marginTop = navDotsActualHeight + 33 + "px";
          this.elementNavDots.style.top = "0";
        }
        if (!mobileMQ.matches) {
          this.elementNavDots.style.top = "-" + navDotsActualHeight / 2 + "px";
          carouselContent.style.marginTop = "16px";
        }
      }
    };

    window.addEventListener("resize", _debounce(resize, 100));
    resize();
  }
}

export default function carouselsInit() {
  const carousels = document.querySelectorAll(".iastate22-carousel-holder") as NodeListOf<HTMLElement>;
  for (let i = 0; i < carousels.length; i++) {
    new Carousel(carousels[i]);
  }
}
