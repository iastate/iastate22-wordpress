export class imageGallery {
  private element: HTMLElement;
  private galleryItems: NodeListOf<HTMLElement>;

  constructor(element: HTMLElement) {
    if (!!element) {
      this.element = element;
      this.galleryItems = this.element.querySelectorAll(".image-gallery__item");
      this.init();
    }
  }

  private init() {
    this.galleryItems.forEach((el, i) => {
      el.addEventListener("mouseleave", () => {
        if (this.element.contains(document.activeElement)) {
          const activ = document.activeElement as HTMLElement;
          activ.blur();
        }
      });
    });
  }
}

export default function imageGalleryInit() {
  const galleries = document.querySelectorAll(".image-gallery") as NodeListOf<HTMLElement>;
  for (let i = 0; i < galleries.length; i++) {
    new imageGallery(galleries[i]);
  }
}
