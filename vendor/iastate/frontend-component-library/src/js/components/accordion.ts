import { Collapse } from "bootstrap";

export default function accordionsInit() {
  const collapseEls = document.querySelectorAll(".accordion .collapse");
  for (let i = 0; i < collapseEls.length; i++) {
    return new Collapse(collapseEls[i]);
  }
}
