import debounce from "lodash.debounce";
const desktopMQ = window.matchMedia("(max-width: 1024px)");

export class AlertBar {
  private element: HTMLElement;
  private siteHeader: HTMLElement;

  constructor(element: HTMLElement) {
    if (!!element) {
      this.element = element;
      this.siteHeader = document.querySelector(".max-bound .site-header") as HTMLElement;
      this.init();
    }
  }

  private init() {
    console.log(this.element.offsetHeight);
    this.setOffset();
    window.addEventListener("resize", () => {
      this.setOffset();
    });
  }

  private setOffset() {
    this.siteHeader.style.top = this.element.offsetHeight + "px";
  }
}

export default function alertBarInit() {
  const alertBar = document.querySelector(".home-announcement") as HTMLElement;

  new AlertBar(alertBar);
}
