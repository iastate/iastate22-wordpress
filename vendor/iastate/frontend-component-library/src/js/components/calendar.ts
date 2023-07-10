import { Calendar } from "@fullcalendar/core";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import listPlugin from "@fullcalendar/list";
import { formatDate } from "@fullcalendar/core";

const mobileMQ = window.matchMedia("(max-width: 990px)");

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
  private calendar: any;
  private desktopView: string;
  private pageUrl: string;
  private apiRoot: string;
  private totalCount: number;
  private totalPages: number;
  private currentPage: number;
  private searchTerms: Array<string>;

  constructor(element: HTMLElement) {
    if (!!element) {
      this.element = element;
      this.eventCalendar = this.element.querySelector(".calendar__wrap");
      this.calendarHeader = this.element.querySelector(".calendar__header");
      this.listButton = this.calendarHeader.querySelector(".calendar__views #list-btn");
      this.monthButton = this.calendarHeader.querySelector(".calendar__views #month-btn");
      this.desktopView = "dayGridMonth";
      this.calendarSearch = this.element.querySelector(".calendar__search form #event-search");
      this.calendarCategories = this.element.querySelector(".calendar__categories #event-categories");
      this.calendarSearchButton = this.element.querySelector(".calendar__search form button[type=submit]");
      this.featuredCheck = this.element.querySelector(".calendar__categories-toggle #featured-events");
      this.searchTerms = ["", ""];
      this.currentPage = 1;
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
      }, // uppercase H for 24-hour clock
      headerToolbar: {
        left: "prev,next today",
        center: "title",
        right: "",
      },
      nextDayThreshold: "00:00:00",
      eventMouseEnter: function(nfo) {
        if (nfo.view.type === "dayGridMonth") {
          nfo.el.classList.add("event-active");
          let moreInfo = nfo.el.querySelector(".event-listing");
          moreInfo.classList.add("on");
          setTimeout(() => {
            moreInfo.classList.add("active");
          }, 50);
        }
      },

      eventMouseLeave: function(nfo) {
        if (nfo.view.type === "dayGridMonth") {
          nfo.el.classList.remove("event-active");
          let moreInfo = nfo.el.querySelector(".event-listing");
          moreInfo.classList.remove("active");
          setTimeout(() => {
            moreInfo.classList.remove("on");
          }, 300);
        }
      },
      eventDidMount: (nfo) => {
        this.fixHeaders(nfo.view.type);
      },
      eventContent: (arg) => {
        let arrayOfDomNodes = [this.buildEventDOM(arg)];
        return { domNodes: arrayOfDomNodes };
      },
      viewDidMount: (arg) => {},
    });
    this.calendar.render();

    // Api Settings
    (this.pageUrl = window.location.protocol + "//" + window.location.host), (this.apiRoot = "/wp-json/wp/v2/");

    // Forces the API location to look for the lando site if editing in Fractal
    if (window.location.host.startsWith("localhost")) {
      this.pageUrl = window.location.protocol + "//isu-wp-composer.lndo.site";
    }

    fetch(this.pageUrl + this.apiRoot + "events?filter[posts_per_page]=-1")
      .then((response) => this.initCalendar(response, null))
      .catch((err) => console.log(err));
    this.listButton.addEventListener("click", (e) => {
      this.changeCalendar(e.target, "listWeek");
    });

    this.monthButton.addEventListener("click", (e) => {
      this.changeCalendar(e.target, "dayGridMonth");
    });

    this.calendarSearchButton.addEventListener("click", (e) => {
      this.runSearch(e);
    });
    this.calendarCategories.addEventListener("change", (e) => {
      this.runSearch(e);
    });
    this.featuredCheck.addEventListener("change", (e) => {
      this.runSearch(e);
    });

    window.addEventListener("resize", () => {
      this.breakpointCheck();
    });
    this.breakpointCheck();
  }

  private initCalendar(dta: any, sstr: string) {
    this.totalCount = dta.headers.get("X-WP-Total");
    this.totalPages = dta.headers.get("X-WP-TotalPages");
    for (var p = 1; p <= this.totalPages; p++) {
      if (sstr !== null) {
        fetch(this.pageUrl + this.apiRoot + "events" + sstr + "&page=" + p + "")
          .then((response) => response.json())
          .then((json) => this.tallyItems(json));
      } else {
        fetch(this.pageUrl + this.apiRoot + "events?page=" + p + "")
          .then((response) => response.json())
          .then((json) => this.tallyItems(json));
      }
    }
  }

  private tallyItems(json) {
    json.forEach((el, i) => {
      if (this.featuredCheck.checked === true) {
        if (el.acf.featured === true) {
          this.addItem(el);
        }
      } else {
        this.addItem(el);
      }
    });
  }

  private addItem(item) {
    console.log(item.event_tags);
    let imgUrl: string, loc: string, featureImg: Object;

    if (item.featured_media) {
      fetch(this.pageUrl + this.apiRoot + "media/" + item.featured_media)
        .then((response) => response.json())
        .then((json) => {
          featureImg = json;
        })
        .catch((err) => console.log(err));
    }
    if (item.locations.length > 0) {
      fetch(this.pageUrl + this.apiRoot + "locations/" + item.locations[0])
        .then((response) => response.json())
        .then((json) => this.aggregateEntry(item, json.name, featureImg))
        .catch((err) => console.log(err));
    } else {
      this.aggregateEntry(item, "", featureImg);
    }
  }

  private aggregateEntry(item, loc, fImg) {
    console.log("fImg: ");
    console.log(fImg);
    let imgUrl: string = fImg !== undefined ? fImg.media_details.sizes.medium.source_url : undefined,
      eventStartTime: string =
        item.acf.event_start_date.start_time !== null
          ? item.acf.event_start_date.start_date + " " + item.acf.event_start_date.start_time
          : item.acf.event_start_date.start_date,
      eventEndTime: string =
        item.acf.event_end_date.end_time !== null
          ? item.acf.event_end_date.end_date + " " + item.acf.event_end_date.end_time
          : item.acf.event_end_date.end_date,
      fullDay: boolean = item.acf.event_start_date.full_day,
      evtTags: Array<string> = [];
    if (item.event_tags.length > 0) {
      item.event_tags.forEach((el, i) => {
        console.log(el);
        fetch(this.pageUrl + this.apiRoot + "event_tags/" + el)
          .then((response) => response.json())
          .then((json) => evtTags.push(json));
      });
      console.log("evtTags:");
      console.log(evtTags);
    }
    if (item.acf.recurring_event === true) {
      this.calendar.addEvent({
        title: item.title.rendered,
        startTime: item.acf.recurring_event_date.start_time,
        endTime: item.acf.recurring_event_date.end_time,
        startRecur: item.acf.recurring_event_date.event_start_date,
        endRecur: item.acf.recurring_event_date.event_end_date,
        daysOfWeek: item.acf.recurring_event_date.recurrence_days,
        resourceId: item.id,
        description: item.excerpt.rendered,
        location: loc,
        interactive: true,
        url: item.link,
        thumbnail: imgUrl,
        allDay: fullDay,
        eventTags: evtTags,
        overlap: true,
      });
    } else {
      this.calendar.addEvent({
        title: item.title.rendered,
        start: eventStartTime,
        end: eventEndTime,
        resourceId: item.id,
        description: item.excerpt.rendered,
        location: loc,
        interactive: true,
        url: item.link,
        thumbnail: imgUrl,
        allDay: fullDay,
        eventTags: evtTags,
        overlap: true,
      });
    }
  }

  private runSearch(e) {
    e.preventDefault();
    let searchString: string = "";
    this.searchTerms[0] = this.calendarSearch.value;
    this.searchTerms[1] = this.calendarCategories.value;
    // This feels dirty
    if (this.searchTerms[0] !== "") {
      searchString += "?search=" + this.searchTerms[0];
    } else if (this.searchTerms[1] !== "") {
      if (this.searchTerms[0] !== "") {
        searchString += "&locations=" + this.searchTerms[1];
      } else {
        searchString += "?locations=" + this.searchTerms[1];
      }
    }
    this.calendar.removeAllEvents();
    if (searchString.length > 0) {
      fetch(this.pageUrl + this.apiRoot + "events" + searchString + "&filter[posts_per_page]=-1")
        .then((response) => this.initCalendar(response, searchString))
        .catch((err) => console.log(err));
    } else {
      fetch(this.pageUrl + this.apiRoot + "events?filter[posts_per_page]=-1")
        .then((response) => this.initCalendar(response, null))
        .catch((err) => console.log(err));
    }
    this.listButton.addEventListener("click", (e) => {
      this.changeCalendar(e.target, "listWeek");
    });
  }

  private breakpointCheck() {
    if (mobileMQ.matches) {
      this.calendar.changeView("listWeek");
      // this.calendar.destroy();
      // this.calendar.render();
      // The above code works! But isn't necessary at the moment.
    } else {
      this.calendar.dayMaxEventRows = true;
      this.calendar.changeView(this.desktopView);
    }
  }

  private changeCalendar(btn, view) {
    this.calendar.changeView(view);
    console.log(view);
    let titls = this.eventCalendar.querySelectorAll(".event-listing__title");
    let rent = btn.parentElement,
      activ = rent.querySelector("button[aria-pressed=true]");

    this.desktopView = view;
    if (activ) {
      activ.setAttribute("aria-pressed", "false");
    }
    btn.setAttribute("aria-pressed", "true");
  }

  private fixHeaders(view) {
    let titls = this.eventCalendar.querySelectorAll(".event-listing__title");
    if (view === "listWeek") {
      titls.forEach((el, i) => {
        if (el.querySelector("span") !== null) {
          let titleLink = el.querySelector("span").getAttribute("data-href");
          let titl = el.textContent;
          el.innerHTML =
            "<a href='" +
            titleLink +
            "'>" +
            titl +
            "<svg xmlns='http://www.w3.org/2000/svg' width='13.338' height='12.273' viewBox='0 0 13.338 12.273'><g id='CTA_Secondary_Arrow' transform='translate(0 0.707)'><path id='Path_52' data-name='Path 52' d='M-13572.044-6709.884l-1.414-1.414,4.723-4.723-4.723-4.722,1.414-1.414,6.137,6.136Z' transform='translate(13579.245 6721.45)' fill='#7c2529'/><path id='Path_1510' data-name='Path 1510' d='M-15709.244-3614.516h-11.514v-2h11.514Z' transform='translate(15720.758 3620.946)' fill='#732b2c'/></g></svg></a>";
        }
      });
    } else {
      let titls = this.eventCalendar.querySelectorAll(".event-listing__title");
      titls.forEach((el, i) => {
        if (el.querySelector("a") !== null) {
          let titleLink = el.querySelector("a").getAttribute("href");
          let titl = el.textContent;
          el.innerHTML =
            "<span data-href='" +
            titleLink +
            ">" +
            titl +
            "<svg xmlns='http://www.w3.org/2000/svg' width='13.338' height='12.273' viewBox='0 0 13.338 12.273'><g id='CTA_Secondary_Arrow' transform='translate(0 0.707)'><path id='Path_52' data-name='Path 52' d='M-13572.044-6709.884l-1.414-1.414,4.723-4.723-4.723-4.722,1.414-1.414,6.137,6.136Z' transform='translate(13579.245 6721.45)' fill='#7c2529'/><path id='Path_1510' data-name='Path 1510' d='M-15709.244-3614.516h-11.514v-2h11.514Z' transform='translate(15720.758 3620.946)' fill='#732b2c'/></g></svg></span>";
        }
      });
    }
  }

  private buildEventDOM(arg) {
    let contentArea = document.createElement("div"),
      ct = document.createElement("div");

    ct.classList.add("event-wrap");
    contentArea.classList.add("event-listing");
    if (arg.event.extendedProps.thumbnail !== undefined) {
      contentArea.innerHTML +=
        "<div class='event-listing__image'><img src='" +
        arg.event.extendedProps.thumbnail +
        "' alt='" +
        "alt" +
        "'/></div>";
    }
    contentArea.innerHTML += "<div class='event-listing__content'></div>";
    let contentBlock = contentArea.querySelector(".event-listing__content");
    if (arg.event.url) {
      contentBlock.innerHTML +=
        "<h4 class='event-listing__title'><span data-href='" +
        arg.event.url +
        "'>" +
        arg.event.title +
        "<svg xmlns='http://www.w3.org/2000/svg' width='13.338' height='12.273' viewBox='0 0 13.338 12.273'><g id='CTA_Secondary_Arrow' transform='translate(0 0.707)'><path id='Path_52' data-name='Path 52' d='M-13572.044-6709.884l-1.414-1.414,4.723-4.723-4.723-4.722,1.414-1.414,6.137,6.136Z' transform='translate(13579.245 6721.45)' fill='#7c2529'/><path id='Path_1510' data-name='Path 1510' d='M-15709.244-3614.516h-11.514v-2h11.514Z' transform='translate(15720.758 3620.946)' fill='#732b2c'/></g></svg></span></h4>";
    } else {
      contentBlock.innerHTML += "<h4 class='event-listing__title'>" + arg.event.title + "</h4>";
    }
    contentBlock.innerHTML +=
      "<time datetime ='" +
      arg.event.startStr +
      "' class='event-listing__date'><div class='event-listing__full-date'>" +
      this.buildDate(arg.event.startStr) +
      "</div>";

    if (arg.event.allDay !== true) {
      let timeString: string;
      if (arg.event.endStr !== "") {
        timeString = this.buildTime(arg.event.startStr) + " to " + this.buildTime(arg.event.endStr);
      } else {
        timeString = this.buildTime(arg.event.startStr);
      }
      contentBlock.querySelector("time").innerHTML +=
        "<div class='event-listing__time'>" + timeString + "</div></time>";
    }
    contentBlock.innerHTML += "</time>";

    if (arg.event.extendedProps.location) {
      contentBlock.innerHTML += "<div class='event-listing__location'>" + arg.event.extendedProps.location + "</div>";
    }

    // Event Link Markup:

    let contentLink = document.createElement("div"),
      endDate = arg.event.endStr.substring(0, 10).replace(/[^0-9]+/g, "") as Number,
      startDate = arg.event.startStr.substring(0, 10).replace(/[^0-9]+/g, "") as Number;
    contentLink.classList.add("event-link");
    if (arg.event.allDay !== true && endDate > startDate !== true) {
      contentLink.innerHTML += "<div class='fc-event-time'>" + this.buildTime(arg.event.startStr) + "</div>";
    }
    contentLink.innerHTML += "<div class='fc-event-title'>" + arg.event.title + "</div>";

    if (arg.event.extendedProps.eventTags && arg.view.type === "listWeek") {
      contentBlock.innerHTML += "<div class='event-listing__tags'></div>";
      let tagBlock = contentBlock.querySelector(".event-listing__tags");
      console.log("Event Tags");
      console.log(arg.event.extendedProps);
      arg.event.extendedProps.eventTags.forEach((el, i) => {
        tagBlock.innerHTML += "<a href=" + el.link + " class='tag'>" + el.name + "</span>";
      });
    }
    if (arg.event.extendedProps.description) {
      contentBlock.innerHTML += "<div class='event-listing__desc'>" + arg.event.extendedProps.description + "</div>";
    }

    ct.appendChild(contentArea);
    ct.appendChild(contentLink);
    return ct;
  }

  private buildDate(dte) {
    let formattedDate = formatDate(dte, {
      month: "long",
      year: "numeric",
      day: "numeric",
      hour12: true,
    });
    return formattedDate;
  }

  private buildTime(dte) {
    let formattedTime = formatDate(dte, {
      hour12: true,
      hour: "numeric",
      minute: "2-digit",
    });
    return formattedTime;
  }
}

export default function calendarInit() {
  const calendars = document.querySelectorAll(".calendar") as NodeListOf<HTMLElement>;
  for (let i = 0; i < calendars.length; i++) {
    new EventCalendar(calendars[i]);
  }
}
