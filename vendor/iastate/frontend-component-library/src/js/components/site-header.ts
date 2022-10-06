import _debounce from "lodash.debounce";
import Headroom from "headroom.js";
import AccessibilityUtilities from "../utilities/accessibility";

const mobileMQ = window.matchMedia("(max-width: 1200px)");

export class SiteHeader {
  private element: HTMLElement;
  private scroller: HTMLElement;
  private headroom: Headroom;
  private openButton: HTMLButtonElement;
  private focusableChildren: NodeListOf<HTMLElement>;
  private parentNavItems: NodeListOf<HTMLElement>;
  private searchBox: HTMLElement;
  private searchTrigger: HTMLElement;
  private closeSearchButton: HTMLButtonElement;
  private searchFormDesktop: HTMLFormElement;
  private formInput: HTMLFormElement;
  public visible: boolean = false;
  private selectedMainNavSectionIndex: number = null;
  private utilityDropdownTrigger: HTMLButtonElement;
  private utilityDropdownMenu: HTMLElement;
  private eventHandlers: any = {
    show: [],
    hide: [],
  };

  constructor(element: HTMLElement) {
    if (!!element) {
      this.element = element;
      this.scroller = this.element.querySelector(".site-header__mega-menu-scroller");
      this.openButton = AccessibilityUtilities.convertAnchorToButton(
        document.querySelector(`[aria-controls="${this.element.id}"]`)
      );
      this.focusableChildren = this.element.querySelectorAll("a, button, input");
      this.parentNavItems = this.element.querySelectorAll(".site-header__mega-menu-main-nav > ul > li");
      this.searchBox = this.element.querySelector(".site-header__search");
      this.searchTrigger = AccessibilityUtilities.convertAnchorToButton(
        document.querySelector(".site-header__search-toggle")
      );
      this.searchFormDesktop = document.querySelector(".site-header__search-form-desktop");
      this.closeSearchButton = AccessibilityUtilities.convertAnchorToButton(
        document.querySelector(".site-header__search-close")
      );
      this.formInput = document.querySelector(".site-header__search-form-desktop input[type='search']");
      this.utilityDropdownTrigger = document.querySelector(".site-header__utility-dropdown-trigger");
      this.utilityDropdownMenu = document.querySelector(".site-header__utility-dropdown-menu");

      this.init();
    }
  }

  private init() {
    this.handleResize();
    this.handleBodyPadding();
    this.initStickyNav();
    this.initiallyHideDropdowns();
    this.handleEsc();
    this.handleTabbing();
    this.handleOpenButtonClick();
    this.handleOutsideClick();
    this.handleMobileBackButtonClicks();
    this.handleParentLinkClicks();
    this.handleTransitionEnd();
    this.initMobileNav();
    this.toggleVisibility();
    this.handleSearch();
    this.handleUtilityDropdown();
  }

  private handleResize() {
    const resize = () => {
      if (!mobileMQ.matches && !this.visible && this.element.getAttribute("aria-hidden") === "true") {
        this.element.setAttribute("aria-hidden", "false");
      }
      if (mobileMQ.matches && !this.visible && this.element.getAttribute("aria-hidden") === "false") {
        this.element.setAttribute("aria-hidden", "true");
      }
    };
    window.addEventListener("resize", _debounce(resize, 100));
    resize();
  }

  private handleBodyPadding() {
    const resize = () => {
      const mainMenu = document.querySelector(".site-header") as HTMLElement;
      const headerTop = document.querySelector(".site-header__top") as HTMLElement;
      const megaMenuMain = document.querySelector(".site-header__mega-menu-main") as HTMLElement;
      const siteHeaderMain = document.querySelector(".site-header__main") as HTMLElement;
      const siteTitle = document.querySelector(".site-header__website-title") as HTMLElement;
      const mainContent = document.querySelector("main") as HTMLElement;

      if (mainContent) {
        if (mobileMQ.matches && mainMenu.classList.contains("site-header--ecosystem")) {
          mainContent.style.paddingTop = headerTop.offsetHeight + siteTitle.offsetHeight + "px";
        }
        if (!mobileMQ.matches && mainMenu.classList.contains("site-header--ecosystem")) {
          mainContent.style.paddingTop = headerTop.offsetHeight + siteHeaderMain.offsetHeight + "px";
        }
      }
    };

    window.addEventListener("resize", _debounce(resize, 100));
    resize();
  }

