// import { Collapse } from "bootstrap";
import "bootstrap";

export class IAStateAccordion {
  private element: HTMLElement;
  private firstAccordionTrigger: HTMLElement;

  constructor(element: HTMLElement) {
    if (!!element) {
      this.element = element;
      this.firstAccordionTrigger = this.element.querySelector(".accordion-item:first-child .accordion-button");
      this.init();
    }
  }

  private init() {
    this.openFirstAccordion();
  }

  private openFirstAccordion() {
    if (this.element.classList.contains("accordion--first-open")) {
      this.firstAccordionTrigger.click();
    }
  }
}

export default function accordionsInit() {
  const collapseEls = document.querySelectorAll(".accordion") as NodeListOf<HTMLElement>;
  for (let i = 0; i < collapseEls.length; i++) {
    return new IAStateAccordion(collapseEls[i]);
  }
}
