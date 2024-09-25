import YoutubePlayer from "youtube-player";
import VimeoPlayer from "@vimeo/player";

export class VideoEmbed {
  private element: HTMLElement;
  private media: HTMLElement;
  private youtubeMedia: HTMLElement;
  private vimeoMedia: HTMLElement;
  private player: any;
  private playButton: HTMLButtonElement;
  private mediaModal: HTMLElement;

  constructor(element: HTMLElement) {
    if (!!element) {
      this.element = element;
      this.mediaModal = this.element.parentElement.parentElement.closest(".iastate22-modal");
      this.init();
      this.media = this.element.querySelector(".video-embed__media-wrap");
      this.youtubeMedia = this.element.querySelector(".video-embed__video");
      this.vimeoMedia = this.element.querySelector(".video--vim");
    }
  }

  private init() {
    this.createPlayButton();
    this.handlePlayButtonClick();
    this.handlePlayButtonHover();
    if (this.mediaModal !== null) {
      this.stopVideo();
    }
  }

  private createVideoPlayer() {
    const playerRoot = this.element.querySelector(".video-embed__video") as HTMLElement;
    const videoId = playerRoot.dataset.vid;
    this.player = YoutubePlayer(playerRoot, {
      videoId,
      playerVars: {
        rel: 0,
      },
    });
    this.handlePlayerEvents();
  }

  private handlePlayButtonClick() {
    this.playButton.addEventListener("click", () => {
      // Lazily create the video player on click.
      if (this.youtubeMedia !== null) {
        this.createVideoPlayer();
      } else if (this.vimeoMedia !== null) {
        this.playVimeoVid();
      }
    });
  }

  private handlePlayButtonHover() {
    this.playButton.addEventListener("mouseover", () => {
      this.media.classList.add("video-embed__video-hover");
    });
    this.playButton.addEventListener("mouseout", () => {
      this.media.classList.remove("video-embed__video-hover");
    });
  }

  private handlePlayerEvents() {
    // Play the video automatically when it first is created.
    this.player.on("ready", () => {
      this.player.playVideo();
    });
    this.player.on("stateChange", (event) => {
      if (!this.media.classList.contains("video-playing")) {
        this.media.classList.add("video-playing");
      }
      if (event.data == 0) {
        this.player.destroy();
        this.media.classList.remove("video-playing");
      }
    });
  }

  private stopVideo() {
    this.mediaModal.addEventListener("click", () => {
      setTimeout(() => {
        if (
          this.mediaModal.classList.contains("is-open") === false &&
          this.media.classList.contains("video-playing") === true
        ) {
          this.player.pauseVideo();
        }
      }, 50);
    });
    window.addEventListener("keydown", (event) => {
      const key = event.key || event.keyCode;
      // Close the nav when the esc key is pressed while it's open
      if (key === "Escape" || key === "Esc" || key === 27) {
        setTimeout(() => {
          if (
            this.mediaModal.classList.contains("is-open") === false &&
            this.media.classList.contains("video-playing") === true
          ) {
            this.player.pauseVideo();
          }
        }, 50);
      }
    });
  }

  private playVimeoVid() {
    let vimeoplayer = new VimeoPlayer(this.vimeoMedia);
    vimeoplayer.play().then(() => {
      if (!this.media.classList.contains("video-playing")) {
        this.media.classList.add("video-playing");
      }
    });
  }

  private createPlayButton() {
    const button = document.createElement("BUTTON") as HTMLButtonElement;
    button.className = "video-embed__button";
    button.innerHTML = `<svg viewBox = "0 0 83 83" preserveAspectRatio="xMidYMid meet" class="video-play-icon" xmlns="http://www.w3.org/2000/svg"><g class="outer-circle" fill="none" fill-rule="evenodd"><g opacity=".377" fill="#FFF" fill-rule="nonzero" transform="translate(1 1)"><circle cx="40" cy="40" r="40"/><path d="M40 0C17.909 0 0 17.909 0 40s17.909 40 40 40 40-17.909 40-40S62.091 0 40 0Zm0 1c21.54 0 39 17.46 39 39S61.54 79 40 79 1 61.54 1 40 18.46 1 40 1Z"/></g><path d="M24 22.818h35V57.93H24z"/><path class="arrow" d="m59.986 39.288-28.433-17.68c-2.073-1.363-3.767-.36-3.767 2.229V58.16c0 2.593 1.694 3.593 3.767 2.229l28.433-17.68c.565-.4.934-1.022 1.014-1.71a2.438 2.438 0 0 0-1.01-1.707l-.004-.004Z" fill="#FFF" fill-rule="nonzero"/></g></svg>`;
    const label = document.createElement("SPAN") as HTMLElement;
    label.className = "visible-for-screen-readers";
    label.textContent = "Play Video";
    button.appendChild(label);
    this.media = this.element.querySelector(".video-embed__media-wrap");
    this.media.appendChild(button);
    this.playButton = button;
  }
}

export default function videoEmbedInit() {
  const videoEmbed = document.querySelectorAll(".video-embed") as NodeListOf<HTMLElement>;
  for (let i = 0; i < videoEmbed.length; i++) {
    new VideoEmbed(videoEmbed[i]);
  }
}
