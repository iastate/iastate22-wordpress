export class Search {
  private element: HTMLElement;
  private form: HTMLFormElement;
  private activeLetter: HTMLInputElement;
  private alphaBar: HTMLElement;
  private letterButtons: NodeListOf<HTMLAnchorElement>;
  private resetButton: HTMLButtonElement;

  constructor(element: HTMLElement) {
    if (!!element) {
      this.element = element;
      this.form = this.element as HTMLFormElement;
      this.activeLetter = this.element.querySelector("input[name=search_letter]");
      this.alphaBar = document.querySelector(".a-z-index-letter-list");
      this.resetButton = this.element.parentElement.querySelector(".filter-form-results button[type=reset]");
      this.init();
    }
  }

  private init() {
    if (!!this.alphaBar) {
      this.letterButtons = this.alphaBar.querySelectorAll("a");
      this.letterButtons.forEach((el, i) => {
        el.addEventListener("click", (e) => {
          this.searchLetter(e);
        });
      });
    }

    if (!!this.resetButton) {
      this.resetButton.addEventListener("click", (e) => {
        this.reset(e);
      });
    }
  }

  private searchLetter(e) {
    e.preventDefault();
    this.activeLetter.setAttribute("value", e.target.textContent);
    this.form.submit();
  }

  private reset(e) {
    e.preventDefault();
    let srch = this.element.querySelector("input#s"),
      selct = this.element.querySelectorAll("select");
    srch.setAttribute("value", "");
    if (!!this.activeLetter) {
      this.activeLetter.setAttribute("value", "");
    }
    selct.forEach((el, i) => {
      el.querySelectorAll("option").forEach((o, i) => {
        o.removeAttribute("selected");
      });
      el.value = "";
      el.selectedIndex = 0;
    });
    this.form.reset();
    this.form.submit();
  }
}

export default function searchInit() {
  const filter = document.querySelector("#searchform") as HTMLElement;
  new Search(filter);
}
