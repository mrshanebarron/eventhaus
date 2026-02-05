<?php
/**
 * Template Name: Request Quote
 * Slug: request-quote
 */
get_header();
?>

<section class="quote-form-section">
    <div class="container">
        <span class="section-label">Request a Quote</span>
        <h1 class="section-title" style="font-size:var(--fs-h1); margin-bottom:var(--space-sm);">Tell Us About Your Event</h1>
        <p style="color:var(--text-secondary); max-width:600px; margin-bottom:var(--space-lg);">
            Complete the form below with your event details and selected items. Our team will respond within 24 hours with a tailored proposal.
        </p>

        <div class="quote-form-layout" x-data="quoteForm()">
            <!-- Form -->
            <div>
                <template x-if="submitted">
                    <div style="text-align:center; padding:var(--space-xl) 0;">
                        <div style="font-size:3rem; margin-bottom:var(--space-md); color:var(--gold);">✓</div>
                        <h2 style="font-size:var(--fs-h2); margin-bottom:var(--space-sm);">Quote Request Submitted</h2>
                        <p style="color:var(--text-secondary); margin-bottom:var(--space-md);">Thank you. We'll review your selection and respond within 24 hours with a tailored proposal.</p>
                        <a href="<?php echo home_url('/catalogue/'); ?>" class="btn">
                            <span>Continue Browsing</span>
                        </a>
                    </div>
                </template>

                <template x-if="!submitted">
                    <form class="quote-form" @submit.prevent="submit()">
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="client_name">Name *</label>
                                <input class="form-input" type="text" id="client_name" x-model="name" required placeholder="Your full name">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="client_email">Email *</label>
                                <input class="form-input" type="email" id="client_email" x-model="email" required placeholder="your@email.com">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="client_phone">Phone</label>
                                <input class="form-input" type="tel" id="client_phone" x-model="phone" placeholder="+41 ...">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="client_company">Company</label>
                                <input class="form-input" type="text" id="client_company" x-model="company" placeholder="Company or organization">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="event_date">Event Date</label>
                                <input class="form-input" type="date" id="event_date" x-model="event_date">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="event_venue">Venue / Location</label>
                                <input class="form-input" type="text" id="event_venue" x-model="venue" placeholder="Venue name or address">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="client_notes">Additional Notes</label>
                            <textarea class="form-textarea" id="client_notes" x-model="notes" placeholder="Tell us about your event — theme, guest count, special requirements..."></textarea>
                        </div>

                        <button type="submit" class="btn btn--solid" :disabled="submitting" style="align-self:flex-start;">
                            <span x-text="submitting ? 'Submitting...' : 'Submit Quote Request'"></span>
                            <svg x-show="!submitting" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </button>
                    </form>
                </template>
            </div>

            <!-- Sidebar Summary -->
            <div class="quote-summary-sidebar">
                <h3 class="quote-summary-title">Your Selection</h3>

                <template x-if="$store.quote.items.length === 0">
                    <div style="padding:var(--space-md) 0; text-align:center;">
                        <p style="color:var(--text-muted); font-size:var(--fs-small); margin-bottom:var(--space-sm);">No items selected yet.</p>
                        <a href="<?php echo home_url('/catalogue/'); ?>" class="btn btn--sm" style="display:inline-flex;">
                            <span>Browse Catalogue</span>
                        </a>
                    </div>
                </template>

                <div class="quote-summary-items">
                    <template x-for="item in $store.quote.items" :key="item.id">
                        <div class="quote-summary-item">
                            <span x-text="item.name" style="flex:1;"></span>
                            <span style="color:var(--text-muted);">×<span x-text="item.qty"></span></span>
                        </div>
                    </template>
                </div>

                <template x-if="$store.quote.items.length > 0">
                    <div class="quote-summary-total">
                        <span>Total Items</span>
                        <span x-text="$store.quote.count" style="color:var(--gold);"></span>
                    </div>
                </template>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
