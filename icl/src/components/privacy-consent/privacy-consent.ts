import Cookies from "js-cookie";

declare global {
  interface Window {
    yett: any;
  }
}

const cookieKey = "privacy-accepted";

export class PrivacyConsent {
  protected element: HTMLElement;
  protected acceptButton: HTMLButtonElement;
  protected declineButton: HTMLButtonElement;
  protected toggleButton: HTMLButtonElement;
  protected userConsented: boolean = false;

  constructor(element: HTMLElement) {
    if (element) {
      this.element = element;
      this.acceptButton = element.querySelector(".privacy-consent__accept");
      this.declineButton = element.querySelector(".privacy-consent__decline");
      this.toggleButton = document.getElementById("privacy-toggle") as HTMLButtonElement;
      this.init();
    }
  }

  /**
   * Get the current page's domain without any subdomains
   * to use the privacy consent value across multiple sites
   * owned by the same company/organization.
   */
  protected get cookieDomain(): string {
    const blacklistedDomains = ["pantheonsite.io"];
    const domainParts = window.location.hostname.split(".") as string[];
    const cookieDomain = domainParts.slice(domainParts.length - 2).join(".");

    // Certain domains like pantheonsite.io prevent cookies from being shared
    // on the root domain. In those cases we need to include the subdomain.
    if (blacklistedDomains.indexOf(cookieDomain) === -1) {
      return cookieDomain;
    } else {
      return window.location.hostname;
    }
  }

  protected init() {
    this.checkForConsent();
    this.addAcceptListener();
    this.addDeclineListener();
    this.addToggleListener();
    if (this.userConsented) {
      this.setToggleState();
      window.addEventListener("DOMContentLoaded", () => {
        window.yett.unblock();
      });
    }
  }

  /**
   * Query local storage to check for previous consent by user.
   */
  protected checkForConsent() {
    const consented = Cookies.get(cookieKey, { domain: this.cookieDomain });
    if (!!consented) {
      this.userConsented = JSON.parse(consented);
    } else {
      // Show the element if the user hasn't provided a response yet
      this.element.removeAttribute("hidden");
    }
  }

  /**
   * Handles accept button click
   */
  protected addAcceptListener() {
    this.acceptButton.addEventListener("click", () => {
      this.handleAccept();
      this.setToggleState();
    });
  }

  /**
   * Sets the privacy-accepted value to true and loads privacy-dependent scripts.
   */
  protected handleAccept() {
    Cookies.set(cookieKey, "true", {
      domain: this.cookieDomain,
      expires: 365,
    });
    this.element.setAttribute("hidden", "hidden");
    window.yett.unblock();
  }

  /**
   * Handles decline button click
   */
  protected addDeclineListener() {
    this.declineButton.addEventListener("click", () => {
      this.handleDecline();
      this.setToggleState(false);
    });
  }

  /**
   * Sets the privacy-accepted value to false
   */
  protected handleDecline() {
    Cookies.set(cookieKey, "false", {
      domain: this.cookieDomain,
      expires: 365,
    });
    this.element.setAttribute("hidden", "hidden");
  }

  /**
   * Toggles privacy-accepted value on click
   */
  protected addToggleListener() {
    if (this.toggleButton) {
      this.toggleButton.addEventListener("click", () => {
        const pressState = this.toggleButton.getAttribute("aria-pressed");
        if (pressState === "true") {
          this.setToggleState(false);
          this.handleDecline();
        } else {
          this.setToggleState();
          this.handleAccept();
        }
      });
    }
  }

  /**
   * Updates privacy toggle aria-pressed attribute and inner html if toggle is on page
   */
  protected setToggleState(toPressed = true) {
    if (this.toggleButton) {
      if (toPressed) {
        this.toggleButton.setAttribute("aria-pressed", "true");
        this.toggleButton.innerHTML = "Tracking Cookies Enabled, Click to Disable";
      } else {
        this.toggleButton.setAttribute("aria-pressed", "false");
        this.toggleButton.innerHTML = "Tracking Cookies Disabled, Click to Enable";
      }
    }
  }

  /**
   * Logs any scripts that create cookies to the console for debugging
   * and determining which scripts need to be flagged.
   */
  public static findNonCompliantScripts() {
    let originalValue = document.cookie;
    Object.defineProperty(document, "cookie", {
      get() {
        return originalValue;
      },
      set(value) {
        console.trace();
        return (originalValue = value);
      },
    });
  }
}

export default function privacyConsentInit() {
  const widgets = document.querySelectorAll(".privacy-consent") as NodeListOf<HTMLElement>;
  for (let i = 0; i < widgets.length; i++) {
    new PrivacyConsent(widgets[i]);
  }
}
