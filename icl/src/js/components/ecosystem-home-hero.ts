import YoutubePlayer from "youtube-player";

export class EcosystemHeroBackgroundVideo {
  private element: HTMLElement;
  private media: HTMLElement;
  private playerRoot: HTMLElement;
  private player: any;
  private playButton: HTMLButtonElement;
  private isPlaying: boolean = false;
  private reducedMotion: boolean;

  constructor(element: HTMLElement) {
    if (!!element) {
      this.element = element;
      this.media = this.element.querySelector(".ecosystem-home-hero__media");
      this.playerRoot = this.element.querySelector(".ecosystem-home-hero__video");
      this.init();
    }
  }

  private init() {
    this.handlePrefersReducedMotionChange();
    this.createVideoPlayer();
    this.createPlayButton();
    this.handlePlayerEvents();
    this.handlePlayButtonClick();
  }

  private handlePrefersReducedMotionChange() {
    const reducedMotionMQ = window.matchMedia("(prefers-reduced-motion)");
    this.reducedMotion = reducedMotionMQ.matches;
    reducedMotionMQ.addEventListener("change", (event) => {
      this.reducedMotion = event.matches;
    });
  }

  private createVideoPlayer() {
    const videoId = this.playerRoot.dataset.vid;
    this.player = YoutubePlayer(this.playerRoot, {
      videoId,
      playerVars: {
        autoplay: this.reducedMotion ? 0 : 1, // Prevent autoplay for users with reduced motion
        controls: 0,
        disablekb: 1,
        enablejsapi: 1,
        fs: 0,
        loop: 1,
        modestbranding: 1,
        rel: 0,
        playlist: videoId,
        playsinline: 1,
      },
    });
  }

  private handlePlayerEvents() {
    this.player.on("ready", () => {
      // playerRoot has become the YouTube iframe as this point so we need a new reference to it
      this.playerRoot = this.element.querySelector(".ecosystem-home-hero__video");
      // This is necessary for autoplay on mobile
      this.player.mute();
      // Only autoplay if reduced motion is not enabled
      if (!this.reducedMotion && !this.isPlaying) {
        this.player.playVideo();
      }
    });
    this.player.on("stateChange", (event) => {
      if (event.data === 1) {
        this.isPlaying = true;
        // Show the player when it initially starts playing
        if (!this.playerRoot.style.opacity) this.playerRoot.style.opacity = "1";
        this.playButton.querySelector(".visible-for-screen-readers").textContent = "Pause Video";
        this.playButton.classList.add("ecosystem-home-hero__video-button--pause");
      }
      if (event.data === 2) {
        this.isPlaying = false;
        this.playButton.querySelector(".visible-for-screen-readers").textContent = "Play Video";
        this.playButton.classList.remove("ecosystem-home-hero__video-button--pause");
      }
    });
  }

  private handlePlayButtonClick() {
    this.playButton.addEventListener("click", () => {
      if (this.isPlaying) {
        this.player.pauseVideo();
      } else {
        this.player.playVideo();
      }
    });
  }

  private createPlayButton() {
    const button = document.createElement("BUTTON") as HTMLButtonElement;
    button.className = "ecosystem-home-hero__video-button";
    const label = document.createElement("SPAN") as HTMLElement;
    label.className = "visible-for-screen-readers";
    label.textContent = "Play Video";
    button.appendChild(label);
    this.media = this.element.querySelector(".ecosystem-home-hero__media");
    this.media.appendChild(button);
    this.playButton = button;
  }
}

export default function ecosystemHeroBackgroundVideo() {
  const ecosystemHeroBackgroundVideo = document.querySelector(".ecosystem-home-hero--background-video") as HTMLElement;
  new EcosystemHeroBackgroundVideo(ecosystemHeroBackgroundVideo);
}
