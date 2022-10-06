export class CollegeHero {
  private element: HTMLElement;
  private media: HTMLElement;
  constructor(element: HTMLElement) {
    if (!!element) {
      this.element = element;
      this.media = this.element.querySelector(".college-hero__media");
      this.init();
    }
  }
  private init() {
    this.randomlySelectHeroImage();
  }
  private randomlySelectHeroImage() {
    const pictures = this.media.querySelectorAll("picture") as NodeListOf<HTMLPictureElement>;
    const selectedIndex = Math.floor(Math.random() * pictures.length);
    for (let i = 0; i < pictures.length; i++) {
      const picture = pictures[i];
      const sources = picture.querySelectorAll("source") as NodeListOf<HTMLSourceElement>;
      const image = picture.querySelector("img") as HTMLImageElement;
      if (i === selectedIndex) {
        // Lazy load the image if it's selected
        for (let j = 0; j < sources.length; j++) {
          const source = sources[j];
          source.srcset = source.dataset.srcset;
          source.removeAttribute("data-srcset");
        }
        image.src = image.dataset.src;
        image.removeAttribute("data-src");
      } else {
        // Delete the node if it's not the selected one
        picture.remove();
      }
    }
  }
}
export default function initCollegeHeroes() {
  const heroes = document.querySelectorAll(".college-hero") as NodeListOf<HTMLElement>;
  for (let i = 0; i < heroes.length; i++) {
    new CollegeHero(heroes[i]);
  }
}
