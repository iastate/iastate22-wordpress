import { Calendar, EventContentArg, formatDate } from "@fullcalendar/core";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import listPlugin from "@fullcalendar/list";

const MobileMQ = window.matchMedia("(max-width: 990px)");

declare const MYSCRIPT: any;

type NetworkEvent = {
  id: number;
  media_details: {
    sizes: {
      medium: {
        source_url: string;
      };
    };
  };
};

export class EventCalendar {
  private element: HTMLElement;
  private eventCalendar: HTMLElement;
  private calendarHeader: HTMLElement;
  private calendarSearch: HTMLInputElement;
  private calendarCategories: HTMLSelectElement;
  private calendarSearchButton: HTMLElement;
  private featuredCheck: HTMLInputElement;
  private monthButton: HTMLButtonElement;
  private listButton: HTMLButtonElement;
  private calendar: Calendar;
  private desktopView: string;
  private pageUrl: string;
  private apiRoot: string;
  private perPage: number;
  private totalCount: number;
  private totalPages: number;
  private currentPage: number;
  private searchTerms: Array<string>;
  private searchReset: HTMLButtonElement;
  private cachedMedia: Map<number, NetworkEvent>;

  constructor(element: HTMLElement) {
    if (!!element) {
      this.element = element;
      this.eventCalendar = this.element.querySelector(".calendar__wrap");
      this.calendarHeader = this.element.querySelector(".calendar__header");
      this.listButton = this.calendarHeader.querySelector(".calendar__views #list-btn");
      this.monthButton = this.calendarHeader.querySelector(".calendar__views #month-btn");
      this.calendarSearch = this.element.querySelector(".calendar__search form #event-search");
      this.calendarCategories = this.element.querySelector(".calendar__categories #event-categories");
      this.calendarSearchButton = this.element.querySelector(".calendar__search form button[type=submit]");
      this.featuredCheck = this.element.querySelector(".calendar__categories-toggle #featured-events");
      this.searchReset = this.element.querySelector(".calendar-reset");
      this.desktopView = "dayGridMonth";
      this.searchTerms = ["", ""];
      this.cachedMedia = new Map();
      this.currentPage = 1;
      this.apiRoot = "wp/v2/";
      this.perPage = 25;
      this.init();
    }
  }

  private init() {
    this.calendar = new Calendar(this.eventCalendar, {
      plugins: [dayGridPlugin, timeGridPlugin, listPlugin],
      initialView: "dayGridMonth",
      eventTimeFormat: {
        hour: "numeric",
        minute: "2-digit",
        meridiem: "short",
      },
      headerToolbar: {
        left: "prev,next today",
        center: "title",
        right: "",
      },
      nextDayThreshold: "00:00:00",
      eventMouseEnter: function(eventHovering) {
        if (eventHovering.view.type === "dayGridMonth") {
          eventHovering.el.classList.add("event-active");
          let moreInfo = eventHovering.el.querySelector(".event-listing");
          moreInfo.classList.add("on");
          setTimeout(() => {
            moreInfo.classList.add("active");
          }, 50);
        }
      },
      eventMouseLeave: function(eventHovering) {
        if (eventHovering.view.type === "dayGridMonth") {
          eventHovering.el.classList.remove("event-active");
          let moreInfo = eventHovering.el.querySelector(".event-listing");
          moreInfo.classList.remove("active");
          setTimeout(() => {
            moreInfo.classList.remove("on");
          }, 300);
        }
      },
      eventDidMount: (eventMount) => {
        //this.fixHeaders(eventMount.view.type);
      },
      eventContent: (eventContent) => {
        let arrayOfDomNodes = [this.buildEventDOM(eventContent)];
        return { domNodes: arrayOfDomNodes };
      },
      viewDidMount: (viewMount) => {},
    });
    this.calendar.render();

    // console.log(MYSCRIPT);
    // Forces the API location to look for the lando site if editing in Fractal
    if (window.location.host.startsWith("localhost")) {
      this.pageUrl = window.location.protocol + "//isu-wp-composer.lndo.site/wp-json/";
    } else {
      this.pageUrl = MYSCRIPT.rootURL;
    }

    this.localFetch("events", { page: 1, per_page: this.perPage })
      .then((response) => this.initCalendar(response, null))
      .catch((error) => console.log(error));

    this.listButton.addEventListener("click", (event) => {
      this.changeCalendar(event.target, "listWeek");
    });
    this.monthButton.addEventListener("click", (event) => {
      this.changeCalendar(event.target, "dayGridMonth");
    });
    this.calendarSearchButton.addEventListener("click", (event) => {
      this.runSearch(event);
    });
    this.calendarCategories.addEventListener("change", (event) => {
      this.runSearch(event);
    });
    this.featuredCheck.addEventListener("change", (event) => {
      this.runSearch(event);
    });
    this.searchReset.addEventListener("click", (event) => {
      this.resetSearch(event);
    });
    window.addEventListener("resize", () => {
      this.breakpointCheck();
    });
    this.breakpointCheck();
  }