  private initStickyNav() {
    const siteHeaderRoot = document.querySelector(".site-header") as HTMLElement;
    this.headroom = new Headroom(siteHeaderRoot, {
      tolerance: 5,
    });
    this.headroom.init();
  }

  private initiallyHideDropdowns() {
    for (let i = 0; i < this.parentNavItems.length; i++) {
      this.toggleNavSectionVisibility(i, false);
    }
  }

  private handleEsc() {
    window.addEventListener("keydown", (event) => {
      const key = event.key || event.keyCode;
      // Close the nav when the esc key is pressed while it's open
      if (key === "Escape" || key === "Esc" || key === 27) {
        if (this.visible) {
          this.visible = false;
          this.toggleVisibility();
          this.openButton.focus();
        }
      }
    });
  }

  protected handleTabbing() {
    window.addEventListener("keyup", (event) => {
      const key = event.key || event.keyCode;
      // Close the nav when tabbing outside of it while it's open
      if (key === "Tab" || key === 9) {
        if (this.visible && !this.element.contains(event.target as HTMLElement)) {
          this.visible = false;
          this.toggleVisibility();
        }
      }
    });
  }

  private handleOpenButtonClick() {
    let clickedViaKeyboard = false as boolean;
    // Set flag used in click handler to determine if click was handled via keyboard
    this.openButton.addEventListener("keydown", (event) => {
      if (event.key === " " || event.key === "Enter" || event.key === "Spacebar") {
        clickedViaKeyboard = true;
      }
    });

    this.openButton.addEventListener("click", () => {
      this.visible = !this.visible;
      this.toggleVisibility();
      if (clickedViaKeyboard && this.visible) {
        setTimeout(() => {
          this.focusableChildren[0]?.focus();
        }, 50);
      }
      // Reset state for subsequent click events
      clickedViaKeyboard = false;
    });
  }

  private handleOutsideClick() {
    document.body.addEventListener("click", (event) => {
      const target = event.target as HTMLElement;

      if (
        !target.closest(".site-header") ||
        (target.classList.contains("site-header__mega-menu-scroller") && !mobileMQ.matches)
      ) {
        this.toggleNavSectionVisibility(this.selectedMainNavSectionIndex, false);
        this.selectedMainNavSectionIndex = null;
      }
    });
  }

  private handleMobileBackButtonClicks() {
    this.element.addEventListener("click", (event) => {
      const target = event.target as HTMLElement;
      if (!!target.closest(".site-header__mega-menu-main-nav-dropdown-back")) {
        this.toggleNavSectionVisibility(this.selectedMainNavSectionIndex, false);
        this.selectedMainNavSectionIndex = null;
      }
    });
  }

  private handleParentLinkClicks() {
    this.element.addEventListener("click", (event) => {
      const target = event.target as HTMLElement;
      const parentLink = target.closest(".site-header__mega-menu-main-nav-parent") as HTMLAnchorElement;
      if (!!parentLink) {
        event.preventDefault();
        const newIndex = parseInt(parentLink.dataset.index);
        const newLinkClicked = newIndex !== this.selectedMainNavSectionIndex;
        if (this.selectedMainNavSectionIndex !== null) {
          this.toggleNavSectionVisibility(this.selectedMainNavSectionIndex, false);
        }
        this.toggleNavSectionVisibility(newIndex, newLinkClicked);
        this.selectedMainNavSectionIndex = newLinkClicked ? newIndex : null;
      }
    });
  }

  private handleTransitionEnd() {
    this.element.addEventListener("transitionend", (event) => {
      if (event.target === event.currentTarget) {
        if (this.element.classList.contains("transitioning")) {
          this.element.classList.remove("transitioning");
        }
      }
    });
  }

