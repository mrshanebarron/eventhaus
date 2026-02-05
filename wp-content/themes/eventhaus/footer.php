<!-- Process Section -->
<section class="process-section">
    <div class="container">
        <span class="section-label reveal">How It Works</span>
        <h2 class="section-title reveal">From Selection to Setup</h2>

        <div class="process-grid">
            <div class="process-step">
                <div class="process-number">01</div>
                <h3 class="process-title">Browse & Select</h3>
                <p class="process-desc">Explore our curated catalogue. Add items to your selection list — no commitment, no account needed.</p>
            </div>
            <div class="process-step">
                <div class="process-number">02</div>
                <h3 class="process-title">Request Quote</h3>
                <p class="process-desc">Submit your selection with event details. We respond within 24 hours with a tailored proposal and availability.</p>
            </div>
            <div class="process-step">
                <div class="process-number">03</div>
                <h3 class="process-title">Refine & Confirm</h3>
                <p class="process-desc">Work with our team to finalise quantities, configurations, and logistics. We handle the details so you don't have to.</p>
            </div>
            <div class="process-step">
                <div class="process-number">04</div>
                <h3 class="process-title">Deliver & Style</h3>
                <p class="process-desc">Professional delivery, setup, and collection. Your event, beautifully furnished — stress-free.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <h2 class="cta-title reveal">Ready to Furnish Your Vision?</h2>
        <p class="cta-subtitle reveal">Tell us about your event. We'll craft a proposal that matches your space, your style, and your timeline.</p>
        <a href="<?php echo home_url('/request-quote/'); ?>" class="btn btn--solid reveal">
            <span>Start Your Quote</span>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
    </div>
</section>

<!-- Footer -->
<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div>
                <a href="<?php echo home_url(); ?>" class="site-logo">Event<em>Haus</em></a>
                <p class="footer-brand-desc">Premium event furniture and styling rentals. From intimate gatherings to grand celebrations — we furnish moments that matter.</p>
            </div>
            <div>
                <h4 class="footer-heading">Catalogue</h4>
                <ul class="footer-links">
                    <?php
                    $footer_cats = get_terms(['taxonomy' => 'rental_category', 'hide_empty' => false]);
                    if ($footer_cats && !is_wp_error($footer_cats)) {
                        foreach ($footer_cats as $cat) {
                            printf('<li><a href="%s">%s</a></li>', get_term_link($cat), esc_html($cat->name));
                        }
                    }
                    ?>
                </ul>
            </div>
            <div>
                <h4 class="footer-heading">Company</h4>
                <ul class="footer-links">
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Our Process</a></li>
                    <li><a href="#">Sustainability</a></li>
                    <li><a href="#">Careers</a></li>
                </ul>
            </div>
            <div>
                <h4 class="footer-heading">Contact</h4>
                <ul class="footer-links">
                    <li><a href="<?php echo home_url('/request-quote/'); ?>">Request a Quote</a></li>
                    <li><a href="mailto:hello@eventhaus.ch">hello@eventhaus.ch</a></li>
                    <li><a href="tel:+41445551234">+41 44 555 12 34</a></li>
                    <li>Zürich &middot; Geneva</li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <span>&copy; <?php echo date('Y'); ?> EventHaus. All rights reserved.</span>
            <span>Zürich, Switzerland</span>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