  private initCalendar(response: Response, search: string | URLSearchParams) {
    this.totalCount = Number(response.headers.get("X-WP-Total"));
    this.totalPages = Number(response.headers.get("X-WP-TotalPages"));
    // Tag check from URL Parameter
    let rootSearch = window.location.search,
      responseURL = new URL(response.url);
    const SearchParams = new URLSearchParams(rootSearch);

    new URLSearchParams(responseURL.search).forEach((value, key) => {
      SearchParams.set(key, value);
    });
    if (search !== null) {
      new URLSearchParams(search).forEach((value, key) => {
        SearchParams.set(key, value);
      });
    }
    if (!SearchParams.has("page")) {
      SearchParams.set("page", this.currentPage.toString());
    }
    if (!SearchParams.has("per_page")) {
      SearchParams.set("per_page", this.perPage.toString());
    }

    // We already have the first request so lets use it
    response.json().then((json) => this.tallyItems(json));

    let currentPage = Number(SearchParams.get("page"));
    for (var pageNumber = currentPage + 1; pageNumber <= this.totalPages; pageNumber++) {
      SearchParams.set("page", `${pageNumber}`);
      this.localFetch("events", SearchParams)
        .then((response) => response.json())
        .then((json) => this.tallyItems(json));
    }
  }

  private getFetchURL(url: string, params: object | URLSearchParams = {}): URL {
    let baseURL = `${this.pageUrl}${this.apiRoot}`;
    if (!(params instanceof URLSearchParams) && Object.keys(params).length === 0) {
      return new URL(`${url}`, baseURL);
    }
    let searchParams = params instanceof URLSearchParams ? params : new URLSearchParams(Object.entries(params));
    return new URL(`${url}?${searchParams}`, baseURL);
  }

  private async tallyItems(json) {
    await this._primeMediaCache(json);
    json.forEach((item) => {
      if (this.featuredCheck.checked === true) {
        if (item.acf.featured === true) {
          this.aggregateEntry(item);
        }
      } else {
        this.aggregateEntry(item);
      }
    });
  }

  private localFetch(url: string, params = {}): Promise<Response> {
    return fetch(this.getFetchURL(url, params).toString());
  }

  private async getFeaturedMedia(mediaId: number): Promise<NetworkEvent | null> {
    if (mediaId <= 0) {
      return new Promise<null>((resolve) => {
        resolve(null);
      });
    }
    let imageId = Number(mediaId);
    if (this.cachedMedia.has(imageId)) {
      return new Promise<NetworkEvent>((resolve) => {
        resolve(this.cachedMedia.get(imageId));
      });
    }
    await this.localFetch("media/" + imageId)
      .then((response) => response.json())
      .then((json) => {
        this.cachedMedia.set(imageId, json);
      })
      .catch((error) => console.log(error));

    return new Promise<NetworkEvent | null>((resolve) => {
      resolve(this.cachedMedia.get(imageId));
    });
  }

