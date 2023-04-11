import _debounce from "lodash.debounce";

const mobileMQ = window.matchMedia("(max-width: 1200px)");

export class Subnav {
  private element: HTMLElement;
  private toggle: HTMLButtonElement;
  private list: HTMLElement;
  private expanded: boolean = false;

  constructor(element: HTMLElement) {
    if (!!element) {
      this.element = element;
      this.list = this.element.querySelector(".subnav__list");

      this.init();
    }
  }

  private init() {
    this.createMobileToggle();
    this.handleResize();
    this.setHorizontalVariantAttributes();
    this.handleToggleClick();
    this.handleListTransition();
  }

  private handleResize() {
    const resize = () => {
      if (mobileMQ.matches) {
        this.toggle.setAttribute("aria-expanded", `${this.expanded}`);
        this.list.setAttribute("aria-labelledby", "subnav-toggle");
        this.list.setAttribute("aria-hidden", `${!this.expanded}`);
      } else {
        this.list.style.height = null;
        this.list.removeAttribute("aria-labelledby");
        this.list.removeAttribute("aria-hidden");
      }
    };
    if (!this.element.classList.contains("subnav--horizontal")) {
      window.addEventListener("resize", _debounce(resize, 150));
      resize();
    }
  }

  private setHorizontalVariantAttributes() {
    if (this.element.classList.contains("subnav--horizontal")) {
      this.toggle.setAttribute("aria-expanded", `${this.expanded}`);
      this.list.setAttribute("aria-labelledby", "subnav-toggle");
      this.list.setAttribute("aria-hidden", `${!this.expanded}`);
    }
  }

  private handleListTransition() {
    this.list.addEventListener("transitionend", (event) => {
      if (event.propertyName === "height") {
        this.list.style.height = null;
      }
    });
  }

  private handleToggleClick() {
    this.toggle.addEventListener("click", () => {
      this.expanded = !this.expanded;
      this.toggleVisibility();
    });
  }

  private createMobileToggle() {
    this.toggle = document.createElement("BUTTON") as HTMLButtonElement;
    this.toggle.className = "subnav__toggle";
    this.toggle.id = "subnav-toggle";
    this.toggle.innerHTML = `<span>Explore This Section</span>`;
    this.toggle.setAttribute("aria-controls", "subnav-list");
    this.toggle.setAttribute("aria-haspopup", "true");

    this.element.insertBefore(this.toggle, this.element.children[0]);
  }

  private toggleVisibility() {
    if (this.expanded) {
      this.list.style.height = `${this.list.scrollHeight}px`;
    } else {
      this.list.style.height = `${this.list.scrollHeight}px`;
      setTimeout(() => {
        this.list.style.height = null;
      }, 50);
    }

    this.toggle.setAttribute("aria-expanded", `${this.expanded}`);
    this.list.setAttribute("aria-hidden", `${!this.expanded}`);
  }
}

export default function subnavInit() {
  new Subnav(document.querySelector(".subnav"));
}
