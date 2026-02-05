/**
 * EventHaus — App JS
 * Alpine.js components + GSAP animations
 * Loaded BEFORE Alpine (defer), so functions are defined when Alpine inits
 */

// ═══════════════════════════════════════════════════════════════
// Quote Selection Store (sessionStorage-backed)
// ═══════════════════════════════════════════════════════════════

function quoteSelection() {
    return {
        items: [],
        panelOpen: false,

        init() {
            const stored = sessionStorage.getItem('eventhaus_selection');
            if (stored) {
                try { this.items = JSON.parse(stored); } catch(e) { this.items = []; }
            }
        },

        get count() {
            return this.items.reduce((sum, item) => sum + item.qty, 0);
        },

        addItem(id, name, image, qty = 1) {
            const existing = this.items.find(i => i.id === id);
            if (existing) {
                existing.qty += qty;
            } else {
                this.items.push({ id, name, image, qty });
            }
            this.save();
            this.showToast(`${name} added to your selection`);
        },

        removeItem(id) {
            this.items = this.items.filter(i => i.id !== id);
            this.save();
        },

        updateQty(id, qty) {
            const item = this.items.find(i => i.id === id);
            if (item) {
                item.qty = Math.max(1, parseInt(qty) || 1);
                this.save();
            }
        },

        clear() {
            this.items = [];
            this.save();
        },

        save() {
            sessionStorage.setItem('eventhaus_selection', JSON.stringify(this.items));
        },

        togglePanel() {
            this.panelOpen = !this.panelOpen;
            document.body.style.overflow = this.panelOpen ? 'hidden' : '';
        },

        closePanel() {
            this.panelOpen = false;
            document.body.style.overflow = '';
        },

        showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            if (!toast) return;
            toast.textContent = message;
            toast.className = 'toast toast-' + type + ' show';
            setTimeout(() => { toast.classList.remove('show'); }, 3000);
        },

        async submitQuote(formData) {
            if (this.items.length === 0) {
                this.showToast('Please add items to your selection first.', 'error');
                return false;
            }

            const data = new FormData();
            data.append('action', 'eventhaus_submit_quote');
            data.append('nonce', eventhaus.nonce);
            data.append('client_name', formData.name);
            data.append('client_email', formData.email);
            data.append('client_phone', formData.phone);
            data.append('client_company', formData.company);
            data.append('event_date', formData.event_date);
            data.append('event_venue', formData.venue);
            data.append('client_notes', formData.notes);
            data.append('selected_items', JSON.stringify(this.items));

            try {
                const res = await fetch(eventhaus.ajaxurl, { method: 'POST', body: data });
                const json = await res.json();

                if (json.success) {
                    this.clear();
                    this.showToast(json.data.message, 'success');
                    return true;
                } else {
                    this.showToast(json.data.message || 'Something went wrong.', 'error');
                    return false;
                }
            } catch (e) {
                this.showToast('Network error. Please try again.', 'error');
                return false;
            }
        }
    };
}

// Quote form component
function quoteForm() {
    return {
        name: '',
        email: '',
        phone: '',
        company: '',
        event_date: '',
        venue: '',
        notes: '',
        submitting: false,
        submitted: false,

        async submit() {
            if (!this.name || !this.email) {
                this.$store.quote.showToast('Please fill in your name and email.', 'error');
                return;
            }
            this.submitting = true;
            const success = await this.$store.quote.submitQuote({
                name: this.name,
                email: this.email,
                phone: this.phone,
                company: this.company,
                event_date: this.event_date,
                venue: this.venue,
                notes: this.notes,
            });
            this.submitting = false;
            if (success) this.submitted = true;
        }
    };
}

// Catalogue filter component
function catalogueFilter() {
    return {
        active: 'all',

        filter(category) {
            this.active = category;
            const cards = document.querySelectorAll('.product-card');
            cards.forEach(card => {
                const cat = card.dataset.category || '';
                if (category === 'all' || cat.toLowerCase() === category.toLowerCase()) {
                    card.style.display = '';
                    gsap.fromTo(card, { opacity: 0, y: 20 }, { opacity: 1, y: 0, duration: 0.4, ease: 'power2.out' });
                } else {
                    card.style.display = 'none';
                }
            });
        }
    };
}


// ═══════════════════════════════════════════════════════════════
// Register Alpine Store (runs when Alpine inits)
// ═══════════════════════════════════════════════════════════════

document.addEventListener('alpine:init', () => {
    Alpine.store('quote', quoteSelection());
    Alpine.store('quote').init();
});


// ═══════════════════════════════════════════════════════════════
// GSAP Animations
// ═══════════════════════════════════════════════════════════════

document.addEventListener('DOMContentLoaded', () => {

    // Header scroll effect
    const header = document.querySelector('.site-header');
    if (header) {
        window.addEventListener('scroll', () => {
            header.classList.toggle('scrolled', window.scrollY > 50);
        }, { passive: true });
    }

    // Wait for GSAP
    if (typeof gsap === 'undefined') return;

    gsap.registerPlugin(ScrollTrigger);

    // Hero entrance
    const heroContent = document.querySelector('.hero-content');
    if (heroContent) {
        const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });
        tl.from('.hero .section-label', { autoAlpha: 0, y: 30, duration: 0.8 })
          .from('.hero-title', { autoAlpha: 0, y: 40, duration: 1 }, '-=0.4')
          .from('.hero-subtitle', { autoAlpha: 0, y: 30, duration: 0.8 }, '-=0.5')
          .from('.hero-actions .btn', { autoAlpha: 0, y: 20, duration: 0.6, stagger: 0.15 }, '-=0.3')
          .from('.hero-stat', { autoAlpha: 0, x: 30, duration: 0.6, stagger: 0.2 }, '-=0.5');
    }

    // Reveal on scroll
    const reveals = document.querySelectorAll('.reveal');
    reveals.forEach(el => {
        gsap.fromTo(el,
            { autoAlpha: 0, y: 30 },
            {
                autoAlpha: 1, y: 0,
                duration: 0.8,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: el,
                    start: 'top 85%',
                    once: true,
                }
            }
        );
    });

    // Category cards stagger
    const catCards = document.querySelectorAll('.category-card');
    if (catCards.length) {
        gsap.fromTo(catCards,
            { autoAlpha: 0, y: 40 },
            {
                autoAlpha: 1, y: 0,
                duration: 0.6,
                stagger: 0.1,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: '.categories-grid',
                    start: 'top 80%',
                    once: true,
                }
            }
        );
    }

    // Product cards stagger
    const prodCards = document.querySelectorAll('.product-card');
    if (prodCards.length) {
        gsap.fromTo(prodCards,
            { autoAlpha: 0, y: 30 },
            {
                autoAlpha: 1, y: 0,
                duration: 0.5,
                stagger: 0.08,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: '.catalogue-grid',
                    start: 'top 80%',
                    once: true,
                }
            }
        );
    }

    // Process steps
    const steps = document.querySelectorAll('.process-step');
    if (steps.length) {
        gsap.fromTo(steps,
            { autoAlpha: 0, y: 30 },
            {
                autoAlpha: 1, y: 0,
                duration: 0.6,
                stagger: 0.15,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: '.process-grid',
                    start: 'top 80%',
                    once: true,
                }
            }
        );
    }
});
