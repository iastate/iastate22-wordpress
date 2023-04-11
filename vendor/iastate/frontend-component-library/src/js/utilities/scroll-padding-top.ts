import _debounce from "lodash.debounce";

export class ScrollPaddingTop {
  private element: HTMLElement;
  private htmlRoot: HTMLElement;
  private styleSheet: HTMLStyleElement;

  constructor(element: HTMLElement) {
    if (!!element) {
      this.element = element;
      this.htmlRoot = document.querySelector("html");
      this.styleSheet = document.createElement("style");
      this.init();
    }
  }

  private init() {
    this.handleResize();
  }

  private handleResize() {
    const resize = () => {
      this.handleScrollPadding();
    };

    window.addEventListener("resize", _debounce(resize, 100));
    resize();
  }

  private handleScrollPadding() {
    const siteHeaderHeight = this.element.offsetHeight;
    this.styleSheet.innerHTML = `html { scroll-padding-top: ${siteHeaderHeight}px }`;
    document.head.appendChild(this.styleSheet);
  }
}

export default function scrollPaddingTopInit() {
  var ecosystemSiteHeader = document.querySelector(".site-header--ecosystem") as HTMLElement;
  new ScrollPaddingTop(ecosystemSiteHeader);
}
