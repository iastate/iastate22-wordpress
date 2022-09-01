export default class AccessibilityUtilities {
  private static userTabbing: boolean = false;

  public static get focusableChildSelector(): string {
    return `a[href], button, input, textarea, select, details, [tabindex]:not([tabindex="-1"])`;
  }

  public static init() {
    this.handleKeydown();
    this.handleMouseDown();
    this.disableTabbingWithinHiddenElements();
  }

  private static handleKeydown() {
    window.addEventListener("keydown", (event) => {
      this.applyKeyboardStylesOnTab(event);
    });
  }

  private static handleMouseDown() {
    window.addEventListener("mousedown", () => {
      this.removeKeyboardStylesOnMousedown();
    });
  }

  /**
   * Add class to html element to selectively apply styles for keyboard users vs mouse users
   */
  private static applyKeyboardStylesOnTab(event) {
    const key = event.key || event.keyCode;
    if (key === "Tab" || (key === 9 && !this.userTabbing)) {
      this.userTabbing = true;
      document.documentElement.classList.add("user-tabbing");
    }
  }

  /**
   * Remove class from html element to selectively apply styles for keyboard users vs mouse users
   */
  private static removeKeyboardStylesOnMousedown() {
    if (this.userTabbing) {
      this.userTabbing = false;
      document.documentElement.classList.remove("user-tabbing");
    }
  }

  public static convertAnchorToButton(anchor: HTMLAnchorElement): HTMLButtonElement {
    if (!!anchor) {
      const button = document.createElement("BUTTON") as HTMLButtonElement;

      // Copy attributes from anchor to button
      for (let i = 0; i < anchor.attributes.length; i++) {
        const attribute = anchor.attributes[i];
        // Exclude irrelevant attributes from being added to button
        if (attribute.name !== "href") {
          button.setAttribute(attribute.name, attribute.value);
        }
      }

      // Copy child elements from anchor to button
      for (let i = 0; i < anchor.childNodes.length; i++) {
        button.appendChild(anchor.childNodes[i].cloneNode(true));
      }

      anchor.parentElement.replaceChild(button, anchor);
      return button;
    }
  }

  /**
   * Use a MutationObserver to watch for elements with aria-hidden or hidden attribute changes
   * and deterministically update tabindex for focusable children of the target element.
   */
  private static disableTabbingWithinHiddenElements() {
    const observer = new MutationObserver((mutations: MutationRecord[]) => {
      for (let i = 0; i < mutations.length; i++) {
        const mutation = mutations[i] as MutationRecord;
        if (
          mutation.type === "attributes" &&
          mutation.attributeName !== null &&
          ["aria-hidden", "hidden"].indexOf(mutation.attributeName) !== -1 &&
          mutation.target.nodeType === Node.ELEMENT_NODE
        ) {
          const isHidden =
            (mutation.attributeName === "hidden" && mutation.oldValue === null) ||
            (mutation.attributeName === "aria-hidden" && (mutation.oldValue === "false" || mutation.oldValue === null));
          const target = mutation.target as HTMLElement;
          const focusableChildren = target.querySelectorAll(this.focusableChildSelector) as NodeListOf<HTMLElement>;
          for (let i = 0; i < focusableChildren.length; i++) {
            const child = focusableChildren[i] as HTMLElement;
            // Prevent unintentionally toggling tabindex for children of nested hidden elements
            if (child.closest("[aria-hidden]") === target) {
              child.tabIndex = isHidden ? -1 : 0;
            }
          }
        }
      }
    });
    observer.observe(document.body, {
      subtree: true,
      attributes: true,
      attributeOldValue: true,
    });
  }
}

export class ToggleTip {
  private element: HTMLElement;
  private trigger: HTMLButtonElement;
  private popup: HTMLElement;
  private popupClose: HTMLButtonElement;
  private popupContent: HTMLElement;
  private visible: boolean = false;

  constructor(element: HTMLElement) {
    if (!!element) {
      this.element = element;
      this.trigger = AccessibilityUtilities.convertAnchorToButton(this.element.querySelector(".toggle-tip__trigger"));
      this.popup = this.element.querySelector(".toggle-tip__popup");
      this.popupClose = AccessibilityUtilities.convertAnchorToButton(
        this.element.querySelector(".toggle-tip__popup-close")
      );
      this.popupContent = this.element.querySelector(".toggle-tip__popup-content");
      this.init();
    }
  }

  private init() {
    this.handleTriggerClick();
    this.handlePopupCloseClick();
    this.handleEsc();
    this.handleClickOutside();
    this.toggleVisibility();
  }

  private handleTriggerClick() {
    let clickedViaKeyboard = false;

    this.trigger.addEventListener("keydown", (event) => {
      if (event.key === " " || event.key === "Enter" || event.key === "Spacebar") {
        clickedViaKeyboard = true;
        this.visible = true;
        this.toggleVisibility(true);
      }
    });

    this.trigger.addEventListener("click", () => {
      clickedViaKeyboard = false;
      this.visible = true;
      this.toggleVisibility();
    });
  }

  private handlePopupCloseClick() {
    let clickedViaKeyboard = false;

    this.trigger.addEventListener("keydown", (event) => {
      if (event.key === " " || event.key === "Enter" || event.key === "Spacebar") {
        clickedViaKeyboard = true;
        this.visible = false;
        this.toggleVisibility(true);
      }
    });

    this.popupClose.addEventListener("click", () => {
      clickedViaKeyboard = false;
      this.visible = false;
      this.toggleVisibility();
    });
  }

  private handleEsc() {
    this.element.addEventListener("keydown", (event) => {
      const key = event.key || event.keyCode;
      // Close the nav when the esc key is pressed while it's open
      if (key === "Escape" || key === "Esc" || key === 27) {
        if (this.visible && this.element.contains(event.target as HTMLElement)) {
          this.visible = false;
          this.toggleVisibility(true);
        }
      }
    });
  }

  private handleClickOutside() {
    document.addEventListener("click", (event) => {
      if (!this.element.contains(event.target as HTMLElement)) {
        this.visible = false;
        this.toggleVisibility();
      }
    });
  }

  private toggleVisibility(viaKeyboard: boolean = false) {
    const popupFocusableChildren = this.popup.querySelectorAll("a, button, [tabindex]") as NodeListOf<HTMLElement>;
    this.popup.setAttribute("aria-hidden", `${!this.visible}`);
    for (let i = 0; i < popupFocusableChildren.length; i++) {
      popupFocusableChildren[i].setAttribute("tabindex", this.visible ? "0" : "-1");
    }
    if (viaKeyboard) {
      if (this.visible) {
        setTimeout(() => {
          this.popupContent.focus();
        }, 50);
      } else {
        setTimeout(() => {
          this.trigger.focus();
        }, 50);
      }
    }
  }
}