  private initMobileNav() {
    for (let i = 0; i < this.parentNavItems.length; i++) {
      const parentItem = this.parentNavItems[i];
      const childList = parentItem.querySelector("ul") as HTMLElement;
      if (!!childList) {
        const clonedParentLI = document.createElement("LI") as HTMLElement;
        const backButtonLI = document.createElement("LI") as HTMLElement;
        const parentLink = parentItem.querySelector("a") as HTMLAnchorElement;
        const parentLinkClone = document.createElement("A") as HTMLAnchorElement;
        const backButton = document.createElement("BUTTON") as HTMLButtonElement;
        const parentLinkCloneArrow = document.createElement("SPAN") as HTMLElement;
        parentLink.classList.add("site-header__mega-menu-main-nav-parent");
        parentLink.dataset.index = `${i}`;
        backButtonLI.classList.add("site-header__mega-menu-main-nav-dropdown-back-wrap");
        clonedParentLI.classList.add("site-header__mega-menu-main-nav-dropdown-parent-wrap");
        backButton.classList.add("site-header__mega-menu-main-nav-dropdown-back");
        backButton.innerHTML = `
          <span class="site-header__mega-menu-main-nav-dropdown-back-icon" aria-hidden="true">&lt;</span>
          <span class="site-header__mega-menu-main-nav-dropdown-back-label">Back</span>
          <span class="visible-for-screen-readers"> to top level of menu</span>
        `;
        parentLinkClone.classList.add("site-header__mega-menu-main-nav-dropdown-parent", "iastate22-link-secondary");
        parentLinkCloneArrow.classList.add("arrow");
        parentLinkClone.textContent = Array.prototype.filter
          .call(parentLink.childNodes, (el) => el.nodeType === Node.TEXT_NODE)
          .map((el) => el.textContent)
          .join("");
        parentLinkClone.href = parentLink.getAttribute("href");
        backButtonLI.appendChild(backButton);
        if (parentLinkClone.getAttribute("href") !== "#") {
          clonedParentLI.appendChild(parentLinkClone);
          parentLinkClone.appendChild(parentLinkCloneArrow);
        } else {
          AccessibilityUtilities.convertAnchorToButton(parentLink);
          clonedParentLI.classList.add("site-header__mega-menu-main-nav-dropdown-parent-wrap-no-href");
        }
        childList.insertBefore(clonedParentLI, childList.firstElementChild);
        childList.insertBefore(backButtonLI, clonedParentLI);
      }
    }
  }

  public toggleVisibility() {
    if (mobileMQ.matches) {
      this.openButton.setAttribute("aria-expanded", `${this.visible}`);
      this.element.setAttribute("aria-hidden", `${!this.visible}`);
      this.element.classList.add("transitioning");
      const siteHeaderContainer = document.querySelector(".site-header");

      for (let i = 0; i < this.focusableChildren.length; i++) {
        this.focusableChildren[i].setAttribute("tabindex", this.visible ? "0" : "-1");
      }

      this.trigger(this.visible ? "show" : "hide");

      this.visible
        ? siteHeaderContainer.classList.add("site-header-open")
        : siteHeaderContainer.classList.remove("site-header-open");
    }
  }

  private toggleNavSectionVisibility(index: number, visible: boolean) {
    if (index !== null) {
      const parentItem = this.parentNavItems[index];
      const parentLink = parentItem.querySelector("button, a") as HTMLButtonElement | HTMLAnchorElement;
      const childList = parentItem.querySelector("ul") as HTMLElement;
      const focusableChildren = childList?.querySelectorAll("a, button") as NodeListOf<HTMLElement>;
      const secondaryMenu = document.querySelector(".site-header__mega-menu-secondary") as HTMLElement;
      if (!parentLink.classList.contains("site-header__parent-link-no-subnav")) {
        parentLink.setAttribute("aria-expanded", `${visible}`);
        childList.setAttribute("aria-hidden", `${!visible}`);
        if (mobileMQ.matches) {
          secondaryMenu.setAttribute("aria-hidden", `${visible}`);
        }
        for (let i = 0; i < focusableChildren.length; i++) {
          focusableChildren[i].setAttribute("tabindex", visible ? "0" : "-1");
        }
      }
    }
  }

  public on(eventType: string, handler: Function) {
    if (this.eventHandlers.hasOwnProperty(eventType)) {
      this.eventHandlers[eventType].push(handler);
    } else {
      throw new Error(`Event type "${eventType}" is not allowed for MegaMenu component.`);
    }
  }

  private trigger(eventType) {
    if (this.eventHandlers.hasOwnProperty(eventType)) {
      for (let i = 0; i < this.eventHandlers[eventType].length; i++) {
        this.eventHandlers[eventType][i]();
      }
    } else {
      throw new Error(`Event type "${eventType}" is not allowed for MegaMenu component.`);
    }
  }

