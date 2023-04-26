import { Calendar } from "@fullcalendar/core";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import listPlugin from "@fullcalendar/list";
import { formatDate } from "@fullcalendar/core";

export class EventCalendar {
  private element: HTMLElement;
  private eventCalendar: HTMLElement;
  private calendarHeader: HTMLElement;
  private monthButton: HTMLButtonElement;
  private listButton: HTMLButtonElement;
  private calendar: any;

  constructor(element: HTMLElement) {
    if (!!element) {
      this.element = element;
      this.eventCalendar = this.element.querySelector(".calendar__wrap");
      this.calendarHeader = this.element.querySelector(".calendar__header");
      this.listButton = this.calendarHeader.querySelector(".calendar__views #list-btn");
      this.monthButton = this.calendarHeader.querySelector(".calendar__views #month-btn");
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
      viewDidMount: (arg) => {
        console.log("// View Change");
        console.log(arg.view.type);
      },
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
      title: "Orioles Tickets! Orioles Tickets! Orioles Tickets!",
      resourceId: "4",
      start: "2023-04-19",
      location: "Oriole Park at Camden Yards",
      interactive: true,
    });
    this.calendar.addEvent({
      title: "Floating Holiday",
      start: "2023-04-21",
      resourceId: "5",
      description: "I'm off. What? There ain't no more to it!",
      location: "Unknown",
      interactive: true,
    });
    this.listButton.addEventListener("click", (e) => {
      this.changeCalendar(e.target, "listWeek");
    });

    this.monthButton.addEventListener("click", (e) => {
      this.changeCalendar(e.target, "dayGridMonth");
    });
  }

  private changeCalendar(btn, view) {
    this.calendar.changeView(view);
    console.log(btn);
    let rent = btn.parentElement,
      activ = rent.querySelector("button[aria-pressed=true]");
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
    contentBlock.innerHTML += "<h4 class='event-listing__title'>" + arg.event.title + "</h4>";
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
      contentBlock.innerHTML += "<div class=''>" + arg.event.extendedProps.description + "</div>";
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
