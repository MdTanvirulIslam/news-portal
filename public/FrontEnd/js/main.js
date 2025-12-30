class MegaMenu {
  constructor() {
    this.menu = document.getElementById("hamburgerBtn");
    this.trigger = document.querySelector(".more_main_menu");

    if (!this.menu || !this.trigger) {
      console.warn("MegaMenu elements not found");
      return;
    }

    this.init();
  }

  init() {
    // Click event
    this.trigger.addEventListener("click", () => this.toggle());

    // Keyboard support (Enter/Space)
    this.trigger.addEventListener("keydown", (e) => {
      if (e.key === "Enter" || e.key === " ") {
        e.preventDefault();
        this.toggle();
      }
    });

    // Close on outside click
    document.addEventListener("click", (e) => {
      if (!this.menu.contains(e.target) && !this.trigger.contains(e.target)) {
        this.hide();
      }
    });

    // Close on Escape key
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape") {
        this.hide();
      }
    });

    // console.log("MegaMenu initialized");
  }

  toggle() {
    const isOpen = this.menu.classList.contains("show");

    if (isOpen) {
      this.hide();
    } else {
      this.show();
    }
  }

  show() {
    this.menu.classList.add("show");
    this.trigger.classList.add("active");
  }

  hide() {
    this.menu.classList.remove("show");
    this.trigger.classList.remove("active");
  }
}

// Initialize when DOM is ready
document.addEventListener("DOMContentLoaded", () => {
  window.megaMenu = new MegaMenu();

  // Keep backward compatibility
  window.toggleMegaMenu = () => {
    if (window.megaMenu) {
      window.megaMenu.toggle();
    }
  };
});