  private handleSearch() {
    if (this.searchTrigger) {
      this.searchTrigger.setAttribute("aria-expanded", "false");
      this.searchFormDesktop.setAttribute("aria-hidden", "true");
      this.closeSearchButton.setAttribute("aria-hidden", "true");
      const utilityNav = document.querySelector(".site-header__utility");
      const formInput = document.querySelector("#searchDesktop");
      const formInputBtn = document.querySelector('.site-header__search-form-desktop button[type="submit"]');
      const formInputBtnSVG = document.querySelector(
        '.site-header__search-form-desktop button[type="submit"] .fa-iastate22-magnifying-glass'
      );
      this.searchTrigger.addEventListener("click", () => {
        this.searchTrigger.setAttribute("aria-expanded", "true");
        this.searchFormDesktop.setAttribute("aria-hidden", "false");
        this.closeSearchButton.setAttribute("aria-hidden", "false");
        this.searchFormDesktop.style.visibility = "visible";
        setTimeout(() => {
          this.formInput.focus();
        }, 300);
      });
      this.closeSearchButton.addEventListener("click", () => {
        this.searchTrigger.setAttribute("aria-expanded", "false");
        this.searchFormDesktop.setAttribute("aria-hidden", "true");
        this.closeSearchButton.setAttribute("aria-hidden", "true");
        setTimeout(() => {
          this.searchFormDesktop.style.visibility = "hidden";
        }, 300);
      });
      window.addEventListener("click", (e) => {
        const target = e.target as Element;
        if (
          this.searchFormDesktop.offsetWidth > 0 &&
          e.target != this.formInput &&
          e.target != formInputBtn &&
          e.target != formInputBtnSVG &&
          !target.closest('.site-header__search-form-desktop button[type="submit"] .fa-iastate22-magnifying-glass')
        ) {
          this.searchTrigger.setAttribute("aria-expanded", "false");
          this.searchFormDesktop.setAttribute("aria-hidden", "true");
          this.closeSearchButton.setAttribute("aria-hidden", "true");
          setTimeout(() => {
            this.searchFormDesktop.style.visibility = "hidden";
          }, 300);
        }
      });
    }
  }

  private handleUtilityDropdown() {
    if (this.utilityDropdownTrigger) {
      this.utilityDropdownTrigger.setAttribute("aria-expanded", "false");
      this.utilityDropdownMenu.setAttribute("aria-hidden", "true");
      const mainDropdownTriggers = document.querySelectorAll(".site-header__mega-menu-main-nav-parent") as NodeListOf<
        HTMLElement
      >;

      this.utilityDropdownTrigger.addEventListener("click", () => {
        if (this.utilityDropdownTrigger.getAttribute("aria-expanded") === "false") {
          this.utilityDropdownTrigger.setAttribute("aria-expanded", "true");
          this.utilityDropdownMenu.setAttribute("aria-hidden", "false");
          window.addEventListener("click", (e) => {
            if (e.target != this.utilityDropdownMenu && e.target != this.utilityDropdownTrigger) {
              this.utilityDropdownTrigger.setAttribute("aria-expanded", "false");
              this.utilityDropdownMenu.setAttribute("aria-hidden", "true");
            }
          });
          mainDropdownTriggers.forEach(function(link) {
            if (link.getAttribute("aria-expanded") === "true") {
              link.setAttribute("aria-expanded", "false");
              link.nextElementSibling.setAttribute("aria-hidden", "true");
            }
          });
          if (this.searchFormDesktop.getAttribute("aria-hidden") === "false") {
            this.searchTrigger.setAttribute("aria-expanded", "false");
            this.searchFormDesktop.setAttribute("aria-hidden", "true");
            this.closeSearchButton.setAttribute("aria-hidden", "true");
            setTimeout(() => {
              this.searchFormDesktop.style.visibility = "hidden";
            }, 300);
          }
        } else {
          this.utilityDropdownTrigger.setAttribute("aria-expanded", "false");
          this.utilityDropdownMenu.setAttribute("aria-hidden", "true");
        }
      });
    }
  }
}

export default function siteHeaderInit() {
  const header = document.querySelector(".site-header__mega-menu") as HTMLElement;
  new SiteHeader(header);
}