  private async aggregateEntry(item) {
    if (item === undefined || item === null) {
      return;
    }
    let newEvent: {
        start: string;
        end: string;
        title: string;
        resourceId: number;
        description: string;
        url: string;
        allDay: boolean;
        location: string;
        overlap: boolean;
        thumbnail?: string;
        interactive: boolean;
        eventTags: string[];
      },
      featuredImg: NetworkEvent;

    newEvent = {
      start: item.acf.event_start_date.start_date,
      end: item.acf.event_end_date.end_date,
      title: item.title.rendered,
      resourceId: item.id,
      description: item.excerpt.rendered,
      url: item.link,
      allDay: item.acf.event_start_date.full_day,
      location: item.acf.location.length > 0 ? item.acf.location : "",
      overlap: true,
      thumbnail: null,
      interactive: true,
      eventTags: [],
    };
    featuredImg = await this.getFeaturedMedia(item.featured_media);

    if (featuredImg !== null && Object.keys(featuredImg).length !== 0) {
      newEvent.thumbnail = featuredImg.media_details.sizes.medium.source_url;
    }
    if (item.acf.event_start_date.start_time !== null) {
      newEvent.start = item.acf.event_start_date.start_date + "T" + item.acf.event_start_date.start_time;
    }
    if (item.acf.event_end_date.end_date === null) {
      newEvent.end = item.acf.event_start_date.start_date + "T" + item.acf.event_end_date.end_time;
    } else {
      newEvent.end = item.acf.event_end_date.end_date + "T" + item.acf.event_end_date.end_time;
    }

    if (item.event_tags.length > 0) {
      item.event_tags.forEach((tag: string, index: any) => {
        this.localFetch("event_tags/" + tag)
          .then((response) => response.json())
          .then((json) => newEvent.eventTags.push(json));
      });
    }

    this.calendar.addEvent(newEvent);
  }

  private runSearch(event: Event) {
    event.preventDefault();
    let searchParams: object = {
      page: 1,
      per_page: this.perPage,
    };
    this.searchTerms[0] = this.calendarSearch.value;
    this.searchTerms[1] = this.calendarCategories.value;
    // This feels dirty
    if (this.searchTerms[1] !== "") {
      if (this.searchTerms[0] !== "") {
        searchParams["search"] = this.searchTerms[0];
        searchParams["event_tags"] = this.searchTerms[1];
      } else {
        searchParams["event_tags"] = this.searchTerms[1];
      }
    } else if (this.searchTerms[0] !== "") {
      searchParams["search"] = this.searchTerms[0];
    }
    this.calendar.removeAllEvents();
    this.localFetch("events", searchParams)
      .then((response) => this.initCalendar(response, null))
      .catch((error) => console.log(error));
    this.listButton.addEventListener("click", (event) => {
      this.changeCalendar(event.target, "listWeek");
    });
  }

  private breakpointCheck() {
    if (MobileMQ.matches) {
      this.calendar.changeView("listWeek");
      // this.calendar.destroy();
      // this.calendar.render();
      // The above code works! But isn't necessary at the moment.
    } else {
      //this.calendar.setOption('dayMaxEventRows', true);
      this.calendar.changeView(this.desktopView);
    }
  }

  private changeCalendar(btn, view: string): void {
    this.calendar.changeView(view);
    let titles = this.eventCalendar.querySelectorAll(".event-listing__title");
    let btnParent = btn.parentElement,
      activeBtn = btnParent.querySelector("button[aria-pressed=true]");

    this.desktopView = view;
    if (activeBtn) {
      activeBtn.setAttribute("aria-pressed", "false");
    }
    btn.setAttribute("aria-pressed", "true");
  }

  private async _primeMediaCache(json: { featured_media: number }[]): Promise<any> {
    let uncachedMediaIds = json
      .map((value) => value.featured_media) // get featured media id
      .filter((value: number, index: any, array: Array<number>) => value > 0 && array.indexOf(value) === index) // only unique values greater than 0
      .filter((value: number) => !this.cachedMedia.has(value)); // id is not already set in the media cache
    if (uncachedMediaIds.length <= 0) {
      return new Promise<void>((resolve) => {
        resolve();
      });
    }
    return this.localFetch("media", { include: uncachedMediaIds.join(",") })
      .then((response) => response.json())
      .then((json) =>
        json.forEach((value: NetworkEvent) => {
          this.cachedMedia.set(value.id, value);
        })
      )
      .catch((error) => console.log(error));
  }

