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
  private monthButton: HTMLButtonElement;
  private listButton: HTMLButtonElement;
  private calendar: any;
  private desktopView: string;

  constructor(element: HTMLElement) {
    if (!!element) {
      this.element = element;
      this.eventCalendar = this.element.querySelector(".calendar__wrap");
      this.calendarHeader = this.element.querySelector(".calendar__header");
      this.listButton = this.calendarHeader.querySelector(".calendar__views #list-btn");
      this.monthButton = this.calendarHeader.querySelector(".calendar__views #month-btn");
      this.desktopView = "dayGridMonth";
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
      eventContent: (arg) => {
        let arrayOfDomNodes = [this.buildEventDOM(arg)];
        return { domNodes: arrayOfDomNodes };
      },
      viewDidMount: (arg) => {},
    });
    this.calendar.render();
    this.calendar.addEvent({
      title: "March 9th Event",
      start: "2023-03-09 15:00",
      resourceId: "1",
      location: "University Library front lobby",
      interactive: true,
    });
    this.calendar.addEvent({
      title: "March 16 Event",
      start: "2023-03-16",
      resourceId: "2",
      location: "Memorial Union Art Gallery",
      interactive: true,
      url: "https://idfive.com",
    });
    this.calendar.addEvent({
      title: "April 12 Event",
      start: "2023-04-12 20:30",
      resourceId: "3",
      url: "https://idfive.com",
      location: "Parts Unknown",
      interactive: true,
    });
    this.calendar.addEvent({
      title: "Orioles Tickets!",
      resourceId: "4",
      start: "2023-05-19",
      location: "Oriole Park at Camden Yards",
      interactive: true,
    });
    this.calendar.addEvent({
      title: "Aquarium Tickets!",
      resourceId: "4",
      start: "2023-05-19 16:00",
      location: "Baltimore Aquarium",
      interactive: true,
    });
    this.calendar.addEvent({
      title: "Floating Holiday",
      start: "2023-05-21",
      resourceId: "5",
      description: "I've got a day off! I've got a day off!",
      location: "Unknown",
      interactive: true,
    });
    this.listButton.addEventListener("click", (e) => {
      this.changeCalendar(e.target, "listWeek");
    });

    this.monthButton.addEventListener("click", (e) => {
      this.changeCalendar(e.target, "dayGridMonth");
    });

    window.addEventListener("resize", () => {
      this.breakpointCheck();
    });
    this.breakpointCheck();
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
    let rent = btn.parentElement,
      activ = rent.querySelector("button[aria-pressed=true]");

    this.desktopView = view;
    if (activ) {
      activ.setAttribute("aria-pressed", "false");
    }
    btn.setAttribute("aria-pressed", "true");
  }

  private buildEventDOM(arg) {
    let contentArea = document.createElement("div"),
      ct = document.createElement("div");

    contentArea.classList.add("event-listing");
    contentArea.innerHTML +=
      "<div class='event-listing__image'><img src='http://placekitten.com/200/300' alt='placekitteh'/></div>";
    contentArea.innerHTML += "<div class='event-listing__content'></div>";
    let contentBlock = contentArea.querySelector(".event-listing__content");
    if (arg.event.url) {
      contentBlock.innerHTML +=
        "<h4 class='event-listing__title'><a href='" +
        arg.event.url +
        "'>" +
        arg.event.title +
        "<svg xmlns='http://www.w3.org/2000/svg' width='13.338' height='12.273' viewBox='0 0 13.338 12.273'><g id='CTA_Secondary_Arrow' transform='translate(0 0.707)'><path id='Path_52' data-name='Path 52' d='M-13572.044-6709.884l-1.414-1.414,4.723-4.723-4.723-4.722,1.414-1.414,6.137,6.136Z' transform='translate(13579.245 6721.45)' fill='#7c2529'/><path id='Path_1510' data-name='Path 1510' d='M-15709.244-3614.516h-11.514v-2h11.514Z' transform='translate(15720.758 3620.946)' fill='#732b2c'/></g></svg></a></h4>";
    } else {
      contentBlock.innerHTML += "<h4 class='event-listing__title'>" + arg.event.title + "</h4>";
    }
    contentBlock.innerHTML +=
      "<time datetime ='" +
      arg.event.startStr +
      "' class='event-listing__date'><div class='event-listing__full-date'>" +
      this.buildDate(arg.event.startStr) +
      "</div><div class='event-listing__time'>" +
      this.buildTime(arg.event.startStr) +
      "</div></time>";
    if (arg.event.extendedProps.location) {
      contentBlock.innerHTML += "<div class='event-listing__location'>" + arg.event.extendedProps.location + "</div>";
    }
    if (arg.event.extendedProps.description) {
      contentBlock.innerHTML += "<div class='event-listing__desc'>" + arg.event.extendedProps.description + "</div>";
    }

    // Event Link Markup:

    let contentLink = document.createElement("div");
    contentLink.classList.add("event-link");
    contentLink.innerHTML += "<div class='fc-event-time'>" + this.buildTime(arg.event.startStr) + "</div>";
    contentLink.innerHTML += "<div class='fc-event-title'>" + arg.event.title + "</div>";
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
