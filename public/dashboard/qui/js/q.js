(() => {
    const getTargetElement = target => {
        if (!target) return null;
        if (target.startsWith("#") || target.startsWith(".") || target.startsWith("[")) {
            return document.querySelector(target);
        }
        return document.getElementById(target) || document.querySelector(target);
    };

    const parseDuration = value => {
        const text = String(value || "").trim();
        if (!text) return 0;
        if (text.endsWith("ms")) return Number.parseFloat(text) || 0;
        if (text.endsWith("s")) return (Number.parseFloat(text) || 0) * 1000;
        return Number.parseFloat(text) || 0;
    };

    const syncSelectableTile = (tile, selected) => {
        tile.classList.toggle("selected", selected);
        const input = tile.querySelector('input[type="radio"], input[type="checkbox"]');
        if (input) input.checked = selected;
    };

    const initListViews = () => {
        document.querySelectorAll(".qlist-view.selectable").forEach(list => {
            const tiles = Array.from(list.querySelectorAll(".qlist-tile"));

            tiles.forEach(tile => {
                const radio = tile.querySelector('input[type="radio"]');
                syncSelectableTile(
                    tile,
                    radio ? radio.checked : tile.classList.contains('selected')
                );

                tile.addEventListener("click", event => {
                    if (event.target.closest('input[type="radio"]')) return;
                    if (tile.tagName === "A") event.preventDefault();
                    tiles.forEach(currentTile => syncSelectableTile(currentTile, currentTile === tile));
                });

                if (radio) {
                    radio.addEventListener("change", () => {
                        tiles.forEach(currentTile => {
                            const currentRadio = currentTile.querySelector('input[type="radio"]');
                            syncSelectableTile(currentTile, currentRadio === radio && radio.checked);
                        });
                    });
                }
            });
        });

        document.querySelectorAll(".qlist-view.multi-selectable").forEach(list => {
            const tiles = Array.from(list.querySelectorAll(".qlist-tile"));

            tiles.forEach(tile => {
                const checkbox = tile.querySelector('input[type="checkbox"]');
                syncSelectableTile(tile, Boolean(checkbox && checkbox.checked));

                tile.addEventListener("click", event => {
                    if (event.target.closest('input[type="checkbox"]')) return;
                    if (tile.tagName === "A") event.preventDefault();
                    const nextSelected = !tile.classList.contains("selected");
                    syncSelectableTile(tile, nextSelected);
                });

                if (checkbox) {
                    checkbox.addEventListener("change", event => {
                        event.stopPropagation();
                        syncSelectableTile(tile, checkbox.checked);
                    });
                }
            });
        });
    };

    const closeSidebar = () => {
        const sidebar = document.querySelector(".qsidebar");
        const overlay = document.querySelector(".qsidebar-overlay");

        if (sidebar) sidebar.classList.remove("open");
        if (overlay) overlay.classList.remove("show", "minimal");
    };

    const initSidebar = () => {
        const sidebar = document.querySelector(".qsidebar");
        const overlay = document.querySelector(".qsidebar-overlay");

        document.querySelectorAll('[data-toggle="qsidebar-open"]').forEach(button => {
            button.addEventListener("click", () => {
                if (!sidebar) return;

                sidebar.classList.add("open");
                if (overlay) overlay.classList.add("show");
                history.pushState({ qSidebarOpen: true }, "");
            });
        });

        if (overlay) overlay.addEventListener("click", closeSidebar);
    };

    const closeModal = modal => {
        if (!modal) return;
        modal.classList.remove("show");
        document.body.style.overflow = "";
    };

    const initModals = () => {
        document.querySelectorAll('[data-toggle="qmodal"]').forEach(button => {
            button.addEventListener("click", () => {
                const modal = getTargetElement(button.getAttribute("data-target"));
                if (!modal) return;

                modal.classList.add("show");
                document.body.style.overflow = "hidden";
                history.pushState({ qModalOpen: true }, "");
            });
        });

        document.addEventListener("click", event => {
            const backdrop = event.target.closest(".qpopup-backdrop");
            if (backdrop) closeModal(backdrop.closest(".qpopup"));

            const closeButton = event.target.closest(".close, .close-modal");
            if (closeButton) closeModal(closeButton.closest(".qpopup"));
        });

        document.addEventListener("keydown", event => {
            if (event.key === "Escape") {
                document.querySelectorAll(".qpopup.show").forEach(closeModal);
                closeSidebar();
            }
        });
    };

    const closeAlert = alertBox => {
        if (!alertBox || !alertBox.classList.contains("popup")) return;

        window.clearTimeout(alertBox.hideTimer);
        alertBox.classList.add("closing");

        const duration = getComputedStyle(alertBox).getPropertyValue("--animation-fast") || "200ms";
        window.setTimeout(() => {
            alertBox.classList.remove("show", "closing");
        }, parseDuration(duration));
    };

    const showAlert = alertBox => {
        if (!alertBox) return;

        window.clearTimeout(alertBox.hideTimer);
        alertBox.classList.remove("closing");
        alertBox.classList.add("show");

        const duration = parseDuration(alertBox.getAttribute("data-duration"));
        if (duration > 0) {
            alertBox.hideTimer = window.setTimeout(() => closeAlert(alertBox), duration);
        }
    };

    const initAlerts = () => {
        document.querySelectorAll("[data-toggle='qalert']").forEach(button => {
            button.addEventListener("click", () => {
                showAlert(getTargetElement(button.getAttribute("data-target")));
            });
        });

        document.addEventListener("click", event => {
            const closeButton = event.target.closest(".qalert .close, .qalert .qalert-close, .qalert .qalert-action .qbtn-icon");
            if (closeButton) closeAlert(closeButton.closest(".qalert"));
        });

        document.querySelectorAll(".qalert.show-onload").forEach(showAlert);
    };

    const initPopupMenus = () => {
        document.addEventListener("click", event => {
            const button = event.target.closest(".qpopup-menu-btn");

            document.querySelectorAll(".qpopup-menu.open").forEach(popup => {
                if (!popup.contains(event.target)) popup.classList.remove("open", "up");
            });

            if (button) {
                event.preventDefault();

                const popup = button.closest(".qpopup-menu");
                const menu = popup ? popup.querySelector(".qpopup-menu-container") : null;
                if (!popup || !menu) return;

                popup.classList.toggle("open");
                popup.classList.remove("up");

                if (popup.classList.contains("open")) {
                    const rect = menu.getBoundingClientRect();
                    const spaceBelow = window.innerHeight - rect.top;

                    if (spaceBelow < rect.height + 50) popup.classList.add("up");
                }

                return;
            }

            const item = event.target.closest(".qpopup-menu-item");
            if (item && !item.dataset.disabled) {
                if (event.target.closest('a[href="#"]')) event.preventDefault();
                const popup = item.closest(".qpopup-menu");
                if (popup) popup.classList.remove("open", "up");
            }
        });
    };

    const initThemeToggle = () => {
        const root = document.documentElement;
        const toggleButton = document.querySelector('[data-toggle="theme"]');
        const savedTheme = localStorage.getItem("theme");

        if (savedTheme === "dark") {
            root.setAttribute("data-theme", "dark");
        } else if (savedTheme === "light") {
            root.removeAttribute("data-theme");
        }

        if (!toggleButton) return;

        toggleButton.addEventListener("click", () => {
            const nextTheme = root.getAttribute("data-theme") === "dark" ? "light" : "dark";

            if (nextTheme === "dark") {
                root.setAttribute("data-theme", "dark");
            } else {
                root.removeAttribute("data-theme");
            }

            localStorage.setItem("theme", nextTheme);
        });
    };

    window.qUI = {
        closeAlert,
        closeModal,
        closeSidebar,
        parseDuration,
        showAlert
    };
    window.closeAlert = closeAlert;
    window.closeModal = closeModal;
    window.closeSidebar = closeSidebar;
    window.showAlert = showAlert;

    window.addEventListener("popstate", () => {
        const openModal = document.querySelector(".qpopup.show");
        const openSidebar = document.querySelector(".qsidebar.open");

        if (openModal) closeModal(openModal);
        if (openSidebar) closeSidebar();
    });

    document.addEventListener("DOMContentLoaded", () => {
        initListViews();
        initSidebar();
        initModals();
        initAlerts();
        initPopupMenus();
        initThemeToggle();
    });
})();