  private buildEventDOM(arg: EventContentArg) {
    let contentArea = document.createElement("div"),
      contentWrapper = document.createElement("div"),
      event = arg.event,
      htmlArrow =
        '<svg xmlns="http://www.w3.org/2000/svg" width="13.338" height="12.273" viewBox="0 0 13.338 12.273"><g id="CTA_Secondary_Arrow" transform="translate(0 0.707)"><path id="Path_52" data-name="Path 52" d="M-13572.044-6709.884l-1.414-1.414,4.723-4.723-4.723-4.722,1.414-1.414,6.137,6.136Z" transform="translate(13579.245 6721.45)" fill="#7c2529"/><path id="Path_1510" data-name="Path 1510" d="M-15709.244-3614.516h-11.514v-2h11.514Z" transform="translate(15720.758 3620.946)" fill="#732b2c"/></g></svg>';

    contentArea.innerHTML += `<title>${event.title}</title>`;

    contentWrapper.classList.add("event-wrap");
    contentArea.classList.add("event-listing");

    if (event.extendedProps.thumbnail !== undefined && event.extendedProps.thumbnail !== null) {
      contentArea.innerHTML += `<div class="event-listing__image"><img src="${event.extendedProps.thumbnail}" alt="alt"></div>`;
    }
    contentArea.innerHTML += '<div class="event-listing__content"></div>';
    let contentBlock = contentArea.querySelector(".event-listing__content");
    if (event.url) {
      contentBlock.innerHTML += `<h4 class="event-listing__title"><a href="${event.url}">${event.title}${htmlArrow}</a></h4>`;
    } else {
      contentBlock.innerHTML += `<h4 class="event-listing__title">${event.title}</h4>`;
    }

    contentBlock.innerHTML += `<time datetime="${
      event.startStr
    }" class="event-listing__date"><div class="event-listing__full-date">${this.buildDate(event.startStr)}</div>`;
    if (event.allDay !== true) {
      let timeString: string;
      if (event.endStr !== "") {
        timeString = this.buildTime(event.startStr) + " to " + this.buildTime(event.endStr);
      } else {
        timeString = this.buildTime(event.startStr);
      }
      contentBlock.querySelector("time").innerHTML += `<div class="event-listing__time">${timeString}</div></time>`;
    }
    contentBlock.innerHTML += "</time>";

    if (event.extendedProps.location) {
      contentBlock.innerHTML += `<div class="event-listing__location">${event.extendedProps.location}</div>`;
    }

    // Event Link Markup:
    let contentLink = document.createElement("div"),
      endDate = event.end instanceof Date ? event.end.getTime() : 0,
      startDate = event.start instanceof Date ? event.start.getTime() : 0;

    contentLink.classList.add("event-link");

    if (event.allDay !== true && endDate > startDate !== true) {
      contentLink.innerHTML += `<div class="fc-event-time">${this.buildTime(event.startStr)}</div>`;
    }
    contentLink.innerHTML += `<div class="fc-event-title">${event.title}</div>`;

    if (event.extendedProps.eventTags && arg.view.type === "listWeek") {
      contentBlock.innerHTML += '<div class="event-listing__tags"></div>';
      let tagBlock = contentBlock.querySelector(".event-listing__tags");
      event.extendedProps.eventTags.forEach((tag, index) => {
        tagBlock.innerHTML += `<span>${tag.name}</span>`;
      });
    }
    if (event.extendedProps.description) {
      contentBlock.innerHTML += `<div class="event-listing__desc">${event.extendedProps.description}</div>`;
    }

    contentWrapper.appendChild(contentArea);
    contentWrapper.appendChild(contentLink);

    return contentWrapper;
  }

  private buildDate(date) {
    let formattedDate = formatDate(date, {
      month: "long",
      year: "numeric",
      day: "numeric",
      hour12: true,
    });
    return formattedDate;
  }

  private buildTime(time) {
    let formattedTime = formatDate(time, {
      hour12: true,
      hour: "numeric",
      minute: "2-digit",
    });
    return formattedTime;
  }

  private resetSearch(event) {
    this.calendarSearch.value = "";
    this.calendarCategories.value = "";
    this.runSearch(event);
  }
}

export default function calendarInit() {
  const Calendars = document.querySelectorAll(".calendar") as NodeListOf<HTMLElement>;
  for (let i = 0; i < Calendars.length; i++) {
    new EventCalendar(Calendars[i]);
  }
}
