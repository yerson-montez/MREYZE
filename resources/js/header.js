// Presentation only: no authentication, requests, payloads or business state.
export function initHeaders() {
    document.querySelectorAll('[data-mreyze-header]').forEach(initHeader);
}

function initHeader(header) {
    if (header.hasAttribute('data-enhanced')) return;
    const toggle = header.querySelector('[data-header-toggle]');
    const panel = header.querySelector('[data-header-panel]');
    const close = header.querySelector('[data-header-close]');
    const backdrop = header.querySelector('[data-header-backdrop]');
    const groups = [...header.querySelectorAll('[data-header-group]')];
    const mobile = window.matchMedia('(max-width: 900px)');
    let opened = false;
    let savedStyles;
    let inertElements = [];

    function closeGroups(except) {
        groups.forEach(group => { if (group !== except) group.open = false; });
    }

    function lockPage() {
        const body = document.body;
        const root = document.documentElement;
        savedStyles = { body: body.style.overflow, root: root.style.overflow, padding: body.style.paddingInlineEnd };
        const scrollbar = window.innerWidth - root.clientWidth;
        if (scrollbar > 0) body.style.paddingInlineEnd = `${parseFloat(getComputedStyle(body).paddingInlineEnd) + scrollbar}px`;
        root.style.overflow = 'hidden';
        body.style.overflow = 'hidden';
        inertElements = [...body.children].filter(el => el !== header && !el.contains(header)).map(el => [el, el.inert]);
        inertElements.forEach(([el]) => { el.inert = true; });
    }

    function unlockPage() {
        if (!savedStyles) return;
        document.body.style.overflow = savedStyles.body;
        document.documentElement.style.overflow = savedStyles.root;
        document.body.style.paddingInlineEnd = savedStyles.padding;
        inertElements.forEach(([el, previous]) => { el.inert = previous; });
        inertElements = [];
        savedStyles = undefined;
    }

    function setOpen(value, restoreFocus = true) {
        opened = value && mobile.matches;
        header.toggleAttribute('data-open', opened);
        toggle.setAttribute('aria-expanded', String(opened));
        toggle.setAttribute('aria-label', opened ? 'Cerrar menú de navegación' : 'Abrir menú de navegación');
        panel.hidden = mobile.matches && !opened;
        backdrop.hidden = !opened;
        if (opened) {
            panel.setAttribute('role', 'dialog');
            panel.setAttribute('aria-modal', 'true');
            panel.setAttribute('aria-labelledby', 'mreyze-menu-title');
            lockPage();
            close.focus({ preventScroll: true });
        } else {
            panel.removeAttribute('role');
            panel.removeAttribute('aria-modal');
            panel.removeAttribute('aria-labelledby');
            closeGroups();
            unlockPage();
            if (restoreFocus) (mobile.matches ? toggle : header.querySelector('.mreyze-header-brand')).focus({ preventScroll: true });
        }
    }

    toggle.addEventListener('click', () => setOpen(!opened));
    close.addEventListener('click', () => setOpen(false));
    backdrop.addEventListener('click', () => setOpen(false));
    panel.addEventListener('click', event => {
        if (opened && event.target.closest('a[href]')) setOpen(false, false);
    });

    groups.forEach(group => {
        const summary = group.querySelector('summary');
        summary.setAttribute('aria-expanded', String(group.open));
        group.addEventListener('toggle', () => {
            summary.setAttribute('aria-expanded', String(group.open));
            if (group.open) closeGroups(group);
        });
        group.addEventListener('focusout', event => {
            if (!mobile.matches && !group.contains(event.relatedTarget)) group.open = false;
        });
        summary.addEventListener('keydown', event => {
            if (event.key === 'ArrowDown') {
                event.preventDefault();
                group.open = true;
                group.querySelector('a, button')?.focus();
            }
        });
    });

    document.addEventListener('click', event => {
        if (!opened && !header.contains(event.target)) closeGroups();
    });
    document.addEventListener('keydown', event => {
        if (event.key === 'Escape') {
            if (opened) { event.preventDefault(); setOpen(false); }
            else {
                const group = groups.find(item => item.open);
                if (group) { event.preventDefault(); closeGroups(); group.querySelector('summary').focus(); }
            }
        }
        if (opened && event.key === 'Tab') {
            const focusable = [...panel.querySelectorAll('a[href], button:not([disabled]), summary, [tabindex="0"]')]
                .filter(el => el.getClientRects().length > 0);
            const first = focusable[0];
            const last = focusable[focusable.length - 1];
            if (event.shiftKey && (document.activeElement === first || !panel.contains(document.activeElement))) {
                event.preventDefault(); last?.focus();
            } else if (!event.shiftKey && (document.activeElement === last || !panel.contains(document.activeElement))) {
                event.preventDefault(); first?.focus();
            }
        }
    });
    mobile.addEventListener('change', () => {
        const hadFocus = opened || panel.contains(document.activeElement);
        setOpen(false, hadFocus);
        toggle.hidden = !mobile.matches;
    });
    // Clear a drawer before the browser stores a page in its back/forward cache.
    window.addEventListener('pagehide', () => setOpen(false, false));
    let scrollFrame = 0;
    const syncScroll = () => {
        header.toggleAttribute('data-scrolled', window.scrollY > 12);
        scrollFrame = 0;
    };
    window.addEventListener('scroll', () => {
        if (!scrollFrame) scrollFrame = requestAnimationFrame(syncScroll);
    }, { passive: true });
    header.setAttribute('data-enhanced', '');
    toggle.hidden = !mobile.matches;
    setOpen(false, false);
    syncScroll();
}
