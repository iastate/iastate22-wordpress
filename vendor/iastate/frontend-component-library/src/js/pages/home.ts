import Flickity from "flickity";
import "flickity-imagesloaded";
import debounce from "lodash.debounce";
import Masonry from "masonry-layout";
import { Card, CardOptions } from "../components/card";

const mobileMQ = window.matchMedia("(max-width: 991px)");
const reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)");

export class PlayPauseAnimation {
  private element: HTMLElement;
  private animationContainer: HTMLElement;
  private animatedSVG: SVGSVGElement;

  constructor(element: HTMLElement) {
    if (!!element) {
      this.element = element;
      this.animationContainer = this.element.querySelector(".home-hero__animation");
      this.animatedSVG = this.element.querySelector(".home-hero__animation svg");
      this.init();
    }
  }

  private init() {
    if (!!this.animatedSVG) {
      this.pausePlayAnimatedSVG();
    }
  }

  private pausePlayAnimatedSVG() {
    if (reducedMotion.matches) {
      this.animatedSVG.pauseAnimations();
    }
  }
}

export class FeaturedStoryCarousel {
  private element: HTMLElement;
  private elementNavDots: HTMLElement;
  private slides: NodeListOf<HTMLElement>;
  private carousel: Flickity;

  constructor(element) {
    this.element = element;
    if (this.element && this.element.querySelector(".home-solutions__carousel")) {
      this.slides = this.element.querySelectorAll(".home-solutions__carousel .iastate22-card");
      this.init();
    }
  }

  private init() {
    this.handleResize();
  }

  private handleResize() {
    const resize = () => {
      this.convertToCarouselOnMobile();
      this.makeCardClickableOnDesktop();
      this.equalCarouselSlideHeight();
    };
    window.addEventListener("resize", debounce(resize, 100));
    resize();
  }

  private makeCardClickableOnDesktop() {
    const card = document.querySelectorAll(".iastate22-card--profile-with-modal") as NodeListOf<HTMLElement>;
    for (let i = 0; i < 3; i++) {
      if (!mobileMQ.matches) {
        card[i].setAttribute("data-clickable", "true");
      } else {
        card[i].setAttribute("data-clickable", "false");
      }
    }
  }

  private convertToCarouselOnMobile() {
    if (mobileMQ.matches && !this.carousel) {
      this.carousel = new Flickity(this.element.querySelector(".home-solutions__carousel"), {
        contain: true,
        imagesLoaded: true,
        lazyLoad: 2,
        dragThreshold: 0,
        wrapAround: false,
        prevNextButtons: false,
        adaptiveHeight: false,
        pageDots: false,
        cellAlign: "left",
      });
    }
    if (!mobileMQ.matches && !!this.carousel) {
      this.carousel.destroy();
      this.carousel = null;
    }
  }

  private equalCarouselSlideHeight() {
    if (mobileMQ.matches) {
      const flickityViewport = document.querySelector(
        ".home-solutions__carousel-holder .flickity-viewport"
      ) as HTMLElement;
      const flickitySlide = document.querySelectorAll(".home-solutions__carousel-slide") as any;
      setTimeout(() => {
        flickitySlide.forEach((slide) => {
          slide.style.height = flickityViewport.offsetHeight + "px";
        });
      }, 500);
    }
  }
}

export class NewsAndEvents {
  private element: HTMLElement;
  private eventsList: NodeListOf<HTMLElement>;
  private newsList: NodeListOf<HTMLElement>;
  private grid: HTMLElement;
  private masonry: Masonry;

  constructor(element: HTMLElement) {
    if (!!element) {
      this.element = element;
      this.eventsList = this.element.querySelectorAll(".home-events-news__event-list ul li");
      this.newsList = this.element.querySelectorAll(".home-events-news__news-list ul li");
      this.init();
    }
  }

  private init() {
    this.handleResize();
  }

  private handleResize() {
    const resize = () => {
      this.createMasonryGrid();
    };
    window.addEventListener("resize", debounce(resize, 100));
    resize();
  }

  private createMasonryGrid() {
    if (!mobileMQ.matches && !this.grid && !this.masonry) {
      const gridWrap = document.createElement("DIV") as HTMLElement;
      const grid = document.createElement("UL") as HTMLElement;
      gridWrap.classList.add("home-news-events-grid-wrap");
      grid.classList.add("home-news-events-grid");
      gridWrap.appendChild(grid);
      this.element.appendChild(gridWrap);
      for (let i = 0; i < 3; i++) {
        const newsClone = this.newsList[i].cloneNode(true) as HTMLElement;
        const eventsClone = this.eventsList[i].cloneNode(true) as HTMLElement;
        const newsImageWrap = document.createElement("DIV") as HTMLElement;
        newsImageWrap.classList.add("home-news-events-grid__image");
        const newsImage = document.createElement("IMG") as HTMLImageElement;
        newsImage.setAttribute("width", "461");
        newsImage.setAttribute("height", "321");
        newsImageWrap.appendChild(newsImage);
        newsImage.src = newsClone.dataset.image;
        newsImage.alt = "";
        newsClone.appendChild(newsImageWrap);
        newsClone.insertBefore(newsImageWrap, newsClone.firstElementChild);
        grid.appendChild(eventsClone);
        grid.appendChild(newsClone);
        new Card(newsClone, { clickable: true, titleClass: "home-events-news__item-title" });
        new Card(eventsClone, { clickable: true, titleClass: "home-events-news__item-title" });
      }
      this.grid = grid;

      this.masonry = new Masonry(this.grid, {
        gutter: 20,
        horizontalOrder: true,
        percentPosition: true,
      });
    }
  }
}

export default function homeInit() {
  const heroAnimationSection = document.querySelector(".home-hero") as HTMLElement;
  new PlayPauseAnimation(heroAnimationSection);

  const carousel = document.querySelector(".home-solutions__carousel-holder") as HTMLElement;
  new FeaturedStoryCarousel(carousel);

  const newsEventsSection = document.querySelector(".home-events-news") as HTMLElement;
  new NewsAndEvents(newsEventsSection);
}
